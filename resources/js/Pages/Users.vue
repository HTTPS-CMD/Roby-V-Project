<script lang="ts" setup>
const props = defineProps<{ roles: models.Role[] }>();

const columns = [
    { field: "name", title: "نام", filter: true, sort: true },
    { field: "email", title: "ایمیل", filter: true, sort: true },
    { field: "status", title: "وضعیت", filter: true, sort: true },
    { field: "roles", title: "نقش", filter: false, sort: false },
];

const datatableRef = useTemplateRef("datatable");

const user = reactive({
    item: undefined,
    modal: false,
});
</script>

<template>
    <AppLayout>
        <Datatable api="users" :cols="columns" ref="datatable">
            <template #roles="{ value }">
                <div class="flex items-center gap-x-2 flex-wrap">
                    <SecondaryButton
                        class="rounded-full"
                        v-for="(item, i) in value"
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
