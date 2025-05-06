import axios, { type AxiosStatic } from "axios";

declare global {
    interface Window {
        axios: AxiosStatic;
    }
}

declare var window: Window;

window.axios = axios;

window.axios.defaults.withCredentials = true;

const token: HTMLMetaElement | null = document.head.querySelector(
    'meta[name="csrf-token"]'
);

if (token) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
} else {
    console.error("CSRF token not found");
}

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
