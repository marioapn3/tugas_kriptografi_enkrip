import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";
import route from "ziggy-js";

// Plugin
import Notifications from "notiwind";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import "@/components/charts/ChartjsConfig.js";

import FloatingVue from "floating-vue";
import "floating-vue/dist/style.css";

// Font Awesome Setup
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { fas } from "@fortawesome/free-solid-svg-icons";
library.add(fas);
import { fab } from "@fortawesome/free-brands-svg-icons";
library.add(fab);
import { far } from "@fortawesome/free-regular-svg-icons";
library.add(far);
import { dom } from "@fortawesome/fontawesome-svg-core";
dom.watch();

createInertiaApp({
    resolve: async (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue");
        return (await pages[`./Pages/${name}.vue`]()).default;
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .mixin({ methods: { route } })
            .use(plugin)
            .use(Notifications)
            .use(FloatingVue)
            .component("Datepicker", Datepicker)
            .component("font-awesome-icon", FontAwesomeIcon)
            .mount(el);
    },
});

InertiaProgress.init();
