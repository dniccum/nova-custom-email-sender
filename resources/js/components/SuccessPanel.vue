<template>
    <div class="flex-row flex-1 m-6">
        <div class="flex flex-wrap h-full w-full border-success rounded-lg relative overflow-hidden">
            <div class="success-background z-0"></div>
            <div class="content-wrapper flex items-center flex-col justify-center w-full p-8 relative z-10">
                <div class="img-wrapper flex items-center justify-center rounded-full">
                    <div class="relative" style="margin: 0 -40px;">
                        <svg width="200" height="150" viewBox="0 0 800 600" fill="none" xmlns="http://www.w3.org/2000/svg" data-reactroot="">
                            <path d="M401.998 586.29C560.113 586.29 688.29 458.113 688.29 299.998C688.29 141.884 560.113 13.707 401.998 13.707C243.884 13.707 115.707 141.884 115.707 299.998C115.707 458.113 243.884 586.29 401.998 586.29Z" fill="#62a8a4"></path>
                            <path d="M402 590C241.795 590 112 460.204 112 300C112 139.795 241.795 10 402 10C469.493 10 534.762 33.734 586.68 76.0102L582.23 81.9436C531.795 40.4091 468.01 17.4169 402.742 17.4169C246.987 17.4169 120.158 144.245 120.158 300C120.158 455.754 246.987 582.583 402.742 582.583C479.135 582.583 551.079 552.174 604.481 498.031L609.672 503.222C554.046 558.849 480.619 590 402 590Z" fill="#C3E5DB"></path>
                            <path d="M676.424 232.508C671.974 213.224 665.299 194.682 656.398 177.623L663.074 174.656C671.974 192.457 678.649 211.741 683.099 231.025L676.424 232.508Z" fill="#C3E5DB"></path>
                            <path d="M312.251 423.859H255.141C250.69 423.859 246.24 420.151 246.24 414.959V241.404C246.24 236.954 249.949 232.504 255.141 232.504H312.251C316.701 232.504 321.151 236.212 321.151 241.404V414.959C321.151 420.151 317.442 423.859 312.251 423.859ZM255.141 239.921C254.399 239.921 253.657 240.662 253.657 241.404V414.959C253.657 415.701 254.399 416.442 255.141 416.442H312.251C312.992 416.442 313.734 415.701 313.734 414.959V241.404C313.734 240.662 312.992 239.921 312.251 239.921H255.141Z" fill="#97e0ca"></path>
                            <path d="M308.55 258.464H339.701C355.277 258.464 368.627 245.855 368.627 229.538V157.594C368.627 145.727 381.977 138.311 392.361 145.727L402.003 152.403C416.837 162.786 424.995 179.845 424.995 197.646V235.472C424.995 248.08 435.379 257.722 447.246 257.722H520.673C538.473 257.722 552.566 271.814 552.566 289.615V390.484C552.566 414.218 533.282 433.502 509.548 433.502H389.394C379.752 433.502 370.11 429.794 361.952 423.86L307.809 380.101V258.464H308.55Z" fill="#97e0ca"></path>
                            <path d="M324.116 430.532H267.006C264.04 430.532 261.814 428.307 261.814 425.341V251.785C261.814 248.819 264.04 246.594 267.006 246.594H324.116C327.083 246.594 329.308 248.819 329.308 251.785V425.341C329.308 428.307 327.083 430.532 324.116 430.532Z" fill="#C3E5DB"></path>
                        </svg>
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
                        <DefaultButton type="button" @click="$emit('reset')">
                            {{ messages['start-over'] }}
                        </DefaultButton>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Translations from "../mixins/Translations";
    import NebulaSenderService from "../services/NebulaSenderService";

    export default {
        name: "SuccessPanel",
        mixins: [
            Translations,
        ],
        computed: {
            successAnimation() {
                return {
                    // animationData: ApprovedAnimation,
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
        width: 200px;
        height: 200px;
        /*background-color: var(--white);*/
    }
</style>