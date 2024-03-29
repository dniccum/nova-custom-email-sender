<template>
    <div class="flex">
        <div style="flex: 3">
            <h3 class="text-base text-80 font-bold mb-3">{{ messages['from-header'] }}</h3>

            <div class="mb-8">
                <p class="mb-2 italic">{{ messages['from-copy'] }}</p>
                <select-control
                    @change="addressSelection"
                    class="w-full"
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
                                   v-model:model="subject"
                                   :disabled="isThinking"
                    ></counter-input>
                </div>
            </div>
            <div class="mb-8">
                <h3 class="text-base text-80 font-bold mb-3">{{ messages['recipients-header'] }}</h3>
                <recipient-form :messages="messages"
                                @add="addAddress"
                                v-model:send-to-all="sendToAll"
                                :loading="isThinking"
                                :recipients="recipients"
                ></recipient-form>
            </div>
            <div class="mb-8">
                <h3 class="text-base text-80 font-bold mb-3">{{ messages['content-header'] }}</h3>

                <div class="mb-6">
                    <p class="mb-2">{{ messages['toggle-use-file'] }}</p>
                    <toggle color="var(--primary)" v-model="useFileContent" :disabled="loading"/>
                </div>
                <div class="mb-8" v-if="useFileContent">
                    <file-select @input="loadFile" v-model="htmlFile" :messages="messages" />
                </div>
                <div class="mb-8" v-else>

                    <p class="mb-2">{{ messages['content-copy'] }}</p>
                    <div class="input-wrapper">
                        <QuillEditor theme="snow"
                                     class="quill-editor"
                                     :options="quillEditorOptions"
                                     v-model:content="htmlContent"
                                     contentType="html"
                                     ref="editor"
                        />
                    </div>
                </div>

                <div class="mt-4">
                    <div v-if="nebulaSenderActive">
                        <div v-if="existingMessage">
                            <h3 class="text-base text-80 font-bold mb-3">{{ messages['send-preview-update-draft'] }}</h3>
                            <p class="mb-2">{{ messages['preview-update-draft-copy'] }}</p>
                        </div>
                        <div v-else>
                            <h3 class="text-base text-80 font-bold mb-3">{{ messages['send-preview-draft'] }}</h3>
                            <p class="mb-2">{{ messages['preview-draft-copy'] }}</p>
                        </div>
                    </div>
                    <div v-else>
                        <h3 class="text-base text-80 font-bold mb-3">{{ messages['send-preview'] }}</h3>
                        <p class="mb-2">{{ messages['preview-copy'] }}</p>
                    </div>

                    <div class="flex" v-if="nebulaSenderActive">
                        <div class="flex-1">
                            <LoadingButton @click="sendMessage" :loading="isThinking" :disabled="isThinking || !formIsValid()" class="mr-4">
                                {{ loading ? messages['send-message-loading'] : messages['send-message'] }}
                            </LoadingButton>
                            <LoadingButton @click="saveDraft" :loading="isThinking" :disabled="isThinking || !draftIsValid()">
                                <span v-if="draftSaved">
                                    {{ draftSaving ? messages['updating'] : messages['update-draft'] }}
                                </span>
                                <span v-else>
                                    {{ draftSaving ? messages['saving'] : messages['save-draft'] }}
                                </span>
                            </LoadingButton>
                        </div>
                        <div class="text-right">
                            <LoadingButton component="OutlineButton" @click="preview" :loading="isThinking" :disabled="isThinking || !formIsValid()">
                                {{ loading ? messages['send-message-loading'] : messages['send-message'] }}
                            </LoadingButton>
                        </div>
                    </div>
                    <div v-else>
                        <LoadingButton class="mr-4" @click="sendMessage" :loading="loading" :disabled="isThinking || !formIsValid()">
                            {{ loading ? messages['send-message-loading'] : messages['send-message'] }}
                        </LoadingButton>
                        <LoadingButton component="OutlineButton" @click="preview" :loading="gettingPreview" :class="{ 'text-white' : isThinking }" :disabled="isThinking || !formIsValid()">
                            {{ gettingPreview ? messages['preview-loading'] : messages['preview'] }}
                        </LoadingButton>
                    </div>
                </div>

            </div>
        </div>
        <div style="flex: 2">
            <div class="recipients-list px-6">
                <h3 class="text-base text-80 font-bold mb-3">{{ messages['recipients-list-header'] }}</h3>
                <div>
                    <ul style="padding-left: 0;">
                        <recipient-item :recipient="recipient"
                                        v-for="(recipient, index) of recipients"
                                        :key="index"
                                        @delete="removeRecipient(index)"
                        ></recipient-item>
                    </ul>
                </div>

                <div v-if="!recipients && sendToAll === false || recipients.length === 0 && sendToAll === false" class="relative rounded p-4 overflow-hidden">
                    <div class="absolute w-full h-full bg-red-500" style="left: 0; top: 0;"></div>
                    <div class="relative flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-white" x-description="Heroicon name: x-circle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm leading-5 font-medium text-white">
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
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';

