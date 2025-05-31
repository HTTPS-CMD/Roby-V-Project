<script lang="ts" setup>
import axios from "axios";
import VueDataTable from "@bhplugin/vue3-datatable";
import { stringify, parse } from "qs";
import { useDatatable } from "@/Stores/datatable";

interface IColumn {
    field: string;
    title: string;
    filter?: boolean;
    sort?: boolean;
}

interface IProps {
    cols: IColumn[];
    api: string;
    includes?: string[];
    noCreatable?: boolean;
    noEditable?: boolean;
    noTimestamp?: boolean;
    noSelectable?: boolean;
    restorable?: boolean;
}

const props = defineProps<IProps>();

const emit = defineEmits<{
    (e: "showForm", value?: any): any | void;
}>();

const columns = computed(() => [
    ...props.cols,
    ...(!props.noTimestamp
        ? [
              {
                  field: "created_at",
                  title: "ایجاد",
                  filter: false,
                  sort: true,
              },
              {
                  field: "updated_at",
                  title: "بروزرسانی",
                  filter: false,
                  sort: true,
              },
          ]
        : []),
    { field: "actions", title: "عملیات", filter: false, sort: false },
]);

const params = reactive({
    current_page: 1,
    pagesize: 20,
    sort_column: "id",
    sort_direction: "asc",
    column_filters: [],
});

const baseUrl = `${props.api}/get`;

const activeUrl = ref(baseUrl);

const store = useDatatable();
const { rows: _rows } = storeToRefs(store);

const isLoading = ref(false);

const route = useUrlSearchParams();

function fetchData() {
    const paramItems = parse(route as any);
    activeUrl.value = baseUrl.concat(
        "?" +
            stringify(
                {
                    per_page: params.pagesize,
                    page: params.current_page,
                    filter: {
                        ...Object.fromEntries(
                            params.column_filters
                                .filter((item) => item.value)
                                .map(({ value, field }) => [field, value])
                        ),
                        // @ts-expect-error
                        ...(!_isEmpty(paramItems) ? paramItems.filter : {}),
                    },
                    sort: `${params.sort_direction === "asc" ? "" : "-"}${
                        params.sort_column
                    }`,
                    include: props.includes?.join(","),
                },
                { arrayFormat: "brackets", skipNulls: true, encode: false }
            )
    );

    isLoading.value = true;
    axios
        .get(activeUrl.value)
        .then(({ data: res }) => {
            _rows.value = res;
        })
        .finally(() => (isLoading.value = false));
}

const changeServer = (data: any) => {
    params.current_page = data.current_page;
    params.pagesize = data.pagesize;
    params.sort_column = data.sort_column;
    params.sort_direction = data.sort_direction;
    params.column_filters = data.column_filters;
    fetchData();
};

const datatableRef = useTemplateRef<any>("datatable");

const removeItems = (ids: number[] | number) => {
    if (!_rows.value?.data) return;

    _remove(_rows.value.data, ({ id: itemId }) =>
        _isArray(ids) ? _includes(ids, itemId || 0) : itemId === ids
    );
};

const handleApiError = (err) => {
    useToast(err.response.data.error, { type: "error" });
};

const deleteGenerate = reactive({
    id: 0,
    modal: false,
});

const selectedRows = debouncedRef([], 1000);

function onDelete() {
    axios
        .delete(
            `${props.api}/${
                deleteGenerate.id === 0
                    ? _join(_map(selectedRows.value, "id"), ",")
                    : deleteGenerate.id
            }`
        )
        .then(({ data: res }) => {
            removeItems(deleteGenerate.id);
            datatableRef.value?.clearSelectedRows();
            useToast(res?.msg, { type: "success" });
        })
        .catch(handleApiError);
    deleteGenerate.modal = false;
}

