<script setup lang="ts">
import {useAuthStore} from "@/stores/auth";

const auth = useAuthStore()

</script>
<template>
    <header class="flex justify-between bg-gray-100 p-4 rounded shadow mb-6">
        <nav class="flex gap-6 ">
            <router-link
                :to="{ name: 'tickets' }"
                class="text-blue-600 hover:text-blue-800 font-medium">
                Tickets
            </router-link>
            <router-link
                :to="{ name: 'users' }"
                class="text-blue-600 hover:text-blue-800 font-medium">
                Users
            </router-link>
        </nav>
        <div class="flex items-center gap-6">
            <p> {{ auth.userName }} ({{ auth.userRole }})</p>
            <button
                @click="auth.logOut();"
                class="px-4 py-2 rounded bg-blue-600 text-white cursor-pointer">
                Log out
            </button>
        </div>
    </header>
</template>
