import moment from 'moment-timezone';
import StorageService from "../services/StorageService";

export default {
    methods: {
        /**
         * @param {String|Number} date
         * @return {String}
         */
        timestamp(date) {
            let { timezone, date_format } = StorageService.configuration.nebula_sender;

            return moment.unix(date)
                .tz(timezone)
                .format(date_format);
        }
    },
}