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
            <h1 class="text-2xl font-bold mb-6">{{ item.name }}</h1>
            <div class="mb-4">
                <span class="font-semibold">Categoria:</span> {{ item.category?.name }}
            </div>
            <div class="mb-6">
                <span class="font-semibold">Descrizione:</span> {{ item.description }}
            </div>
            <h2 class="text-xl font-semibold mb-4">Dettaglio pezzi</h2>
            <table class="min-w-full bg-white border rounded shadow mb-8">
                <thead>
                    <tr>
                    <th class="px-4 py-2 border-b">Serial</th>
                    <th class="px-4 py-2 border-b">Stato</th>
                    <th class="px-4 py-2 border-b">Azione</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="pezzo in item.dettaglio_pezzi" :key="pezzo.id">
                    <td class="px-4 py-2 border-b">{{ pezzo.seriale }}</td>
                    <td class="px-4 py-2 border-b">
                        <span :class="statusClass(pezzo.stato)">{{ pezzo.stato }}</span>
                    </td>
                    <td class="px-4 py-2 border-b">
                        <button v-if="pezzo.stato === 'disponibile'" @click="requestDetail(pezzo)" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Richiedi</button>
                        <span v-else>Non disponibile</span>
                    </td>
                    </tr>
                </tbody>
            </table>
            <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                    <h2 class="text-xl font-semibold mb-4">Richiesta pezzo</h2>
                    <form @submit.prevent="confirmRequest">
                        <div class="mb-3">
                            <label class="block mb-1 font-semibold">Serial:</label>
                            <span>{{ selectedDetail?.serial }}</span>
                        </div>
                        <div class="mb-3">
                            <label class="block mb-1 font-semibold">Data inizio</label>
                            <input v-model="form.data_inizio" type="date" class="border rounded px-3 py-2 w-full" required />
                        </div>
                        <div class="mb-3">
                            <label class="block mb-1 font-semibold">Data fine</label>
                            <input v-model="form.data_fine" type="date" class="border rounded px-3 py-2 w-full" required />
                        </div>
                        <div class="mb-3">
                            <label class="block mb-1 font-semibold">Note aggiuntive</label>
                            <textarea v-model="form.note" class="border rounded px-3 py-2 w-full" rows="2" placeholder="Aggiungi note opzionali..."></textarea>
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Invia richiesta</button>
                            <button type="button" @click="showModal = false" class="px-4 py-2 text-red-400 border rounded">Annulla</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

</template>

<script setup>

import { ref } from 'vue';
import { router, usePage, Link } from '@inertiajs/vue3';

const props = defineProps({
  item: Object,
});

import { useForm } from '@inertiajs/vue3';
const logoutForm = useForm({});
function logout() {
    logoutForm.post('/logout');
}

const showModal = ref(false);
const selectedDetail = ref(null);
const form = ref({
    data_inizio: '',
    data_fine: '',
    note: '',
});

function requestDetail(detail) {
    selectedDetail.value = detail;
    form.value = { data_inizio: '', data_fine: '', note: '' };
    showModal.value = true;
}

function confirmRequest() {
    router.post(route('user.requests.store'), {
        item_detail_id: selectedDetail.value.id,
        data_inizio: form.value.data_inizio,
        data_fine: form.value.data_fine,
        note: form.value.note,
    }, {
        onSuccess: () => {
            showModal.value = false;
        }
    });
}

function statusClass(stato) {
  if (stato === 'disponibile') return 'text-green-600 font-bold';
  if (stato === 'in_uso') return 'text-red-600 font-bold';
  if (stato === 'in_attesa') return 'text-yellow-600 font-bold';
  return 'text-gray-500';
}
</script>

<style scoped>
td,th{
    text-align: center;
}
</style>
