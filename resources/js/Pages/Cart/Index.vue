<script setup>
import {Head} from '@inertiajs/vue3'
import MainLayout from "@/Layouts/MainLayout.vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import MainTitle from "@/Components/MainTitle.vue";
import ContentWrapper from "@/Components/ContentWrapper.vue";
import {useStore} from "vuex";

const store = useStore();

const props = defineProps({
        order: Object,
        breadcrumbs: Array,
    }
)

</script>

<template>
    <Head :title="$page.props.title"/>
    <MainLayout>
        <ContentWrapper class="mt-6">
            <Breadcrumbs :breadcrumbs="breadcrumbs" class="mb-6"/>
            <MainTitle :title="__('Cart')"/>

        </ContentWrapper>
    </MainLayout>
</template>


<style scoped>

</style>
