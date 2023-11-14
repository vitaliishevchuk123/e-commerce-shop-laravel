<script setup>
import Footer from "@/Layouts/Footer.vue";
import Header from "@/Layouts/Header.vue";
</script>

<template>
    <div class="main-wrapper min-h-screen bg-gray-100">
        <Header/>

        <!-- Page Content -->
        <main>
            <slot/>
        </main>
        <Footer/>
    </div>
</template>

<style scoped lang="scss">
@import "./resources/scss/_variables.scss";

.main-wrapper {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
</style>
