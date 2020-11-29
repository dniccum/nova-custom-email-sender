<template>
    <div class="flex-1 flex items-center justify-center">
        <div class="font-medium text-60 text-4xl">
            {{ messages['select-compose-to-create-message'] }}
        </div>
    </div>
</template>

<script>
    import Translations from "../../../mixins/Translations";

    export default {
        name: "Home",
        mixins: [
            Translations
        ]
    }
</script>

<style scoped>

</style>