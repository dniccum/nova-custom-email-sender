/**
 * @class
 * @classdesc A user/recipient that will receive a message
 */
export default class Recipient {
    /**
     * @constructor
     * @param {string} email
     * @param {string|null} name
     */
    constructor(email, name = null) {
        /**
         * @type {string}
         */
        this.email = email;

        /**
         * @type {string|null}
         */
        this.name = name;
    }
}