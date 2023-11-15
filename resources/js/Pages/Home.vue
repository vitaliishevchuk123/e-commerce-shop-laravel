<script setup>
import { Head } from '@inertiajs/vue3'
import MainLayout from "@/Layouts/MainLayout.vue";
import MainSlider from "@/Components/MainSlider.vue";
import PopularBrands from "@/Components/PopularBrands.vue";

const props = defineProps({
    title: String,
    brands: Array,
}
)

console.log(props.brands)

</script>

<template>
    <Head :title="$page.props.title" />
    <MainLayout>
        <MainSlider/>
        <PopularBrands :brands="brands"/>
    </MainLayout>
</template>


<style scoped>

</style>
