<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import {defineProps, onMounted, ref} from "vue";
import SvgIcon from "@/Components/Icons/SvgIcon.vue";
import {Inertia} from "@inertiajs/inertia";
import OrderRow from "@/Components/OrderRow.vue";
import FlashMessage from "@/Components/FlashMessage.vue";

const props = defineProps({
    ordersHistory: Array,
});

const ordersHistory = ref([]);
const selectedFile = ref(null)
const fileInput = ref(null)

onMounted(() => {
    props.ordersHistory.forEach((file, index) => {
        ordersHistory.value.push({
            id: index + 1,
            path: file.path,
            name: file.name,
            updated_at: file.updated_at,
        });
    });
});

const downloadOrderCsv = (path) => {
    window.open('/orders/download?filename=' + encodeURIComponent(path), '_blank');
}

const onFileSelected = (event) => {
    selectedFile.value = event.target.files[0]
}

const uploadFile = () => {
    if (!selectedFile.value) {
        alert('ファイルを選択してください。');
        return;
    }

    const formData = new FormData();
    formData.append('file', selectedFile.value);

    Inertia.post(route('orders.upload'), formData);
};


</script>

<template>
    <Head title="Order"/>
    <AuthenticatedLayout>
        <FlashMessage/>
        <div class="main_title_wrapper my-3">
            <h1 class="page-h1-title">売上実績UL</h1>
        </div>

        <h3 class="sub_title mt-3">ファイルUL</h3>

        <div class="row">
            <div class="col-3">
                <input
                    ref="fileInput"
                    type="file"
                    class="form-control"
                    name="fba_csv"
                    @change="onFileSelected"
                />
            </div>
            <div class="col-auto">
                <button
                    class="btn btn-primary btn_w-165px d-flex align-items-center justify-content-center"
                    type="button"
                    @click="uploadFile"
                >
                    <SvgIcon icon="svgFileUploadSolid" class="svg_icon_wh16"/>
                    <span class="ms-2">売上実績UL</span>
                </button>
            </div>
        </div>

        <h3 class="sub_title">アップロード履歴</h3>
        <div class="fs12 mb-3">
            最新のアップロード履歴10件を表示します。<br>
        </div>
        <!--アップロード履歴ある時はこちらを表示-->
        <div v-if="ordersHistory.length > 0">
            <table id="standard" class="table_standard_box_2 table-70">
                <thead>
                <tr>
                    <th>NO</th>
                    <th>登録日時</th>
                    <th>登録ファイル名</th>
                    <th>ダウンロード</th>
                </tr>
                </thead>
                <tbody>
                <OrderRow v-for='order in ordersHistory' :key="order.id" :order="order" @download="downloadOrderCsv"/>
                </tbody>
            </table>
        </div>
        <div v-else>
            <p class="alert alert_not_data mt-3 d-flex">
                <SvgIcon icon="circleInfoSolid" class="svg_icon_wh16 me-1"/>
                <span class="ms-2">該当するデータが存在しません。</span>
            </p>
        </div>

    </AuthenticatedLayout>
</template>
