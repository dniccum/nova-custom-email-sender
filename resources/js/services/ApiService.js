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
    }

}