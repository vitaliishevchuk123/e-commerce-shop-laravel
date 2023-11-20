<script setup>
import {Head} from '@inertiajs/vue3'
import MainLayout from "@/Layouts/MainLayout.vue";
import MainSlider from "@/Components/MainSlider.vue";
import PopularBrands from "@/Components/PopularBrands.vue";
import HomeGym from "@/Components/HomeGym.vue";
import FitnessClubGym from "@/Components/FitnessClubGym.vue";

const props = defineProps({
        title: String,
        sliders: Array,
        brands: Array,
        homeGymCats: Array,
        fitnessClubGymCats: Array,
    }
)

</script>

<template>
    <Head :title="$page.props.title"/>
    <MainLayout>
        <MainSlider :sliders="sliders" v-motion-slide-bottom/>
        <HomeGym :categories="homeGymCats" v-motion-slide-bottom/>
        <FitnessClubGym :categories="fitnessClubGymCats" v-motion-slide-visible-once-bottom/>
        <PopularBrands :brands="brands" v-motion-slide-visible-once-bottom/>
    </MainLayout>
</template>


<style scoped>

</style>
