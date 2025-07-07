<script lang="ts" setup>
const props = defineProps<{ roles: models.Role[] }>();

const columns = [
    { field: "name", title: "نام", filter: true, sort: true },
    { field: "email", title: "ایمیل", filter: true, sort: true },
    { field: "status", title: "وضعیت", filter: true, sort: true },
    { field: "roles", title: "نقش", filter: false, sort: false },
];

const user = reactive({
    item: undefined,
    modal: false,
});
</script>

<template>
    <Head title="کاربران"></Head>
    <AppLayout>
        <Datatable
            api="users"
            :cols="columns"
            :includes="['roles']"
            no-selectable
            @show-form="
                (args) => {
                    user.item = args
                        ? {
                              ..._pick(args, ['id', 'name', 'email', 'status']),
                              roles: args.roles[0].name,
                              password: '',
                              password_confirm: '',
                              has_password: false,
                          }
                        : undefined;
                    user.modal = true;
                }
            "
        >
            <template #roles="{ value }">
                <div class="flex items-center gap-x-2 flex-wrap">
                    <SecondaryButton
                        class="rounded-full"
                        v-for="(item, i) in value.roles"
                        :key="i"
                        v-text="item.title"
                    ></SecondaryButton>
                </div>
            </template>
        </Datatable>
        <DialogsUser
            v-model:modal="user.modal"
            :item="user.item"
            :roles="roles || []"
        />
    </AppLayout>
</template>
