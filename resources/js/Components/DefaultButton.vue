<script setup>
defineProps({
    'text': String,
})
</script>

<template>
    <button class="slider-button text-sm md:text-base lg:text-lg xl:text-xl">
        {{ text}}
    </button>
</template>

<style scoped lang="scss">
.slider-button {
    display: flex;
    padding: 0.5em 1em;
    justify-content: center;
    align-items: center;
    gap: 10px;

    border-radius: 4px;
    border: 1px solid #F53B49;

    color: #F53B49;
    font-style: normal;
    font-weight: 700;
    line-height: normal;

    &:hover {
        background: white;
    }
}
</style>
