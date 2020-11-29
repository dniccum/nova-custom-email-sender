<template>
    <modal
        data-testid="nebula-sender-confirm-modal"
        tabindex="-1"
        role="dialog"
        @modal-close="handleClose"
    >
        <form
            autocomplete="off"
            @keydown="handleKeydown"
            @submit.prevent.stop="handleConfirm"
            class="w-action bg-white rounded-lg shadow-lg overflow-hidden"
        >
            <div>
                <heading :level="2" class="border-b border-40 py-8 px-8">
                    <slot name="header"></slot>
                </heading>

                <p class="text-80 px-8 my-8">
                    <slot></slot>
                </p>
            </div>

            <div class="bg-30 px-6 py-3 flex">
                <div class="flex items-center ml-auto">
                    <button
                        dusk="cancel-confirm-button"
                        type="button"
                        @click.prevent="handleClose"
                        class="btn btn-link dim cursor-pointer text-80 ml-auto mr-6"
                    >
                        {{ cancelText }}
                    </button>

                    <button
                        ref="runButton"
                        dusk="confirm-action-button"
                        :disabled="working"
                        type="submit"
                        class="btn btn-default"
                        :class="`btn-${type}`"
                    >
                        <loader v-if="working" width="30"></loader>
                        <span v-else>{{ confirmText }}</span>
                    </button>
                </div>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    props: {
        working: Boolean,
        type: {
            type: String,
            default: 'danger',
        },
        confirmText: {
            type: String,
            default: "Yes"
        },
        cancelText: {
            type: String,
            default: "Cancel"
        }
    },

    /**
     * Mount the component.
     */
    mounted() {
        // If the modal has inputs, let's highlight the first one, otherwise
        // let's highlight the submit button
        if (document.querySelectorAll('.modal input').length) {
            document.querySelectorAll('.modal input')[0].focus()
        } else {
            this.$refs.runButton.focus()
        }
    },

    methods: {
        /**
         * Stop propogation of input events unless it's for an escape or enter keypress
         */
        handleKeydown(e) {
            if (['Escape', 'Enter'].indexOf(e.key) !== -1) {
                return
            }

            e.stopPropagation()
        },

        /**
         * Execute the selected action.
         */
        handleConfirm() {
            this.$emit('confirm')
        },

        /**
         * Close the modal.
         */
        handleClose() {
            this.$emit('close')
        },
    },
}
</script>