import Translations from "../mixins/Translations";
import CounterInput from "./CounterInput";
import RecipientForm from "./RecipientForm";
import FileSelect from "./FileSelect";
import PreviewModal from "./PreviewModal";
import RecipientItem from "./RecipientItem";

import StorageService from "../services/StorageService";

import Toggle from '@vueform/toggle'
import NebulaSenderService from "../services/NebulaSenderService";
import ApiService from "../services/ApiService";

export default {
    name: "MessageForm",
    mixins: [
        Translations,
    ],
    props: {
        existingMessage: Object
    },
    components: {
        PreviewModal,
        CounterInput,
        RecipientForm,
        FileSelect,
        Toggle,
        RecipientItem,
        QuillEditor,
    },
    data() {
        return {
            loading: false,
            draftSaving: false,
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
    beforeMount() {
        // Sets draft content
        if (this.existingMessage && !_.isEmpty(this.existingMessage)) {
            this.from = this.existingMessage.from;
            this.subject = this.existingMessage.subject;
            this.sendToAll = this.existingMessage.send_to_all;
            this.recipients = this.existingMessage.recipients;
            this.htmlContent = this.existingMessage.content;
            this.draftSaved = true;
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
            if (this.loading || this.gettingPreview || this.draftSaving) {
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
                        { 'header': 1 },
                        { 'header': 2 },
                        { 'list': 'ordered' },
                        { 'list': 'bullet' },
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
                placeholder: this.messages['content-placeholder'],
                theme: 'snow'
            }
        },
        quillEditor() {
            return this.$refs.editor.getQuill()
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
            if (this.subject && this.subject.length === 0 || this.htmlContent && this.htmlContent.length === 0) {
                return false;
            }

            if (this.recipients && this.recipients.length === 0 && !this.sendToAll) {
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
            return !(this.htmlContent.length === 0 || this.from.length === 0);
        },
        /**
         * @param {boolean} loading
         * @param {boolean} isDraft
         * @return {void}
         */
        setLoading(loading = true, isDraft = false) {
            if(!this.useFileContent) {
                this.quillEditor.enable(!loading)
            }
            if (isDraft) {
                this.draftSaving = loading;
            } else {
                this.loading = loading;
            }
        },
        /**
         * @name addressSelection
         * @description Handles the "From" address selection
         * @param {string} address
         */
        addressSelection(address) {
            this.from = address;
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
                Nova.success(response)
                vm.$emit('success')
                vm.setLoading(false);
            }).catch(error => {
                let status = error.status

                if (status === 422) {
                    Nova.error(error.data.message)
                } else {
                    Nova.error(error.statusText)
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
            this.setLoading(true, true);
            let template = StorageService.configuration.template.view;
            let request;

            if (this.existingMessage) {
                request = ApiService.updateDraft(
                    this.existingMessage.id,
                    this.htmlContent,
                    this.from,
                    this.existingMessage.template,
                    this.subject,
                    this.recipients,
                    this.sendToAll
                )
            } else {
                request = ApiService.createDraft(
                    this.from,
                    template,
                    this.htmlContent,
                    this.subject,
                    this.recipients,
                    this.sendToAll
                )
            }

            request.then(response => {
                Nova.success(this.messages['draft-saved'])

                if (this.existingMessage) {
                    this.setLoading(false, true);
                    this.$emit('update', response.data);
                } else {
                    this.$router.push({ name: 'nebula-sender-drafts-edit', params: { id: response.data.id }})
                }
            }).catch(error => {
                let status = error.status

                if (status === 422) {
                    Nova.error(error.data.message)
                } else {
                    Nova.error(error.statusText)
                }
                this.setLoading(false, true);
            })
        }
    }
}
</script>

<style scoped>

</style>