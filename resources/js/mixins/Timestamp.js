import momentTz from 'moment-timezone/builds/moment-timezone-with-data-10-year-range.min';
import StorageService from "../services/StorageService";

export default {
    methods: {
        /**
         * @param {String|Number} date
         * @return {String}
         */
        timestamp(date) {
            let { timezone, date_format } = StorageService.configuration.nebula_sender;

            return momentTz.unix(date)
                .tz(timezone)
                .format(date_format);
        }
    },
}