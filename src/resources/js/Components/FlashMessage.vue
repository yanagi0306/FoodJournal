<script setup>
</script>

<template>
    <div v-if="$page.props.flash.status === 'success'" class="bg-blue-300 text-white p-4" v-html="$page.props.flash.message"></div>
    <div v-if="$page.props.flash.status === 'error'" class="bg-red-300 text-white p-4" v-html="$page.props.flash.message"></div>
</template>
