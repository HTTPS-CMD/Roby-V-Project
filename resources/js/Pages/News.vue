<script lang="ts" setup>
const columns = [
    { field: "title", title: "عنوان", filter: true, sort: true },
    { field: "user_id", title: "ایجاد کننده", filter: true, sort: true },
    {
        field: "content",
        title: "محتوا",
        filter: true,
        sort: false,
    },
    { field: "status", title: "وضعیت", filter: true, sort: true },
];

const news = reactive({
    item: undefined,
    modal: false,
});
</script>

<template>
    <Head title="اخبار"></Head>
    <AppLayout>
        <Datatable
            api="news"
            :cols="columns"
            :includes="['user']"
            @show-form="
                (args) => {
                    news.item = args
                        ? _pick(args, ['id', 'title', 'content', 'status'])
                        : undefined;
                    news.modal = true;
                }
            "
        >
            <template #user_id="{ value }">
                <div class="flex items-center gap-x-2 flex-wrap">
                    <SecondaryButton
                        class="rounded-full"
                        v-text="value.user?.name || ''"
                    ></SecondaryButton>
                </div>
            </template>
            <template #content="{ value }">
                <div class="max-w-32 truncate" v-html="value.content"></div>
            </template>
            <template #status="{ value }">
                {{ _find(status, ["value", value.status])?.label }}
            </template>
        </Datatable>
        <DialogsNews v-model:modal="news.modal" :item="news.item" />
    </AppLayout>
</template>
