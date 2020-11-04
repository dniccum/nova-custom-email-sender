<template>
    <div class="flex flex-1">
        <message-list class="flex flex-1 items-center justify-center text-70" v-if="loading">
            <loader class="text-60" />
        </message-list>
        <message-list class="flex" v-else-if="!loading && drafts.length === 0">
            <div class="flex flex-1 items-center justify-center text-70">
                {{ messages['no-drafts'] }}
            </div>
        </message-list>
        <message-list v-else-if="!loading && drafts.length > 0">
            <email-card v-for="draft of drafts"
                        :key="`key-${draft.id}`"
            ></email-card>
        </message-list>
    </div>
</template>

<script>
    import NebulaSenderLayout from "../NebulaSenderLayout";
    import MessageList from "../MessageList";
    import EmailCard from "../EmailCard";
    import ApiService from "../../../services/ApiService";
    import Translations from "../../../mixins/Translations";

    export default {
        name: "Drafts",
        mixins: [
            Translations,
        ],
        components: {
            MessageList,
            NebulaSenderLayout,
            EmailCard,
        },
        data() {
            return {
                loading: true,
                drafts: []
            }
        },
        async mounted() {
            try {
                let { data } = await ApiService.drafts();
                this.drafts = data;
                this.loading = false;
            } catch (e) {
                console.error(e);
                this.$toasted.show(this.messages['general-drafts-error'], {type: 'error'});
            }
        }
    }
</script>

<style scoped>

</style>