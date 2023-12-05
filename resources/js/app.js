import './bootstrap';
import '../css/app.css';

import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../vendor/tightenco/ziggy/dist/vue.m';
import myMixin from './mixin.js';
import store from './store';

import {MotionPlugin} from '@vueuse/motion'


const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({el, App, props, plugin}) {
        const app = createApp({render: () => h(App, props)})
            .use(plugin)
            .use(MotionPlugin)
            .mixin(myMixin)
            .use(ZiggyVue, Ziggy);

        app.provide('store', store);

        return app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
