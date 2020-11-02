<template>
    <div class="flex">
        <div class="w-3/5">
            <h3 class="text-base text-80 font-bold mb-3">{{ messages['from-header'] }}</h3>

            <div class="mb-8">
                <p class="mb-2 italic">{{ messages['from-copy'] }}</p>
                <select-control
                    v-model="from"
                    class="w-full form-control form-select"
                    :disabled="config.from.options.length <= 1 || isThinking"
                >
                    <option value="" selected disabled>
                        {{ messages['choose-an-option'] }}
                    </option>
                    <option v-for="option in config.from.options" :key="option.address" :value="option.address">
                        {{ option.name }}
                    </option>
                </select-control>
            </div>

            <div class="mb-8">
                <h3 class="text-base text-80 font-bold mb-3">{{ messages['subject-header'] }}</h3>
                <div class="mb-8">
                    <p class="mb-2 italic">{{ messages['subject-copy'] }}</p>
                    <counter-input :placeholder="messages['subject-placeholder']"
                                   :model.sync="subject"
                                   :disabled="isThinking"
                    ></counter-input>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-base text-80 font-bold mb-3">{{ messages['recipients-header'] }}</h3>
                <recipient-form :messages="messages"
                                @add="addAddress"
                                :send-to-all.sync="sendToAll"
                                :loading="isThinking"
                                :recipients="recipients"
                ></recipient-form>
            </div>

            <div class="mb-8">
                <h3 class="text-base text-80 font-bold mb-3">{{ messages['content-header'] }}</h3>

                <div class="mb-6">
                    <p class="mb-2">{{ messages['toggle-use-file'] }}</p>
                    <toggle-button :width="60" :height="26" color="var(--primary)" v-model="useFileContent" :disabled="loading"/>
                </div>
                <div class="mb-8" v-if="useFileContent">
                    <file-select @input="loadFile" v-model="htmlFile" :messages="messages" />
                </div>
                <div class="mb-8" v-else>

                    <p class="mb-2">{{ messages['content-copy'] }}</p>
                    <div class="input-wrapper">
                        <quill-editor class="quill-editor"
                                      :options="quillEditorOptions"
                                      v-model="htmlContent"
                                      ref="myQuillEditor"
                        ></quill-editor>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <h3 class="text-base text-80 font-bold mb-3">{{ messages['send-preview'] }}</h3>
                <p class="mb-2">{{ messages['preview-copy'] }}</p>

                <button class="btn btn-default btn-primary" @click="sendMessage"
                        :disabled="isThinking || !formIsValid()">
                    {{ loading ? messages['send-message-loading'] : messages['send-message'] }}
                </button>
                <button class="btn btn-default btn-secondary" @click="preview"
                        :disabled="isThinking || !formIsValid()">
                    {{ gettingPreview ? messages['preview-loading'] : messages['preview'] }}
                </button>
            </div>
        </div>

        <div class="w-2/5">
            <div class="recipients-list px-6">
                <h3 class="text-base text-80 font-bold mb-3">{{ messages['recipients-list-header'] }}</h3>
                <div>
                    <ul class="divide-y divide-gray-200" style="padding-left: 0;">
                        <recipient-item :recipient="recipient"
                                        v-for="(recipient, index) of recipients"
                                        :key="index"
                                        @delete="removeRecipient(index)"
                        ></recipient-item>
                    </ul>
                </div>
                <div v-if="recipients.length === 0 && sendToAll === false" class="p-4 bg-danger rounded">
                    <p class="text-white">{{ messages['recipients-no-address-found'] }}</p>
                </div>
                <div v-if="sendToAll === true" class="p-4 bg-primary rounded">
                    <p class="text-white">{{ messages['recipients-send-all'] }}</p>
                </div>
            </div>
        </div>

        <preview-modal ref="previewModal" @preview="setGettingPreview"></preview-modal>
    </div>
</template>

