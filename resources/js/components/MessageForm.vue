<template>
    <div class="flex-row mb-8 lg:mb-20" v-if="!complete">
        <div class="flex flex-wrap md:w-full">
            <div class="w-full mb-6 md:mb-0 lg:pr-10">
                <heading class="mb-6">{{ messages['tool-name'] }}</heading>

                <card class="flex px-6 py-4">
                    <div class="w-3/5">
                        <h3 class="text-base text-80 font-bold mb-3">{{ messages['subject-header'] }}</h3>
                        <div class="mb-8">
                            <p class="mb-2 italic">{{ messages['subject-copy'] }}</p>
                            <counter-input :placeholder="messages['subject-placeholder']"
                                           :model.sync="subject"
                                           :disabled="isThinking()"
                            ></counter-input>
                        </div>

                        <h3 class="text-base text-80 font-bold mb-3">{{ messages['recipients-header'] }}</h3>

                        <recipient-form :messages="messages"
                                        @add="addAddress"
                                    :send-to-all.sync="sendToAll"
                                    :loading="isThinking()"
                                    :recipients="recipients"
                        ></recipient-form>

                        <h3 class="text-base text-80 font-bold mb-3">{{ messages['content-header'] }}</h3>
                        <div class="mb-8">
                            <p class="mb-2">{{ messages['content-copy'] }}</p>
                            <div class="input-wrapper">
                                <quill-editor class="quill-editor"
                                              :options="quillEditorOptions"
                                              v-model="htmlContent"
                                              ref="myQuillEditor"
                                ></quill-editor>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h3 class="text-base text-80 font-bold mb-3">{{ messages['send-preview'] }}</h3>
                            <p class="mb-2">{{ messages['preview-copy'] }}</p>

                            <button class="btn btn-default btn-primary" @click="sendMessage" :disabled="isThinking() || !formIsValid()">
                                {{ loading ? messages['send-message-loading'] : messages['send-message'] }}
                            </button>
                            <button class="btn btn-default btn-secondary" @click="preview" :disabled="isThinking() || !formIsValid()">
                                {{ gettingPreview ? messages['preview-loading'] : messages['preview'] }}
                            </button>
                        </div>
                    </div>

                    <div class="w-2/5">
                        <card class="recipients-list px-6 py-4" style="background-color: var(--20)">
                            <h3 class="text-base text-80 font-bold mb-3">{{ messages['recipients-list-header'] }}</h3>

                            <div v-if="recipients.length > 0" class="recipient-result" v-for="(recipient, index) of recipients">
                                <div class="name" v-if="recipient.name && recipient.name.length > 0">
                                    <strong>{{ recipient.name }}</strong>
                                    <{{ recipient.address }}>
                                </div>
                                <div class="name" v-else>
                                    {{ recipient.address }}
                                </div>
                                <div class="button-wrapper">
                                    <button :title="messages['remove']" class="appearance-none cursor-pointer text-70 hover:text-danger mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current"><path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path></svg>
                                    </button>
                                </div>
                            </div>

                            <div v-if="recipients.length === 0" class="p-4 bg-danger rounded">
                                <p class="text-white">No addresses have been added.</p>
                            </div>
                        </card>
                    </div>
                </card>
            </div>
        </div>
    </div>

    <success-panel v-else @reset="reset" :messages="messages"></success-panel>
</template>

