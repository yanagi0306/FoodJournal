<script setup>
import Header from "@/Layouts/Header.vue";
import Footer from "@/Layouts/Footer.vue";
import GlobalNav from "@/Layouts/GlobalNav.vue";

</script>

<template>
    <header class="ly_header">
        <Header/>
        <GlobalNav/>
    </header>

    <div class="ly_cont">
        <div class="ly_cont_inner">
            <main class="ly_cont_main ly_custom_ml" data-contents_ml="true">
                <div class="ly_cont_main_inner" data-contents_ml="true">
                    <slot/>
                </div>
            </main>
        </div>
    </div>

    <footer class="container-fluid fixed-bottom py-2 bg-light" data-contents_ml="true">
            <Footer/>
    </footer>

</template>
