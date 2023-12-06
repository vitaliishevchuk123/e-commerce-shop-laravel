<script setup>

const props = defineProps({
        product: Object,
    }
)

</script>

<template>
    <div class="product-details flex flex-col h-full text-sm">
        <div v-if="product.quantity > 0" class="flex items-center gap-2 text-xs mb-2">
            <div class="flex items-center gap-1 color-green">
                <span>{{ __('In stock') }}</span>
                <div class="flex gap-1">
                    <div class="green-dot"></div>
                    <div class="green-dot"></div>
                    <div class="green-dot"></div>
                </div>
            </div>
            <span class="color-blue">{{ __('In showroom') }}</span>
        </div>
        <div class="detail-wrapper">
            <div class="main-properties mb-2">
                <p v-for="(attribute_value, index) in product.attribute_values.slice(0, 3)"
                   :key="`index-${index}`"
                   class="property"
                >
                    <span class="text-gray-500">{{ attribute_value.attribute.name }}:</span>
                    {{ attribute_value.value }}
                </p>
                <div class="property flex items-center"><span class="text-gray-500 mr-1">Рейтинг:</span>
                    <div class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path
                                d="M6 0.5L7.91148 3.86908L11.7063 4.6459L9.09284 7.50492L9.52671 11.3541L6 9.752L2.47329 11.3541L2.90716 7.50492L0.293661 4.6459L4.08852 3.86908L6 0.5Z"
                                fill="#F99808"/>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path
                                d="M6 0.5L7.91148 3.86908L11.7063 4.6459L9.09284 7.50492L9.52671 11.3541L6 9.752L2.47329 11.3541L2.90716 7.50492L0.293661 4.6459L4.08852 3.86908L6 0.5Z"
                                fill="#F99808"/>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path
                                d="M6 0.5L7.91148 3.86908L11.7063 4.6459L9.09284 7.50492L9.52671 11.3541L6 9.752L2.47329 11.3541L2.90716 7.50492L0.293661 4.6459L4.08852 3.86908L6 0.5Z"
                                fill="#F99808"/>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path
                                d="M6 0.5L7.91148 3.86908L11.7063 4.6459L9.09284 7.50492L9.52671 11.3541L6 9.752L2.47329 11.3541L2.90716 7.50492L0.293661 4.6459L4.08852 3.86908L6 0.5Z"
                                fill="#F99808"/>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path
                                d="M6 0.5L7.91148 3.86908L11.7063 4.6459L9.09284 7.50492L9.52671 11.3541L6 9.752L2.47329 11.3541L2.90716 7.50492L0.293661 4.6459L4.08852 3.86908L6 0.5Z"
                                fill="#F99808"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2">
            <div class="prices">
                <div v-if="product.sale_price" class="sale-price text-base">{{ product.sale_price }} грн.</div>
                <div v-if="product.price" class="price text-sm">{{ product.price }} грн.</div>
            </div>
            <div class="flex items-center cursor-pointer">
<!--                <Link :href="route('product', product.slug)">-->
                    <div class="w-auto buy flex items-center gap-2 text-white rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="23" viewBox="0 0 20 23" fill="none">
                            <path
                                d="M1.4 7.13816H18.4571V19.0001C18.4571 20.7121 17.0692 22.1001 15.3571 22.1001H4.5C2.78792 22.1001 1.4 20.7121 1.4 19.0001V7.13816Z"
                                stroke="white" stroke-width="1.8"/>
                            <path
                                d="M5.73624 11.4761V4.99988C5.73624 2.79074 7.5271 0.999884 9.73624 0.999884H10.1172C12.3263 0.999884 14.1172 2.79074 14.1172 4.99988V11.4761"
                                stroke="white" stroke-width="1.8"/>
                        </svg>
                        {{ __('Buy') }}
                    </div>
<!--                </Link>-->
            </div>
        </div>

    </div>
</template>

<style lang="scss">

.color-green {
    color: #2FC509;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
}

.color-blue {
    color: #4B7EE8;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
}

.green-dot {
    width: 0.6em;
    height: 0.6em;
    border-radius: 1em;
    background: #2FC509;
}

.property {
    font-style: normal;
    font-weight: 400;
    line-height: 150%; /* 19.6px */
}

.buy {
    background: #F53B49;
    display: flex;
    padding: 1em 1.5em;
    justify-content: center;
    align-items: center;
    gap: 4px;
}

.sale-price {
    color: #1A1A25;
    font-style: normal;
    font-weight: 700;
    line-height: 135.938%; /* 23.109px */
}

.price {
    color: #858FA4;
    font-style: normal;
    font-weight: 400;
    line-height: 135.938%; /* 20.391px */
    text-decoration: line-through;
}
</style>
