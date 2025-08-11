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
                <li><Link href="/admin/dashboard">Home</Link></li>
                    <li><Link :href="route('user.inventory')">Inventario</Link></li>
                <li><Link :href="route('user.requests.index')">Le mie richieste</Link></li>
            </ul>
            </nav>
        </aside>
        <main class="main-content">
            <h1 class="text-2xl font-bold mb-6">Inventario</h1>
            <button @click="showAcquistoModal = true" class="mb-4 bg-green-600 text-white px-4 py-2 rounded">Richiedi articolo non presente</button>
            <!-- Modal richiesta acquisto -->
            <div v-if="showAcquistoModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                    <h2 class="text-xl font-semibold mb-4">Richiesta acquisto articolo</h2>
                    <form @submit.prevent="submitAcquisto">
                        <div class="mb-3">
                            <label class="block mb-1">Nome articolo</label>
                            <input v-model="acquistoForm.nome_articolo" required class="border rounded px-3 py-2 w-full" />
                        </div>
                        <div class="mb-3">
                            <label class="block mb-1">Categoria</label>
                            <select v-model="acquistoForm.category_id" required class="border rounded px-3 py-2 w-full">
                                <option value="">Seleziona categoria</option>
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="block mb-1">Data inizio</label>
                            <input type="date" v-model="acquistoForm.data_inizio" required class="border rounded px-3 py-2 w-full" />
                        </div>
                        <div class="mb-3">
                            <label class="block mb-1">Data fine</label>
                            <input type="date" v-model="acquistoForm.data_fine" required class="border rounded px-3 py-2 w-full" />
                        </div>
                        <div class="mb-3">
                            <label class="block mb-1">Note</label>
                            <textarea v-model="acquistoForm.note" class="border rounded px-3 py-2 w-full"></textarea>
                        </div>
                        <div class="flex justify-end gap-2">
                            <button @click="showAcquistoModal = false" type="button" class="px-4 py-2 rounded border">Annulla</button>
                            <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">Invia richiesta</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="flex flex-wrap gap-4 mb-6">
                <input v-model="filters.search" @input="applyFilters" type="text" placeholder="Cerca per nome..." class="border rounded px-3 py-2" />
                <select v-model="filters.category_id" @change="applyFilters" class="border rounded select-category">
                    <option value="">Categorie</option>
                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
            </div>
            <table class="min-w-full bg-white border rounded shadow">
                <thead>
                    <tr>
                    <th class="px-4 py-2 border-b">Nome</th>
                    <th class="px-4 py-2 border-b">Categoria</th>
                    <th class="px-4 py-2 border-b">Disponibili</th>
                    <th class="px-4 py-2 border-b">Azione</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in items" :key="item.id">
                    <td class="px-4 py-2 border-b">{{ item.name }}</td>
                    <td class="px-4 py-2 border-b">{{ item.category?.name }}</td>
                    <td class="px-4 py-2 border-b">{{ item.dettaglio_pezzi?.length || 0 }}</td>
                    <td class="px-4 py-2 border-b">
                        <Link v-if="item.dettaglio_pezzi?.length" :href="route('user.items.show', [item.id])" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Dettaglio</Link>
                        <span v-else class="text-gray-400">Non disponibile</span>
                    </td>
                    </tr>
                </tbody>
            </table>
            <!-- Modal richiesta -->
            <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                    <h2 class="text-xl font-semibold mb-4">Richiesta pezzo</h2>
                    <p class="mb-4">Vuoi richiedere <b>{{ selectedItem?.name }}</b>?</p>
                    <div class="flex justify-end gap-2">
                    <button @click="showModal = false" class="px-4 py-2 rounded border">Annulla</button>
                    <button @click="confirmRequest" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Conferma</button>
                    </div>
                </div>
            </div>
        </main>

  </div>
</template>

<script setup>
const showAcquistoModal = ref(false);
const acquistoForm = ref({
    nome_articolo: '',
    category_id: '',
    data_inizio: '',
    data_fine: '',
    note: ''
});

function submitAcquisto() {
    router.post(route('user.requests.storeAcquisto'), acquistoForm.value, {
        onSuccess: () => {
            showAcquistoModal.value = false;
            acquistoForm.value = { nome_articolo: '', category_id: '', data_inizio: '', data_fine: '', note: '' };
        }
    });
}
import { ref } from 'vue';
import { router, usePage, Link } from '@inertiajs/vue3';

const props = defineProps({
  items: Array,
  categories: Array,
  filters: Object,
});

import { useForm } from '@inertiajs/vue3';
const logoutForm = useForm({});
function logout() {
    logoutForm.post('/logout');
}

const filters = ref({
  search: props.filters?.search || '',
  category_id: props.filters?.category_id || '',
});

function applyFilters() {
  router.get(route('user.inventory'), filters.value, { preserveState: true, replace: true });
}

const showModal = ref(false);
const selectedItem = ref(null);

function requestItem(item) {
  selectedItem.value = item;
  showModal.value = true;
}

function confirmRequest() {
  // Qui andrà la chiamata POST per la richiesta
  // router.post(route('user.requests.store'), { item_id: selectedItem.value.id })
  alert('Richiesta inviata per ' + selectedItem.value.name);
  showModal.value = false;
}
</script>

<style scoped>
.container {
  max-width: 900px;
}
.select-category {
    width: 200px;
}
td,th{
    text-align: center;
}
</style>
