<script lang="ts" setup>
export interface IFlash {
    msg?: string;
    error?: string;
    item?: any;
}

const { props } = usePage();

watch(
    () => props.flash as IFlash,
    (v) => {
        if (v.msg) {
            useToast(v.msg, { type: "success" });
        }
        if (v.error) {
            useToast(v.error, { type: "error" });
        }
    }
);

useFavicon("./logo.png");
</script>

<template>
    <slot />
</template>
