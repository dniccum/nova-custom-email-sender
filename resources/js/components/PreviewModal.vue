<template>
    <div class="modal-wrapper fixed w-screen h-screen pin z-30 flex flex-col items-center justify-center" v-show="visible">
        <div class="background absolute w-full h-full"></div>
        <DefaultButton type="button" @click="close" class=" z-10 mb-8">
            {{ closeCopy }}
        </DefaultButton>
        <div class="iframe-wrapper relative z-10">
            <iframe id="preview-frame" class="absolute w-full h-full pin rounded-lg"></iframe>
        </div>
        <DefaultButton type="button" @click="close" class=" z-10 mt-8">
            {{ closeCopy }}
        </DefaultButton>
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

            /**
             * @name preview
             * @param {string} from
             * @param {string} subject
             * @param {array} recipients
             * @param {string} htmlContent
             * @param {boolean} sendToAll
             */
            preview(from, subject, recipients, htmlContent, sendToAll = false) {
                let vm = this;

                this.$emit('preview', true)

                Nova.request().post('/nova-vendor/custom-email-sender/preview', {
                    from,
                    subject,
                    recipients,
                    htmlContent,
                    sendToAll,
                }).then(response => {
                    document.getElementById('preview-frame').contentWindow.document.write(response.data.content);
                    vm.visible = true;
                    this.$emit('preview', false)
                }).catch(error => {
                    let response = error.response;
                    let status = response.status

                    if (status === 422) {
                        Nova.error(response.data.message)
                    } else {
                        Nova.error(response.statusText)
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