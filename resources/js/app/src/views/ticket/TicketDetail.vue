<script setup lang="ts">
import {ref, onUnmounted} from 'vue'
import {useApi} from '@/composables/useApi'
import {useRoute} from "vue-router";
import {toast} from 'vue3-toastify';

const route = useRoute()

interface Comment {
    id: number
    user_name: string
    text: string
    created_at: string
}

interface Ticket {
    id: number
    title: string
    description: string
    status: string
    created_at: string
    updated_at: string
    assigned: string
    comments: Comment[]
}

const ticket = ref<Ticket | null>(null)
const loading = ref(false)
const error = ref<string | null>(null)
const commentText = ref('')

const {api} = useApi()
let channel = null;
const fetchTicket = async (id: string) => {
    loading.value = true
    try {
        await api.get<Ticket>(`ticket/${route.params.id}`).then((r: Ticket) => {
            channel = window.Echo.private(`Ticket.${route.params.id}`);
            channel.listen('TicketCommentAddBroadcastEvent', e => {
                if (e.comment.action === 'create') {
                    ticket.value.comments.unshift(e.comment);
                    toast("New comment from " + e.comment.user_name);
                }
            });
            ticket.value = r.data
        })
    } catch (e: any) {
        console.error(e.message)
    } finally {
        loading.value = false
    }
}

const submitComment = async () => {
    api.post(`/ticket/${ticket.value.id}/comment`, {
        text: commentText.value.trim(),
    }).then(r => {
        commentText.value = ''
        ticket.value.comments.unshift(r.data)
    })

}

fetchTicket()

onUnmounted(() => {
    if (channel) {
        channel.stopListening('TicketCommentAddBroadcastEvent')
        window.Echo.leave(`Ticket.${route.params.id}`)
    }
})


</script>

<template>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-md">
        <div v-if="loading" class="text-gray-500 text-center py-10">
            Loading ticket...
        </div>

        <div v-if="ticket" class="space-y-6">
            <h1 class="text-3xl font-semibold text-gray-800">{{ ticket.title }}</h1>
            <p class="text-gray-700 leading-relaxed">{{ ticket.description }}</p>

            <div class="flex flex-wrap gap-6 text-sm text-gray-600">
                <p><span class="font-semibold">Status:</span> {{ ticket.status }}</p>
                <p><span class="font-semibold">Assigned to:</span> {{ ticket.assigned }}</p>
                <p><span class="font-semibold">Created at:</span> {{ new Date(ticket.created_at).toLocaleString() }}</p>
            </div>

            <form @submit.prevent="submitComment" class="mt-6">
                <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Add Comment</label>
                <textarea
                    id="comment"
                    v-model="commentText"
                    rows="4"
                    class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Write your comment here..."
                ></textarea>
                <button
                    type="submit"
                    class="mt-2 bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-300"
                >
                    Submit Comment
                </button>
            </form>
            <section>
                <h2 class="text-2xl font-semibold mb-4 border-b border-gray-200 pb-2">Comments</h2>
                <ul class="space-y-4 max-h-96 overflow-y-auto">
                    <li
                        v-for="comment in ticket.comments"
                        :key="comment.id"
                        class="bg-gray-500 p-4 rounded-md shadow-sm"
                    >
                        <div class="text-sm text-gray-500 mb-1">
                            <b class="text-gray-800">{{ comment.user_name }}</b>
                            <span class="ml-2 italic text-gray-400 text-xs">
                {{ new Date(comment.created_at).toLocaleString() }}
              </span>
                        </div>
                        <p class="text-gray-700">{{ comment.text }}</p>
                    </li>
                </ul>
            </section>
        </div>
    </div>
</template>
