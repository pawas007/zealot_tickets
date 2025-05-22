<script setup lang="ts">
import {useLayout} from '@/composables/useLayout'

const {layoutComponent} = useLayout()
</script>
<template>
    <component :is="layoutComponent">
        <router-view/>
    </component>
</template>
