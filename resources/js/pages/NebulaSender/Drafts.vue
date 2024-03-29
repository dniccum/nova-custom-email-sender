<template>
    <NebulaSenderLayout>
        <Head title="Drafts - Nebula Sender" />

        <div class="flex flex-1">
            <message-list class="flex flex-1 items-center justify-center" v-if="loading">
                <loader class="text-gray-700 dark:text-gray-200" />
            </message-list>
            <message-list class="flex" v-else-if="!loading && drafts.length === 0">
                <div class="flex flex-1 items-center justify-center text-gray-400 dark:text-gray-100">
                    {{ messages['no-drafts'] }}
                </div>
            </message-list>
            <message-list v-else-if="!loading && drafts.length > 0">
                <email-card v-for="draft of drafts"
                            :key="`key-${draft.id}`"
                            :show-drafts="true"
                            :message="draft"
                            @show="showMessage(draft)"
                            @edit="editDraft"
                            @delete="showDelete"
                            :active="activeMessage && activeMessage.id === draft.id"
                ></email-card>

                <infinite-loading spinner="spiral" direction="bottom" @infinite="infiniteHandler" v-if="drafts.length >= requestLimit">
                    <span slot="no-more"></span>
                    <span slot="no-results"></span>
                </infinite-loading>
            </message-list>

            <!--
            <message-preview :message="activeMessage"
                             ref="messagePreview"
                             :show-preview="false"
                             :show-edit="true"
                             :show-clone="true"
                             :show-delete="true"
                             @delete="showDelete"
            ></message-preview>

            <confirm-modal v-if="confirmDelete.visible"
                           :confirm-text="messages['yes-delete']"
                           :cancel-text="messages['nevermind']"
                           :working="confirmDelete.loading"
                           @confirm="deleteDraft"
                           @close="confirmDelete.visible = false"
                           type="danger"
            >
                <template #header>
                    {{ messages['are-you-sure'] }}
                </template>
                {{ messages['are-you-sure-delete-draft'] }}
                <div class="p-2 bg-30 border border-50 rounded mt-2 mb-2">
                    {{ confirmDelete.message.subject ? confirmDelete.message.subject : messages['no-subject'] }}
                </div>
            </confirm-modal>
            -->
        </div>
    </NebulaSenderLayout>
</template>

<script>
import MessageList from "../../components/NebulaSender/MessageList";
import EmailCard from "../../components/NebulaSender/EmailCard";
import ApiService from "../../services/ApiService";
import Translations from "../../mixins/Translations";
import PreviewMessage from "../../mixins/PreviewMessage";
import Scroller from "../../mixins/Scroller";
import ConfirmModal from "../../components/NebulaSender/ConfirmModal";
import NebulaSenderLayout from "../../components/NebulaSender/NebulaSenderLayout";
import TranslationService from "../../services/TranslationService";
import StorageService from "../../services/StorageService";

export default {
    name: "Drafts",
    props: {
        settings: Object
    },
    mixins: [
        Translations,
        PreviewMessage,
        Scroller,
    ],
    components: {
        MessageList,
        EmailCard,
        ConfirmModal,
        NebulaSenderLayout,
    },
    data() {
        return {
            loading: true,
            drafts: [],
            confirmDelete: {
                message: {},
                visible: false,
                loading: false,
            }
        }
    },
    beforeMount() {
        TranslationService.localization = this.settings.messages;
        StorageService.configuration = this.settings.config;
    },
    async mounted() {
        try {
            let { data } = await ApiService.drafts(0, this.requestLimit);
            this.drafts = data;
            this.loading = false;
        } catch (e) {
            console.error(e);
            Nova.error(this.messages['general-drafts-error'])
        }
    },
    methods: {
        /**
         * @name showDelete
         * @param {Number} id
         * @return {void}
         */
        editDraft(id) {
            this.$router.push({ name: 'nebula-sender-drafts-edit', params: { id }})
        },

        /**
         * @name showDelete
         * @param {Object} message
         * @return {void}
         */
        showDelete(message) {
            this.confirmDelete.visible = true;
            this.confirmDelete.message = message;
        },

        async deleteDraft() {
            this.confirmDelete.loading = true;
            let { id } = this.confirmDelete.message;

            await ApiService.deleteDraft(id);
            this.confirmDelete = {
                visible: false,
                loading: false,
                message: {},
            }
            // Disables the active message
            if (this.activeMessage && this.activeMessage.id === id) {
                this.activeMessage = null;
            }
            // Removes the deleted message from the draft list
            for(let i = 0; i < this.drafts.length; i++) {
                let target = this.drafts[i];
                if (target.id === id) {
                    this.drafts.splice(i, 1);
                    break;
                }
            }

            Nova.success(this.messages['draft-successfully-deleted'])
        },

        /**
         * @inheritDoc
         */
        async infiniteHandler($state) {
            let { data } = await ApiService.drafts(this.drafts.length, this.requestLimit);

            if (data.length) {
                this.drafts.push(...data);

                $state.loaded();
            } else {
                $state.complete();
            }
        }
    }
}
</script>

<style scoped>

</style>