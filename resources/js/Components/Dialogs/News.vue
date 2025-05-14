<script lang="ts" setup>
import { status } from "@/Composables/StaticVars";

export type INews = Omit<models.News, "id" | "slug"> & {
    id?: number;
};

const props = withDefaults(
    defineProps<{
        item?: INews;
    }>(),
    {
        item: () => ({
            id: undefined,
            title: "",
            content: "",
            status: status[0].value,
        }),
    }
);

const modal = defineModel("modal", { default: false });
</script>

<template>
    <FormModal
        route-name="news"
        :item="item"
        pronounce="خبر"
        v-model:modal="modal"
    >
        <FormKit type="text" name="title" label="عنوان" validation="required" />
        <FormKit
            type="select"
            :options="status"
            name="status"
            label="وضعیت"
        ></FormKit>
        <div class="col-span-full">
            <FormKit type="textarea" label="متن سوال" name="content" />
        </div>
    </FormModal>
</template>
