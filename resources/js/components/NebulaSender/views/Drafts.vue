<template>
    <div class="flex flex-1">
        <message-list class="flex flex-1 items-center justify-center text-70" v-if="loading">
            <loader class="text-60" />
        </message-list>
        <message-list class="flex" v-else-if="!loading && drafts.length === 0">
            <div class="flex flex-1 items-center justify-center text-70">
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
        </message-list>

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
                {{ confirmDelete.message.subject }}
            </div>
        </confirm-modal>
    </div>
</template>

<script>
    import MessageList from "../MessageList";
    import EmailCard from "../EmailCard";
    import ApiService from "../../../services/ApiService";
    import Translations from "../../../mixins/Translations";
    import PreviewMessage from "../../../mixins/PreviewMessage";
    import ConfirmModal from "../ConfirmModal";

    export default {
        name: "Drafts",
        mixins: [
            Translations,
            PreviewMessage,
        ],
        components: {
            MessageList,
            EmailCard,
            ConfirmModal,
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
        async mounted() {
            try {
                let { data } = await ApiService.drafts();
                this.drafts = data;
                this.loading = false;
            } catch (e) {
                console.error(e);
                this.$toasted.show(this.messages['general-drafts-error'], {type: 'error'});
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

                this.$toasted.show(this.messages['draft-successfully-deleted'], {type: 'success'});
            }
        }
    }
</script>

<style scoped>

</style>