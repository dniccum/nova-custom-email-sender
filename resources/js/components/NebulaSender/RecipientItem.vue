<template>
    <li class="py-4 flex items-center border-b border-50">
        <img class="h-9 w-9 rounded-full" :src="imageUrl" alt="">
        <div class="flex flex-col flex-1 mx-3">
            <span class="text-sm leading-5 font-bold text-80" v-if="recipient.name && recipient.name.length > 0">
                {{ recipient.name }}
            </span>
            <span class="text-sm leading-5 text-60">{{ recipient.email }}</span>
        </div>
        <div>
            <button type="button" @click="$emit('delete')" :title="messages['remove']"
                    class="appearance-none cursor-pointer text-70 hover:text-danger mr-3">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </li>
</template>

<script>
    import Translations from "../../mixins/Translations";

    export default {
        name: "RecipientItem",
        mixins: [
            Translations,
        ],
        props: {
            recipient: Object
        },
        computed: {
            imageUrl() {
                let name = '';
                if (this.recipient.name && this.recipient.name.length > 0) {
                    name = this.recipient.name;
                } else {
                    name = this.recipient.email.slice(0, 2);
                }
                return `https://ui-avatars.com/api/?name=${encodeURI(name)}&color=7F9CF5&background=EBF4FF`;
            }
        }
    }
</script>

<style scoped>

</style>