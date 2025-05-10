<script lang="ts" setup>
import { status } from "@/Composables/StaticVars";

export type IUser = Omit<models.User, "roles"> & { roles: models.Role | null };

const props = withDefaults(
    defineProps<{ item?: IUser; roles: models.Role[] }>(),
    {
        item: () => ({
            id: undefined,
            email: "",
            name: "",
            password: "",
            status: true,
            profile_photo_url: "",
            roles: null,
        }),
    }
);

const isPassword = reactive({
    password: true,
    conf_password: true,
});

const modal = defineModel("modal", { default: false });
</script>

<template>
    <FormModal route-name="users" :item="item" v-model:modal="modal">
        <FormKit type="text" name="name" label="نام"></FormKit>
        <FormKit type="email" name="email" label="ایمیل"></FormKit>
        <FormKit
            type="select"
            :options="status"
            name="status"
            label="وضعیت"
        ></FormKit>
        <FormKit
            :type="isPassword.password ? 'password' : 'text'"
            name="password"
            label="گذرواژه"
        ></FormKit>
        <FormKit
            :type="isPassword.conf_password ? 'password' : 'text'"
            name="password"
            label="تکرار گذرواژه"
        ></FormKit>
        <FormKit
            type="select"
            :options="
                roles.map(({ title: label, name: value }) => ({
                    label: label || '-',
                    value,
                }))
            "
            name="roles"
            label="نقش"
        ></FormKit>
    </FormModal>
</template>
