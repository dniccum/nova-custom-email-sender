<template>
    <loading-card v-if="loading" class="flex flex-col px-6 py-4" style="min-height: 400px;"></loading-card>

    <div class="email-sender" v-else>
        <message-form
                :quill-configuration="config.editor"
                :messages="messages"
        ></message-form>

        <preview-modal :close-copy="messages['close']" ></preview-modal>
    </div>
</template>

<script>
    import MessageForm from './MessageForm';
    import PreviewModal from './PreviewModal';

    export default {
        name: 'CustomEmailSender',
        components: {
            MessageForm,
            PreviewModal,
        },
        data() {
            return {
                loading: true,
                config: {
                    editor: {},
                },
                messages: {}
            }
        },
        mounted() {
            this.getConfig();
        },
        methods: {
            getConfig() {
                let vm = this;

                Nova.request().get('/nova-vendor/custom-email-sender/config').then(response => {
                    vm.config = response.data.config;
                    vm.messages = response.data.messages;
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
