<script setup>
import { Head } from '@inertiajs/vue3'
import MainLayout from "@/Layouts/MainLayout.vue";
import MainSlider from "@/Components/MainSlider.vue";

defineProps({ title: String })

</script>

<template>
    <Head :title="title" />
    <MainLayout>
        <MainSlider/>
    </MainLayout>
</template>


<style scoped>

</style>
