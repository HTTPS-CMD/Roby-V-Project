<script lang="ts" setup generic="T">
const props = defineProps<{ item: T; routeName: string }>();

const modal = defineModel("modal", { default: false });

const form = useForm(props.item || {});

function submitHandler(fields, node) {
    form[!_has(props.item, ["id"]) ? "post" : "patch"](
        route(
            props.routeName.concat(
                _has(props.item, ["id"]),
                ".store",
                ".update"
            ),
            _has(props.item, ["id"]) ? { id: props.item.id } : {}
        ),
        {
            onSuccess(res) {
                console.log(res);
            },
        }
    )(fields, node);
}
</script>

<template>
    <DialogModal :show="modal" @close="modal = false">
        <template #content>
            <FormKit
                type="form"
                @submit="submitHandler"
                :plugins="[form.plugin]"
            >
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
                    <slot></slot>
                </div>
                <template #submit>
                    <FormKit
                        type="submit"
                        :label="_has(props.item, ['id']) ? 'ویرایش' : 'ثبت'"
                        :disabled="form.processing.value"
                    ></FormKit>
                </template>
            </FormKit>
        </template>
    </DialogModal>
</template>
