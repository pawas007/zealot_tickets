<script setup lang="ts">
defineProps<{
    currentPage: number
    lastPage: number
}>()

const emit = defineEmits<{
    (e: 'page-change', page: number): void
}>()
</script>
<template>
    <div
        v-if="lastPage > 1"
        class="flex justify-center items-center gap-4 mt-8 select-none">
        <button
            :disabled="currentPage === 1"
            @click="emit('page-change', currentPage - 1)"
            class="px-4 py-2 rounded bg-blue-600 text-white disabled:bg-gray-300 disabled:cursor-not-allowed  transition-colors duration-300 cursor-pointer">
            Prev
        </button>
        <span class="text-gray-700 font-medium">
      Page {{ currentPage }} of {{ lastPage }}
    </span>
        <button
            :disabled="currentPage === lastPage"
            @click="emit('page-change', currentPage + 1)"
            class="px-4 py-2 rounded bg-blue-600 text-white disabled:bg-gray-300 disabled:cursor-not-allowed  transition-colors duration-300 cursor-pointer">
            Next
        </button>
    </div>
</template>

