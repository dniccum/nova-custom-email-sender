/**
 * @name EmailUtility
 * @type {Object}
 */
const EmailUtility = {

    /**
     * @name validateEmailAddress
     * @description validates the email address
     * @param address
     * @return {boolean}
     */
    validateEmailAddress(address) {
        let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        let isValid = re.test(String(address).toLowerCase());

        return isValid;
    },

};

export default EmailUtility;