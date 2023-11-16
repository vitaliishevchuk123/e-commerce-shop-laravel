<script setup>
import {onMounted, ref} from 'vue';
import {usePage} from '@inertiajs/vue3'

const page = usePage();

const selectedCategory = ref(null);

function showChildren(category) {
    selectedCategory.value = category;
}

onMounted(() => {
    // Вибір першої категорії при завантаженні компонента
    if (page.props.catalogRootCats.length > 0) {
        selectedCategory.value = page.props.catalogRootCats[0];
    }
});
</script>

<template>
    <div id="top-catalog-menu" data-modal-placement="top-left" tabindex="-1"
         class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-7xl max-h-full mx-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700 flex">
                <button type="button"
                        class="absolute text-xl top-0 right-0 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="top-catalog-menu">
                    x
                </button>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                    <div v-for="category in $page.props.catalogRootCats"

                    >
                        <div :class="{
                            'text-lg  p-1 pl-2': true,
                            'bg-gray-100': selectedCategory == category,
                            }"
                             @click="showChildren(category)"
                        >
                            <span class="root-cat mr-1">{{ category.name }}</span>
                            <span v-if="category.children.length" class="text-xs text-gray-500">
                                <i class="fa-solid fa-arrow-right"></i>
                            </span>
                        </div>
                        <div
                            class="grid lg:hidden children-cats lg:w-full lg:m-6 lg:grid lg:grid-cols-3 lg:grid-rows-4 lg:gap-4"
                            v-if="selectedCategory == category">
                            <div v-for="child in selectedCategory.children" :key="'mob' + child.id"
                                 class="child-cat flex items-center gap-2">
                                <div class="w-20 h-20 flex items-center">
                                    <img :src="child.image" :alt="child.name + ' image'">
                                </div>
                                <div class="child-title">{{ child.name }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="hidden lg:grid children-cats lg:w-full lg:m-6 lg:grid lg:grid-cols-3 lg:grid-rows-4 lg:gap-4"
                    v-if="selectedCategory">
                    <div v-for="child in selectedCategory.children" :key="child.id"
                         class="child-cat flex items-center gap-2">
                        <div class="w-20 h-20 flex items-center">
                            <img :src="child.image" :alt="child.name + ' image'">
                        </div>
                        <div class="child-title">{{ child.name }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
#top-catalog-menu {
    top: 147px;
}

.root-cat {
    color: #1A1A25;
    font-style: normal;
    font-weight: 600;
    line-height: normal;
}

.child-title {
    font-style: normal;
    font-weight: 400;
    line-height: 150%;
}
</style>
