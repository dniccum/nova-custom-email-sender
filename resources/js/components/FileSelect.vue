<template>
    <label class="file-select">
        <div class="btn btn-primary btn-default mr-2">
            <span >Select File</span>
        </div>
        <span v-if="value">Selected File: {{value.name}}</span>
        <input type="file" @change="handleFileChange"/>
    </label>
</template>

<script>
    export default {
        name: "FileSelect",
        props: {
            value: File
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
