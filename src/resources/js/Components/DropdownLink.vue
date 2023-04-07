<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <Link
        class="btn btn-link block w-full px-3 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
    >
        <slot />
    </Link>
</template>
