<script setup></script>

<template>
    <div class="sort-fields flex flex-wrap gap-2">
        <div class="sort-field sort-field_active text-sm flex">
            <div class="arrow-icon-wrapper">
                <svg v-if="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <line x1="5" y1="8" x2="19" y2="8" stroke="#384255" stroke-width="2"/>
                    <line x1="5" y1="12" x2="14" y2="12" stroke="#384255" stroke-width="2"/>
                    <line x1="5" y1="16" x2="9" y2="16" stroke="#384255" stroke-width="2"/>
                </svg>
                <svg v-if="false" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <line y1="-1" x2="14" y2="-1" transform="matrix(1 0 0 -1 5 15)" stroke="#384255" stroke-width="2"/>
                    <line y1="-1" x2="9" y2="-1" transform="matrix(1 0 0 -1 5 11)" stroke="#384255" stroke-width="2"/>
                    <line y1="-1" x2="4" y2="-1" transform="matrix(1 0 0 -1 5 7)" stroke="#384255" stroke-width="2"/>
                </svg>
            </div>
            <div class="pt-[0.15em]">
                {{ __('For popularity') }}
            </div>
        </div>
        <div class="sort-field text-sm pt-[0.15em]">{{ __('For novelty') }}</div>
        <div class="sort-field text-sm pt-[0.15em]">{{ __('For price') }}</div>
        <div class="sort-field text-sm pt-[0.15em]">{{ __('For rating') }}</div>
    </div>
</template>

<style scoped lang="scss">
.sort-field {
    color: #384255;
    font-style: normal;
    font-weight: 400;
    line-height: 140%; /* 19.6px */

    &_active {
        font-style: normal;
        font-weight: 700;
        line-height: normal;
    }
}

.arrow-icon-wrapper {
}
</style>
