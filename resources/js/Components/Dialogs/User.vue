<script lang="ts" setup>
import { status } from "@/Composables/StaticVars";

export type IUser = Omit<models.User, "roles" | "id"> & {
    roles: models.Role | null;
    has_password: boolean;
    password_confirmation: string;
    id?: number;
};

const props = withDefaults(
    defineProps<{ item?: IUser; roles: models.Role[] }>(),
    {
        item: () => ({
            id: undefined,
            email: "",
            name: "",
            password: "",
            password_confirmation: "",
            status: true,
            profile_photo_url: "",
            roles: null,
            has_password: true,
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
    <FormModal
        route-name="users"
        :item="item"
        v-model:modal="modal"
        pronounce="کاربر"
        v-slot="{ value }"
    >
        <FormKit
            type="text"
            name="name"
            label="نام"
            validation="required|string"
        ></FormKit>
        <FormKit
            type="email"
            name="email"
            label="ایمیل"
            validation="required|email"
        ></FormKit>
        <FormKit
            type="select"
            :options="status"
            name="status"
            label="وضعیت"
        ></FormKit>
        <div class="col-span-full border-b border-gray-500" v-if="item?.id">
            <FormKit
                type="radio"
                name="has_password"
                label="گذرواژه"
                :options="[
                    { label: 'دارد', value: true },
                    { label: 'ندارد', value: false },
                ]"
            ></FormKit>
        </div>
        <template v-if="value.has_password">
            <FormKit
                :type="isPassword.password ? 'password' : 'text'"
                name="password"
                label="گذرواژه"
                validation="required"
            ></FormKit>
            <FormKit
                :type="isPassword.conf_password ? 'password' : 'text'"
                name="password_confirmation"
                label="تکرار گذرواژه"
                validation="required"
            ></FormKit>
        </template>
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
