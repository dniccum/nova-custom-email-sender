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
                <div v-if="nebulaSenderActive">
                    <h3 class="text-base text-80 font-bold mb-3">{{ messages['send-preview-draft'] }}</h3>
                    <p class="mb-2">{{ messages['preview-draft-copy'] }}</p>
                </div>
                <div v-else>
                    <h3 class="text-base text-80 font-bold mb-3">{{ messages['send-preview'] }}</h3>
                    <p class="mb-2">{{ messages['preview-copy'] }}</p>
                </div>

                <div class="flex" v-if="nebulaSenderActive">
                    <div class="flex-1">
                        <button class="btn btn-default btn-primary" @click="sendMessage"
                                :disabled="isThinking || !formIsValid()">
                            {{ loading ? messages['send-message-loading'] : messages['send-message'] }}
                        </button>
                        <button class="btn btn-default btn-primary" @click="saveDraft"
                                :disabled="isThinking || !draftIsValid()">
                            <span v-if="draftSaved">
                                {{ loading ? messages['updating'] : messages['update-draft'] }}
                            </span>
                            <span v-else>
                                {{ loading ? messages['saving'] : messages['save-draft'] }}
                            </span>
                        </button>
                    </div>
                    <div class="text-right">
                        <button class="btn btn-default btn-secondary" @click="preview"
                                :disabled="isThinking || !formIsValid()">
                            {{ gettingPreview ? messages['preview-loading'] : messages['preview'] }}
                        </button>
                    </div>
                </div>
                <div v-else>
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

                <div v-if="recipients.length === 0 && sendToAll === false" class="relative rounded-md p-4 overflow-hidden">
                    <div class="absolute w-full h-full bg-danger opacity-25" style="left: 0; top: 0;"></div>
                    <div class="relative flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-danger" x-description="Heroicon name: x-circle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm leading-5 font-medium text-danger">
                                {{ messages['recipients-no-address-found'] }}
                            </h3>
                        </div>
                    </div>
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
    import NebulaSenderService from "../../services/NebulaSenderService";
    import ApiService from "../../services/ApiService";

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
                loading: false,
                from: '',
                subject: '',
                sendToAll: false,
                useFileContent: false,
                gettingPreview: false,
                recipients: [],
                htmlFile: null,
                htmlContent: '',
                draftSaved: false,
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
            },
            /**
             * @return {boolean}
             */
            nebulaSenderActive() {
                return NebulaSenderService.active;
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
             * @name draftIsValid
             * @description Is the form ready to be saved to a draft
             * @return {boolean}
             */
            draftIsValid() {
                if (this.htmlContent.length === 0) {
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

                ApiService.sendMessage(
                    this.from,
                    this.subject,
                    this.sendToAll,
                    this.recipients,
                    this.htmlContent
                ).then(response => {
                    vm.$toasted.show(response, {type: 'success'});
                    vm.$emit('success')
                    vm.setLoading(false);
                }).catch(error => {
                    let status = error.status

                    if (status === 422) {
                        this.$toasted.show(error.data.message, {type: 'error'})
                    } else {
                        this.$toasted.show(error.statusText, {type: 'error'})
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
                this.$refs.previewModal.preview(
                    this.from,
                    this.subject,
                    this.recipients,
                    this.htmlContent,
                    this.sendToAll
                );
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

            saveDraft() {

            }
        }
    }
</script>

<style scoped>

</style>