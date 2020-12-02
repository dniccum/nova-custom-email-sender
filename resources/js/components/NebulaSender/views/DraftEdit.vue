<template>
    <div>
        Draft Edit
    </div>
</template>

<script>
    import MessageList from "../MessageList";
    import EmailCard from "../EmailCard";
    import ApiService from "../../../services/ApiService";
    import Translations from "../../../mixins/Translations";

    export default {
        name: "DraftEdit",
        mixins: [
            Translations,
        ],
        components: {
            MessageList,
            EmailCard,
        },
        data() {
            return {
                loading: true,
                drafts: []
            }
        },
        async mounted() {
            console.log()
            try {
                let { data } = await ApiService.draft(this.$route.params.id);
                console.log(data);
            //     this.drafts = data;
            //     this.loading = false;
            } catch (e) {
                console.error(e);
                // this.$toasted.show(this.messages['general-drafts-error'], {type: 'error'});
            }
        }
    }
</script>

<style scoped>

</style>