<template>
    <div class="email-sender">
        <loader v-if="loading"></loader>

        <message-form
                :quill-configuration="config.editor"
                v-else></message-form>
    </div>
</template>

<script>
    import MessageForm from './MessageForm';
    import Loader from './Loader';

    export default {
        name: 'CustomEmailSender',
        components: {
            MessageForm,
            Loader,
        },
        data() {
            return {
                loading: true,
                config: {
                    editor: {}
                }
            }
        },
        mounted() {
            this.getConfig()
        },
        methods: {
            getConfig() {
                let vm = this;

                Nova.request().get('/nova-vendor/custom-email-sender/config').then(response => {
                    vm.config = response.data;
                }).catch(error => {
                    this.$toasted.show(error.response.data, { type: 'error' })
                }).finally(() => {
                    vm.loading = false;
                });
            },
        }
    }
</script>

<style>

</style>
