<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import {Link} from '@inertiajs/vue3';

</script>

<template>
    <footer class="footer w-full bottom-0 bg-dark-site text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="footer-top-wrapper border-b border-header">

            </div>
            <div class="footer-bottom-wrapper flex items-center">
                <div class="footer-policy flex-col items-center space-y-2">
                    <Link :href="route('dashboard')">
                        <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                    </Link>
                    <div class="footer-policy__text">
                        © WellFitness. 2023 Всі права захищені
                    </div>
                </div>
                <div class="social-icons px-2 flex text-gray-500">
                    <div class="social-icons__item mx-2">
                        <i class="fa-brands fa-facebook"></i>
                    </div>
                    <div class="social-icons__item mx-2">
                        <i class="fa-brands fa-instagram"></i>
                    </div>
                    <div class="social-icons__item mx-2">
                        <i class="fa-brands fa-telegram"></i>
                    </div>
                    <div class="social-icons__item mx-2">
                        <i class="fa-brands fa-viber"></i>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>

<style scoped lang="scss">
@import "./resources/scss/_variables.scss";

.footer {
    min-height: 200px;
    &-top-wrapper {
        min-height: 100px;
    }
    &-bottom-wrapper {
        min-height: 100px;
    }

}

.footer-policy {
    &__text {
        color: #858FA4;

        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: 140%; /* 19.6px */
    }
}

.border-header {
    border-bottom-color: rgba(133, 143, 164, 0.15);
}
</style>
