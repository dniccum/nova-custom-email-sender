<template>
    <div class="flex flex-1">
        <message-list class="flex flex-1 items-center justify-center text-70" v-if="loading">
            <loader class="text-60" />
        </message-list>
        <message-list class="flex" v-else-if="!loading && emailMessages.length === 0">
            <div class="flex flex-1 items-center justify-center text-70">
                {{ messages['no-messages'] }}
            </div>
        </message-list>
        <message-list v-else-if="!loading && emailMessages.length > 0">
            <email-card v-for="message of emailMessages"
                        :key="`key-${message.id}`"
                        :message="message"
                        @show="showMessage(message)"
                        :active="activeMessage && activeMessage.id === message.id"
            ></email-card>

            <infinite-loading spinner="spiral" direction="bottom" @infinite="infiniteHandler" v-if="emailMessages.length >= requestLimit">
                <span slot="no-more"></span>
                <span slot="no-results"></span>
            </infinite-loading>
        </message-list>

        <message-preview :message="activeMessage"
                         ref="messagePreview"
                         :show-resend="true"
                         @resend="showResendConfirm"
        ></message-preview>

        <confirm-modal v-if="confirmResend.visible"
                       :confirm-text="messages['yes-resend']"
                       :cancel-text="messages['nevermind']"
                       :working="confirmResend.loading"
                       @confirm="resendMessage"
                       @close="confirmResend.visible = false"
        >
            <template #header>
                {{ messages['are-you-sure'] }}
            </template>
            {{ messages['are-you-sure-resend-message'] }}
            <div class="p-2 bg-30 border border-50 rounded mt-2 mb-2">
                {{ confirmResend.message.subject }}
            </div>
        </confirm-modal>
    </div>
</template>

<script>
    import MessageList from "../MessageList";
    import EmailCard from "../EmailCard";
    import ApiService from "../../../services/ApiService";
    import Translations from "../../../mixins/Translations";
    import Scroller from "../../../mixins/Scroller";
    import Timestamp from "../../../mixins/Timestamp";
    import PreviewMessage from "../../../mixins/PreviewMessage";
    import ActionPane from "../ActionPane";
    import ConfirmModal from "../ConfirmModal";

    export default {
        name: "SentMessages",
        mixins: [
            Translations,
            Timestamp,
            PreviewMessage,
            Scroller,
        ],
        components: {
            ConfirmModal,
            ActionPane,
            MessageList,
            EmailCard,
        },
        data() {
            return {
                loading: true,
                emailMessages: [],
                confirmResend: {
                    visible: false,
                    message: {},
                    loading: false
                }
            }
        },
        async mounted() {
            try {
                let { data } = await ApiService.messages(0, this.requestLimit);
                this.emailMessages = data;
                this.loading = false;
            } catch (e) {
                console.error(e);
                this.$toasted.show(this.messages['general-messages-error'], {type: 'error'});
            }
        },
        methods: {
            /**
             * @name showResendConfirm
             * @param {Object} message
             * @return {void}
             */
            showResendConfirm(message) {
                this.confirmResend.message = message;
                this.confirmResend.visible = true;
            },

            async resendMessage() {
                this.confirmResend.loading = true;
                let { from, subject, send_to_all, recipients, content } = this.confirmResend.message;

                try {
                    await ApiService.sendMessage(
                        from,
                        subject,
                        send_to_all,
                        recipients,
                        content
                    );

                    this.loading = false;
                    this.activeMessage = null;
                    this.confirmResend.loading = false;
                    this.confirmResend.visible = false;
                    this.$toasted.show(this.messages['your-message-has-been-resent'], {type: 'success'});
                } catch (e) {
                    console.error(error);
                    this.$toasted.show(this.messages['there-was-a-problem-resending-your-message'], {type: 'error'})
                    this.confirmResend.loading = false;
                }
            },

            /**
             * @inheritDoc
             */
            async infiniteHandler($state) {
                let { data } = await ApiService.messages(this.emailMessages.length, this.requestLimit);

                if (data.length) {
                    this.emailMessages.push(...data);

                    $state.loaded();
                } else {
                    $state.complete();
                }
            }
        }
    }
</script>

<style scoped>

</style>