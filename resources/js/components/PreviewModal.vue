<template>
    <div class="modal-wrapper fixed w-screen h-screen pin z-30 flex flex-col items-center justify-center" v-show="visible">
        <div class="background absolute w-full h-full" style="background-color: var(--primary); opacity: .8"></div>
        <button class="btn btn-default btn-white text-primary z-10 mb-8" type="button" @click="close">
            {{ closeCopy }}
        </button>
        <div class="iframe-wrapper relative z-10">
            <iframe id="preview-frame" class="absolute w-full h-full pin rounded-lg"></iframe>
        </div>
        <button class="btn btn-default btn-white text-primary z-10 mt-8" type="button" @click="close">
            {{ closeCopy }}
        </button>
    </div>
</template>

<script>
    export default {
        name: "PreviewModal",
        props: {
            closeCopy: String,
        },
        data() {
            return {
                visible: false
            }
        },
        mounted() {
            let vm = this;

            Nova.$on('show-email-preview', (response) => {
                vm.visible = true;
                document.getElementById('preview-frame').contentWindow.document.write(response);
            })
        },
        methods: {
            close() {
                this.visible = false;
                document.getElementById('preview-frame').contentWindow.document.body.innerHTML = '';
            }
        }
    }
</script>

<style scoped>
    .iframe-wrapper {
        width: 600px;
        height: calc(80vh - 60px);
    }
</style>