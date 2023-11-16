<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import {Link} from '@inertiajs/vue3';
import {ref} from "vue";
import NavLink from "@/Components/NavLink.vue";
import IconCounter from "@/Components/IconCounter.vue";

const showingNavigationDropdown = ref(false);

const activeClass = 'text-blue-500';

function isActive(path) {
    return route().current() === path || route().current().includes(path);
}

function logout() {
    this.$inertia.delete(route('logout'))
}

</script>

<template>
    <header>
        <nav class="nav bg-dark-site border-b border-header">
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex items-center mr-6">
                            <Link :href="route('home')">
                                <ApplicationLogo
                                    class="block h-9 w-auto fill-current text-gray-800"
                                />
                            </Link>
                        </div>
                        <!-- Navigation Links -->
                        <div class="hidden md:flex md:items-center md:gap-6">
                            <NavLink :href="route('home')"
                                     :active="route().current('home')"
                            >
                                Головна
                            </NavLink>
                        </div>
                    </div>

                    <div class="hidden md:flex md:items-center md:ml-6 md:gap-6">
                        <!-- Settings Dropdown -->
                        <div v-if="$page.props.auth.user" class="ml-3 relative">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white hover:text-gray focus:outline-none transition ease-in-out duration-150"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('profile.edit')">Особистий кабінет</DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button">
                                        Вийти
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                        <template v-else>
                            <NavLink v-if="$page.props.canRegister"
                                     :href="route('register')"
                                     :active="route().current('register')"
                                     class="nav-link nav-link--white">
                                Зареєструватись
                            </NavLink>
                            <NavLink v-if="$page.props.canLogin"
                                     :active="route().current('login')"
                                     :href="route('login')"
                                     class="nav-link nav-link--white">
                                <span
                                    class="mr-1"
                                >Увійти
                                    </span>
                                <i class="fa-solid fa-user"></i>
                            </NavLink>
                        </template>
                    </div>

                    <!-- Hamburger -->
                    <div class="-mr-2 flex items-center md:hidden">
                        <button
                            @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="inline-flex items-center justify-center p-2 rounded-md text-white focus:outline-none transition duration-150 ease-in-out"
                        >
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path
                                    :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                                <path
                                    :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div
                :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                class="md:hidden"
            >
                <div class="pt-2 pb-3 space-y-1">
                    <ResponsiveNavLink :href="route('home')" :active="route().current('home')">
                        Головна
                    </ResponsiveNavLink>
                    <ResponsiveNavLink v-if="$page.props.canRegister"
                                       :active="route().current('register')"
                                       :href="route('register')">
                        Зареєструватись
                    </ResponsiveNavLink>
                    <ResponsiveNavLink v-if="$page.props.canLogin"
                                       :active="route().current('login')"
                                       :href="route('login')">
                                <span
                                    class="mr-1"
                                >Увійти
                                    </span>
                        <i class="fa-solid fa-user"></i>
                    </ResponsiveNavLink>
                </div>

                <!-- Responsive Settings Options -->
                <div v-if="$page.props.auth.user" class="pt-4 pb-1 border-t border-gray-800">
                    <div class="px-4">
                        <div class="font-medium text-white">
                            {{ $page.props.auth.user.name }}
                        </div>
                        <div class="font-medium text-sm text-gray-400">{{ $page.props.auth.user.email }}</div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <ResponsiveNavLink :href="route('profile.edit')">Особистий кабінет</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                            Вийти
                        </ResponsiveNavLink>
                    </div>
                </div>
            </div>
        </nav>
        <!-- Page Heading -->
        <div class="bg-dark-site shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between">
                <div class="flex items-center justify-center">
                    <div class="catalog-button mr-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <g clip-path="url(#clip0_776_27)">
                                <path
                                    d="M3.33333 0H0.666667C0.298667 0 0 0.298667 0 0.666667V3.33333C0 3.70133 0.298667 4 0.666667 4H3.33333C3.70133 4 4 3.70133 4 3.33333V0.666667C4 0.298667 3.70133 0 3.33333 0Z"
                                    fill="white"/>
                                <path
                                    d="M3.33333 6H0.666667C0.298667 6 0 6.29867 0 6.66667V9.33333C0 9.70133 0.298667 10 0.666667 10H3.33333C3.70133 10 4 9.70133 4 9.33333V6.66667C4 6.29867 3.70133 6 3.33333 6Z"
                                    fill="white"/>
                                <path
                                    d="M3.33333 12H0.666667C0.298667 12 0 12.2987 0 12.6667V15.3333C0 15.7013 0.298667 16 0.666667 16H3.33333C3.70133 16 4 15.7013 4 15.3333V12.6667C4 12.2987 3.70133 12 3.33333 12Z"
                                    fill="white"/>
                                <path
                                    d="M9.33333 0H6.66667C6.29867 0 6 0.298667 6 0.666667V3.33333C6 3.70133 6.29867 4 6.66667 4H9.33333C9.70133 4 10 3.70133 10 3.33333V0.666667C10 0.298667 9.70133 0 9.33333 0Z"
                                    fill="white"/>
                                <path
                                    d="M9.33333 6H6.66667C6.29867 6 6 6.29867 6 6.66667V9.33333C6 9.70133 6.29867 10 6.66667 10H9.33333C9.70133 10 10 9.70133 10 9.33333V6.66667C10 6.29867 9.70133 6 9.33333 6Z"
                                    fill="white"/>
                                <path
                                    d="M9.33333 12H6.66667C6.29867 12 6 12.2987 6 12.6667V15.3333C6 15.7013 6.29867 16 6.66667 16H9.33333C9.70133 16 10 15.7013 10 15.3333V12.6667C10 12.2987 9.70133 12 9.33333 12Z"
                                    fill="white"/>
                                <path
                                    d="M15.3333 0H12.6667C12.2987 0 12 0.298667 12 0.666667V3.33333C12 3.70133 12.2987 4 12.6667 4H15.3333C15.7013 4 16 3.70133 16 3.33333V0.666667C16 0.298667 15.7013 0 15.3333 0Z"
                                    fill="white"/>
                                <path
                                    d="M15.3333 6H12.6667C12.2987 6 12 6.29867 12 6.66667V9.33333C12 9.70133 12.2987 10 12.6667 10H15.3333C15.7013 10 16 9.70133 16 9.33333V6.66667C16 6.29867 15.7013 6 15.3333 6Z"
                                    fill="white"/>
                                <path
                                    d="M15.3333 12H12.6667C12.2987 12 12 12.2987 12 12.6667V15.3333C12 15.7013 12.2987 16 12.6667 16H15.3333C15.7013 16 16 15.7013 16 15.3333V12.6667C16 12.2987 15.7013 12 15.3333 12Z"
                                    fill="white"/>
                            </g>
                            <defs>
                                <clipPath id="clip0_776_27">
                                    <rect width="16" height="16" fill="white"/>
                                </clipPath>
                            </defs>
                        </svg>
                        <button data-modal-target="top-catalog-menu" data-modal-toggle="top-catalog-menu" class="font-semibold text-xl text-white leading-tight">Каталог</button>
                    </div>
                </div>
                <div class="hidden sm:grid sm:grid-cols-3 md:grid-cols-4 sm:gap-2 lg:flex lg:items-center lg:gap-6">
                    <NavLink href="#"><span class="text-white">Бренди</span></NavLink>
                    <NavLink href="#"><span class="text-white">Сервіс</span></NavLink>
                    <NavLink href="#"><span class="text-white">Послуги</span></NavLink>
                    <NavLink href="#"><span class="text-white">Підтримка</span></NavLink>
                    <NavLink href="#"><span class="text-white">Про компанію</span></NavLink>
                    <NavLink href="#"><span class="text-white">Блог</span></NavLink>
                    <NavLink href="#"><span class="text-white">Де купити</span></NavLink>
                </div>
                <div class="flex items-center gap-6">
                    <IconCounter href="#" :count="15">
                        <img src="img/front/heart.svg" alt="heart">
                    </IconCounter>
                    <IconCounter href="#" :count="2">
                        <img src="img/front/cart.svg" alt="cart">
                    </IconCounter>
                </div>
                <slot name="header"/>
            </div>
        </div>
    </header>
</template>

<style scoped lang="scss">
@import "./resources/scss/_variables.scss";

.bg-dark-site {
    background: $main-dark;
}

.border-header {
    border-bottom-color: rgba(133, 143, 164, 0.15);
}

.catalog-button {
    display: inline-flex;
    padding: 1em 1em;
    justify-content: center;
    align-items: center;
    gap: 6px;
    border-radius: 4px;
    background: #F53B49;

    &:hover {
        background: #E32A38;
        cursor: pointer;
    }
}

.c-text-gray {
    color: $text-gray;
}

.nav-link {
    color: $text-gray;
    text-align: right;
    font-size: 14px;
    font-style: normal;
    font-weight: 400;
    line-height: 140%;

    &:hover {
        color: #FFF;
    }

    &--white {
        color: #FFF;
    }
}
</style>
