/**
 * @name ApiService
 * @description - A service/factory that will handle all Ajax features
 */
export default {

    /**
     * @name drafts
     * @description Returns all of the drafts associated with this key
     * @return {Promise<Object>}
     */
    drafts() {
        return new Promise((resolve, reject) => {
            Nova.request().get(`/nova-vendor/custom-email-sender/nebula-sender-drafts`).then(success => {
                return resolve(success.data)
            }).catch(error => {
                return reject(error.response.data);
            });
        });
    },

    /**
     * @name sendMessage
     * @description Sends the message
     * @param {string} from
     * @param {string} subject
     * @param {boolean} sendToAll
     * @param {array} recipients
     * @param {string} htmlContent
     * @return {Promise<Object>}
     */
    sendMessage(from, subject, sendToAll, recipients= [], htmlContent) {
        return new Promise((resolve, reject) => {
            Nova.request().post('/nova-vendor/custom-email-sender/send', {
                from,
                subject,
                sendToAll,
                recipients,
                htmlContent,
            }).then(response => {
                return resolve(response.data)
            }).catch(error => {
                return reject(error.response);
            });
        })
    }
}