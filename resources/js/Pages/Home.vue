<script setup>
import { Head } from '@inertiajs/vue3'
import MainLayout from "@/Layouts/MainLayout.vue";

defineProps({ title: String })

</script>

<template>
    <Head :title="title" />
    <MainLayout>
        <template #header>

        </template>
        <h1>{{ title }}</h1>
        <p>Hello, welcome to your first Inertia app!</p>
    </MainLayout>
</template>


<style scoped>

</style>
