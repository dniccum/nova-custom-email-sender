<template>
    <loading-card v-if="loading" class="flex flex-col px-6 py-4" style="min-height: 400px;"></loading-card>

    <div class="relative rounded overflow-hidden min-h-screen flex flex-row bg-white email-sender" v-else>
        <div class="flex-1 flex-row mb-8 lg:mb-20 p-8" v-if="!complete">
            <div class="flex flex-wrap md:w-full">
                <div class="w-full mb-6 md:mb-0 lg:pr-10">
                    <heading class="mb-6">{{ messages['create-new-message'] }}</heading>

                    <message-form ref="messageForm" @success="success"></message-form>
                </div>
            </div>
        </div>

        <success-panel v-else @reset="reset"></success-panel>
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
