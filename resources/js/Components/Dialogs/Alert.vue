<script lang="ts" setup>
const props = defineProps<{
    title: string;
    content: string;
    type: "info" | "danger" | "warning" | "success";
}>();

const modal = defineModel("modal", { default: false });

const emit = defineEmits<{
    (e: "ok"): void;
}>();

const icon = computed(
    () =>
        ({
            info: "ph-info-duotone",
            danger: "ph-warning-circle-duotone",
            warning: "ph-shield-warning-duotone",
            success: "ph-check-circle-duotone",
        }[props.type])
);
</script>

<template>
    <DialogModal :show="modal" @close="modal = false">
        <template #title> {{ title }} </template>
        <template #content>
            <div class="flex flex-col items-center gap-y-3">
                <component :is="icon" :class="[type, 'size-8']" />
                <div class="text-sm">{{ content }}</div>
            </div>
        </template>
        <template #footer>
            <PrimaryButton @click="emit('ok')">تأیید</PrimaryButton>
            <SecondaryButton @click="modal = false" class="ms-2"
                >خیر</SecondaryButton
            >
        </template>
    </DialogModal>
</template>

<style scoped>
.info {
    @apply text-blue-500;
}
.danger {
    @apply text-red-500;
}
.warning {
    @apply text-amber-500;
}
.success {
    @apply text-green-500;
}
</style>
