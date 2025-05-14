<script lang="ts" setup>
import type { IToast } from "@/App.vue";

const props = withDefaults(
    defineProps<{
        item: any;
        routeName: string;
        pronounce?: string;
    }>(),
    { pronounce: "" }
);

const modal = defineModel("modal", { default: false });

const form = ref(props.item);

function submitHandler(fields, node) {
    const f = useForm(form.value);
    f[!props.item?.id ? "post" : "patch"](
        route(
            props.routeName.concat(!props.item?.id ? ".store" : ".update"),
            props.item?.id ? { id: props.item.id } : undefined
        )
    )(fields, node);
}

watch(modal, (v) => {
    form.value = toRaw(props.item);
    if (!v) form.value = {};
});

const { props: pageProps } = usePage();

watch(pageProps.flash as IToast, (v) => {
    if (v.msg) modal.value = false;
});
</script>

<template>
    <DialogModal :show="modal" @close="modal = false">
        <template #title>
            <div class="flex items-center gap-x-2">
                <ph-pencil-line v-if="item?.id" />
                <ph-plus-circle-duotone v-else />
                {{ `${item?.id ? "ویرایش" : "ایجاد"} ${pronounce}` }}
            </div>
        </template>
        <template #content>
            <FormKit type="form" @submit="submitHandler" v-model="form">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
                    <slot :value="form"></slot>
                </div>
                <template #submit>
                    <FormKit
                        type="submit"
                        :label="props.item?.id ? 'ویرایش' : 'ثبت'"
                    ></FormKit>
                </template>
            </FormKit>
        </template>
    </DialogModal>
</template>
