<script setup lang="ts">
import {ref, onUnmounted} from 'vue'
import {useRoute, useRouter} from 'vue-router'
import {toast} from 'vue3-toastify'
import {useApi} from '@/composables/useApi'
import TicketComments from '@/components/ticket/TicketComment.vue'
import type {Ticket, TicketComment} from '@/types/ticket'
import {useFormatLabel} from '@/composables/useFormatLabel'

const {formatLabel} = useFormatLabel()
const route = useRoute()
const {api} = useApi()
const router = useRouter()
const ticket = ref<Ticket | null>(null)
const loading = ref(false)

let channel: any = null

const fetchTicket = async () => {
    loading.value = true
    try {
        const response = await api.get<Ticket>(`ticket/${route.params.id}`)
        ticket.value = response.data
        channel = window.Echo.private(`Ticket.${route.params.id}`)
        channel.listen('TicketCommentAddBroadcastEvent', e => {
            if (e.comment.action === 'create') {
                ticket.value?.comments.unshift(e.comment)
                toast.success(`New comment from ${e.comment.user_name}`)
            }
        })
    } catch (error: any) {
        console.error(error.message)
    } finally {
        loading.value = false
    }
}

const submitComment = async (text: string) => {
    if (!ticket.value) return
    try {
        const response = await api.post<TicketComment>(`/ticket/${ticket.value.id}/comment`, {
            text
        })
        ticket.value.comments.unshift(response.data)
    } catch (error: any) {
        console.error(error.message)
    }
}

const deleteComment = async (id: number) => {
    try {
        await api.delete(`/ticket/${ticket.value.id}/comment/${id}`)
        ticket.value.comments = ticket.value.comments.filter(c => c.id !== id)
        toast.success(`Comment deleted`)
    } catch (e: any) {
        console.error(e.message)
    }
}

const deleteTicket = async () => {
    try {
        await api.delete(`/ticket/${ticket.value.id}`)
        await router.push({name: 'tickets'})
        toast.success(`Ticket "${ticket.value.title}" deleted successfully`)
    } catch (e: any) {
        console.error(e)
    }
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
                <p><span class="font-semibold">Status:</span> {{ formatLabel(ticket.status) }}</p>
                <p><span class="font-semibold">Assigned to:</span> {{ ticket.assigned }}</p>
                <p>
                    <span class="font-semibold">Created at:</span>
                    {{ new Date(ticket.created_at).toLocaleString() }}
                </p>
            </div>
            <div class="flex justify-end w-full">
                <button
                    @click="deleteTicket"
                    class="bg-red-600 text-white px-4 py-2 rounded transition cursor-pointer"
                >
                    Delete Ticket
                </button>
            </div>
            <TicketComments
                :comments="ticket.comments"
                @submit="submitComment"
                @delete="deleteComment"
            />
        </div>
    </div>
</template>
