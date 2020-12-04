import MessagePreview from "../components/NebulaSender/MessagePreview";

export default {
    components: {
        MessagePreview
    },
    data() {
        return {
            activeMessage: null
        }
    },
    methods: {
        /**
         * @name showMessage
         * @param {Object} message
         */
        showMessage(message) {
            this.activeMessage = message;
            this.$refs.messagePreview.preview(message.html);
        },
    },
}