<script setup>
import {Head} from '@inertiajs/vue3'
import MainLayout from "@/Layouts/MainLayout.vue";
import MainSlider from "@/Components/MainSlider.vue";
import PopularBrands from "@/Components/PopularBrands.vue";
import HomeGym from "@/Components/HomeGym.vue";
import FitnessClubGym from "@/Components/FitnessClubGym.vue";

const props = defineProps({
        title: String,
        brands: Array,
        homeGymCats: Array,
        fitnessClubGymCats: Array,
    }
)

</script>

<template>
    <Head :title="$page.props.title"/>
    <MainLayout>
        <MainSlider/>
        <HomeGym :categories="homeGymCats"/>
        <FitnessClubGym :categories="fitnessClubGymCats"/>
        <PopularBrands :brands="brands"/>


        <div class="block space-y-4 md:flex md:space-y-0 md:space-x-4 md:rtl:space-x-reverse">
            <!-- Modal toggle -->
            <button class="block w-full md:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                Top left
            </button>
        </div>
    </MainLayout>
</template>


<style scoped>

</style>
