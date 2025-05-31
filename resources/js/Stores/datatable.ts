import { defineStore } from "pinia";

export const useDatatable = defineStore("datatableStore", () => {
    const rows = ref<TPageProps>();

    return { rows };
});
