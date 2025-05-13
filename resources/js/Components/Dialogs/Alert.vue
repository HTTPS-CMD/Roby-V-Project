<script lang="ts" setup>
defineProps<{
    title: string;
    content: string;
}>();

const modal = defineModel("modal", { default: false });

const emit = defineEmits<{
    (e: "ok"): void;
}>();
</script>

<template>
    <ConfirmationModal :show="modal" @close="modal = false">
        <template #title> {{ title }} </template>
        <template #content>
            {{ content }}
        </template>
        <template #footer>
            <PrimaryButton @click="emit('ok')">تأیید</PrimaryButton>
            <SecondaryButton @click="modal = false" class="ms-2"
                >خیر</SecondaryButton
            >
        </template>
    </ConfirmationModal>
</template>
