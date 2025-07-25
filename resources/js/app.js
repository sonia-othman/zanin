import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { Ziggy } from './ziggy.js';
import i18n from './i18n.js';
import '../css/app.css';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'ZaninTech';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18n) // Add i18n plugin
            .use({
                install(app) {
                    app.config.globalProperties.$ziggy = Ziggy;
                    app.config.globalProperties.$route = (name, params) => {
                        const route = Ziggy.routes[name];
                        if (!route) return '#';
                        
                        let url = route.uri;
                        if (params) {
                            Object.keys(params).forEach(key => {
                                url = url.replace(`{${key}}`, params[key]);
                            });
                        }
                        // Force it to use 127.0.0.1 to avoid CORS
                        return `http://127.0.0.1:8000${url.startsWith('/') ? '' : '/'}${url}`;
                    };
                }
            });

        // Set the initial locale from props
        if (props.initialPage.props.locale) {
            i18n.global.locale.value = props.initialPage.props.locale;
        }

        return app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});