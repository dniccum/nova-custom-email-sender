<template>
    <NebulaSenderLayout>
        <Head title="New - Nebula Sender" />
        <message-form-wrapper v-if="!complete">
            <heading class="mb-6">{{ messages['create-new-message'] }}</heading>

            <message-form ref="messageForm" @success="success"></message-form>
        </message-form-wrapper>

        <success-panel v-else @reset="reset"></success-panel>
    </NebulaSenderLayout>
</template>

<script>
    import NebulaSenderLayout from '../../components/NebulaSender/NebulaSenderLayout';
    import Translations from "../../mixins/Translations";
    import TranslationService from "../../services/TranslationService";
    import StorageService from "../../services/StorageService";
    import CreateNewMessage from "../../mixins/CreateNewMessage";

    export default {
        name: "New",
        props: {
            settings: Object
        },
        mixins: [
            Translations,
            CreateNewMessage,
        ],
        components: {
            NebulaSenderLayout
        },
        data() {
            return {
                complete: false,
            }
        },
        beforeMount() {
            TranslationService.localization = this.settings.messages;
            StorageService.configuration = this.settings.config;
        },
        mounted() {
            console.log(this)
        }
    }
</script>

<style scoped>

</style>