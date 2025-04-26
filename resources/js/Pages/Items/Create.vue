<script setup>
import { useForm } from '@inertiajs/vue3'
import { Editor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import { onBeforeUnmount, ref } from 'vue'

const title = ref('Create Item')

const form = useForm({
    title: '',
    image: null,
    description: '',
})

const editor = new Editor({
    extensions: [StarterKit],
    content: '',
    onUpdate: ({ editor }) => {
        form.description = editor.getHTML()
    },
})

onBeforeUnmount(() => editor.destroy())

const handleImage = (e) => {
    form.image = e.target.files[0]
}

const submit = () => {
    form.post(route('items.store'))
}
</script>

<template>
    <Head :title="title" />

    <h1
        v-text="title"
        class="xl:text-4xl text-3xl font-medium text-neutral-900 xl:mb-8 mb-4"
    ></h1>

    <div class="bg-white rounded-2xl xl:p-10 p-6 border border-brand-200">
        <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-4 w-full mx-auto">
            <div class="form-row">
                <div class="form-col">
                    <label class="block font-semibold">Title</label>
                    <input v-model="form.title" type="text" class="input" />
                    <div v-if="form.errors.title" class="text-red-500">{{ form.errors.title }}</div>
                </div>

                <div class="form-col">
                    <label class="block font-semibold">Image</label>
                    <input @change="handleImage" type="file" />
                    <div v-if="form.errors.image" class="text-red-500">{{ form.errors.image }}</div>
                </div>

                <div class="form-col">
                    <label class="block font-semibold">Description</label>
                    <EditorContent :editor="editor" class="border p-2 rounded" />
                    <div v-if="form.errors.description" class="text-red-500">{{ form.errors.description }}</div>
                </div>
            </div>

            <button :disabled="form.processing" class="button">
                Submit
            </button>
        </form>
    </div>
</template>

<style scoped>

</style>
