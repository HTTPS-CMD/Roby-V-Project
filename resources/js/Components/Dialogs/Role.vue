<script lang="ts" setup>
export type IRole = Omit<models.Role, "permissions" | "guard_name" | "id"> & {
    permissions: string[];
    id?: number;
};

const props = withDefaults(defineProps<{ item?: IRole; permissions: any }>(), {
    item: () => ({
        id: undefined,
        name: "",
        title: "",
        permissions: [],
    }),
});

const modal = defineModel("modal", { default: false });
</script>

<template>
    <FormModal
        route-name="roles"
        :item="item"
        pronounce="نقش"
        v-model:modal="modal"
    >
        <FormKit
            type="text"
            label="نام نقش"
            name="title"
            validation="required"
        />
        <FormKit
            type="text"
            label="نام لاتین"
            name="name"
            placeholder="role-1"
            help="نام لاتین نقش به صورت role-1"
            validation="required|alpha_dash"
        />
        <div v-for="(item, index) in _values(permissions)" :key="index">
            <span>{{ item[0].group_name }}</span>
            <FormKit
                type="checkbox"
                name="permissions"
                :options="
                    item.map(({ title: label, name: value }) => ({
                        label,
                        value,
                    }))
                "
            ></FormKit>
        </div>
    </FormModal>
</template>
