import TranslationService from "../services/TranslationService";

export default {
    computed: {
        /**
         * @name messages
         * @description Returns an object of available translation properties and strings
         * @return {Object}
         */
        messages() {
            return TranslationService.localization;
        }
    }
}