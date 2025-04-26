<script setup>
import { onMounted, ref } from 'vue'
import axios from 'axios'
import { Link, usePage } from '@inertiajs/vue3'

const title = ref("Items");
const page = usePage()
const items = ref([...page.props.initialItems])
const nextCursor = ref(page.props.nextCursor)
const loading = ref(false)

const scrollArea = ref(null)

const loadMore = async () => {
    if (!nextCursor.value || loading.value) return

    loading.value = true
    const { data } = await axios.get('/items', {
        params: { cursor: nextCursor.value },
        headers: { Accept: 'application/json' }
    })

    items.value.push(...data.data)
    nextCursor.value = data.next_cursor
    loading.value = false
}

const onScroll = () => {
    const bottomReached = scrollArea.value.scrollHeight - scrollArea.value.scrollTop <= scrollArea.value.clientHeight + 200
    if (bottomReached) loadMore()
}

onMounted(() => {
    scrollArea.value.addEventListener('scroll', onScroll)
})
</script>

<template>
    <Head :title="title" />

    <h1
        v-text="title"
        class="xl:text-4xl text-3xl font-medium text-neutral-900 xl:mb-8 mb-4"
    ></h1>

    <div class="bg-white rounded-2xl xl:p-10 p-6 border border-brand-200">
        <Link href="/items/create" class="block text-blue-500 mb-4">+ Add New Item</Link>

        <div ref="scrollArea" class="w-full scroll-area">
            <div v-for="item in items" :key="item.id" class="p-4 border-b">
                <div class="flex items-start gap-4">
                    <img v-if="item.image_path" :src="`/storage/${item.image_path}`" alt="Item Image" class="w-16 h-16 object-contain flex-shrink-0">
                    <div class="flex flex-col">
                        <h2 class="font-bold">{{ item.title }}</h2>
                        <div v-html="item.description" class="text-gray-700"></div>
                    </div>
                </div>
            </div>

            <div v-if="loading" class="text-center py-4">Loading...</div>
        </div>
    </div>
</template>

<style scoped>
.scroll-area {
    max-height: 500px;
    overflow-y: auto;
}
</style>
