<template>
    <div class="wrapper">
        <input type="text" class="w-full form-control form-input form-input-bordered"
               :name="name" :id="name" v-model="interface" :disabled="this.$attrs.disabled" autocomplete="off" :placeholder="placeholder" @blur="blur">

        <div class="auto-complete-box" v-if="showResults" v-click-outside="clickOutside">
            <div class="auto-complete-loader" v-if="loadingInterface">
                <loading-card v-if="loading" class="flex flex-col px-6 py-4" style="min-height: 60px;"></loading-card>
            </div>
            <div class="auto-complete-result" @click="selectResult(result)" v-if="!loadingInterface && searchResults.length >= 1" v-for="result of searchResults">
                <span class="font-bold">{{ result.name }}</span>
                <span class="font-italic font-light"><{{ result.email }}></span>
            </div>
            <div class="auto-complete-no-results flex flex-row justify-center items-center" style="padding: 10px 0;" v-if="!loadingInterface && searchResults.length === 0 && searchIsEmail()">
                <span class="mr-4">{{ messages['recipients-click-to-add'] }}</span>
                <button @click="$emit('ad-hoc')" type="button" class="btn btn-default btn-primary">
                    {{ messages['add-address'] }}
                </button>
            </div>
            <div class="auto-complete-no-results" v-if="!loadingInterface && searchResults.length === 0 && !searchIsEmail()">
                {{ messages['recipients-no-results'] }}.
            </div>
        </div>
    </div>
</template>

<script>
    import EmailUtility from "../services/EmailUtility";

    export default {
        name: "AutocompleteInput",
        props: {
            messages: Object,
            name: String,
            model: String,
            loading: {
                type: Boolean,
                default: false
            },
            results: {
                type: Array,
                default: () => {
                    return []
                }
            },
            noResultsActionButtonText: {
                type: String,
                default: ''
            },
        },
        data() {
            return {
                showResults: false,
                canceller: Promise,
                timer: null,
                ignoreSearch: true,
            }
        },
        computed: {
            interface: {
                get() {
                    return this.model;
                },
                set(val) {
                    this.$emit('update:model', val)
                }
            },
            loadingInterface: {
                get() {
                    return this.loading;
                },
                set(val) {
                    this.$emit('update:loading', val)
                }
            },
            searchResults: {
                get() {
                    return this.results;
                },
                set(val) {
                    this.$emit('update:results', val)
                }
            },
            placeholder() {
                if (this.$attrs.placeholder) {
                    return this.$attrs.placeholder
                } else {
                    return 'Search...'
                }
            },
        },
        watch: {
            model: function (newValue, oldValue) {
                let vm = this;

                if (!vm.ignoreSearch) {
                    if (newValue !== oldValue) {
                        if (!newValue || newValue === '') {
                            vm.searchResults = [];
                            vm.loadingInterface = false;
                            vm.showResults = false;
                        } else {
                            if (newValue.length >= 1) {
                                vm.loadingInterface = true;
                                vm.showResults = true;

                                vm.canceller.resolve();
                                clearTimeout(vm.timer);

                                vm.timer = setTimeout(() => {
                                    vm.performSearch();
                                }, 300);
                            }
                        }
                    }
                }

                vm.ignoreSearch = false;
            }
        },
        methods: {
            performSearch() {
                let vm = this;

                vm.canceller = Promise;
                vm.loadingInterface = true;
                vm.searchResults.length = 0;

                this.$emit('search', {
                    query: vm.model,
                    timeout: vm.canceller
                })
            },

            selectResult(result) {
                let vm = this;

                vm.$emit('select', result);

                for (let i = 0; i < this.searchResults.length; i++) {
                    let target = this.searchResults[i];

                    if (result.email === target.email) {
                        this.searchResults.splice(i, 1);
                    }
                }

                if (this.searchResults.length === 0) {
                    setTimeout(() => {
                        vm.interface = '';
                        vm.showResults = false;
                        vm.loadingInterface = false;
                    }, 100)
                }
            },

            blur() {
                let vm = this;

                setTimeout(() => {
                    vm.$emit('blur')
                }, 200)
            },

            noResults() {
                let vm = this;

                setTimeout(() => {
                    vm.showResults = false;
                    vm.loadingInterface = false;
                }, 100);

                vm.$emit('no-results');
            },

            clickOutside($event) {
                let path = $event.path;

                for (let i = 0; i < path.length; i++) {
                    let target = path[i];

                    if (target.id === 'email-search-form') {
                        return;
                    }
                }

                this.showResults = false;
                this.loadingInterface = false;

                this.$emit('click-outside')
            },

            searchIsEmail() {
                return EmailUtility.validateEmailAddress(this.model);
            }
        }
    }
</script>

<style scoped>
    .wrapper {
        position: relative;
    }
    .auto-complete-box {
        background-color: var(--20);
        border: 1px solid var(--40);
    }
    .auto-complete-box .auto-complete-result {
        color: var(--80)
    }
    .auto-complete-box .auto-complete-result:hover {
        background-color: var(--white);
        color: var(--primary)
    }
    .auto-complete-box .auto-complete-no-results {
        color: var(--90)
    }
</style>