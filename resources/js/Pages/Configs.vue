<script lang="ts" setup>
import CountryFlag from "vue-country-flag-next";

const props = defineProps<{
    operators: string[];
}>();

const columns = [
    { field: "title", title: "عنوان", filter: true, sort: true },
    { field: "server_id", title: "سرور", filter: true, sort: true },
    { field: "user_id", title: "کاربر", filter: true, sort: true },
    { field: "used", title: "استفاده شده", filter: true, sort: true },
    { field: "operator", title: "اپراتور", filter: true, sort: true },
    { field: "config", title: "کانفیگ", filter: true, sort: true },
    { field: "expire", title: "انقضاء", filter: true, sort: true },
    { field: "status", title: "وضعیت", filter: true, sort: true },
];

const config = reactive({
    item: undefined,
    modal: false,
});

const { copy } = useClipboard();

async function copyConfig(config: string) {
    const content = await decryptAES(config);
    copy(content);
    useToast("کپی شد", { type: "success" });
}
</script>

<template>
    <AppLayout>
        <Datatable
            api="configs"
            :cols="columns"
            :includes="['user', 'server']"
            @show-form="
                (args) => {
                    config.item = args
                        ? _omit(args, [
                              'updated_at',
                              'created_at',
                              'server',
                              'user',
                          ])
                        : undefined;
                    config.modal = true;
                }
            "
        >
            <template #server_id="{ value }">
                <div class="flex items-center gap-x-2">
                    <CountryFlag
                        :country="value.server.location"
                        size="small"
                    />
                    {{ value.server.name }}
                </div>
            </template>
            <template #user_id="{ value }">
                {{ value.user.name }}
            </template>
            <template #config="{ value }">
                <div
                    @click="copyConfig(value.config)"
                    class="border border-dashed border-gray-500 hover:border-solid hover:border-green-500 px-2 py-1.5 rounded-full cursor-pointer hover:text-green-500 transition"
                >
                    <ph-copy-simple-light />
                    <span class="text-xs">کپی کانفیگ</span>
                </div>
            </template>
            <template #expire="{ value }">
                {{ $d(new Date(value.expire), "date") }}
            </template>
            <template #status="{ value }">
                {{ _find(status, ["value", value.status])?.label }}
            </template>
        </Datatable>
        <DialogsConfig
            v-model:modal="config.modal"
            :item="config.item"
            :operators="operators"
        />
    </AppLayout>
</template>
