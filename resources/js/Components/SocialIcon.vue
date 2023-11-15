<script setup>
</script>
<template>
        <div class="social-icons__item mx-2 sm:text-md md:text-lg lg:text-xl cursor-pointer">
            <slot/>
        </div>
</template>
<style scoped lang="scss">
.social-icons__item {
    &:hover {
        color: rgba(30, 144, 255, 0.6);
    }
}
</style>
