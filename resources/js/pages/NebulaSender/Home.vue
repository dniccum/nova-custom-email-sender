<template>
    <NebulaSenderLayout>
        <Head title="Nebula Sender Home" />
        <div class="flex-1 flex items-center justify-center">
            <div class="font-medium text-60 text-4xl">
                {{ messages['select-compose-to-create-message'] }}
            </div>
        </div>
    </NebulaSenderLayout>
</template>

<script>
    import NebulaSenderLayout from '../../components/NebulaSender/NebulaSenderLayout';
    import Translations from "../../mixins/Translations";
    import TranslationService from "../../services/TranslationService";
    import StorageService from "../../services/StorageService";

    export default {
        name: "Home",
        props: {
            settings: Object
        },
        mixins: [
            Translations
        ],
        components: {
            NebulaSenderLayout
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