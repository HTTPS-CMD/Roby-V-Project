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
            operator: props.operators[0],
            status: status[0].value,
            expire: null,
            server_id: null,
            config_encrypted: "",
            used: 0,
            user_id: null,
        }),
    }
);

const modal = defineModel("modal", { default: false });

// TODO : servers, users
</script>

<template>
    <FormModal
        route-name="configs"
        :item="item"
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
        />>
        <FormKit
            type="select"
            name="server_id"
            label="سرور"
            :options="[]"
            validation="required"
        />
        <FormKit
            type="select"
            name="user_id"
            label="کاربر"
            :options="[]"
            validation="required"
        />
        <FormKit
            type="textarea"
            name="config_encrypted"
            label="کانفیگ"
            validation="required"
        />
        <FormKit
            type="number"
            name="used"
            label="استفاده شده"
            help="از مگابایت به بالا"
            suffix="MB"
        />
    </FormModal>
</template>
