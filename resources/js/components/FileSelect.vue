<template>
    <div>
        <label class="block mb-2">{{ messages['select-content-file'] }}</label>
        <label class="file-select">
            <div class="btn btn-primary btn-default mr-2">
                <span >{{ messages['select-file'] }}</span>
            </div>
            <span v-if="value">{{ messages['selected-file'] }} {{value.name}}</span>
            <input type="file" accept="text/html,.html" @change="handleFileChange"/>
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
            }
        }
    }
</script>

<style scoped>
    .file-select > input[type="file"] {
        display: none;
    }
</style>
