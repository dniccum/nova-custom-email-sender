<template>
    <div class="input-wrapper w-full">
        <input
                ref="inputtag"
                class="form-control form-input form-input-bordered w-full"
                :placeholder="placeholder"
                type="text"
                v-model="interface"
                v-on:keydown="addNew"
                :disabled="disabled"
        />
        <div class="counter" :style="counterColor()" v-if="interface.length > 0">
            {{ counterNumber() }}
        </div>
    </div>
</template>

<script>
    export default {
        name: "CounterInput",
        props: {
            model: String,
            placeholder: String,
            limit: {
                type: Number,
                default: () => {
                    return 60
                }
            },
            disabled: {
                type: Boolean,
                default: false
            }
        },
        mounted() {
            //
        },
        computed: {
            interface: {
                get() {
                    return this.model
                },
                set(val) {
                    this.$emit('update:model', val)
                }
            },
            isLimit: function() {
                return this.limit > 0 && Number(this.limit) === this.interface.length;
            },
        },
        methods: {
            addNew(e) {
                let allowedKeys = [
                    8,
                    9,
                    16,
                    17,
                    18,
                    91,
                    93,
                ];

                if (allowedKeys.indexOf(e.which) >= 0 || !this.isLimit) {
                    return;
                }

                this.interface = this.interface.substring(0, this.limit);
                e && e.preventDefault();
            },

            counterNumber() {
                return this.limit - this.interface.length;
            },

            counterColor() {
                if (this.counterNumber() <= 30 && this.counterNumber() > 10) {
                    return {
                        color: 'var(--warning)',
                        fontSize: '1.1rem',
                        fontWeight: 'bold'
                    }
                } else if (this.counterNumber() <= 10 && this.counterNumber() >= 0) {
                    return {
                        color: 'var(--danger)',
                        fontSize: '1.3rem',
                        fontWeight: 'bold'
                    }
                } else {
                    return {
                        color: 'var(--80)'
                    }
                }
            }
        }
    }
</script>

<style scoped>
    .input-wrapper {
        position: relative;
    }
    .input-wrapper .form-input {
        padding-right: 46px;
    }
    .counter {
        position: absolute;
        width: 40px;
        height: 35px;
        right: 0;
        top: 0;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>