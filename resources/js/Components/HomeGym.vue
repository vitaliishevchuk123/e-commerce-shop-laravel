<script setup>
import BlockTitle from "@/Components/BlockTitle.vue";

defineProps({
        categories: Array,
    }
)
</script>

<template>
    <div class="max-w-7xl mx-auto p-4 mb-6">
        <BlockTitle title="Тренажери для дому"/>
        <div class="grid grid-rows-6 grid-cols-2 gap-4 lg:grid-rows-3 lg:grid-cols-4 lg:gap-8">
            <div
                v-for="(category, index) in categories"
                :key="index"
                :class="{
                'home-gym-cat relative overflow-hidden': true,
                'bg-dark-site': index === 0,
                'col-span-2 lg:col-span-1 lg:row-span-2': index < 2,
                'h-48': index >= 2 && index < 6,
                'lg:row-span-3': index >= 6,
            }">
                <div class="p-7"
                     :class="{
                    'home-gym-cat__title absolute z-[30] w-24 text-base xl:text-lg xl:w-48': true,
                    'home-gym-cat__title_white': index === 0
                }">
                    {{ category.name }}
                </div>
                <img
                    class="absolute z-[10] bottom-0 right-0 max-w-fit max-h-full"
                    :src="category.image"
                    :alt="category.name + ' image'"
                />
            </div>
        </div>
    </div>

</template>


<style scoped lang="scss">
@import "./resources/scss/_variables.scss";

.home-gym-cat {
    border-radius: 6px;
    background: #F0F1F7;

    &__title {
        color: #1A1A25;
        font-style: normal;
        font-weight: 800;
        line-height: 120%; /* 24px */
        &_white {
            color: white;
        }
    }
}

.bg-dark-site {
    background: $main-dark;
}
</style>
