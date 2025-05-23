<script setup lang="ts">
import {useApi} from '@/composables/useApi'
import {useRouter} from 'vue-router'
import {useForm, useField} from 'vee-validate'
import {toast} from "vue3-toastify";
import {ref} from "vue";
import { useFormatLabel } from '@/composables/useFormatLabel'

const { formatLabel } = useFormatLabel()
const {api} = useApi()
const router = useRouter()
const {handleSubmit, errors, setErrors} = useForm()
const initData = ref({})
const {value: title, handleBlur: titleBlur} = useField('title', 'required|max:400')
const {value: description, handleBlur: descBlur} = useField('description', 'required')
const {value: status} = useField('status')
const {value: userId} = useField('user_id', 'required')

api.get('/ticket-data').then(r=> initData.value = r.data)
const submit = handleSubmit(async (values) => {
    try {
        await api.post('/ticket', values).then(r => {
            toast(`Ticket : "${title.value} " created`)
        router.push({name: 'ticket-detail', params: {id: r.data.ticket_id}})
        })

    } catch (e: any) {
        if (e.response?.data?.errors) {
            setErrors(e.response.data.errors)
        }
    }
})

</script>
<template>
    <div class="max-w-xl mx-auto mt-12 p-6 bg-white shadow rounded">
        <h1 class="text-2xl font-bold mb-6">Create Ticket</h1>
        <form class="space-y-5" @submit.prevent="submit">
            <div>
                <label class="block mb-1 font-medium text-gray-700">Title</label>
                <input
                    v-model="title"
                    @blur="titleBlur"
                    class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2"
                    :class="errors.title ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
                />
                <p class="text-red-500 text-sm mt-1" v-if="errors.title">{{ errors.title }}</p>
            </div>
            <div>
                <label class="block mb-1 font-medium text-gray-700">Description</label>
                <textarea
                    v-model="description"
                    @blur="descBlur"
                    rows="4"
                    class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2"
                    :class="errors.description ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
                ></textarea>
                <p class="text-red-500 text-sm mt-1" v-if="errors.description">{{ errors.description }}</p>
            </div>
            <div v-if="initData.statuses">
                <label class="block mb-1 font-medium text-gray-700">Status</label>
                <select
                    v-model="status"
                    class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2"
                >
                    <option value="">Select status</option>
                    <option
                        v-for="statusValue in  initData.statuses"
                        :key="statusValue"
                        :value="statusValue"
                    >
                        {{ formatLabel(statusValue)}}
                    </option>
                </select>
            </div>
            <div v-if=" initData.users">
                <label class="block mb-1 font-medium text-gray-700">Assign To</label>
                <select
                    v-model="userId"
                    class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2"
                    :class="errors.user_id ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'">
                    <option value="">Select user</option>
                    <option
                        v-for="user in  initData.users"
                        :key="user.id"
                        :value="user.id">
                        {{ user.name }} ({{ user.email }})
                    </option>
                </select>
                <p class="text-red-500 text-sm mt-1" v-if="errors.user_id">{{ errors.user_id }}</p>
            </div>
            <button
                type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                Create Ticket
            </button>
        </form>
    </div>
</template>
