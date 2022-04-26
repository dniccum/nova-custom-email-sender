<template>
    <div>
        <Head title="Custom Email Sender" />

        <loading-card v-if="loading" class="flex flex-col px-6 py-4" style="min-height: 400px;"></loading-card>

        <Card
            class="flex flex-col items-start justify-center"
            style="min-height: 300px"
            v-else
        >
            <message-form-wrapper v-if="!complete">
                <heading class="mb-6">{{ messages['create-new-message'] }}</heading>

                <message-form ref="messageForm" @success="success"></message-form>
            </message-form-wrapper>

            <success-panel v-else @reset="reset"></success-panel>
        </Card>
    </div>
</template>

<script>
    import NebulaSenderService from "../services/NebulaSenderService";
    import Translations from "../mixins/Translations";
    import CreateNewMessage from "../mixins/CreateNewMessage";
    import TranslationService from "../services/TranslationService";
    import StorageService from "../services/StorageService";

    export default {
        name: 'CustomEmailSender',
        mixins: [
            Translations,
            CreateNewMessage,
        ],
        data() {
            return {
                loading: true,
            }
        },
        mounted() {
            this.getConfig();
        },
        methods: {
            getConfig() {
                Nova.request().get('/nova-vendor/custom-email-sender/config').then(response => {
                    this.config = response.data.config;

                    TranslationService.localization = response.data.messages;
                    StorageService.configuration = response.data.config;

                    if (response.data.nebula_sender_active) {
                        NebulaSenderService.active = true;
                        this.$router.push('/custom-email-sender/nebula-sender')
                    }
                    this.loading = false;
                }).catch(error => {
                    this.$toasted.show(error.response.data, { type: 'error' })
                    this.loading = false;
                });
            },

        }
    }
</script>

<style>

</style>
