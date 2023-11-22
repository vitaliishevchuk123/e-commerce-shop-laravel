<script setup>
import {Head} from '@inertiajs/vue3'
import MainLayout from "@/Layouts/MainLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import {defineProps} from 'vue';
import MainTitle from "@/Components/MainTitle.vue";
import ContentWrapper from "@/Components/ContentWrapper.vue";
import SortingFields from "@/Components/SortingFields.vue";
import CategorySiblings from "@/Components/CategorySiblings.vue";
import CatalogProducts from "@/Components/CatalogProducts.vue";

defineProps({
        title: String,
        breadcrumbs: Array,
        category: Object,
        categorySiblings: Array,
        products: Array,
    }
)

</script>

<template>
    <Head :title="$page.props.title"/>
    <MainLayout>
        <ContentWrapper class="mt-6">
            <Breadcrumbs :breadcrumbs="breadcrumbs" class="mb-6"/>
            <MainTitle :title="category.name"/>
            <div v-if="categorySiblings.length" class="grid grid-cols-[2fr,1fr] mb-6">
                <CategorySiblings :categorySiblings="categorySiblings"/>
                <SortingFields/>
            </div>
            <CatalogProducts :products="products"/>
        </ContentWrapper>
    </MainLayout>
</template>


<style scoped>

</style>
