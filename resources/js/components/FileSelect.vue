<template>
    <div>
        <label class="block mb-2">{{ messages['select-content-file'] }}</label>
        <label class="file-select">
            <DefaultButton class="mr-2" @click="trigger">
                <span >{{ messages['select-file'] }}</span>
            </DefaultButton>
            <span v-if="value">{{ messages['selected-file'] }} {{value.name}}</span>
            <input type="file" ref="file-input" accept="text/html,.html" @change="handleFileChange"/>
        </label>
    </div>
</template>

<script>
    export default {
        name: "FileSelect",
        props: {
            value: File,
            messages: {
                type: Object,
                required: true
            }
        },

        methods: {
            handleFileChange(e) {
                this.$emit('input', e.target.files[0])
            },
            trigger() {
                this.$refs["file-input"].click()
            }
        }
    }
</script>

<style scoped>
    .file-select > input[type="file"] {
        display: none;
    }
</style>
