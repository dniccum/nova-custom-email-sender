<template>
    <div class="flex-row mb-8 lg:mb-20 w-1/2">
        <div class="flex flex-wrap md:w-full">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 lg:pr-10">
                <heading class="mb-6">Custom Email Sender</heading>

                <h3 class="text-base text-80 font-bold mb-3">Subject</h3>
                <div class="mb-8">
                    <p class="mb-2 italic">Enter the subject line for your message</p>
                    <counter-input placeholder="Message subject line"
                                   :model.sync="subject"
                    ></counter-input>
                </div>

                <h3 class="text-base text-80 font-bold mb-3">Recipients</h3>
                <div class="mb-8">
                    <div class="mb-6">
                        <p class="mb-2">Would you like to send this message to all of the users?</p>
                        <toggle-button :width="60" :height="26" color="var(--primary)" v-model="sendToAll" />
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
        data() {
            return {
                loading: false,
                sendToAll: false,
                subject: '',
                recipients: [],
                htmlContent: ''
            }
        },
        computed: {
            quillEditorOptions() {
                return {
                    modules: {
                        toolbar: [
                            { 'header': 1}, { 'header': 2 },
                            { 'list': 'ordered'}, { 'list': 'bullet' },
                            'bold',
                            'italic',
                            'link',
                        ]
                    }
                }
            },
            quillEditor() {
                // this.quillEditor.enable(false)

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

                vm.loading = true;

                Nova.request().post('/nova-vendor/custom-email-sender/send', {
                    subject: vm.subject,
                    sendToAll: vm.sendToAll,
                    recipients: vm.recipients,
                    htmlContent: this.htmlContent
                }).then(response => {
                    console.log(response);
                }).catch(error => {
                    let response = error.response;
                    let status = response.status

                    if (status === 422) {
                        this.$toasted.show(response.data.message, { type: 'error' })
                    } else {
                        this.$toasted.show(response.statusText, { type: 'error' })
                    }
                }).finally(() => {
                    vm.loading = false;
                });
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
</style>