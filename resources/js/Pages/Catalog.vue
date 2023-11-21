<script setup>
import {Head} from '@inertiajs/vue3'
import MainLayout from "@/Layouts/MainLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import { defineProps } from 'vue';
import MainTitle from "@/Components/MainTitle.vue";
import ContentWrapper from "@/Components/ContentWrapper.vue";

defineProps({
        title: String,
        breadcrumbs: String,
        category: Object,
    }
)

</script>

<template>
    <Head :title="$page.props.title"/>
    <MainLayout>
        <ContentWrapper class="mt-6">
            <Breadcrumbs :breadcrumbs="breadcrumbs" class="mb-6"/>
            <MainTitle :title="category.name"/>
        </ContentWrapper>
    </MainLayout>
</template>


<style scoped>

</style>
