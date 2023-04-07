<script setup>
import {ref} from 'vue';
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import SvgIcon from "@/Components/Icons/SvgIcon.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
</script>

<template>
    <div class="header_inner navbar-light border-bottom p-1 h-px-450">

        <!--header_top -->
        <div class="header_inner navbar-light border-bottom p-1">
            <div class="header_inner_left">
                <div class="d-inline-flex bd-highlight">
                    <div class="app_logo p-1 mx-3 bd-highlight">
                        <a class="navbar-brand" href="{{'/'}}">
                            <img src="/images/food_journal_logo.png" class="navbar-brand-img" alt="logo" width="175" height="25">
                        </a>
                    </div>
                </div>
            </div>

            <!-- 所属会社名と所属店舗名の表示 -->
            <div class="company_store_info mx-3 d-flex align-items-center">
                <span>{{ $page.props.auth.user.company_name }} / {{ $page.props.auth.user.store_name }}</span>
            </div>

            <div class="hidden sm:flex sm:items-center">
                <!-- Settings Dropdown -->
                <div class="user_logo px-3 relative">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                        <span class="d-flex align-items-center inline-flex rounded-md">
                            <a class="d-flex align-items-center link-dark text-decoration-none">
                                <SvgIcon icon="svgUserRegular" class="svg_icon_wh24 mr-2"/>
                                {{ $page.props.auth.user.name }} 様
                                <SvgIcon icon="svgDrop" class="svg_icon_wh16 mr-2"/>
                            </a>

                        </span>
                        </template>
                        <template #content>
                            <DropdownLink :href="route('logout')" method="post" as="button" class="dropdown-item text-muted" aria-labelledby="navbarDropdown">
                                Log Out
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </div>
        </div>
    </div>

</template>

<style scoped>
.app_logo {
    min-width: 183px;
}

.user_logo {
    min-width: 141px;
}

.company_store_info {
    font-size: 0.75rem;
    color: #2E3E56;
}
</style>

