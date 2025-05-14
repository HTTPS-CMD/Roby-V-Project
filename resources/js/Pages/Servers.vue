<script lang="ts" setup>
import CountryFlag from "vue-country-flag-next";

const props = defineProps<{ tags: models.Tag[] }>();

const columns = [
    { field: "name", title: "نام", filter: true, sort: true },
    { field: "latin_name", title: "نام لاتین", filter: true, sort: true },
    {
        field: "with_count_configs",
        title: "‌کانفیگ‌ها",
        filter: false,
        sort: false,
    },
    { field: "traffic", title: "ترافیک", filter: false, sort: true },
    { field: "location", title: "منطقه", filter: true, sort: true },
    { field: "status", title: "وضعیت", filter: true, sort: true },
    { field: "users", title: "کاربران", filter: false, sort: false },
];

const server = reactive({
    item: undefined,
    modal: false,
});
</script>

<template>
    <AppLayout>
        <Datatable
            api="servers"
            :cols="columns"
            :includes="['users', 'tags']"
            @show-form="
                (args) => {
                    server.item = args
                        ? {
                              ..._omit(args, [
                                  'created_at',
                                  'updated_at',
                                  'tags',
                              ]),
                              tags: args.tags.map((item) => item.name.fa),
                          }
                        : undefined;
                    server.modal = true;
                }
            "
        >
            <template #tags="{ value }">
                <div class="flex items-center gap-x-2 flex-wrap">
                    <SecondaryButton
                        class="rounded-full"
                        v-for="(item, i) in value.tags"
                        :key="i"
                        v-text="`#${item.name.fa}`"
                    ></SecondaryButton>
                </div>
            </template>
            <template #status="{ value }">
                {{ _find(status, ["value", value.status])?.label }}
            </template>
            <template #location="{ value }">
                <div class="flex items-center gap-x-2">
                    <CountryFlag :country="value.location" size="small" />
                    {{
                        _find(countries, ["code", value.location.toUpperCase()])
                            ?.name_fa
                    }}
                </div>
            </template>
            <template #traffic="{ value }">
                {{ useConvertFileSize(value.traffic) }}
            </template>
            <template #with_count_configs="{ value }">
                {{ `${value.with_count_configs} کانفیگ` }}
            </template>
        </Datatable>
        <DialogsServer
            v-model:modal="server.modal"
            :item="server.item"
            :tags="_map(props.tags, 'name.fa')"
        />
    </AppLayout>
</template>
