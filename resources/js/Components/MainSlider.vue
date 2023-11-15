<script setup>
import 'flowbite';
import {ref, onMounted} from "vue";
const mainCarousel = ref(null)
onMounted(() => {
    // mainCarousel.focus()
})
</script>

<template>
    <div ref="mainCarousel" class="relative w-full p-8" data-carousel="slide" data-carousel-interval="3000">
        <!-- Carousel wrapper -->
        <div class="relative h-56 overflow-hidden rounded-lg md:h-72 xl:h-96">
            <!-- Item 1 -->
            <div class="hidden duration-700 ease-in-out" data-carousel-item="active">
                <div class="absolute inset-0 slider-bg"></div> <!-- Транспарентна заливка -->
                <img src="img/front/slider/girl-run.png"
                     class="absolute block -translate-x-1/2 -translate-y-1/2 top-1/2 -right-24 xl:right-0 h-full" alt="...">
                <div class="relative max-w-7xl mx-auto">
                    <div class="slider-text-wrapper absolute grid grid-cols-1 gap-2 top-4 left-4 xl:top-10 xl:left-10">
                        <div class="slider-title uppercase">
                            Zero Runner
                        </div>
                        <div class="slider-text uppercase">
                            Біг з нульовим ударним навантаженням на суглоби
                        </div>
                        <div class="slider-button-wrapper">
                            <button class="slider-button">
                                Дізнатись більше
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Item 2 -->
            <div class="hidden duration-700 ease-in-out" data-carousel-item>
                <div class="absolute inset-0 slider-bg"></div> <!-- Транспарентна заливка -->
                <img src="img/front/slider/man-run.png"
                     class="absolute block -translate-x-1/2 -translate-y-1/2 top-1/2 -right-24 xl:right-0 h-full" alt="...">
                <div class="relative max-w-7xl mx-auto">
                    <div class="slider-text-wrapper absolute grid grid-cols-1 gap-2 top-4 left-4 xl:top-10 xl:left-10">
                        <div class="slider-title uppercase">
                            Бігова доріжка
                        </div>
                        <div class="slider-text uppercase">
                            Для підготовки до марафону
                        </div>
                        <div class="slider-button-wrapper">
                            <button class="slider-button">
                                Дізнатись більше
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Slider controls -->
        <div class="absolute bottom-16 right-16 xl:right-32 flex">
            <button type="button"
                    class="z-30 flex items-center justify-center px-4 cursor-pointer group focus:outline-none"
                    data-carousel-prev>
                <span
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white group-hover:bg-white/50 group-focus:ring-4 group-focus:ring-white group-focus:outline-none">
                    <svg class="w-4 h-4 text-red-500  rtl:rotate-180" aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 1 1 5l4 4"/>
                    </svg>
                </span>
            </button>
            <button type="button"
                    class="z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                    data-carousel-next>
                <span
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white group-hover:bg-white/50  group-focus:ring-4 group-focus:ring-white group-focus:outline-none">
                    <svg class="w-4 h-4 text-red-500  rtl:rotate-180" aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m1 9 4-4-4-4"/>
                    </svg>
                </span>
            </button>
        </div>

    </div>


</template>

<style scoped lang="scss">

.slider-bg {
    background-color: #E9EAEF;
}

.slider-title {
    color: #F53B49;
    font-size: 18px;
    font-style: normal;
    font-weight: 900;
    line-height: 110%; /* 26.4px */
    text-transform: uppercase;
}

.slider-button {
    display: flex;
    padding: 0.5em 1em;
    justify-content: center;
    align-items: center;
    gap: 10px;

    border-radius: 4px;
    border: 1px solid #F53B49;

    color: #F53B49;
    font-size: 12px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;

    &:hover {
        background: white;
    }
}

.slider-text-wrapper {
    width: 250px;
}

.slider-text {
    color: #1A1A25;
    font-size: 24px;
    font-style: normal;
    font-weight: 900;
    line-height: 110%; /* 54.5px */
    text-transform: uppercase;
}

@media (min-width: 768px) {
    .slider-title {
        font-size: 22px;
    }

    .slider-button {
        padding: 12px 20px 13px 20px;
        font-size: 14px;
    }

    .slider-text-wrapper {
        width: 400px;
    }

    .slider-text {
        font-size: 36px;
    }
}

@media (min-width: 1024px) {
    .slider-title {
        font-size: 24px;
    }

    .slider-button {
        padding: 12px 20px 13px 20px;
        font-size: 14px;
    }

    .slider-text-wrapper {
        width: 450px;
    }

    .slider-text {
        font-size: 40px;
    }
}

@media (min-width: 1280px) {
    .slider-title {
        font-size: 24px;
    }

    .slider-button {
        padding: 12px 20px 13px 20px;
        font-size: 14px;
    }

    .slider-text-wrapper {
        width: 512px;
    }

    .slider-text {
        font-size: 48px;
    }
}
</style>