<script>
    import 'quill/dist/quill.core.css'
    import 'quill/dist/quill.snow.css'
    import 'quill/dist/quill.bubble.css'

    import {quillEditor} from 'vue-quill-editor'

    import Translations from "../../mixins/Translations";
    import CounterInput from "../CounterInput";
    import RecipientForm from "../RecipientForm";
    import FileSelect from "../FileSelect";
    import PreviewModal from "../PreviewModal";
    import RecipientItem from "./RecipientItem";

    import StorageService from "../../services/StorageService";

    import { ToggleButton } from 'vue-js-toggle-button'

    // 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=7F9CF5&background=EBF4FF';

    export default {
        name: "MessageForm",
        mixins: [
            Translations,
        ],
        components: {
            PreviewModal,
            CounterInput,
            RecipientForm,
            FileSelect,
            quillEditor,
            ToggleButton,
            RecipientItem,
        },
        data() {
            return {
                from: '',
                subject: '',
                sendToAll: false,
                useFileContent: false,
                gettingPreview: false,
                recipients: [],
                htmlFile: null,
                htmlContent: '',
            }
        },
        computed: {
            /**
             * @return {Object}
             */
            config() {
                return StorageService.configuration;
            },
            /**
             * @return {boolean}
             */
            isThinking() {
                if (this.loading || this.gettingPreview) {
                    return true
                }

                return false
            },
            /**
             * @return {Object}
             */
            quillConfiguration() {
                if (!StorageService.configuration.editor) {
                    return {
                        toolbar: [
                            {
                                'header': 1
                            },
                            {
                                'header': 2
                            },
                            {
                                'list': 'ordered'
                            },
                            {
                                'list': 'bullet'
                            },
                            'bold',
                            'italic',
                            'link',
                        ]
                    }
                }

                return StorageService.configuration.editor;
            },
            /**
             * @return {Object}
             */
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
            /**
             * @name addAddress
             * @description Add recipient to the list
             * @param {Object} userObject
             */
            addAddress(userObject) {
                this.recipients.push(userObject);
            },
            loadFile(file) {
                const reader = new FileReader();
                reader.onload = e => {
                    this.$emit("load", e.target.result)
                    this.htmlContent = e.target.result
                }
                reader.readAsText(file);
            },
            /**
             * @name formIsValid
             * @description Is the form ready to be sent/submitted
             * @return {boolean}
             */
            formIsValid() {
                if (this.subject.length === 0 || this.htmlContent.length === 0) {
                    return false;
                }

                if (this.recipients.length === 0 && !this.sendToAll) {
                    return false;
                }

                return true;
            },
            /**
             * @param {boolean} loading
             * @return {void}
             */
            setLoading(loading = true) {
                if(!this.useFileContent) {
                    this.quillEditor.enable(!loading)
                }
                this.loading = loading;
            },
            /**
             * @name sendMessage
             * @description Sends the message with the defined
             * @return {void}
             */
            sendMessage() {
                let vm = this;

                vm.setLoading();

                Nova.request().post('/nova-vendor/custom-email-sender/send', {
                    from: vm.from,
                    subject: vm.subject,
                    sendToAll: vm.sendToAll,
                    recipients: vm.recipients,
                    htmlContent: this.htmlContent
                }).then(response => {
                    vm.$toasted.show(response.data, {type: 'success'});
                    vm.complete = true;
                    vm.setLoading(false);
                }).catch(error => {
                    let response = error.response;
                    let status = response.status

                    if (status === 422) {
                        this.$toasted.show(response.data.message, {type: 'error'})
                    } else {
                        this.$toasted.show(response.statusText, {type: 'error'})
                    }

                    vm.setLoading(false);
                });
            },
            /**
             * @param {boolean} loading
             */
            setGettingPreview(loading = true) {
                if(!this.useFileContent) {
                    this.quillEditor.enable(!loading)
                }
                this.gettingPreview = loading;
            },

            preview() {
                this.$refs.previewModal.preview();
            },

            reset() {
                this.subject = '';
                this.sendToAll = false;
                this.complete = false;
                this.recipients = [];
                this.htmlContent = '';
                this.useFileContent = false;
            },

            /**
             * @name removeRecipient
             * @param {Number} index
             * @return {void}
             */
            removeRecipient(index) {
                this.recipients.splice(index, 1);
            },
        }
    }
</script>

<style scoped>

</style>