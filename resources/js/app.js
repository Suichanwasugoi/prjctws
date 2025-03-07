import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faEdit, faTrash } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faTachometerAlt, faCalendarAlt, faUsers, faUser } from '@fortawesome/free-solid-svg-icons';

library.add(faEdit, faTrash);
library.add(faTachometerAlt, faCalendarAlt, faUsers, faUser);

const appName = import.meta.env.VITE_APP_NAME || 'Event Manager';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        app.use(plugin)
           .use(ZiggyVue)
           .component('font-awesome-icon', FontAwesomeIcon)
           .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
