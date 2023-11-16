<script setup>
defineProps({
        brands: Array,
}
)
</script>

<template>
    <div class="max-w-7xl mx-auto p-4 mb-6">
        <h2 class="brands-tittle mb-6 text-xl md:text-2xl lg:text-3xl">Популярні бренди</h2>
        <div class="grid grid-cols-3 grid-rows-3 lg:grid-cols-4 xl:grid-cols-5">
            <div class="brand-wrapper flex items-center justify-center" v-for="(brand, index) in brands">
                <div v-if="index >= 9 && index < 12" class="hidden lg:block xl:block">
                    <img class="brand-item" :src="brand.logo" :alt="brand.name">
                </div>
                <div v-else-if="index >= 12" class="hidden lg:hidden xl:block">
                    <img class="brand-item" :src="brand.logo" :alt="brand.name">
                </div>
                <img v-else class="brand-item" :src="brand.logo" :alt="brand.name">
            </div>
        </div>

    </div>
</template>

<style scoped lang="scss">
.brands-tittle {
    color: #1A1A25;
    font-style: normal;
    font-weight: 700;
    line-height: 110%; /* 33px */
}
.brand-wrapper {
    background: white;
    border: 1px solid rgba(133, 143, 164, 0.20);
}
</style>
