<template>
    <div class="flex-row mb-8 lg:mb-20 w-1/2" v-if="!complete">
        <div class="flex flex-wrap md:w-full">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 lg:pr-10">
                <heading class="mb-6">Custom Email Sender</heading>

                <h3 class="text-base text-80 font-bold mb-3">Subject</h3>
                <div class="mb-8">
                    <p class="mb-2 italic">Enter the subject line for your message</p>
                    <counter-input placeholder="Message subject line"
                                   :model.sync="subject"
                                   :disabled="loading"
                    ></counter-input>
                </div>

                <h3 class="text-base text-80 font-bold mb-3">Recipients</h3>
                <div class="mb-8">
                    <div class="mb-6">
                        <p class="mb-2">Would you like to send this message to all of the users?</p>
                        <toggle-button :width="60" :height="26" color="var(--primary)" v-model="sendToAll" :disabled="loading" />
                    </div>
                    <p class="mb-2">Enter the users'/recipients' email addresses that you would like to send this message to.</p>
                    <div class="input-wrapper">
                        <email-input-tag
                                v-model="recipients"
                                placeholder="Email addresses"
                                class="form-control form-input form-input-bordered"
                                :validate="validateEmailAddress"
                                :read-only="sendToAll || loading"
                        ></email-input-tag>
                    </div>
                </div>

                <h3 class="text-base text-80 font-bold mb-3">Content</h3>
                <div class="mb-8">
                    <p class="mb-2">Add the content for the message that you would like to send.</p>
                    <div class="input-wrapper">
                        <quill-editor class="quill-editor"
                                      :options="quillEditorOptions"
                                      v-model="htmlContent"
                                      ref="myQuillEditor"
                        ></quill-editor>
                    </div>
                </div>

                <div class="mt-4">
                    <h3 class="text-base text-80 font-bold mb-3">Send</h3>
                    <p class="mb-2">Click the button below to send the message.</p>

                    <button class="btn btn-default btn-primary" @click="sendMessage" :disabled="loading || !formIsValid()">
                        {{ loading ? 'Sending. Please wait...' : 'Send Message' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-row" v-else>
        <div class="flex flex-wrap w-full border-success rounded-lg relative overflow-hidden">
            <div class="success-background"></div>
            <div class="content-wrapper flex items-center flex-col justify-center w-full p-8">
                <div class="img-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="#FFF"><defs><linearGradient y2="157.23" x2="0" y1="211.23" gradientUnits="userSpaceOnUse" id="0"><stop stop-color="#2fae61"/><stop offset="1" stop-color="#4bdf88"/></linearGradient></defs><circle r="28" cy="184.55" cx="768.86" fill="url(#0)" transform="matrix(.92857 0 0 .92857-681.94-139.37)"/><path d="m773.85 193.97l-1.89-1.89c-.259-.259-.574-.389-.945-.389-.371 0-.686.13-.945.389l-9.116 9.13-4.085-4.099c-.259-.259-.574-.389-.945-.389-.371 0-.686.13-.945.389l-1.89 1.89c-.259.259-.389.574-.389.945 0 .37.13.686.389.945l5.03 5.03 1.89 1.89c.259.259.574.389.945.389.37 0 .685-.13.945-.389l1.89-1.89 10.06-10.06c.259-.259.389-.574.389-.945 0-.37-.13-.685-.389-.945" fill="#fff" fill-opacity=".851" transform="matrix(1.33268 0 0 1.33268-985.46-232.86)"/></svg>
                </div>

                <div class="w-1/2 mt-6 mb-6 text-center" style="color: var(--white)">
                    <h2 class="text-3xl mb-6">Emails have been sent.</h2>
                    <p class="text-lg mb-8">Your email message has been sent successfully. If you would like to send another message, please click the button below to start over.</p>
                    <p>
                        <button class="btn btn-default btn-white text-primary" @click="reset">
                            Start Over
                        </button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import 'quill/dist/quill.core.css'
    import 'quill/dist/quill.snow.css'
    import 'quill/dist/quill.bubble.css'

    import { quillEditor } from 'vue-quill-editor'
    import { ToggleButton } from 'vue-js-toggle-button'

    import EmailInputTag from './EmailInputTag';
    import CounterInput from './CounterInput';

    export default {
        name: "MessageForm",
        components: {
            quillEditor,
            EmailInputTag,
            CounterInput,
            ToggleButton
        },
        props: {
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
                    }
                }
            },
            quillEditor() {
                return this.$refs.myQuillEditor.quill
            }
        },
        methods: {
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
                    this.$toasted.show('Invalid email address.', { type: 'error' })
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

            /**
             * @param {boolean} loading
             */
            setLoading(loading=true) {
                this.quillEditor.enable(!loading)
                this.loading = loading;
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
    .success-background {
        position: absolute;
        width: 100%;
        height: 100%;
        background-color: var(--success);
        opacity: .7;
        z-index: -1;
    }
    .img-wrapper {
        width: 100px;
        height: 100px;
    }

</style>
