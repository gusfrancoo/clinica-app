import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

// PrimeVue imports
import PrimeVue from 'primevue/config';
import FileUpload from 'primevue/fileupload';
import Button from 'primevue/button';
import ProgressBar from 'primevue/progressbar';
import Divider from 'primevue/divider';
import Image from 'primevue/image';





import Aura from '@primevue/themes/aura';
import 'primeicons/primeicons.css'


import theme from 'tailwindcss/defaultTheme';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        // Criando o app Vue
        const vueApp = createApp({ render: () => h(App, props) });

        // Usando o plugin do Inertia e o ZiggyVue
        vueApp.use(plugin)
              .use(ZiggyVue)
              .use(PrimeVue, {
                theme: {
                    preset: Aura,
                    options: {
                        prefix: 'p',
                        darkModeSelector: 'system',
                        cssLayer: false
                    }
                }
              }); // Habilitando o PrimeVue


        vueApp.component('FileUpload', FileUpload);
        vueApp.component('Button', Button);
        vueApp.component('ProgressBar', ProgressBar);
        vueApp.component('Divider', Divider);
        vueApp.component('Image', Image);

        // Montando o app
        vueApp.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});