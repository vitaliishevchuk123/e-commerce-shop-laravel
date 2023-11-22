<script setup>
import ProductCard from "@/Components/ProductCard.vue";

defineProps({
        products: Array,
    }
)
</script>

<template>
    <div class="grid grid-cols-4 gap-6 mr-4">
        <ProductCard v-for="(product, index) in products"
                     :key="'catalog-product-' + index"
                     :product="product"
        />
    </div>
</template>

<style scoped lang="scss">

</style>
