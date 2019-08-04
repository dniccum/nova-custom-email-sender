<template>
    <div class="mb-8">
        <div class="mb-6">
            <p class="mb-2">{{ messages['recipients-toggle-copy'] }}</p>
            <toggle-button :width="60" :height="26" color="var(--primary)" v-model="sendToAllInterface" :disabled="loading" />
        </div>
        <transition name="slide-fade">
            <div  v-if="!sendToAllInterface">
                <p class="mb-2">{{ messages['recipients-manual-input-copy'] }}</p>
                <form @submit.prevent="addAdHocEmail" class="flex flex-wrap">
                    <input
                        class="form-control form-input form-input-bordered flex-1"
                        :placeholder="messages['recipients-manual-input-placeholder']"
                        type="text"
                        v-model="adHocEmailAddress"
                        :disabled="loading"
                    />
                    <button type="submit" class="btn btn-default btn-primary ml-4" :disabled="loading || !validateEmailAddress()">
                        {{ messages['add-address'] }}
                    </button>
                </form>
            </div>
        </transition>
    </div>
</template>

<script>
    import EmailInputTag from './EmailInputTag';
    import { ToggleButton } from 'vue-js-toggle-button'

    export default {
        name: "RecipientForm",
        components: {
            EmailInputTag,
            ToggleButton,
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
                manualRecipients: [],
                adHocEmailAddress: ''
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
                let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                let isValid = re.test(String(this.adHocEmailAddress).toLowerCase());

                return isValid;
            },

            addAdHocEmail() {
                if (this.validateEmailAddress() === false) {
                    return false;
                }

                this.$emit('add', {
                    'name': null,
                    'address': this.adHocEmailAddress
                });

                this.adHocEmailAddress = '';
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