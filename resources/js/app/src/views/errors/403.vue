<template>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gray-100 text-center">
        <h1 class="text-6xl font-bold text-red-600">403</h1>
        <p class="text-2xl text-gray-800 mt-4">Access Denied</p>
        <p class="text-gray-600 mt-2">You don’t have permission to access this page.</p>
        <router-link
            :to="{ name: 'home' }"
            class="mt-6 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
        >
            Go Home
        </router-link>
    </div>
</template>
