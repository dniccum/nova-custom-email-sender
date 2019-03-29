<template>
    <div class="flex-row">
        <div class="flex flex-wrap w-full border-success rounded-lg relative overflow-hidden">
            <div class="success-background"></div>
            <div class="content-wrapper flex items-center flex-col justify-center w-full p-8">
                <div class="img-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="#FFF"><defs><linearGradient y2="157.23" x2="0" y1="211.23" gradientUnits="userSpaceOnUse" id="0"><stop stop-color="#2fae61"/><stop offset="1" stop-color="#4bdf88"/></linearGradient></defs><circle r="28" cy="184.55" cx="768.86" fill="url(#0)" transform="matrix(.92857 0 0 .92857-681.94-139.37)"/><path d="m773.85 193.97l-1.89-1.89c-.259-.259-.574-.389-.945-.389-.371 0-.686.13-.945.389l-9.116 9.13-4.085-4.099c-.259-.259-.574-.389-.945-.389-.371 0-.686.13-.945.389l-1.89 1.89c-.259.259-.389.574-.389.945 0 .37.13.686.389.945l5.03 5.03 1.89 1.89c.259.259.574.389.945.389.37 0 .685-.13.945-.389l1.89-1.89 10.06-10.06c.259-.259.389-.574.389-.945 0-.37-.13-.685-.389-.945" fill="#fff" fill-opacity=".851" transform="matrix(1.33268 0 0 1.33268-985.46-232.86)"/></svg>
                </div>

                <div class="w-1/2 mt-6 mb-6 text-center" style="color: var(--white)">
                    <h2 class="text-3xl mb-6">{{ messages['success-header'] }}</h2>
                    <p class="text-lg mb-8">{{ messages['success-copy'] }}</p>
                    <p>
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
    export default {
        name: "SuccessPanel",
        props: {
            messages: Object
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
        z-index: -1;
    }
    .img-wrapper {
        width: 100px;
        height: 100px;
    }
</style>