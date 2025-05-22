<script setup lang="ts">
import { ref } from 'vue'
import { useApi } from '@/composables/useApi'

// ПЕРЕНЕСТИ В ІНШИЙ ФАЙЛ
interface Ticket {
    id: number
    title: string
    description: string
    status: string
    created_at: string
    updated_at: string
    assigned: string
}

const tickets = ref<Ticket[]>([])
const loading = ref(false)
const currentPage = ref(1)
const lastPage = ref(1)

const { api } = useApi()

async function fetchTickets(page = 1) {
    loading.value = true
    try {
        const response = await api.get(`ticket?page=${page}`)
        tickets.value = response.data.data
        currentPage.value = response.data.meta.current_page
        lastPage.value = response.data.meta.last_page
    } catch (e: any) {
        console.error(e.message)
    } finally {
        loading.value = false
    }
}

fetchTickets()

function goToPage(page: number) {
    if (page < 1 || page > lastPage.value) return
    fetchTickets(page)
}
</script>

<template>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-md">
        <h1 class="text-4xl font-bold mb-6 text-gray-800">Tickets</h1>

        <div v-if="loading" class="text-center py-10 text-gray-500">
            Loading tickets...
        </div>

        <ul v-if="!loading" class="space-y-6">
            <li
                v-for="ticket in tickets"
                :key="ticket.id"
                class="border border-gray-200 rounded-lg p-5 hover:shadow-lg transition-shadow duration-300"
            >
                <router-link
                    :to="{ name: 'ticket-detail', params: { id: ticket.id } }"
                    class="text-xl font-semibold text-blue-600 hover:underline"
                >
                    {{ ticket.title }}
                </router-link>

                <div class="mt-1 flex flex-wrap items-center text-sm text-gray-600 space-x-4">
                    <span class="font-medium">Status:</span> <span>{{ ticket.status }}</span>
                    <span class="font-medium">Assigned to:</span> <span>{{ ticket.assigned }}</span>
                    <span class="font-medium">Created at:</span>
                    <time :datetime="ticket.created_at">{{ new Date(ticket.created_at).toLocaleString() }}</time>
                </div>

                <p class="mt-3 text-gray-700 leading-relaxed">{{ ticket.description }}</p>
            </li>
        </ul>

        <div
            v-if="lastPage > 1"
            class="flex justify-center items-center gap-4 mt-8 select-none"
        >
            <button
                :disabled="currentPage === 1"
                @click="goToPage(currentPage - 1)"
                class="px-4 py-2 rounded bg-blue-600 text-white disabled:bg-gray-300 disabled:cursor-not-allowed hover:bg-blue-700 transition-colors duration-300"
            >
                Prev
            </button>

            <span class="text-gray-700 font-medium">
        Page {{ currentPage }} of {{ lastPage }}
      </span>

            <button
                :disabled="currentPage === lastPage"
                @click="goToPage(currentPage + 1)"
                class="px-4 py-2 rounded bg-blue-600 text-white disabled:bg-gray-300 disabled:cursor-not-allowed hover:bg-blue-700 transition-colors duration-300"
            >
                Next
            </button>
        </div>
    </div>
</template>
