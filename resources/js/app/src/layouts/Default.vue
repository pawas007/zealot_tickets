<script setup lang="ts">
import Header from "../components/global/Header.vue";
import {useAuthStore} from "@/stores/auth";
const auth = useAuthStore()
auth.fetchAuthUser()
</script>

<template>
    <div class="default-layout">
        <Header/>
        <main>
            <slot/>
        </main>
    </div>
</template>
