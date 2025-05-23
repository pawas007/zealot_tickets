<script setup lang="ts">
import { ref } from 'vue'
import type { TicketComment } from '@/types/ticket'
import { useAuthStore } from '@/stores/auth'

const props = defineProps<{
    comments: TicketComment[]
}>()

const emit = defineEmits<{
    (e: 'submit', text: string): void
    (e: 'delete', id: number): void
}>()

const auth = useAuthStore()
const attemptedSubmit = ref(false)
const commentText = ref('')

const submit = () => {
    attemptedSubmit.value = true
    const text = commentText.value.trim()
    if (!text) return

    emit('submit', text)
    commentText.value = ''
    attemptedSubmit.value = false
}

const canDelete = (comment: TicketComment) => {
    return auth.user?.id === comment.user_id
}

const deleteComment = (id: number) => {
    emit('delete', id)
}
</script>
<template>
    <div>
        <form @submit.prevent="submit" class="mt-6">
            <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Add Comment</label>
            <textarea
                id="comment"
                v-model="commentText"
                rows="4"
                :class="[
          'w-full border rounded-md p-2 focus:outline-none focus:ring-2',
          attemptedSubmit && !commentText.trim()
            ? 'border-red-500 focus:ring-red-500'
            : 'border-gray-300 focus:ring-blue-500'
        ]"
                placeholder="Write your comment here..."
            ></textarea>
            <button
                type="submit"
                class="mt-2 bg-blue-600 text-white px-5 py-2 rounded-lg transition-colors duration-300 cursor-pointer"
            >
                Submit Comment
            </button>
        </form>

        <section>
            <h2 class="mt-2 text-2xl font-semibold mb-4 border-b border-gray-200 pb-2">Comments</h2>
            <ul class="space-y-4 max-h-96 overflow-y-auto">
                <li
                    v-for="comment in comments"
                    :key="comment.id"
                    class="p-4 rounded-md shadow-sm border border-gray-200">
                    <div class="flex justify-between text-sm text-gray-500 mb-1">
                        <div>
                            <span class="ml-2 italic text-gray-400 text-xs">{{ new Date(comment.created_at).toLocaleString() }}</span>
                        </div>
                        <button
                            v-if="canDelete(comment)"
                            @click="deleteComment(comment.id)"
                            class="text-red-500 text-xs cursor-pointer">
                            Delete
                        </button>
                    </div>
                    <p class="text-gray-700">{{ comment.text }}</p>
                </li>
                <li v-if="!comments.length">
                    No comments found for this ticket
                </li>
            </ul>
        </section>
    </div>
</template>


