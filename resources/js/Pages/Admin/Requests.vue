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
                <li><Link href="/admin/items">Inventario</Link></li>
                <li><Link :href="route('admin.requests.index')">Richieste</Link></li>
                <li><Link href="/admin/statistiche">Statistiche</Link></li>
            </ul>
            </nav>
        </aside>
        <main class="main-content">
            <div class="mb-4 flex gap-4">
                <select v-model="selectedStato" @change="filter" class="border rounded px-3 py-2">
                    <option value="">Stati</option>
                    <option value="in_attesa">In attesa</option>
                    <option value="confermata">Confermata</option>
                    <option value="annullata">Annullata</option>
                </select>
                <button :class="['px-4 py-2 rounded-l', filtro === 'normali' ? 'bg-blue-600 text-white' : 'bg-gray-200']" @click="filtro = 'normali'">Richieste inventario</button>
                <button :class="['px-4 py-2 rounded-r', filtro === 'acquisto' ? 'bg-blue-600 text-white' : 'bg-gray-200']" @click="filtro = 'acquisto'">Richieste acquisto</button>
            </div>

            <table class="min-w-full bg-white border rounded shadow">
                <thead>
                    <tr v-if="filtro === 'normali'">
                        <th class="px-4 py-2 border-b">Utente</th>
                        <th class="px-4 py-2 border-b">Articolo</th>
                        <th class="px-4 py-2 border-b">Serial</th>
                        <th class="px-4 py-2 border-b">Periodo</th>
                        <th class="px-4 py-2 border-b">Note</th>
                        <th class="px-4 py-2 border-b">Stato</th>
                        <th class="px-4 py-2 border-b">Azioni</th>
                    </tr>
                    <tr v-else>
                        <th class="px-4 py-2 border-b">Utente</th>
                        <th class="px-4 py-2 border-b">Nome articolo</th>
                        <th class="px-4 py-2 border-b">Note</th>
                        <th class="px-4 py-2 border-b">Stato</th>
                        <th class="px-4 py-2 border-b">Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-if="filtro === 'normali'">
                        <tr v-for="req in requests_inventario" :key="req.id">
                            <td class="px-4 py-2 border-b">{{ req.user?.name }}</td>
                            <td class="px-4 py-2 border-b">{{ req.item_detail?.item?.name }}</td>
                            <td class="px-4 py-2 border-b">{{ req.item_detail?.seriale }}</td>
                            <td class="px-4 py-2 border-b">{{ req.data_inizio }} - {{ req.data_fine }}</td>
                            <td class="px-4 py-2 border-b">{{ req.note }}</td>
                            <td class="px-4 py-2 border-b"><span :class="statusClass(req.stato)">{{ req.stato }}</span></td>
                            <td class="px-4 py-2 border-b">
                                <button v-if="req.stato === 'in_attesa'" @click="confirm(req.id)" class="bg-green-600 text-white px-2 py-1 rounded mr-2">Conferma</button>
                                <button v-if="req.stato === 'in_attesa'" @click="cancel(req.id)" class="bg-red-600 text-white px-2 py-1 rounded">Annulla</button>
                            </td>
                        </tr>
                    </template>
                    <template v-else>
                        <tr v-for="req in requests_acquisto" :key="req.id">
                            <td class="px-4 py-2 border-b">{{ req.user?.name }}</td>
                            <td class="px-4 py-2 border-b">{{ req.nome_articolo }}</td>
                            <td class="px-4 py-2 border-b">{{ req.note }}</td>
                            <td class="px-4 py-2 border-b"><span :class="statusClass(req.stato)">{{ req.stato }}</span></td>
                            <td class="px-4 py-2 border-b">
                                <button v-if="req.stato === 'in_attesa'" @click="confirm(req.id)" class="bg-green-600 text-white px-2 py-1 rounded mr-2">Conferma</button>
                                <button v-if="req.stato === 'in_attesa'" @click="cancel(req.id)" class="bg-red-600 text-white px-2 py-1 rounded">Annulla</button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </main>
    </div>

</template>

<script setup>
import { ref } from 'vue';
const filtro = ref('normali');
import { router, Link } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';

const logoutForm = useForm({});
function logout() {
    logoutForm.post('/logout');
}
const props = defineProps({
    requests_inventario: Array,
    requests_acquisto: Array,
    filters: Object,
});

const selectedStato = ref(props.filters?.stato || '');

function statusClass(stato) {
    if (stato === 'confermata') return 'text-green-600 font-bold';
    if (stato === 'annullata') return 'text-red-600 font-bold';
    if (stato === 'in_attesa') return 'text-yellow-600 font-bold';
    return 'text-gray-500';
}

function filter() {
    router.get(route('admin.requests.index'), { stato: selectedStato.value }, { preserveState: true, replace: true });
}

function confirm(id) {
    router.post(route('admin.requests.confirm', id));
}
function cancel(id) {
    router.post(route('admin.requests.cancel', id));
}
</script>

<style scoped>
.container {
  max-width: 1100px;
}
td,th{
    text-align: center;
}
select{
    width: 200px;
}
</style>
