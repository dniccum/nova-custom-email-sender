<template>
    <loading-card v-if="loading" class="flex-1 w-full flex flex-col px-6 py-4" style="min-height: 400px;"></loading-card>

    <message-form-wrapper v-else>
        <div class="mb-8">
            <router-link :to="{ 'name': 'nebula-sender-drafts' }" class="btn inline-flex items-center btn-default btn-outline">
                <svg class="w-6 h-6 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                {{ messages['back-to-drafts'] }}
            </router-link>
        </div>
        <div class="mb-6">
            <heading class="mb-2">{{ messages['edit-draft'] }}</heading>
            <p class="text-sm text-80">{{ messages['draft-last-updated'] }} {{ timestamp(message.updated) }}</p>
        </div>

        <message-form ref="messageForm"
                      @success="success"
                      @update="updateDraft"
                      :existing-message="message"
        ></message-form>
    </message-form-wrapper>
</template>

<script>
    import CreateNewMessage from "../../../mixins/CreateNewMessage";
    import ApiService from "../../../services/ApiService";
    import Translations from "../../../mixins/Translations";
    import Timestamp from "../../../mixins/Timestamp";

    export default {
        name: "DraftEdit",
        mixins: [
            CreateNewMessage,
            Translations,
            Timestamp,
        ],
        components: {
            //
        },
        data() {
            return {
                loading: true,
                message: {}
            }
        },
        async mounted() {
            try {
                let { data } = await ApiService.draft(this.$route.params.id);
                this.message = data;
                this.$nextTick(() => {
                    this.loading = false;
                })
            } catch (e) {
                console.error(e);
                this.$toasted.show(this.messages['general-draft-error'], {type: 'error'});
            }
        },
        methods: {
            async success() {
                this.$router.push({ 'name' : 'nebula-sender-drafts' });

                try {
                    await ApiService.deleteDraft(this.message.id);
                } catch (error) {
                    console.error(error);
                    this.$toasted.show(this.messages['general-draft-error'], {type: 'error'});
                }
            },

            /**
             * @name updateDraft
             * @description Updates the base draft object
             * @param {Object} message
             */
            updateDraft(message) {
                this.message = message;
            }
        }
    }
</script>

<style scoped>

</style>