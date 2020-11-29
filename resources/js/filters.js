/**
 * @name Filters
 */
export default {

    /**
     * @name limit
     * @description Will truncate text based on the size of the numeric value
     * @param {String} value
     * @param {Number} size
     * @return {string}
     */
    limit(value, size) {
        if (!value) return '';
        value = value.toString();

        if (value.length <= size) {
            return value;
        }
        return value.substr(0, size) + '...';
    },

    /**
     * @name stripped
     * @description Will remove any HTML content from a string
     * @param {String} value
     * @return {void|undefined|string|*}
     */
    stripped(value) {
        let regex = /(<([^>]+)>)/ig;
        return value.replace(regex, "");
    }
}