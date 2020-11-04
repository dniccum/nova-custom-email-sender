<template>
    <div class="flex-row m-6">
        <div class="flex flex-wrap h-full w-full border-success rounded-lg relative overflow-hidden">
            <div class="success-background z-0"></div>
            <div class="content-wrapper flex items-center flex-col justify-center w-full p-8 relative z-10">
                <div class="img-wrapper flex items-center justify-center rounded-full">
                    <div class="relative" style="margin: 0 -40px;">
                        <lottie :options="successAnimation" :height="200" :width="200" />
                    </div>
                </div>

                <div class="w-1/2 mt-6 mb-6 text-center" style="color: var(--white)">
                    <h2 class="text-3xl mb-6">{{ messages['success-header'] }}</h2>
                    <p class="text-lg mb-8">{{ messages['success-copy'] }}</p>
                    <p class="text-center" v-if="nebulaSenderActive">
                        <router-link :to="{ name: 'nebula-sender-sent' }"
                                     class="btn btn-default btn-secondary mr-2"
                        >
                            {{ messages['view-sent-messages'] }}
                        </router-link>
                        <button class="btn btn-default btn-white text-primary"
                                type="button"
                                @click="$emit('reset')">
                            {{ messages['create-new-message'] }}
                        </button>
                    </p>
                    <p class="text-center" v-else>
                        <button class="btn btn-default btn-white text-primary" type="button" @click="$emit('reset')">
                            {{ messages['start-over'] }}
                        </button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Translations from "../mixins/Translations";
    import Lottie from 'vue-lottie';
    import * as ApprovedAnimation from "../animations/approved.json";
    import NebulaSenderService from "../services/NebulaSenderService";

    export default {
        name: "SuccessPanel",
        mixins: [
            Translations,
        ],
        components: {
            Lottie
        },
        props: {
            messages: Object
        },
        computed: {
            successAnimation() {
                return {
                    animationData: ApprovedAnimation,
                }
            },
            nebulaSenderActive() {
                return NebulaSenderService.active;
            }
        }
    }
</script>

<style scoped>
    .success-background {
        position: absolute;
        width: 100%;
        height: 100%;
        background-color: var(--success);
        opacity: .7;
        z-index: 0;
    }
    .img-wrapper {
        width: 120px;
        height: 120px;
        background-color: var(--white);
    }
</style>