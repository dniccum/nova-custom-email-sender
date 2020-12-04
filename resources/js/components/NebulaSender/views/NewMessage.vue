<template>
    <message-form-wrapper v-if="!complete">
        <heading class="mb-6">{{ messages['create-new-message'] }}</heading>

        <message-form ref="messageForm" @success="success"></message-form>
    </message-form-wrapper>

    <success-panel v-else @reset="reset"></success-panel>
</template>

<script>
    import Translations from "../../../mixins/Translations";
    import CreateNewMessage from "../../../mixins/CreateNewMessage";
    import NebulaSenderService from "../../../services/NebulaSenderService";

    export default {
        name: "NewMessage",
        mixins: [
            Translations,
            CreateNewMessage,
        ],
        data() {
            return {
                complete: false,
            }
        },
        computed: {
            config() {
                return NebulaSenderService.configuration;
            }
        },
        methods: {
            //
        }
    }
</script>

<style scoped>

</style>