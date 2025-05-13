<script lang="ts" setup>
import { useFormKitContextById } from "@formkit/vue";

const props = withDefaults(
    defineProps<{
        item: any;
        routeName: string;
        pronounce?: string;
    }>(),
    { pronounce: "" }
);

const modal = defineModel("modal", { default: false });

const form = useForm(props.item);

function submitHandler(fields, node) {
    form[!props.item?.id ? "post" : "patch"](
        route(
            props.routeName.concat(!props.item?.id ? ".store" : ".update"),
            props.item?.id ? { id: props.item.id } : undefined
        )
    )(fields, node);
}

const id = useId();
const formContext = useFormKitContextById(id);

watch(modal, () => {
    if (formContext.value) {
        formContext.value.value = toRaw(props.item);
        formContext.value.node.input(toRaw(props.item));
    }
    //     form.node.value?.input(toRaw(props.item));
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
            <FormKit
                :id="id"
                type="form"
                @submit="submitHandler"
                :plugins="[form.plugin]"
            >
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
                    <slot :value="formContext?.value"></slot>
                </div>
                <template #submit>
                    <FormKit
                        type="submit"
                        :label="props.item?.id ? 'ویرایش' : 'ثبت'"
                        :disabled="form.processing.value"
                    ></FormKit>
                </template>
            </FormKit>
        </template>
    </DialogModal>
</template>
