<template>
    <action-pane :class="{ 'flex' : !message }">
        <div class="flex-1 flex items-center justify-center" v-if="!message">
            <div class="font-medium text-60 text-4xl">
                {{ messages['select-message-to-view'] }}
            </div>
        </div>
        <div v-else-if="message">
            <header class="flex items-start border-b border-40 pb-4 mb-5">
                <div class="flex-1">
                    <h2 class="font-bold text-90 text-3xl">{{ message.subject }}</h2>
                    <div class="mt-2 text-80">
                        <h4 class="font-normal text-base pb-2">
                            {{ messages['from'] }}: {{ message.from }}
                        </h4>
                    </div>
                    <div class="mt-2 text-80">
                        <h4 class="font-normal text-base pb-2">
                            {{ messages['recipients'] }}
                        </h4>
                        <div v-if="message.send_to_all" class="italic text-70 text-sm">
                            {{ messages['message-sent-to-all-users'] }}
                        </div>
                        <div v-else>
                            <span class="text-sm" v-for="(recipient, index) of message.recipients">
                                <span v-if="recipient.name">
                                    {{ recipient.name }} <<a :href="`mailto:${recipient.email}`" class="underline text-primary hover:text-80">{{ recipient.email }}</a>>{{ index < message.recipients.length - 1 ? ', ' : ''  }}
                                </span>
                                <span v-else>
                                    <a :href="`mailto:${recipient.email}`" class="underline text-primary hover:text-80">{{ recipient.email }}</a>{{ index < message.recipients.length - 1 ? ', ' : ''  }}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="ml-4 text-right">
                    <div class="text-80 font-light font-italic">
                        {{ timestamp(message.created) }}
                    </div>
                    <div class="mt-4">
                        <button class="outline-none text-primary hover:text-80 mr-2"
                                v-if="showEdit"
                                @click="editMessage"
                                :disabled="loading"
                                v-tooltip="messages['edit-draft']">
                            <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <button class="outline-none text-70 hover:text-80 mr-2"
                                v-if="showResend"
                                @click="$emit('resend', message)"
                                :disabled="loading"
                                v-tooltip="messages['resend']">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <button class="outline-none text-70 hover:text-80 mr-2"
                                v-if="showClone"
                                @click="cloneMessage"
                                :disabled="loading"
                                v-tooltip="messages['clone']">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M7 9a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9z" />
                                <path d="M5 3a2 2 0 00-2 2v6a2 2 0 002 2V5h8a2 2 0 00-2-2H5z" />
                            </svg>
                        </button>
                        <button class="outline-none text-danger hover:text-90"
                                v-if="showDelete"
                                @click="$emit('delete', message)"
                                :disabled="loading"
                                v-tooltip="messages['delete']">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </header>
            <section class="mt-2 pt-4 pb-4 leading-normal">
                <div v-html="messageContent"></div>
            </section>
            <h3 class="block font-medium text-90 text-xl pt-4 my-3 text-80" v-if="showPreview">
                {{ messages['message-preview'] }}
            </h3>
            <section class="preview-wrapper" v-if="showPreview">
                <iframe id="sent-preview-frame" class="absolute w-full h-full pin"></iframe>
            </section>
        </div>
    </action-pane>
</template>

<script>
    import Translations from "../../mixins/Translations";
    import Timestamp from "../../mixins/Timestamp";
    import ActionPane from "./ActionPane";
    import ApiService from "../../services/ApiService";

    export default {
        name: "MessagePreview",
        props: {
            message: Object,
            showResend: {
                type: Boolean,
                default: false
            },
            showClone: {
                type: Boolean,
                default: true
            },
            showPreview: {
                type: Boolean,
                default: true
            },
            showDelete: {
                type: Boolean,
                default: false
            },
            showEdit: {
                type: Boolean,
                default: false
            },
        },
        mixins: [
            Translations,
            Timestamp,
        ],
        components: {
            ActionPane,
        },
        data() {
            return {
                loading: false,
            }
        },
        computed: {
            messageContent() {
                if (this.message) {
                    return this.message.content;
                }
            }
        },
        methods: {
            preview(html) {
                if (this.showPreview) {
                    this.$nextTick(() => {
                        document.getElementById('sent-preview-frame').contentWindow.document.body.innerHTML = '';

                        setTimeout(() => {
                            document.getElementById('sent-preview-frame').contentWindow.document.write(html);
                        }, 200)
                    })
                }
            },

            async cloneMessage() {
                this.loading = true;

                let { data } = await ApiService.clone(this.message.id);
                this.$router.push({ name: 'nebula-sender-drafts-edit', params: { id: data.id }})
            },

            editMessage() {
                this.$router.push({ name: 'nebula-sender-drafts-edit', params: { id: this.message.id }})
            }
        }
    }
</script>

<style scoped>

</style>