const exportTable = (type: string) => {
    let records = datatableRef.value?.getSelectedRows();
    if (!records?.length) {
        records = _rows.value?.data;
    }
    const filename = "table";

    if (type === "csv" || type === "txt") {
        // CSV or TXT
        const coldelimiter = ",";
        const linedelimiter = "\n";
        let result = props.cols
            .filter((d: any) => !d.hide)
            .map((d: any) => {
                return d.title;
            })
            .join(coldelimiter);
        result += linedelimiter;
        records.map((item: any) => {
            props.cols
                .filter((d: any) => !d.hide)
                .map((d: any, index: number) => {
                    if (index > 0) {
                        result += coldelimiter;
                    }
                    const val = d.field
                        .split(".")
                        .reduce(
                            (acc: any, part: any) => acc && acc[part],
                            item
                        );
                    result += val;
                });
            result += linedelimiter;
        });

        if (result === null) return;

        if (type === "csv") {
            var data =
                "data:application/csv;charset=utf-8," +
                encodeURIComponent(result);
            var link = document.createElement("a");
            link.setAttribute("href", data);
            link.setAttribute("download", filename + ".csv");
            link.click();
        }

        if (type === "txt") {
            var data =
                "data:application/txt;charset=utf-8," +
                encodeURIComponent(result);
            var link = document.createElement("a");
            link.setAttribute("href", data);
            link.setAttribute("download", filename + ".txt");
            link.click();
        }
    } else if (type === "print") {
        // PRINT
        let rowhtml = "<p>" + filename + "</p>";
        rowhtml +=
            '<table style="width: 100%; " cellpadding="0" cellcpacing="0"><thead><tr style="color: #515365; background: #eff5ff; -webkit-print-color-adjust: exact; print-color-adjust: exact; "> ';
        props.cols
            .filter((d: any) => !d.hide)
            .map((d: any) => {
                rowhtml += "<th>" + d.title + "</th>";
            });
        rowhtml += "</tr></thead>";
        rowhtml += "<tbody>";

        records.map((item: any) => {
            rowhtml += "<tr>";
            props.cols
                .filter((d: any) => !d.hide)
                .map((d: any) => {
                    const val = d.field
                        .split(".")
                        .reduce(
                            (acc: any, part: any) => acc && acc[part],
                            item
                        );
                    rowhtml += "<td>" + val + "</td>";
                });
            rowhtml += "</tr>";
        });
        rowhtml +=
            "<style>body {font-family:Arial; color:#495057;}p{text-align:center;font-size:18px;font-weight:bold;margin:15px;}table{ border-collapse: collapse; border-spacing: 0; }th,td{font-size:12px;text-align:left;padding: 4px;}th{padding:8px 4px;}tr:nth-child(2n-1){background:#f7f7f7; }</style>";
        rowhtml += "</tbody></table>";
        const winPrint: any = window.open(
            "",
            "",
            "left=0,top=0,width=1000,height=600,toolbar=0,scrollbars=0,status=0"
        );
        winPrint.document.write("<title>" + filename + "</title>" + rowhtml);
        winPrint.document.close();
        winPrint.focus();
        winPrint.onafterprint = () => {
            winPrint.close();
        };
        winPrint.print();
    }
};

function onRestore(id: number | number[]) {
    axios
        .get(`${props.api}/restore/${id.toString()}`)
        .then((res: any) => {
            removeItems(id);
            useToast(res?.msg, { type: "success" });
        })
        .catch(handleApiError);
}

onMounted(() => fetchData());

defineExpose({ data: _rows });
</script>