<script>
    import 'quill/dist/quill.core.css'
    import 'quill/dist/quill.snow.css'
    import 'quill/dist/quill.bubble.css'

    import { quillEditor } from 'vue-quill-editor'

    import CounterInput from './CounterInput';
    import SuccessPanel from './SuccessPanel';
    import RecipientForm from './RecipientForm';

    export default {
        name: "MessageForm",
        components: {
            quillEditor,
            CounterInput,
            SuccessPanel,
            RecipientForm,
        },
        props: {
            messages: Object,
            quillConfiguration: Object,
            default: {
                toolbar: [
                    { 'header': 1}, { 'header': 2 },
                    { 'list': 'ordered'}, { 'list': 'bullet' },
                    'bold',
                    'italic',
                    'link',
                ]
            }
        },
        data() {
            return {
                loading: false,
                gettingPreview: false,
                sendToAll: false,
                subject: '',
                recipients: [],
                htmlContent: '',
                complete: false
            }
        },
        mounted() {
            this.$refs.myQuillEditor.$el.addEventListener('keydown', e => {
                e.stopPropagation()
            }, false);
        },
        computed: {
            quillEditorOptions() {
                return {
                    modules: {
                        ...this.quillConfiguration
                    },
                    placeholder: this.messages['content-placeholder']
                }
            },
            quillEditor() {
                return this.$refs.myQuillEditor.quill
            }
        },
        methods: {
            handleKeydown(event) {
                // resets default
            },

            isThinking() {
                if (this.loading || this.gettingPreview) {
                    return true
                }

                return false
            },

            formIsValid() {
                if (this.subject.length === 0 || this.htmlContent.length === 0) {
                    return false;
                }

                if (this.recipients.length === 0 && !this.sendToAll) {
                    return false;
                }

                return true;
            },

            addAddress(userObject) {
                this.recipients.push(userObject);
            },

            sendMessage() {
                let vm = this;

                vm.setLoading();

                Nova.request().post('/nova-vendor/custom-email-sender/send', {
                    subject: vm.subject,
                    sendToAll: vm.sendToAll,
                    recipients: vm.recipients,
                    htmlContent: this.htmlContent
                }).then(response => {
                    vm.$toasted.show(response.data, { type: 'success' });
                    vm.complete = true;
                }).catch(error => {
                    let response = error.response;
                    let status = response.status

                    if (status === 422) {
                        this.$toasted.show(response.data.message, { type: 'error' })
                    } else {
                        this.$toasted.show(response.statusText, { type: 'error' })
                    }
                }).finally(() => {
                    vm.setLoading(false);
                });
            },

            preview() {
                let vm = this;

                vm.setGettingPreview();

                Nova.request().post('/nova-vendor/custom-email-sender/preview', {
                    subject: vm.subject,
                    sendToAll: vm.sendToAll,
                    recipients: vm.recipients,
                    htmlContent: this.htmlContent
                }).then(response => {
                    Nova.$emit('show-email-preview', response.data.content)
                }).catch(error => {
                    let response = error.response;
                    let status = response.status

                    if (status === 422) {
                        this.$toasted.show(response.data.message, { type: 'error' })
                    } else {
                        this.$toasted.show(response.statusText, { type: 'error' })
                    }
                }).finally(() => {
                    vm.setGettingPreview(false);
                });
            },

            /**
             * @param {boolean} loading
             */
            setLoading(loading=true) {
                this.quillEditor.enable(!loading)
                this.loading = loading;
            },

            /**
             * @param {boolean} loading
             */
            setGettingPreview(loading=true) {
                this.quillEditor.enable(!loading)
                this.gettingPreview = loading;
            },

            reset() {
                this.subject = '';
                this.sendToAll = false;
                this.complete = false;
                this.recipients = [];
                this.htmlContent = '';
            }
        }
    }
</script>

<style>
    .quill-editor {
        position: relative;
        background-color: var(--white);
    }
    .ql-editor {
        height: 200px;
    }
    .ql-editor p,
    .ql-editor ol,
    .ql-editor ul,
    .ql-editor pre,
    .ql-editor blockquote,
    .ql-editor h1,
    .ql-editor h2,
    .ql-editor h3,
    .ql-editor h4,
    .ql-editor h5,
    .ql-editor h6 {
        margin-bottom: 18px;
    }
    .btn-secondary {
        background-color: var(--info);
        color: var(--white)
    }
    .btn-secondary:not(:disabled):hover {
        opacity: .8;
    }
</style>
