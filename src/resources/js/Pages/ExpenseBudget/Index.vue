<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import {defineProps, onMounted, ref} from "vue";
import SvgIcon from "@/Components/Icons/SvgIcon.vue";
import {Inertia} from "@inertiajs/inertia";
import TableRow from "@/Components/TableRow.vue";
import FlashMessage from "@/Components/FlashMessage.vue";

const props = defineProps({
    stores: Array,
    expenseCategories: Array,
});

const stores = ref([]);
const expenseCategories = ref([]);

onMounted(() => {
    props.stores.forEach((store, index) => {
        store.value.push({
            no: index + 1,
            id: store.id,
            name: store.name,
        });
    });

    props.expenseCategories.forEach((category, index) => {
        category.value.push({
            no: index + 1,
            id: category.id,


        });
    });
})

</script>