<template>
    <div class="container m-auto py-8">
        <div class="mb-5 flex items-center gap-2">
            <div class="grow">
                <PrimaryButton @click="emit('showForm')" v-if="!noCreatable">
                    <ph-plus-circle-duotone class="size-5" />
                    جدید
                </PrimaryButton>
                <DangerButton
                    class="ms-2"
                    v-if="!noSelectable"
                    :disabled="!selectedRows.length"
                    @click="
                        () => {
                            deleteGenerate.id = 0;
                            deleteGenerate.modal = true;
                        }
                    "
                >
                    <ph-trash class="size-5" />
                    {{ `حذف (${selectedRows.length})` }}
                </DangerButton>
                <SecondaryButton
                    class="ms-2"
                    v-if="restorable"
                    :disabled="!selectedRows.length"
                    @click="onRestore(_map(selectedRows, 'id'))"
                >
                    <ph-arrow-clockwise class="size-5" />
                    {{ `بازگردانی (${selectedRows.length})` }}
                </SecondaryButton>
            </div>
            <SecondaryButton
                type="button"
                class="btn btn-small"
                @click="exportTable('csv')"
            >
                <ph-microsoft-excel-logo class="size-5" />
                CSV
            </SecondaryButton>
            <SecondaryButton
                type="button"
                class="btn btn-small"
                @click="exportTable('txt')"
            >
                <ph-file-txt class="size-5" />
                TXT
            </SecondaryButton>
            <SecondaryButton
                type="button"
                class="btn btn-small"
                @click="exportTable('print')"
            >
                <ph-printer class="size-5" />
                PRINT
            </SecondaryButton>
        </div>
        <VueDataTable
            ref="datatable"
            :rows="_rows?.data || []"
            :columns="columns"
            :loading="isLoading"
            :pageSize="params.pagesize"
            :totalRows="_rows?.total"
            sortable
            isServerMode
            columnFilter
            stickyHeader
            :hasCheckbox="!noSelectable"
            :sortColumn="params.sort_column"
            :sortDirection="params.sort_direction"
            @change="changeServer"
            @rowSelect="selectedRows = $event"
        >
            <template v-for="(_, slotName) in $slots" #[slotName]="slotProps">
                <slot
                    :name="slotName"
                    v-bind="slotProps"
                    v-if="
                        !_includes(
                            [
                                'actions',
                                ...(!noTimestamp
                                    ? ['created_at', 'updated_at']
                                    : []),
                            ],
                            slotName
                        )
                    "
                />
            </template>
            <template #created_at="{ value }" v-if="!noTimestamp">
                {{ $d(value.created_at, "datetime") }}
            </template>
            <template #updated_at="{ value }" v-if="!noTimestamp">
                {{ $d(value.updated_at, "datetime") }}
            </template>
            <template #actions="{ value }">
                <div class="flex items-center gap-x-2 justify-evenly">
                    <SecondaryButton
                        @click="onRestore(value.id)"
                        v-if="restorable && value.deleted_at"
                    >
                        <ph-arrow-clockwise />
                    </SecondaryButton>
                    <SecondaryButton
                        @click="emit('showForm', value)"
                        v-if="!noEditable"
                    >
                        <ph-pencil-line />
                    </SecondaryButton>
                    <DangerButton
                        @click="
                            () => {
                                deleteGenerate.id = value.id;
                                deleteGenerate.modal = true;
                            }
                        "
                    >
                        <ph-trash />
                    </DangerButton>
                </div>
            </template>
        </VueDataTable>
        <DialogsAlert
            title="حذف آیتم"
            :content="`آیا مایل به حذف آیتم${
                deleteGenerate.id === 0 ? 'ها' : ''
            } انتخابی هستید؟`"
            v-model:modal="deleteGenerate.modal"
            @ok="onDelete"
        />
    </div>
</template>

<style>
tbody tr td {
    @apply !text-right !text-white;
}

thead th,
.bh-bg-blue-light {
    @apply bg-slate-800 text-white;
}

.bh-table-responsive table th .bh-filter > .bh-form-control,
.bh-table-responsive table th .bh-filter > button {
    @apply !border !border-gray-700;
}

input.bh-form-control,
.bh-pagesize,
.bh-pagesize option {
    @apply !bg-slate-700 !text-white border-slate-600;
}

thead th .bh-filter > button {
    @apply !bg-slate-500;
}

.bh-table-responsive table.bh-table-hover tbody tr:hover {
    @apply !bg-slate-700;
}

.bh-table-responsive table th .bh-filter > button svg,
.bh-pagination,
.bh-pagination .bh-page-item {
    @apply !text-white;
}

.bh-pagination {
    direction: ltr !important;
}

.bh-table-responsive table th .bh-filter {
    @apply rtl:flex-row-reverse;
}
</style>
