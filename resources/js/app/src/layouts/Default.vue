<script setup lang="ts">
import {useAuthStore} from "@/stores/auth";

const auth = useAuthStore()
auth.fetchAuthUser()
</script>

<template>
    <div class="default-layout">
        <header class="flex justify-between bg-gray-100 p-4 rounded shadow mb-6">
            <nav class="flex gap-6 ">
                <router-link
                    :to="{ name: 'login' }"
                    class="text-blue-600 hover:text-blue-800 font-medium"
                >
                    Login
                </router-link>
                <router-link
                    :to="{ name: 'tickets' }"
                    class="text-blue-600 hover:text-blue-800 font-medium"
                >
                    Tickets
                </router-link>
            </nav>
            <div>
                {{auth.userName}}
            </div>
        </header>
        <main>
            <slot/>
        </main>
    </div>
</template>
