<script setup>

import CheckboxFilterItem from "@/Components/CheckboxFilterItem.vue";
import DefaultButton from "@/Components/DefaultButton.vue";
import RangeFilterItem from "@/Components/RangeFilterItem.vue";

defineProps({
        products: Array,
    }
)
</script>

<template>
    <div>
        <CheckboxFilterItem/>
        <hr class="my-4"/>
        <CheckboxFilterItem/>
        <hr class="my-4"/>
        <RangeFilterItem/>
        <hr class="my-4"/>
        <DefaultButton text="Очистить" :text-x-s="true"/>
    </div>
</template>

<style scoped lang="scss">
</style>
