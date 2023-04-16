<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link} from '@inertiajs/vue3';
import {onMounted, ref} from "vue";
import SvgIcon from "@/Components/Icons/SvgIcon.vue";

const props = defineProps({
    'uploadHistory': Array,
})

const filesList = ref([])

onMounted(() => {
    props.uploadHistory.forEach((file, index) => {
        const filePathParts = file.name.split('/');
        const directory = filePathParts.slice(0, -1).join('/');
        const fileName = filePathParts.slice(-1)[0];

        filesList.value.push({
            id: index + 1,
            directory: directory,
            name: fileName,
            created_at: file.created_at,
        })
    })
})


const updateFile = (e) => {
    // 日本語変換時のenter
    if (e.keyCode === 229) return
    Inertia.get(route('customers.index', {search: search.value}))
    document.getElementById('inputSearch').focus();
}

</script>

<template>
    <Head title="Order"/>
    <AuthenticatedLayout>
        <div class="main_title_wrapper my-3">
            <h1 class="page-h1-title">売上実績UL</h1>
        </div>

        <h3 class="sub_title mt-3">ファイルUL</h3>

        <div class="row">
            <div class="col-3">
                <input
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
            最新のアップロード履歴5件を表示します。<br>
        </div>
        <!--            アップロード履歴ある時はこちらを表示-->
        <div v-if="filesList.length > 0">
            <table id="standard" class="table_standard_box_2 table-70">
                <thead>
                <tr>
                    <th width="10%">NO</th>
                    <th width="25%">登録日時</th>
                    <th width="35%">登録ファイル名</th>
                    <th width="30%">ダウンロード</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for='file in filesList' :key="file.id">
                    <td class="text-center">{{ file.index }}</td>
                    <td>{{ file.created_at }}</td>
                    <td>{{ file.name }}</td>
                    <td class="text-center">
                        <input type="button" class="btn_small-blue-3" @click="downloadOrderCsv()" value="ダウンロード">
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div v-else>
            <p class="alert alert_not_data mt-3 d-flex">
                <SvgIcon icon="circleInfoSolid" class="svg_icon_wh16 me-1"/>
                <span class="ms-2">該当するデータが存在しません。</span>
            </p>
            <input type="hidden" name="fbaShipResultFile" value="">
        </div>

    </AuthenticatedLayout>
</template>
