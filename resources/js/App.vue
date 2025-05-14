<script lang="ts" setup>
export interface IToast {
    msg?: string;
    error?: string;
}

const { props } = usePage();

watch(
    () => props.flash as IToast,
    (v) => {
        if (v.msg) {
            useToast(v.msg, { type: "success" });
        }
        if (v.error) {
            useToast(v.error, { type: "error" });
        }
    }
);
</script>

<template>
    <slot />
</template>
