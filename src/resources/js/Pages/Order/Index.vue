<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, Link} from '@inertiajs/vue3';
import {onMounted, ref} from "vue";
import SvgIcon from "@/Components/Icons/SvgIcon.vue";
import {Inertia} from "@inertiajs/inertia";


const props = defineProps({
    'ordersHistory': Array,
})

const ordersHistory = ref([])

onMounted(() => {
    props.ordersHistory.forEach((file, index) => {
        const filePathParts = file.name.split('/');
        const directory = filePathParts.slice(0, -1).join('/');
        const fileName = filePathParts.slice(-1)[0];

        ordersHistory.value.push({
            id: index + 1,
            directory: directory,
            name: fileName,
            created_at: file.created_at,
        })
    })
})

const selectedFile = ref(null)

const onFileSelected = (event) => {
    selectedFile.value = event.target.files[0]
}

const uploadFile = async () => {
    if (!selectedFile.value) {
        alert('ファイルを選択してください。')
        return
    }

    const formData = new FormData()
    formData.append('file', selectedFile.value)

    try {
        await Inertia.post(route('orders.upload'), formData, {
            onSuccess: () => {
                alert('ファイルが正常にアップロードされました。')
                location.reload();
            },
            onError: (errors) => {
                console.error(errors)
                alert(errors.message || 'ファイルのアップロード中にエラーが発生しました。')
            }
        })
    } catch (error) {
        console.error(error)
        alert('ファイルのアップロード中にエラーが発生しました。')
    }
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
            最新のアップロード履歴10件を表示します。<br>
        </div>
        <!--            アップロード履歴ある時はこちらを表示-->
        <div v-if="ordersHistory.length > 0">
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
                <tr v-for='order in ordersHistory' :key="file.id">
                    <td class="text-center">{{ order.index }}</td>
                    <td>{{ order.created_at }}</td>
                    <td>{{ order.name }}</td>
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
