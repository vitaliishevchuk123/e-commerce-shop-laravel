<script setup>
import ProductCard from "@/Components/ProductCard.vue";
import Pagination from "@/Components/Pagination.vue";

defineProps({
        products: Array,
        total: Number,
        perPage: Number,
        currentPage: Number,
    }
)
</script>

<template>
    <div>
        <div class="product-cards grid grid-cols-4 gap-6 mr-4 mb-6">
            <ProductCard v-for="(product, index) in products"
                         :key="'catalog-product-' + index"
                         :product="product"
            />
        </div>
        <Pagination :per-page="perPage" :total="total" :current-page="currentPage"/>
    </div>
</template>

<style scoped lang="scss">
.product-cards {
    align-self: start;
}
</style>
