<script setup lang="ts">
import { ref } from 'vue'
import { useApi } from '@/composables/useApi'
import Pagination from '@/components/global/Pagination.vue'

const { api } = useApi()
const users = ref([])
const currentPage = ref(1)
const lastPage = ref(1)
const loading = ref(false)

const fetchUsers = async (page = 1) => {
    loading.value = true
    try {
        const response = await api.get(`/users?page=${page}`)
        users.value = response.data.data
        currentPage.value = response.data.meta.current_page
        lastPage.value = response.data.meta.last_page
    } catch (e: any) {
        console.error(e.message)
    } finally {
        loading.value = false
    }
}

const handlePageChange = (page: number) => {
    if (page < 1 || page > lastPage.value) return
    fetchUsers(page)
}

fetchUsers()
</script>

<template>
    <div class="max-w-4xl mx-auto p-6 bg-white rounded shadow">
        <h1 class="text-2xl font-bold mb-6">Users</h1>

        <div v-if="loading" class="text-center text-gray-500 py-10">
            Loading users...
        </div>

        <ul v-if="!loading" class="space-y-4">
            <li
                v-for="user in users"
                :key="user.id"
                class="border px-4 py-3 rounded shadow-sm"
            >
                <div class="font-semibold text-lg">{{ user.name }}</div>
                <div class="text-sm text-gray-700">{{ user.email }}</div>
                <div class="text-sm text-gray-500">Role: {{ user.role }}</div>
            </li>
        </ul>

        <Pagination
            v-if="!loading && lastPage > 1"
            :currentPage="currentPage"
            :lastPage="lastPage"
            @page-change="handlePageChange"
        />
    </div>
</template>
