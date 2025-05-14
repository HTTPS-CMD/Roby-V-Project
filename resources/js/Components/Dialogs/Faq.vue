<script lang="ts" setup>
import { status } from "@/Composables/StaticVars";

export type IFaq = Omit<models.Faq, "id" | "sortable"> & { id?: number };

const props = withDefaults(
    defineProps<{
        item?: IFaq;
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
        route-name="faqs"
        :item="item"
        pronounce="سوال"
        v-model:modal="modal"
    >
        <FormKit type="text" label="عنوان" name="title" validation="required" />
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
