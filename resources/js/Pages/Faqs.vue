<script lang="ts" setup>
import axios from "axios";
import draggable from "vuedraggable";

const faq = reactive({
    item: undefined,
    modal: false,
});

const faqs = ref<models.Faq[]>([]);
const { canRedo, canUndo, clear, undo, redo, history } = useRefHistory(faqs);

onBeforeMount(() => {
    axios.get(route("faqs.getIndex")).then(({ data }) => {
        faqs.value = data;
        clear();
    });
});

const save = () => {
    axios.put(route("faqs.sort"), faqs.value).then(() => {
        clear();
    });
};
</script>

<template>
    <AppLayout>
        <div class="flex items-center gap-x-2">
            <SecondaryButton :disabled="!canUndo" @click="undo">
                <ph-arrow-arc-right />
            </SecondaryButton>
            <SecondaryButton :disabled="!canRedo" @click="redo">
                <ph-arrow-arc-left />
            </SecondaryButton>
            <div class="grow">
                <PrimaryButton @click="save" :disabled="!history.length">
                    <ph-check-bold />
                    "ذخیره"
                </PrimaryButton>
            </div>
        </div>
        <draggable class="flex flex-col gap-y-2" v-model="faqs">
            <TransitionGroup>
                <div
                    v-for="(item, index) in faqs"
                    :key="item.id"
                    class="rounded-xl bg-slate-700 text-white p-2 flex items-center justify-between"
                >
                    <div class="flex items-center gap-x-2">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                        >
                            <path
                                fill="none"
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M5.7 9.3L3 12m0 0l2.7 2.7M3 12h18M9.3 5.7L12 3m0 0l2.7 2.7M12 3v18m2.7-2.7L12 21m0 0l-2.7-2.7m9-9L21 12m0 0l-2.7 2.7"
                            />
                        </svg>
                        {{ `${index + 1}. ${item.title}` }}
                    </div>
                    <div class="flex items-center gap-x-2">
                        <PrimaryButton
                            @click="
                                () => {
                                    faq.item = _omit(item, [
                                        'create_at',
                                        'update_at',
                                    ]);
                                    faq.modal = true;
                                }
                            "
                        >
                            <ph-pencil-simple />
                        </PrimaryButton>
                        <DangerButton>
                            <ph-trash />
                        </DangerButton>
                    </div>
                </div>
            </TransitionGroup>
        </draggable>
        <DialogsFaq v-model:modal="faq.modal" :item="faq.item" />
    </AppLayout>
</template>
