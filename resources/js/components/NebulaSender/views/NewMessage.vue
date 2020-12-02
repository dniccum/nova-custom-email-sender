<template>
    <div class="flex-1 flex-row mb-8 lg:mb-20 p-8" v-if="!complete">
        <div class="flex flex-wrap md:w-full">
            <div class="w-full mb-6 md:mb-0 lg:pr-10">
                <heading class="mb-6">{{ messages['create-new-message'] }}</heading>

                <message-form ref="messageForm" @success="success"></message-form>
            </div>
        </div>
    </div>

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