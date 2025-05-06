import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import AutoImport from "unplugin-auto-import/vite";
import Components from "unplugin-vue-components/vite";
import Icons from "unplugin-icons/vite";
import IconsResolver from "unplugin-icons/resolver";
import Unfonts from "unplugin-fonts/vite";

export default defineConfig({
    build: {
        minify: true,
    },
    css: {
        preprocessorOptions: {
            scss: { api: "modern-compiler" },
        },
    },
    plugins: [
        laravel({
            input: "resources/js/app.ts",
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        Components({
            dts: "./resources/js/Types/components.d.ts",
            dirs: ["resources/js/Components", "resources/js/Layouts"],
            deep: true,
            include: [
                /\.vue$/,
                /\.vue\?vue/, // .vue
                /\.md$/, // .md
            ],
            resolvers: [
                IconsResolver({ enabledCollections: ["ph"], prefix: false }),
            ],
            directoryAsNamespace: true,
        }),
        AutoImport({
            include: [
                /\.[tj]sx?$/, // match .ts, .tsx, .js, .jsx
                /\.[tj]sx?$/, // .ts, .tsx, .js, .jsx
                /\.vue$/,
                /\.vue\?vue/, // .vue
            ],
            imports: [
                "vue",
                "@vueuse/core",
                { "ziggy-js": ["route"] },
                { "@vueuse/integrations": ["*"] },
                { "@inertiajs/vue3": ["router", "useForm"] },
            ],
            dirs: ["./resources/js/Composables", "./resources/js/Utils"],
            dts: "./resources/js/Types/auto-imports.d.ts",
            vueTemplate: true,
        }),
        Icons({
            autoInstall: true,
        }),
        Unfonts({
            custom: {
                display: "swap",
                preload: true,
                families: {
                    iransans: {
                        src: [
                            "./resources/fonts/Woff/IRANSansXFaNum*",
                            "./resources/fonts/Woff2/IRANSansXFaNum*",
                        ],
                        transform(font) {
                            const name = "IRANSansXFaNum-";
                            switch (font.basename) {
                                case `${name}ThinD4`:
                                    font.weight = 100;
                                    break;
                                case `${name}UltraLightD4`:
                                    font.weight = 200;
                                    break;
                                case `${name}LightD4`:
                                    font.weight = 300;
                                    break;
                                case `${name}MediumD4`:
                                    font.weight = 500;
                                    break;
                                case `${name}DemiBoldD4`:
                                    font.weight = 600;
                                    break;
                                case `${name}ExtraBoldD4`:
                                    font.weight = 800;
                                    break;
                                case `${name}BlackD4`:
                                    font.weight = 900;
                                    break;
                                case `${name}ExtraBlackD4`:
                                    font.weight = 950;
                                    break;
                                case `${name}HeavyD4`:
                                    font.weight = 1000;
                                    break;
                            }

                            return font;
                        },
                    },
                },
            },
        }),
    ],
});
