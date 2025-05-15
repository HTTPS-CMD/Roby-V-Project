<script lang="ts" setup>
import { status } from "@/Composables/StaticVars";
import { addDays, format } from "date-fns";

export type IConfig = Omit<
    models.VConfig,
    "id" | "user_id" | "server_id" | "is_expired" | "total"
> & {
    id?: number;
    server_id: number | null;
    user_id: number | null;
    total: number | null;
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
            total: null,
            user_id: null,
        }),
    }
);

const modal = defineModel("modal", { default: false });

const dates = computed(() =>
    [1, 3, 7, 30, 60, 90, 120, 180, 365].map((day, i) => ({
        label: day < 30 ? day + " روز" : Math.round(day / 30) + " ماه",
        value: format(addDays(new Date(), day), "yyyy-MM-dd"),
    }))
);
</script>

<template>
    <FormModal
        route-name="configs"
        :item="
            !item.operator.length
                ? {
                      ..._omit(item, ['operator', 'expire']),
                      operator: props.operators[0],
                      expire: dates[0].value,
                  }
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
        <FormKit type="select" name="expire" label="انقضاء" :options="dates" />
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
            name="total"
            label="حجم ترافیک"
            help="از مگابایت به بالا"
            suffix="MB"
        />
    </FormModal>
</template>
