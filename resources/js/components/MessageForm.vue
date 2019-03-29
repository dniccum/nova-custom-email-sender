<template>
    <div class="flex-row mb-8 lg:mb-20" v-if="!complete">
        <div class="flex flex-wrap md:w-full">
            <div class="w-full mb-6 md:mb-0 lg:pr-10">
                <heading class="mb-6">{{ messages['tool-name'] }}</heading>

                <card class="px-6 py-4">
                    <h3 class="text-base text-80 font-bold mb-3">{{ messages['subject-header'] }}</h3>
                    <div class="mb-8">
                        <p class="mb-2 italic">{{ messages['subject-copy'] }}</p>
                        <counter-input :placeholder="messages['subject-placeholder']"
                                       :model.sync="subject"
                                       :disabled="isThinking()"
                        ></counter-input>
                    </div>

                    <h3 class="text-base text-80 font-bold mb-3">{{ messages['recipients-header'] }}</h3>
                    <div class="mb-8">
                        <div class="mb-6">
                            <p class="mb-2">{{ messages['recipients-toggle-copy'] }}</p>
                            <toggle-button :width="60" :height="26" color="var(--primary)" v-model="sendToAll" :disabled="isThinking()" />
                        </div>
                        <p class="mb-2">{{ messages['recipients-manual-input-copy'] }}</p>
                        <div class="input-wrapper">
                            <email-input-tag
                                    v-model="recipients"
                                    :placeholder="messages['recipients-manual-input-placeholder']"
                                    class="form-control form-input form-input-bordered"
                                    :validate="validateEmailAddress"
                                    :read-only="sendToAll || isThinking()"
                            ></email-input-tag>
                        </div>
                    </div>

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
    import { ToggleButton } from 'vue-js-toggle-button'

    import EmailInputTag from './EmailInputTag';
    import CounterInput from './CounterInput';
    import SuccessPanel from './SuccessPanel';

    export default {
        name: "MessageForm",
        components: {
            quillEditor,
            EmailInputTag,
            CounterInput,
            ToggleButton,
            SuccessPanel,
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
            //
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

            validateEmailAddress(value) {
                let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                let isValid = re.test(String(value).toLowerCase());

                if (!isValid) {
                    this.$toasted.show(this.messages['invalid-email'], { type: 'error' })
                }

                return isValid;
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
