<script setup>

const activeClass = 'text-blue-500';

function isActive(path) {
    return route().current() === path || route().current().includes(path);
}

function logout() {
    this.$inertia.delete(route('logout'))
}

</script>

<template>
    <p v-if="$page.props.flash.message"
       class="bg-indigo-600 h-10 flex items-center justify-center text-sm font-medium text-white px-4 sm:px-6 lg:px-8">
        {{ $page.props.flash.message }}
    </p>

    <nav class="bg-gray-800">
        <div class="max-w-7xl mx-auto">
            <div class="relative flex items-center justify-between h-16">
                <div class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start">
                    <div class="flex space-x-4">
                        <a :href="route('home')" :class="{[activeClass]: isActive('home')}"
                           class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                            Home
                        </a>

<!--                        <a :href="route('users.index')" :class="{[activeClass]: isActive('users')}"-->
<!--                           class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">-->
<!--                            Users-->
<!--                        </a>-->
                    </div>
                </div>
                <div v-if="$page.props.canLogin" class="text-right">
                    <a
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium"
                    >
                        Dashboard
                    </a>

                    <template v-else>
                        <a
                            :href="route('login')"
                            class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium"
                        >Log in
                        </a>

                        <a
                            v-if="$page.props.canRegister"
                            :href="route('register')"
                            class="text-gray-300 hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium"
                        >Register
                        </a
                        >
                    </template>
                </div>
            </div>
        </div>
    </nav>

    <div v-if="$page.props.user">
        {{ $page.props.user.email }}

        <div>
            <a @click="logout">Выйти</a>
        </div>
    </div>

    <div class="container mx-auto">
        <div class="my-10">
            <slot/>
        </div>
    </div>
</template>

<style scoped>

</style>
