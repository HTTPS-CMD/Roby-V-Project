<script lang="ts" setup>
import axios from "axios";
import { stringify } from "qs";

const props = withDefaults(
    defineProps<{
        routeName: string;
        searchKey: string;
        itemKey?: string;
        itemValue?: string;
        filter?: any;
    }>(),
    { itemKey: "label", itemValue: "value" }
);

const value = defineModel({ default: null });

const search = ref();

const data = ref<TPageProps>();

const page = ref(1);

const loading = ref(false);
const endData = ref(false);

watch(
    data,
    (v) => {
        if (v?.data.length) value.value = v.data[0][props.itemValue];
    },
    { once: true }
);

async function fetchData() {
    loading.value = true;
    await axios
        .get(
            route(props.routeName + ".getIndex").concat(
                "?" +
                    stringify(
                        {
                            filter: {
                                [props.searchKey]: search.value,
                                ...props.filter,
                            },
                            page: page.value,
                        },
                        {
                            arrayFormat: "brackets",
                            skipNulls: true,
                            encode: false,
                        }
                    )
            )
        )
        .then(({ data: res }) => {
            data.value = res;
            if (data.value?.current_page === data.value?.last_page) {
                endData.value = true;
            }
        })
        .finally(() => (loading.value = false));
}

const selectEl = ref();

// const { reset } = useInfiniteScroll(
//     selectEl,
//     () => {
//         page.value++;
//         fetchData();
//     },
//     {
//         distance: 20,
//         canLoadMore: () => !loading.value && !endData.value,
//     }
// );

watchDebounced(
    search,
    () => {
        page.value = 1;
        endData.value = false;
        fetchData();
        // reset();
    },
    { debounce: 1000, maxWait: 2000, immediate: true }
);
</script>

<template>
    <FormKit
        ref="selectEl"
        type="select"
        v-bind="_omit($attrs, _keys($props).concat(['options', 'type']))"
        :options="
            data?.data.map((item) => ({
                label: item[itemKey],
                value: item[itemValue],
            })) || []
        "
        v-model="value"
    ></FormKit>
</template>
