<script setup>

const props = defineProps({
    text: {
        type: String
    },
    href: {
        type: String,
        default: 'javascript:void(0);',
    },
})
console.log(props.href)
</script>

<template>
    <button v-if="href == 'javascript:void(0);'" class="slider-button text-sm md:text-base lg:text-lg xl:text-xl">
        {{ text}}
    </button>
    <a v-else
       :href="href"
       class="slider-button text-sm md:text-base lg:text-lg xl:text-xl">
        {{ text}}
    </a>
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
