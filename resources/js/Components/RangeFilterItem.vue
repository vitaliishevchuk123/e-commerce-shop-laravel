<script setup>
import {ref} from "vue";
import Slider from '@vueform/slider'

const price = ref([minPrice, maxPrice]);
const minPrice = 0;
const maxPrice = 1000;

const updatePrice = (value, index) => {
    price.value[index] = parseFloat(value);
};

defineProps({
        filters: Array,
    }
)
</script>

<template>
    <div class="filter-item text-sm">
        <div class="property-name mb-2">Цена</div>
        <div>
            <div class="flex items-center mb-6">
                <template v-for="(value, index) in price" :key="'filter'+index">
                    <input
                        class="range-input text-sm w-16 p-1 rounded border-gray-300"
                        type="number"
                        :value="value"
                        :min="minPrice"
                        :max="maxPrice"
                        @input="updatePrice($event.target.value, index)"
                    />
                    <template v-if="index === 0 && price.length > 1">
                        <div class="mx-2 w-4 h-[0.1em] bg-gray-500"></div>
                    </template>
                </template>
            </div>
            <Slider v-model="price"
                    tooltipPosition="bottom"
                    showTooltip="drag"
                    class="slider-red mx-2"
                    :min="minPrice"
                    :max="maxPrice"
            />
        </div>
    </div>
</template>

<style src="@vueform/slider/themes/default.css"></style>

<style scoped lang="scss">

.slider-red {
    --slider-connect-bg: #EF4444;
    --slider-tooltip-bg: #EF4444;
    --slider-handle-ring-color: #EF444430;
    --slider-height: 2px;
}

.range-input {
    color: #384255;
    font-style: normal;
    font-weight: 500;
    line-height: 140%; /* 16.8px */
}

.property-name {
    color: #384255;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
}

</style>
