import MessageForm from "../components/MessageForm";
import SuccessPanel from '../components/SuccessPanel';

export default {
    components: {
        MessageForm,
        SuccessPanel,
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