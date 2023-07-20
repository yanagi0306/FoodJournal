<script setup>
import SvgIcon from "@/Components/Icons/SvgIcon.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";

const menus = [
    {
        title: {name: "実績管理", icon: 'svgListAltRegular'},
        links: [
            {name: "売上実績", path: 'orders.index'},
            {name: "仕入実績", path: 'purchases.index'},
            {name: "月次収入", path: 'top.index'},
            {name: "月次支出", path: 'top.index'},
            {name: "日次収入", path: 'top.index'},
            {name: "日次支出", path: 'top.index'}
        ],
    },
    {
        title: {name: "予算管理", icon: 'svgFaBook'},
        links: [
            {name: "収入予算", path: 'top.index'},
            {name: "支出予算", path: 'expense_budgets.index'},
            {name: "収入予算一括登録", path: 'top.index'},
            {name: "支出予算一括登録", path: 'top.index'}
        ],
    },
    {
        title: {name: "帳簿管理", icon: 'svgChartBarRegular'},
        links: [
            {name: "月次情報", path: 'top.index'},
            {name: "日次情報", path: 'top.index'}
        ],
    },
    {
        title: {name: "商品マスタ管理", icon: 'svgProductSolid'},
        links: [
            {name: "商品マスタ登録", path: 'top.index'},
            {name: "商品マスタカテゴリ登録", path: 'top.index'}
        ],
    },
    {
        title: {name: "カテゴリ管理", icon: 'toolboxSolid'},
        links: [
            {name: "収入カテゴリ", path: 'top.index'},
            {name: "支出カテゴリ", path: 'top.index'}
        ],
    },
    {
        title: {name: "ユーザー管理", icon: 'svgUserCogSolid'},
        links: [
            {name: "ユーザー管理", path: 'top.index'}
        ],
    },
];

</script>
<template>
    <div class="ml-2 -mr-0.5 h-2 w-2"></div>

    <div class="navbar navbar-expand navbar-light bg-white border-bottom shadow-sm p-0">
        <div class="container-fluid nav">
            <div class="navbar-collapse nav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 d-flex flex-nowrap align-items-center ">
                    <li class="nav-item dropdown mx-1" v-for="menu in menus" :key="menu.title.name">
                        <Dropdown align="left" width="48" class="nav-link text-dark">
                            <template #trigger>
                                <span class="d-flex align-items-center inline-flex rounded-md">
                                    <a class="d-flex align-items-center link-dark text-decoration-none">
                                        <SvgIcon :icon="menu.title.icon" class="svg_icon_wh24 mr-2"></SvgIcon>
                                        {{ menu.title.name }}
                                        <SvgIcon icon="svgDrop" class="svg_icon_wh16 mr-2"/>
                                    </a>
                                </span>
                            </template>
                            <template #content class="dropdown-menu">
                                <DropdownLink
                                    v-for="link in menu.links"
                                    :key="link.name"
                                    :href="route(link.path)"
                                    method="get"
                                    as="button"
                                    class="dropdown-item text-muted"
                                    aria-labelledby="navbarDropdown"
                                >
                                    {{ link.name }}
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<style scoped>
.navbar-nav {
    flex-wrap: nowrap;
}

.nav-item {
    white-space: nowrap;
}
</style>




