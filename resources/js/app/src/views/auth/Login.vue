<script setup lang="ts">

import {useRouter} from 'vue-router'
import {useApi} from '@/composables/useApi'
import {useField, useForm} from 'vee-validate'

const {handleSubmit, errors, setErrors} = useForm()
const router = useRouter()
const {api} = useApi()

// TODO: delete init data
const {value: email, handleBlur: emailBlur} = useField('email', 'required|email',
    {
        initialValue: 'admin1@example.com'
    })
const {value: password, handleBlur: passwordBlur} = useField('password', 'required',
    {
        initialValue: 'password'
    })

const submit = handleSubmit(async (values) => {

    try {
        const response = await api.post('/login', values)
        localStorage.setItem('token', response.data.token)
        window.Echo.options.auth.headers.Authorization = 'Bearer ' + response.data.token
        await router.push({name: 'home'})
    } catch (e) {
        setErrors({
            email: e.response.data.error
        })
    }
})
</script>

<template>
    <div class="max-w-sm mx-auto mt-20 p-6 bg-white rounded shadow-md">
        <h2 class="text-2xl font-semibold mb-6 text-center">Login</h2>
        <form class="space-y-4">
            <div>
                <label for="email" class="block mb-1 font-medium text-gray-700">Email:</label>
                <input
                    type="email"
                    name="name"
                    v-model="email"
                    @blur="emailBlur"
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <div class="text-blue-600" v-if="errors.email">
                    {{ errors.email }}
                </div>
            </div>
            <div>
                <label for="password" class="block mb-1 font-medium text-gray-700">Password:</label>
                <input
                    id="password"
                    v-model="password"
                    @blur="passwordBlur"
                    name="password"
                    type="password"
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <div class="text-blue-600" v-if="errors.password">
                    {{ errors.password }}
                </div>
            </div>
            <button
                @click="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-md  transition cursor-pointer">
                Login
            </button>
            <div>
                <p>No account?</p>
                <router-link
                    :to="{ name: 'register' }"
                    class="text-blue-600 hover:text-blue-800 font-medium">
                    Register
                </router-link>
            </div>
        </form>
    </div>
</template>
