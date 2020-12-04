import InfiniteLoading from 'vue-infinite-loading';

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