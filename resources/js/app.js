import * as Sentry from "@sentry/vue";

import { route, ZiggyVue } from 'ziggy-js';

import { createApp, defineAsyncComponent, h } from 'vue';
import { createInertiaApp, Link, Head } from '@inertiajs/vue3';

import { userCan } from "@js/utilities/permissions.js";

const AppLayout = defineAsyncComponent(() => import('@js/Layouts/App.vue'));

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        let page = pages[`./Pages/${name}.vue`];

        page.default.layout = page.default.layout || AppLayout;

        return page;
    },

    setup({ el, App, props, plugin }) {
        const VueApp = createApp({ render: () => h(App, props) });

        Sentry.init({
            app: VueApp,
            dsn: import.meta.env.VITE_SENTRY_DSN_PUBLIC,
            environment: import.meta.env.VITE_APP_ENV,
        });

        VueApp.use(plugin)
            .use(ZiggyVue);

        VueApp.mixin({ methods: { userCan } });

        VueApp.component('Head', Head)
            .component('Link', Link)
            .component('PageTitle', defineAsyncComponent(() => import('@js/Components/PageTitle.vue')))
            .component('Notice', defineAsyncComponent(() => import('@js/Components/Notice.vue')));

        VueApp.mount(el);
    },

    title: title => title ? `${title} | Template` : 'Template',

    progress: {
        color: '#000',
    },
});
