<script lang="ts" setup>
const props = defineProps<{
    permissions: {
        group: string;
        group_name: string;
        title: string;
        name: string;
    }[];
}>();

const groupedPermissions = computed(() => _groupBy(props.permissions, "group"));

const columns = [
    { field: "title", title: "عنوان", filter: true, sort: true },
    { field: "name", title: "نام", filter: true, sort: true },
    // {
    //     field: "with_count_users",
    //     title: "تعداد کاربران",
    //     filter: false,
    //     sort: false,
    // },
    { field: "permissions", title: "دسترسی‌ها", filter: false, sort: false },
];

const role = reactive({
    item: undefined,
    modal: false,
});
</script>

<template>
    <Head title="نقش‌ها"></Head>
    <AppLayout>
        <Datatable
            api="roles"
            :cols="columns"
            :includes="['permissions']"
            no-selectable
            @show-form="
                (args) => {
                    role.item = args
                        ? {
                              ..._pick(args, ['id', 'name', 'title']),
                              permissions: _map(args.permissions, 'name'),
                          }
                        : undefined;
                    role.modal = true;
                }
            "
        >
            <template #permissions="{ value }">
                <div
                    class="flex items-center gap-x-2 flex-wrap"
                    v-if="value.permissions?.length"
                >
                    <SecondaryButton
                        class="rounded-full"
                        v-for="(item, i) in value.permissions.slice(0, 6)"
                        :key="i"
                        v-text="item.title"
                    ></SecondaryButton>
                </div>
                <span v-else>-</span>
            </template>
        </Datatable>
        <DialogsRole
            v-model:modal="role.modal"
            :item="role.item"
            :permissions="groupedPermissions"
        />
    </AppLayout>
</template>
