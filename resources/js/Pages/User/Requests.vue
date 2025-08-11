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
            <div class="mb-4">
                <button :class="['px-4 py-2 rounded-l', filtro === 'normali' ? 'bg-blue-600 text-white' : 'bg-gray-200']" @click="filtro = 'normali'">Richieste inventario</button>
                <button :class="['px-4 py-2 rounded-r', filtro === 'acquisto' ? 'bg-blue-600 text-white' : 'bg-gray-200']" @click="filtro = 'acquisto'">Richieste acquisto</button>
            </div>
            <table class="min-w-full bg-white border rounded shadow">
            <thead>
                <tr>
                <th class="px-4 py-2 border-b">Tipo</th>
                <th class="px-4 py-2 border-b">Articolo / Nome</th>
                <th class="px-4 py-2 border-b">Serial</th>
                <th class="px-4 py-2 border-b">Periodo</th>
                <th class="px-4 py-2 border-b">Note</th>
                <th class="px-4 py-2 border-b">Stato</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="req in filteredRequests" :key="req.id">
                    <td class="px-4 py-2 border-b">{{ req.tipo === 'acquisto' ? 'Acquisto' : 'Inventario' }}</td>
                    <td class="px-4 py-2 border-b">
                        <span v-if="req.tipo === 'acquisto'">{{ req.nome_articolo }}</span>
                        <span v-else>{{ req.item_detail?.item?.name }}</span>
                    </td>
                    <td class="px-4 py-2 border-b">{{ req.tipo === 'acquisto' ? '-' : req.item_detail?.seriale }}</td>
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

import { ref, computed } from 'vue';
import { router, usePage, Link, useForm } from '@inertiajs/vue3';

// Stato per il filtro richieste (normali/acquisto)
const filtro = ref('normali');

// Props: lista delle richieste dell'utente
const props = defineProps({
    requests: Array,
});

// Filtra le richieste in base al tipo selezionato
const filteredRequests = computed(() => {
    if (filtro.value === 'acquisto') {
        return props.requests.filter(r => r.tipo === 'acquisto');
    }
    return props.requests.filter(r => r.tipo !== 'acquisto');
});

// Inertia: useForm gestisce lo stato del form di logout
const logoutForm = useForm({});

/**
 * Esegue il logout dell'utente tramite una richiesta POST usando Inertia.js
 */
function logout() {
    logoutForm.post('/logout');
}

/**
 * Restituisce la classe CSS per lo stato della richiesta
 * @param {String} stato - Stato della richiesta
 */
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
