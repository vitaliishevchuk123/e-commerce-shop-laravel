<script setup>
defineProps({
        brands: Array,
}
)
</script>

<template>
    <div class="max-w-7xl mx-auto p-4">
        <h2 class="brands-tittle">Популярні бренди</h2>
        <div class="grid grid-cols-5">
            <div v-for="brand in brands">
                <img :src="brand.logo" :alt="brand.name">
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.brands-tittle {
    color: #1A1A25;
    font-size: 30px;
    font-style: normal;
    font-weight: 700;
    line-height: 110%; /* 33px */
}
</style>
