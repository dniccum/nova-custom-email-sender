<template>
    <loading-card v-if="loading" class="flex flex-col px-6 py-4" style="min-height: 400px;"></loading-card>

    <div class="email-sender" v-else>
        <message-form
            :quill-configuration="config.editor"
            :messages="messages"
            :from-select-options="config.from"
        ></message-form>

        <preview-modal :close-copy="messages['close']" ></preview-modal>
    </div>
</template>

<script>
    import MessageForm from './MessageForm';
    import PreviewModal from './PreviewModal';
    import NebulaSenderService from "../services/NebulaSenderService";

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
                messages: {},
            }
        },
        mounted() {
            this.getConfig();
        },
        methods: {
            getConfig() {
                Nova.request().get('/nova-vendor/custom-email-sender/config').then(response => {
                    this.config = response.data.config;
                    this.messages = response.data.messages;
                    NebulaSenderService.localization = response.data.messages;
                    if (response.data.nebula_sender_active) {
                        NebulaSenderService.active = true;
                        this.$router.push('/custom-email-sender/nebula-sender')
                    }
                    this.loading = false;
                }).catch(error => {
                    this.$toasted.show(error.response.data, { type: 'error' })
                    this.loading = false;
                });
            },
        }
    }
</script>

<style>

</style>
