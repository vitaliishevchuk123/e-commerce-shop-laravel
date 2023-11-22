<script setup>

defineProps({
        products: Array,
    }
)
</script>

<template>
    <div class="filter-item text-sm">
        <div class="property-name mb-2">Производители</div>
        <ul>
            <li class="mb-2">
                <label class="filter-label" for="gym80">
                    <input class="rounded border-gray-300" type="checkbox" id="gym80" name="manufacturer" value="Gym80">
                    Gym80
                </label>
            </li>
            <li class="mb-2">
                <label class="filter-label" for="cardioPower">
                    <input class="rounded border-gray-300" type="checkbox" id="cardioPower" name="manufacturer" value="CardioPower">
                    CardioPower
                </label>
            </li>
        </ul>
    </div>
</template>

<style scoped lang="scss">
input[type="checkbox"]:checked {
    background-color: #2FC509; /* Зелений колір заливки */
}

.property-name {
    color: #384255;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
}

.filter-label {
    color: #384255;
    font-style: normal;
    font-weight: 400;
    line-height: 140%; /* 19.6px */
}
</style>
