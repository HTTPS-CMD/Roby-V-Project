<script lang="ts" setup>
import { status } from "@/Composables/StaticVars";

export type IConfig = Omit<
    models.VConfig,
    "id" | "user_id" | "server_id" | "is_expired"
> & {
    id?: number;
    server_id: number | null;
    user_id: number | null;
};

const props = withDefaults(
    defineProps<{
        item?: IConfig;
        operators: string[];
    }>(),
    {
        item: () => ({
            id: undefined,
            title: "",
            operator: "",
            status: status[0].value,
            expire: null,
            server_id: null,
            used: 0,
            user_id: null,
        }),
    }
);

const modal = defineModel("modal", { default: false });
</script>

<template>
    <FormModal
        route-name="configs"
        :item="
            !item.operator.length
                ? { ..._omit(item, ['operator']), operator: props.operators[0] }
                : item
        "
        pronounce="کانفیگ"
        v-model:modal="modal"
    >
        <FormKit type="text" name="title" label="عنوان" validation="required" />
        <FormKit
            type="select"
            name="operator"
            label="اپراتور"
            :options="operators"
            validation="required"
        />
        <FormKit type="select" name="status" label="وضعیت" :options="status" />
        <FormKit
            type="date"
            name="expire"
            label="انقضاء"
            validation="nullable|date_after:now"
        />
        <SelectApi
            route-name="servers"
            search-key="name"
            item-key="name"
            item-value="id"
            name="server_id"
            label="سرور"
            validation="required"
        />
        <SelectApi
            route-name="users"
            search-key="name"
            item-key="name"
            item-value="id"
            name="user_id"
            label="کاربر"
            validation="required"
        />
        <FormKit
            type="number"
            name="used"
            label="حجم ترافیک"
            help="از مگابایت به بالا"
            suffix="MB"
        />
    </FormModal>
</template>
