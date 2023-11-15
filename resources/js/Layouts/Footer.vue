<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import {Link} from '@inertiajs/vue3';
import PaymentIcons from "@/Components/PaymentIcons.vue";
import PaymentIcon from "@/Components/PaymentIcon.vue";
import SocialIcons from "@/Components/SocialIcons.vue";
import SocialIcon from "@/Components/SocialIcon.vue";</script>

<template>
    <footer class="footer w-full bottom-0 bg-dark-site text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="footer-top-wrapper border-b border-header">
            </div>
            <div class="footer-bottom-wrapper grid grid-cols-2 md:flex md:items-center md:justify-around">
                <div class="footer-policy flex-col items-center space-y-2 row-span-2 flex items-center justify-center">
                    <Link :href="route('dashboard')">
                        <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800"/>
                    </Link>
                    <div class="footer-policy__text">
                        © WellFitness. 2023 Всі права захищені
                    </div>
                </div>
                <SocialIcons>
                    <SocialIcon>
                        <i class="fa-brands fa-facebook"></i>
                    </SocialIcon>
                    <SocialIcon>
                        <i class="fa-brands fa-instagram"></i>
                    </SocialIcon>
                    <SocialIcon>
                        <i class="fa-brands fa-telegram"></i>
                    </SocialIcon>
                    <SocialIcon>
                        <i class="fa-brands fa-viber"></i>
                    </SocialIcon>
                </SocialIcons>
                <PaymentIcons>
                    <PaymentIcon>
                        <img src="img/front/payments/visa.svg" alt="visa">
                    </PaymentIcon>
                    <PaymentIcon>
                        <img src="img/front/payments/mastercard.svg" alt="mastercard">
                    </PaymentIcon>
                    <PaymentIcon>
                        <img src="img/front/payments/symbols.svg" alt="symbols">
                    </PaymentIcon>
                    <PaymentIcon>
                        <img src="img/front/payments/paypal.svg" alt="paypal">
                    </PaymentIcon>
                </PaymentIcons>
            </div>
        </div>
    </footer>
</template>

<style scoped lang="scss">
@import "./resources/scss/_variables.scss";

.footer {
    min-height: 130px;

    &-top-wrapper {
        min-height: 30px;
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
