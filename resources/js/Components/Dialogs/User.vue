<script lang="ts" setup>
const props = defineProps<{ item?: models.User }>();

const modal = defineModel("modal", { default: false });
</script>

<template>
    <DialogModal :show="modal" @close="modal = false"></DialogModal>
</template>
