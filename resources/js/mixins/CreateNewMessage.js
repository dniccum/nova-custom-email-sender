import MessageFormWrapper from "../components/MessageFormWrapper";
import MessageForm from "../components/MessageForm";
import SuccessPanel from '../components/SuccessPanel';

export default {
    components: {
        MessageForm,
        SuccessPanel,
        MessageFormWrapper,
    },
    data() {
        return {
            complete: false,
        }
    },
    methods: {
        /**
         * @name reset
         * @return {void}
         */
        reset() {
            this.complete = false;
            this.$nextTick(() => {
                this.$refs.messageForm.reset();
            })
        },
        /**
         * @name success
         * @return {void}
         */
        success() {
            this.complete = true;
        }
    },
}