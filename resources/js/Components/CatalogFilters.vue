<script setup>
import CheckboxFilterItem from "@/Components/CheckboxFilterItem.vue";
import DefaultButton from "@/Components/DefaultButton.vue";
import RangeFilterItem from "@/Components/RangeFilterItem.vue";

const props = defineProps({
        filters: Array,
        prices: Object,
    }
)
</script>

<template>
    <div>
        <template v-for="(filter, index) in filters">
            <CheckboxFilterItem v-if="filter.frontend_type === 'checkbox'" :filter="filter" :key="'attribute-'+index"/>
            <hr class="my-4"/>
        </template>
        <RangeFilterItem :title="__('Price')" :min-value="prices.min_price" :max-value="prices.max_price"/>
        <hr class="my-4"/>
        <DefaultButton text="Очистить" :text-x-s="true"/>
    </div>
</template>

<style scoped lang="scss">
</style>
