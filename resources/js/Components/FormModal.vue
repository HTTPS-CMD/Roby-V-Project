<script lang="ts" setup>
import type { IFlash } from "@/App.vue";
import { useDatatable } from "@/Stores/datatable";

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

const store = useDatatable();

const { rows: _rows } = storeToRefs(store);

function submitHandler(fields, node) {
    const f = useForm(form.value);
    f[!props.item?.id ? "post" : "patch"](
        route(
            props.routeName.concat(!props.item?.id ? ".store" : ".update"),
            props.item?.id ? { id: props.item.id } : undefined
        ),
        {
            onSuccess({ props }: { props: any }) {
                console.log(props);

                if (props.flash.msg) modal.value = false;
                if (props.flash.item) {
                    if (
                        props.flash.item.created_at ===
                        props.flash.item.updated_at
                    ) {
                        _rows.value?.data.unshift(props.flash.item);
                    } else {
                        let item = _rows.value?.data.find(
                            ({ id }) => id === props.flash.item.id
                        );
                        item = props.flash.item;
                    }
                }
            },
        }
    )(fields, node);
}

watch(modal, (v) => {
    form.value = toRaw(props.item);
    if (!v) form.value = {};
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
