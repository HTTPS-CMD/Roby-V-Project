<script lang="ts" setup>
const columns = [
    { field: "title", title: "عنوان", filter: true, sort: true },
    { field: "link", title: "لینک", filter: false, sort: false },
    { field: "status", title: "وضعیت", filter: true, sort: true },
    { field: "all_users", title: "نمایش برای همه", filter: true, sort: true },
];

const notification = reactive({
    item: undefined,
    modal: false,
});
</script>

<template>
    <Head title="اعلان‌ها"></Head>
    <AppLayout>
        <Datatable
            api="notifications"
            :cols="columns"
            :includes="['users']"
            @show-form="
                (args) => {
                    notification.item = args
                        ? {
                              ..._omit(args, [
                                  'updated_at',
                                  'created_at',
                                  'users',
                              ]),
                              users: _map(args.users, 'id'),
                          }
                        : undefined;
                    notification.modal = true;
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
        <DialogsNotification
            v-model:modal="notification.modal"
            :item="notification.item"
        />
    </AppLayout>
</template>
