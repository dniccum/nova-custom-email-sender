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
        props: {
            settings: Object
        },
        mixins: [
            Translations,
            CreateNewMessage,
        ],
        data() {
            return {
                loading: true,
            }
        },
        beforeMount() {
            this.getConfig();
        },
        mounted() {
            this.loading = false;
        },
        methods: {
            getConfig() {
                TranslationService.localization = this.settings.messages;
                StorageService.configuration = this.settings.config;
                this.config = this.settings.config;
            },

        }
    }
</script>

<style>

</style>
