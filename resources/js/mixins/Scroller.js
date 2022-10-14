import InfiniteLoading from "infinite-loading-vue3";

export default {
    components: {
        InfiniteLoading,
    },
    computed: {
        /**
         * @name requestLimit
         * @return {number}
         */
        requestLimit() {
            return 25;
        }
    },
    methods: {
        /**
         * @name infiniteHandler
         * @param $state
         * @return {Promise<void>}
         */
        async infiniteHandler($state) {

        },
    },
}