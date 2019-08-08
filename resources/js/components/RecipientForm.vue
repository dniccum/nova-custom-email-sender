<template>
    <div class="mb-8">
        <div class="mb-6">
            <p class="mb-2">{{ messages['recipients-toggle-copy'] }}</p>
            <toggle-button :width="60" :height="26" color="var(--primary)" v-model="sendToAllInterface" :disabled="loading" />
        </div>
        <transition name="slide-fade">
            <div  v-if="!sendToAllInterface">
                <p class="mb-2">{{ messages['recipients-manual-input-copy'] }}</p>
                <form id="email-search-form" @submit.prevent="searchSubmit" class="flex flex-wrap">
                    <auto-complete-input
                            class="form-control flex-1"
                            name="search"
                            :loading.sync="loading"
                            :model.sync="search"
                            :results.sync="searchResults"
                            @search="performSearch"
                            @select="selectResult"
                            @ad-hoc="addAdHocEmail"
                            :placeholder="messages['recipients-manual-input-placeholder']"
                            :messages="messages"
                    ></auto-complete-input>
                </form>
            </div>
        </transition>
    </div>
</template>

<script>
    import EmailInputTag from './EmailInputTag';
    import AutoCompleteInput from './AutoCompleteInput';
    import { ToggleButton } from 'vue-js-toggle-button'
    import EmailUtility from "../services/EmailUtility";

    export default {
        name: "RecipientForm",
        components: {
            EmailInputTag,
            ToggleButton,
            AutoCompleteInput,
        },
        props: {
            messages: Object,
            sendToAll: {
                type: Boolean,
                default() {
                    return false;
                }
            },
            loading: {
                type: Boolean,
                default() {
                    return false;
                }
            },
            recipients: {
                type: Array,
                default() {
                    return []
                }
            }
        },
        data() {
            return {
                loading: false,
                searchResults: [],
                search: ''
            }
        },
        computed: {
            sendToAllInterface: {
                get() {
                    return this.sendToAll
                },
                set(val) {
                    this.$emit('update:send-to-all', val)
                }
            },
            recipientsInterface: {
                get() {
                    return this.recipients
                },
                set(val) {
                    this.$emit('update:recipients', val)
                }
            },
        },
        methods: {
            validateEmailAddress() {
                return EmailUtility.validateEmailAddress(this.search);
            },

            addAdHocEmail() {
                if (this.searchResults.length > 0 || this.validateEmailAddress() === false) {
                    return false;
                }

                this.$emit('add', {
                    'name': null,
                    'email': this.search
                });

                this.search = '';
            },

            searchSubmit() {
                if (this.searchResults.length === 1) {
                    this.$emit('add', this.searchResults[0]);
                    this.searchResults.length = 0;
                    this.search = '';
                } else {
                    this.addAdHocEmail();
                }
            },

            performSearch($e) {
                Nova.request().get('/nova-vendor/custom-email-sender/search', {
                    params: {
                        search: $e.query
                    },
                    timeout: $e.timeout
                }).then(results => {
                    this.searchResults = results.data;
                    this.loading = false;
                })
            },

            selectResult(result) {
                for (let i = 0; i < this.searchResults.length; i++) {
                    let target = this.searchResults[i];

                    if (result.email === target.email) {
                        this.$emit('add', target);
                    }
                }
            }
        }
    }
</script>

<style scoped>
    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }
    .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
        opacity: 0;
    }
    .slide-fade-enter-active {
        transition: all .3s ease;
    }
    .slide-fade-leave-active {
        transition: all .5s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }
    .slide-fade-enter, .slide-fade-leave-to
        /* .slide-fade-leave-active below version 2.1.8 */ {
        transform: translateX(10px);
        opacity: 0;
    }
</style>