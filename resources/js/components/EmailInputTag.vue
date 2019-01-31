<template>
    <div
        @click="focusNewTag()"
        :class="{
            'read-only': readOnly,
            'vue-input-tag-wrapper--active': isInputActive,
        }"
        class="vue-input-tag-wrapper"
    >
        <span v-for="(tag, index) in innerTags" :key="index" class="input-tag">
            <span>{{ tag }}</span>
            <a v-if="!readOnly" @click.prevent.stop="remove(index)" class="remove"></a>
        </span>
        <input
                v-if="!readOnly && !isLimit"
                ref="inputtag"
                :placeholder= "placeholder"
                type= "text"
                v-model= "newTag"
                v-on:keydown.delete.stop="removeLastTag"
                v-on:keydown= "addNew"
                v-on:blur= "handleInputBlur"
                v-on:focus= "handleInputFocus"
                :class= "cssClasses"
                @keydown.stop="handleKeydown"
        />
    </div>
</template>

<script>
    export default {
        name: "EmailInputTag",

        props: {
            value: {
                type: Array,
                default: () => []
            },
            placeholder: {
                type: String,
                default: ""
            },
            readOnly: {
                type: Boolean,
                default: false
            },
            validate: {
                type: String | Function | Object,
                default: ""
            },
            addTagOnKeys: {
                type: Array,
                default: function() {
                    return [
                        13, // Return
                        188, // Comma ','
                        9 // Tab
                    ];
                }
            },
            addTagOnBlur: {
                type: Boolean,
                default: false
            },
            limit: {
                type: Number,
                default: -1
            },
            allowDuplicates: {
                type: Boolean,
                default: false
            }
        },

        data() {
            return {
                newTag: "",
                innerTags: [...this.value],
                isInputActive: false
            };
        },

        computed: {
            isLimit: function() {
                return this.limit > 0 && Number(this.limit) === this.innerTags.length;
            },
            cssClasses() {
                return `new-tag`
            }
        },

        methods: {
            handleKeydown() {
                // resets default
            },

            focusNewTag() {
                if (this.readOnly || !this.$el.querySelector(".new-tag")) {
                    return;
                }
                this.$el.querySelector(".new-tag").focus();
            },

            handleInputFocus() {
                this.isInputActive = true;
            },

            handleInputBlur(e) {
                this.isInputActive = false;
                this.addNew(e);
            },

            addNew(e) {
                const keyShouldAddTag = e
                    ? this.addTagOnKeys.indexOf(e.keyCode) !== -1
                    : true;

                const typeIsNotBlur = e && e.type !== "blur";

                if (
                    (!keyShouldAddTag && (typeIsNotBlur || !this.addTagOnBlur)) ||
                    this.isLimit
                ) {
                    return;
                }

                if (
                    this.newTag &&
                    (this.allowDuplicates || this.innerTags.indexOf(this.newTag) === -1) &&
                    this.validateIfNeeded(this.newTag)
                ) {
                    this.innerTags.push(this.newTag);
                    this.newTag = "";
                    this.tagChange();

                    e && e.preventDefault();
                }
            },

            validateIfNeeded(tagValue) {
                if (this.validate === "" || this.validate === undefined) {
                    return true;
                }

                if (typeof this.validate === "function") {
                    return this.validate(tagValue);
                }

                if (
                    typeof this.validate === "string" &&
                    Object.keys(validators).indexOf(this.validate) > -1
                ) {
                    return validators[this.validate].test(tagValue);
                }

                if (
                    typeof this.validate === "object" &&
                    this.validate.test !== undefined
                ) {
                    return this.validate.test(tagValue);
                }

                return true;
            },

            remove(index) {
                this.innerTags.splice(index, 1);
                this.tagChange();
            },

            removeLastTag() {
                if (this.newTag) {
                    return;
                }
                this.innerTags.pop();
                this.tagChange();
            },

            tagChange() {
                this.$emit("update:tags", this.innerTags);
                this.$emit("input", this.innerTags);
            }
        }
    };
</script>

<style scoped>

</style>