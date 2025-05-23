<script setup lang="ts">
import {useRouter} from 'vue-router'
import {useApi} from '@/composables/useApi'
import {useField, useForm} from 'vee-validate'

const {handleSubmit, errors, setErrors} = useForm()
const router = useRouter()
const {api} = useApi()

const {value: name, handleBlur: nameBlur} = useField(
    'name',
    'required|max:255'
)

const {value: email, handleBlur: emailBlur} = useField(
    'email',
    'required|email|max:255'
)

const {value: password, handleBlur: passwordBlur} = useField(
    'password',
    'required|min:6'
)

const submit = handleSubmit(async (values) => {
    try {
        await api.post('/register', values)
        await router.push({name: 'login'})
    } catch (e: any) {
        if (e.response?.data?.errors && e.response.status === 422) {
            setErrors(e.response.data.errors)
        }else {
            console.error(e)
        }
    }
})
</script>
<template>
    <div class="max-w-sm mx-auto mt-20 p-6 bg-white rounded shadow-md">
        <h2 class="text-2xl font-semibold mb-6 text-center">Register</h2>
        <form class="space-y-4" @submit.prevent="submit">
            <div>
                <label for="name" class="block mb-1 font-medium text-gray-700">Name:</label>
                <input
                    id="name"
                    name="name"
                    v-model="name"
                    @blur="nameBlur"
                    type="text"
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2"
                    :class="errors.name ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
                />
                <div class="text-red-500 text-sm mt-1" v-if="errors.name">{{ errors.name }}</div>
            </div>
            <div>
                <label for="email" class="block mb-1 font-medium text-gray-700">Email:</label>
                <input
                    id="email"
                    name="email"
                    v-model="email"
                    @blur="emailBlur"
                    type="email"
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2"
                    :class="errors.email ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
                />
                <div class="text-red-500 text-sm mt-1" v-if="errors.email">{{ errors.email }}</div>
            </div>
            <div>
                <label for="password" class="block mb-1 font-medium text-gray-700">Password:</label>
                <input
                    id="password"
                    name="password"
                    v-model="password"
                    @blur="passwordBlur"
                    type="password"
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2"
                    :class="errors.password ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500'"
                />
                <div class="text-red-500 text-sm mt-1" v-if="errors.password">{{ errors.password }}</div>
            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-md transition hover:bg-blue-700 cursor-pointer"
            >
                Register
            </button>
            <div>
                <p>Already have account?</p>
                <router-link
                    :to="{ name: 'register' }"
                    class="text-blue-600 hover:text-blue-800 font-medium">
                    Login
                </router-link>
            </div>
        </form>
    </div>
</template>
