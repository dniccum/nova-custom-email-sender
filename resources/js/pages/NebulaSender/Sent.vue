<template>
    <NebulaSenderLayout>
        <Head title="Sent Messages - Nebula Sender" />

        <div class="flex flex-1">
            <message-list class="flex flex-1 items-center justify-center" v-if="loading">
                <loader class="text-gray-700 dark:text-gray-200" />
            </message-list>
            <message-list class="flex" v-else-if="!loading && emailMessages.length === 0">
                <div class="flex flex-1 items-center justify-center text-gray-700 dark:text-gray-200">
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

            <confirm-modal v-model:visible="confirmResend.visible"
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
                <div class="p-2 bg-gray-300 dark:bg-gray-700 border border-50 rounded mt-2 mb-2">
                    {{ confirmResend.message.subject }}
                </div>
            </confirm-modal>
        </div>
    </NebulaSenderLayout>
</template>

<script>
import MessageList from "../../components/NebulaSender/MessageList";
import EmailCard from "../../components/NebulaSender/EmailCard";
import ApiService from "../../services/ApiService";
import Translations from "../../mixins/Translations";
import PreviewMessage from "../../mixins/PreviewMessage";
import Scroller from "../../mixins/Scroller";
import ConfirmModal from "../../components/NebulaSender/ConfirmModal";
import NebulaSenderLayout from "../../components/NebulaSender/NebulaSenderLayout";
import TranslationService from "../../services/TranslationService";
import StorageService from "../../services/StorageService";
import Timestamp from "../../mixins/Timestamp";
import ActionPane from "../../components/NebulaSender/ActionPane";

export default {
    name: "Sent",
    props: {
        settings: Object
    },
    mixins: [
        Translations,
        Timestamp,
        PreviewMessage,
        Scroller,
    ],
    components: {
        MessageList,
        EmailCard,
        ConfirmModal,
        NebulaSenderLayout,
        ActionPane,
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
    beforeMount() {
        TranslationService.localization = this.settings.messages;
        StorageService.configuration = this.settings.config;
    },
    async mounted() {
        try {
            let { data } = await ApiService.messages(0, this.requestLimit);
            this.emailMessages = data;
            this.loading = false;
        } catch (e) {
            console.error(e);
            Nova.error(this.messages['general-messages-error'])
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
                Nova.success(this.messages['your-message-has-been-resent'])
            } catch (e) {
                console.error(error);
                Nova.error(this.messages['there-was-a-problem-resending-your-message'])
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