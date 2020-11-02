<template>
    <div class="flex-1 flex-row mb-8 lg:mb-20 p-8" v-if="!complete">
        <div class="flex flex-wrap md:w-full">
            <div class="w-full mb-6 md:mb-0 lg:pr-10">
                <heading class="mb-6">{{ messages['create-new-message'] }}</heading>

                <message-form></message-form>
            </div>
        </div>
    </div>


    <!--
    <div class="email-sender">
        <message-form
            :quill-configuration="config.editor"
            :messages="messages"
            :from-select-options="config.from"
        ></message-form>

        <preview-modal :close-copy="messages['close']" ></preview-modal>
    </div>
    -->
</template>

<script>
    import Translations from "../../../mixins/Translations";
    import MessageForm from '../MessageForm';
    import PreviewModal from '../../PreviewModal';
    import NebulaSenderService from "../../../services/NebulaSenderService";

    export default {
        name: "NewMessage",
        mixins: [
            Translations,
        ],
        components: {
            MessageForm,
            PreviewModal,
        },
        data() {
            return {
                complete: false
            }
        },
        computed: {
            config() {
                return NebulaSenderService.configuration;
            }
        }
    }
</script>

<style scoped>

</style>