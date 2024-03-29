<template>
    <Card class="card px-3 pt-3" :class="{ 'active' : active, 'show-drafts': showDrafts, 'pb-3' : !showDrafts, }">
        <div class="button-wrapper z-50 flex items-center justify-end" v-if="showDrafts">
            <button @click="$emit('edit', message.id)" type="button" class="action-button rounded-full text-80 hover:text-white hover:bg-primary mr-2" :title="messages['edit-draft']">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                    <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                </svg>
            </button>
            <button @click="$emit('delete', message)" type="button" class="action-button rounded-full text-gray-800 dark:text-gray-500 hover:text-white hover:bg-red-500" :title="messages['delete-draft']">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        <div class="mb-3">
            <div class="font-bold mb-1">
                <span v-if="message.subject && message.subject.length > 0">
                    {{ message.subject }}
                </span>
                <span class="italic" v-else>
                    {{ messages['no-subject'] }}
                </span>
            </div>
            <div class="text-gray-600 dark:text-gray-500 font-light font-italic text-xs">
                {{ timestamp(message.created) }}
            </div>
        </div>
        <div class="font-light text-gray-900 dark:text-gray-400 text-sm">
            {{ contentPreview }}
        </div>
        <div class="card-click-overlay" @click="select"></div>
    </Card>
</template>

<script>
    import Timestamp from "../../mixins/Timestamp";
    import Translations from "../../mixins/Translations";

    export default {
        name: "EmailCard",
        mixins: [
            Timestamp,
            Translations,
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
            showDrafts: {
                type: Boolean,
                default: false
            }
        },
        computed: {
            /**
             * @return {string}
             */
            contentPreview() {
                if (this.message.content && this.message.content.length > 0) {
                    let strippedContent = this.stripped(this.message.content);
                    return this.limit(strippedContent, 80);
                }

                return '';
            }
        },
        methods: {
            select() {
                this.$emit('show', this.message);
            },
            /**
             * @name limit
             * @description Will truncate text based on the size of the numeric value
             * @param {String} value
             * @param {Number} size
             * @return {string}
             */
            limit(value, size) {
                if (!value) return '';
                value = value.toString();

                if (value.length <= size) {
                    return value;
                }
                return value.substr(0, size) + '...';
            },
            /**
             * @name stripped
             * @description Will remove any HTML content from a string
             * @param {String} value
             * @return {void|undefined|string|*}
             */
            stripped(value) {
                let regex = /(<([^>]+)>)/ig;
                return value.replace(regex, "");
            }
        }
    }
</script>

<style scoped>

</style>