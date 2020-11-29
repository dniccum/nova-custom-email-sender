<template>
    <card class="px-6 py-4" :class="{ 'active' : active }">
        <div class="mb-3">
            <div class="font-bold mb-1">
                {{ message.subject }}
            </div>
            <div class="text-80 font-light font-italic text-xs">
                {{ timestamp(message.created) }}
            </div>
        </div>
        <div class="font-light text-90 text-sm">
            {{ contentPreview }}
        </div>
        <div class="card-click-overlay" @click="select"></div>
    </card>
</template>

<script>
    import Timestamp from "../../mixins/Timestamp";

    export default {
        name: "EmailCard",
        mixins: [
            Timestamp,
        ],
        props: {
            active: {
                type: Boolean,
                default: false
            },
            message: {
                type: Object,
                required: true,
            },
        },
        computed: {
            /**
             * @return {string}
             */
            contentPreview() {
                if (this.message.content && this.message.content.length > 0) {
                    let strippedContent = this.$options.filters.stripped(this.message.content);
                    return this.$options.filters.limit(strippedContent, 80);
                }

                return '';
            }
        },
        methods: {
            select() {
                this.$emit('show', this.message);
            }
        }
    }
</script>

<style scoped>

</style>