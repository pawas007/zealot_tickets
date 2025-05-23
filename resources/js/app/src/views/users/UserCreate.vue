<script setup lang="ts">
import {ref} from "vue";
import {useRouter} from 'vue-router'
import {useApi} from '@/composables/useApi'
import {useForm, useField} from 'vee-validate'
import {toast} from 'vue3-toastify'
import {Role} from "../../types/user";
import {useFormatLabel} from '@/composables/useFormatLabel'

const {formatLabel} = useFormatLabel()
const {api} = useApi()
const router = useRouter()
const {handleSubmit, errors, setErrors} = useForm()
const roles = ref<Role[]>([])

const fetchRoles = async () => {
    const res = await api.get('/roles')
    roles.value = res.data
}

const {value: name, handleBlur: nameBlur} = useField('name', 'required|max:255')
const {value: email, handleBlur: emailBlur} = useField('email', 'required|email')
const {value: password, handleBlur: passwordBlur} = useField('password', 'required|min:6')
const {value: role} = useField('role', 'required')


const submit = handleSubmit(async (values) => {
    try {
        await api.post('/users', values)
        toast.success(`User "${name.value}" created successfully`)
        await router.push({name: 'users'})
    } catch (e: any) {
        if (e.response?.data?.errors && e.response.status === 422) {
            setErrors(e.response.data.errors)
        } else {
            console.error(e)
        }
    }
})

fetchRoles()
</script>

<template>
    <div class="max-w-md mx-auto mt-10 p-6 bg-white rounded shadow">
        <h1 class="text-2xl font-bold mb-6 text-center">Create User</h1>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block text-sm font-medium">Name</label>
                <input
                    v-model="name"
                    @blur="nameBlur"
                    type="text"
                    class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2"
                    :class="errors.name ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
                />
                <p class="text-red-500 text-sm mt-1" v-if="errors.name">{{ errors.name }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium">Email</label>
                <input
                    v-model="email"
                    @blur="emailBlur"
                    type="email"
                    class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2"
                    :class="errors.email ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
                />
                <p class="text-red-500 text-sm mt-1" v-if="errors.email">{{ errors.email }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium">Password</label>
                <input
                    v-model="password"
                    @blur="passwordBlur"
                    type="password"
                    class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2"
                    :class="errors.password ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
                />
                <p class="text-red-500 text-sm mt-1" v-if="errors.password">{{ errors.password }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium">Role</label>
                <select
                    v-model="role"
                    class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2"
                    :class="errors.role ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
                >
                    <option value="">Select role</option>
                    <option v-for="r in roles" :key="r.id" :value="r.id">
                        {{ formatLabel(r.name) }}
                    </option>
                </select>
                <p class="text-red-500 text-sm mt-1" v-if="errors.role">{{ errors.role }}</p>
            </div>
            <button
                type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition cursor-pointer">
                Create User
            </button>
        </form>
    </div>
</template>
