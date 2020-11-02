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
    import Translations from "../mixins/Translations";

    export default {
        name: "PreviewModal",
        mixins: [
            Translations,
        ],
        data() {
            return {
                visible: false,
                previewContent: null
            }
        },
        mounted() {
            let vm = this;

            Nova.$on('show-email-preview', (response) => {
                vm.visible = true;
                document.getElementById('preview-frame').contentWindow.document.write(response);
            })
        },
        computed: {
            /**
             * @return {string}
             */
            closeCopy() {
                return this.messages['close']
            }
        },
        methods: {
            close() {
                this.visible = false;
                document.getElementById('preview-frame').contentWindow.document.body.innerHTML = '';
            },

            preview() {
                let vm = this;

                this.$emit('preview', true)

                Nova.request().post('/nova-vendor/custom-email-sender/preview', {
                    subject: vm.subject,
                    sendToAll: vm.sendToAll,
                    recipients: vm.recipients,
                    htmlContent: vm.htmlContent,
                    from: vm.from
                }).then(response => {
                    document.getElementById('preview-frame').contentWindow.document.write(response);
                    vm.visible = true;
                }).catch(error => {
                    let response = error.response;
                    let status = response.status

                    if (status === 422) {
                        this.$toasted.show(response.data.message, {type: 'error'})
                    } else {
                        this.$toasted.show(response.statusText, {type: 'error'})
                    }
                    this.$emit('preview', false)
                });
            },
        }
    }
</script>

<style scoped>
    .iframe-wrapper {
        width: 600px;
        height: calc(80vh - 60px);
    }
</style>