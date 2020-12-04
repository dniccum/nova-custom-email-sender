/**
 * @name ApiService
 * @description - A service/factory that will handle all Ajax features
 */
export default {

    /**
     * @name drafts
     * @description Returns all of the drafts associated with this key
     * @param {Number|null} offset
     * @param {Number|null} limit
     * @return {Promise<Object>}
     */
    drafts(offset = null, limit = null) {
        return new Promise((resolve, reject) => {
            Nova.request().get(`/nova-vendor/custom-email-sender/nebula-sender-drafts`, {
                params: {
                    offset,
                    limit,
                }
            }).then(success => {
                return resolve(success.data)
            }).catch(error => {
                return reject(error.response.data);
            });
        });
    },

    /**
     * @name draft
     * @description Returns a drafted message
     * @param {Number} id
     * @return {Promise<Object>}
     */
    draft(id) {
        return new Promise((resolve, reject) => {
            Nova.request().get(`/nova-vendor/custom-email-sender/nebula-sender-drafts/${id}`).then(success => {
                return resolve(success.data)
            }).catch(error => {
                return reject(error.response.data);
            });
        });
    },

    /**
     * @name createDraft
     * @description Creates a new draft message
     * @param {String} from
     * @param {String} template
     * @param {String} content
     * @param {String|null} subject
     * @param {array|null} recipients
     * @param {boolean} send_to_all
     * @return {Promise<Object>}
     */
    createDraft(from, template, content, subject = null, recipients = [], send_to_all = false) {
        return new Promise((resolve, reject) => {
            Nova.request().post(`/nova-vendor/custom-email-sender/nebula-sender-drafts`, {
                from,
                template,
                content,
                subject,
                recipients,
                send_to_all,
            }).then(success => {
                return resolve(success.data)
            }).catch(error => {
                return reject(error.response.data);
            });
        });
    },

    /**
     * @name updateDraft
     * @description Updates an existing draft message
     * @param {Number} id
     * @param {String} content
     * @param {String|null} from
     * @param {String|null} template
     * @param {String|null} subject
     * @param {array|null} recipients
     * @param {boolean} send_to_all
     * @return {Promise<Object>}
     */
    updateDraft(id, content, from = null, template = null, subject = null, recipients = [], send_to_all = false) {
        return new Promise((resolve, reject) => {
            Nova.request().put(`/nova-vendor/custom-email-sender/nebula-sender-drafts/${id}`, {
                from,
                template,
                content,
                subject,
                recipients,
                send_to_all,
            }).then(success => {
                return resolve(success.data)
            }).catch(error => {
                return reject(error.response.data);
            });
        });
    },

    /**
     * @name deleteDraft
     * @description Deletes a draft by it's ID
     * @param {Number} id
     * @return {Promise<Object>}
     */
    deleteDraft(id) {
        return new Promise((resolve, reject) => {
            Nova.request().delete(`/nova-vendor/custom-email-sender/nebula-sender-drafts/${id}`).then(success => {
                return resolve(success.data)
            }).catch(error => {
                return reject(error.response.data);
            });
        });
    },

    /**
     * @name messages
     * @description Returns a list of available messages
     * @param {Number|null} offset
     * @param {Number|null} limit
     * @return {Promise<Object>}
     */
    messages(offset = null, limit = null) {
        return new Promise((resolve, reject) => {
            Nova.request().get(`/nova-vendor/custom-email-sender/nebula-sender-messages`, {
                params: {
                    offset,
                    limit,
                }
            }).then(success => {
                return resolve(success.data)
            }).catch(error => {
                return reject(error.response.data);
            });
        });
    },

    /**
     * @name clone
     * @description Clones an existing message; sent or draft
     * @param {Number} messageId
     * @return {Promise<Object>}
     */
    clone(messageId) {
        return new Promise((resolve, reject) => {
            Nova.request().post(`/nova-vendor/custom-email-sender/nebula-sender-clone/${messageId}`, {
                //
            }).then(success => {
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