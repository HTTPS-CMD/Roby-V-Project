<script lang="ts" setup>
import { status } from "@/Composables/StaticVars";
import SelectApi from "../SelectApi.vue";

export type INotification = Omit<models.Notification, "id" | "users"> & {
    users: number[];
    id?: number;
};

const props = withDefaults(
    defineProps<{
        item?: INotification;
    }>(),
    {
        item: () => ({
            title: "",
            content: "",
            link: null,
            status: status[0].value,
            all_users: false,
            users: [],
        }),
    }
);

const modal = defineModel("modal", { default: false });
</script>

<template>
    <FormModal
        route-name="notifications"
        :item="item"
        pronounce="اعلان"
        v-model:modal="modal"
    >
        <FormKit type="text" name="title" label="عنوان" />
        <FormKit type="text" name="link" label="لینک" />
        <FormKit type="select" name="status" label="وضعیت" :options="status" />
        <FormKit type="checkbox" name="all_users" label="نمایش برای همه" />
        <SelectApi
            route-name="users"
            search-key="name"
            item-key="name"
            item-value="id"
            name="users"
            label="نمایش برای کاربران"
            multiple
        />
        <div class="col-span-full">
            <FormKit type="textarea" name="content" label="متن" />
        </div>
    </FormModal>
</template>
