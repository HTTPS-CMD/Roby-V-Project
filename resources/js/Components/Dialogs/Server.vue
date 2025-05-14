<script lang="ts" setup>
export type IServer = Omit<models.Server, "id">;

const props = withDefaults(
    defineProps<{
        item?: IServer;
        tags: string[];
    }>(),
    {
        item: () => ({
            id: undefined,
            name: "",
            latin_name: "",
            config: "",
            traffic: 0,
            location: "",
            status: status[0].value,
            users: [],
            tags: [],
        }),
    }
);

const modal = defineModel("modal", { default: false });
</script>

<template>
    <FormModal
        route-name="servers"
        :item="item"
        pronounce="سرور"
        v-model:modal="modal"
    >
        <FormKit type="text" name="name" label="نام" validation="required" />
        <FormKit
            type="text"
            name="latin_name"
            label="نام لاتین"
            validation="required"
        />
        <FormKit
            type="textarea"
            name="config"
            label="کانفیگ"
            validation="required"
        />
        <FormKit type="number" name="traffic" label="ترافیک" />
        <FormKit
            type="select"
            name="location"
            label="مکان"
            :options="
                countries.map(({ name_fa, code }) => ({
                    label: name_fa,
                    value: code.toLowerCase(),
                }))
            "
        />
        <FormKit type="select" name="status" label="وضعیت" :options="status" />
        <FormKit
            type="select"
            name="tags"
            label="تگ‌ها"
            :options="tags"
            multiple
        />
        <SelectApi
            name="users"
            label="کاربران"
            item-key="name"
            item-value="id"
            route-name="users"
            search-key="name"
        />
    </FormModal>
</template>
