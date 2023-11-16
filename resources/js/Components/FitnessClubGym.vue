<script setup>
import BlockTitle from "@/Components/BlockTitle.vue";
import DefaultButton from "@/Components/DefaultButton.vue";

defineProps({
        categories: Array,
    }
)
</script>

<template>
    <div v-if="categories.length" class="max-w-7xl mx-auto p-4 mb-6">
        <BlockTitle title="Для фітнес клубів"/>
        <div class="grid grid-rows-4 grid-cols-2 gap-4 lg:grid-rows-2 lg:grid-cols-4 lg:gap-8 mb-6">
            <div
                v-for="(category, index) in categories"
                :key="index"
                :class="{
                'fitness-club-cat relative overflow-hidden': true,
                'bg-dark-site': index === 0,
                'col-span-2': index < 1,
                'h-48': index >= 2 && index < 6,
            }">
                <div class="p-7"
                     :class="{
                    'fitness-club-cat__title absolute z-[30] w-24 text-base xl:text-lg xl:w-48': true,
                    'fitness-club-cat__title_white': index === 0
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
        <DefaultButton text="Всі категорії"/>
    </div>
</template>


<style scoped lang="scss">
@import "./resources/scss/_variables.scss";

.fitness-club-cat {
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
