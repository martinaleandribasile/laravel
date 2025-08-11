<script setup>
import { Head, usePage, router } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const page = usePage();
onMounted(() => {
    const user = page.props.auth?.user;
    if (!user) {
        router.visit('/login');
        return;
    }
    if (user.role === 'admin') {
        router.visit('/admin/dashboard');
    } else {
        router.visit('/user/dashboard');
    }
});
</script>

<template></template>
