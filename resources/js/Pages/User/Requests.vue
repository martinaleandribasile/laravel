<template>
    <div class="admin-dashboard">
        <nav class="navbar">
            <div class="logo">
            <span class="logo-w">W</span><span class="logo-e">ea</span><span class="logo-r">r</span><span class="logo-h">h</span><span class="logo-o">ou</span><span class="logo-s">s</span><span class="logo-e2">e</span>
            </div>
            <div class="profile-menu">
            <div class="dropdown">
                <button class="profile-btn">Profilo ▾</button>
                <div class="dropdown-content">
                    <button @click="logout" class="logout-btn">Logout</button>
                </div>
            </div>
            </div>
        </nav>
        <aside class="sidebar">
            <h2>{{ userName }}</h2>
            <nav>
                <ul>
                    <li><Link :href="route('user.dashboard')">Home</Link></li>
                    <li><Link :href="route('user.inventory')">Inventario</Link></li>
                    <li><Link :href="route('user.requests.index')">Le mie richieste</Link></li>
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            <h1 class="text-2xl font-bold mb-6">Le mie richieste</h1>
            <table class="min-w-full bg-white border rounded shadow">
            <thead>
                <tr>
                <th class="px-4 py-2 border-b">Serial</th>
                <th class="px-4 py-2 border-b">Articolo</th>
                <th class="px-4 py-2 border-b">Periodo</th>
                <th class="px-4 py-2 border-b">Note</th>
                <th class="px-4 py-2 border-b">Stato</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="req in requests" :key="req.id">
                <td class="px-4 py-2 border-b">{{ req.item_detail?.seriale }}</td>
                <td class="px-4 py-2 border-b">{{ req.item_detail?.item?.name }}</td>
                <td class="px-4 py-2 border-b">{{ req.data_inizio }} - {{ req.data_fine }}</td>
                <td class="px-4 py-2 border-b">{{ req.note }}</td>
                <td class="px-4 py-2 border-b">
                    <span :class="statusClass(req.stato)">{{ req.stato }}</span>
                </td>
                </tr>
            </tbody>
            </table>
        </main>
    </div>

</template>

<script setup>

    import { ref } from 'vue';
    import { router, usePage, Link } from '@inertiajs/vue3';

    const props = defineProps({
        requests: Array,
    });

    import { useForm } from '@inertiajs/vue3';
    const logoutForm = useForm({});
    function logout() {
        logoutForm.post('/logout');
    }

    function statusClass(stato) {
        if (stato === 'confermata') return 'text-green-600 font-bold';
        if (stato === 'annullata') return 'text-red-600 font-bold';
        if (stato === 'in_attesa') return 'text-yellow-600 font-bold';
        return 'text-gray-500';
    }
</script>

<style scoped>
.container {
  max-width: 900px;
}
td,th{
    text-align: center;
}
</style>
