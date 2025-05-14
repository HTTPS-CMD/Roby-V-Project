import "./bootstrap";
import "../css/app.css";
import "unfonts.css";
import "vue3-toastify/dist/index.css";
import "@bhplugin/vue3-datatable/dist/style.css";

import { createApp, h, type DefineComponent } from "vue";
import { createInertiaApp, Head, Link } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/src/js";
import Vue3Toastify, { type ToastContainerOptions } from "vue3-toastify";
import { plugin as formPlugin, defaultConfig } from "@formkit/vue";
import App from "./App.vue";
import { rootClasses } from "./Utils/formkit.theme";

const appName = window.document.getElementsByTagName("title")[0]?.innerText;

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        const page = resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob<DefineComponent>("./Pages/**/*.vue")
        );
        page.then((module) => {
            module.default.layout = module.default.layout || App;
        });
        return page;
    },
    // @ts-expect-error
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(Vue3Toastify, {
                position: "bottom-left",
                rtl: true,
                bodyClassName: "font-sans",
            } as ToastContainerOptions)
            .use(
                formPlugin,
                defaultConfig({
                    locale: "fa",
                    config: {
                        rootClasses,
                    },
                })
            )
            .component("Link", Link)
            .component("Head", Head)
            .mount(el);
    },
    progress: {
        color: "#6875f5",
    },
});
