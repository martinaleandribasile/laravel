<template>
    <div>
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
                        <li><Link href="/admin/items">Inventario</Link></li>
                        <li><a href="#richieste">Richieste</a></li>
                        <li><a href="#statistiche">Statistiche</a></li>
                    </ul>
                </nav>
            </aside>
            <main class="main-content">
                <div class="item-show">
                    <h1>{{ item.name }}</h1>
                    <p>Categoria: {{ item.category?.name }}</p>
                    <p>Descrizione: {{ item.description }}</p>
                    <div class="item-stats">
                        <span>Disponibili: <b>{{ disponibili }}</b></span>
                        <span>In uso: <b>{{ in_uso }}</b></span>
                        <span>In attesa: <b>{{ in_attesa }}</b></span>
                    </div>
                    <div class="header-row-end">
                        <button class="add-btn" @click="showAddDetail = true">+ Aggiungi Pezzo</button>
                    </div>
                    <div v-if="showAddDetail" class="modal-overlay">
                        <div class="modal-content">
                            <h2 class="modal-title">Aggiungi Pezzo</h2>
                            <form @submit.prevent="submitDetail">
                                <div class="form-group">
                                    <label>Seriale</label>
                                    <input v-model="detailForm.seriale" required />
                                    <span v-if="detailForm.errors.seriale" class="error">{{ detailForm.errors.seriale }}</span>
                                </div>
                                <div class="form-group">
                                    <label>Colore</label>
                                    <input v-model="detailForm.colore" />
                                </div>
                                <div class="form-group">
                                    <label>RAM</label>
                                    <input v-model="detailForm.ram" />
                                </div>
                                <div class="form-group">
                                    <label>Altro</label>
                                    <input v-model="detailForm.altro" />
                                </div>
                                <div class="form-group">
                                    <label>Stato</label>
                                    <select v-model="detailForm.stato" required>
                                        <option value="disponibile">Disponibile</option>
                                        <option value="in_uso">In uso</option>
                                        <option value="in_attesa">In attesa</option>
                                    </select>
                                    <span v-if="detailForm.errors.stato" class="error">{{ detailForm.errors.stato }}</span>
                                </div>
                                <div class="form-group">
                                    <label>Inizio uso</label>
                                    <input type="date" v-model="detailForm.data_inizio_uso" />
                                </div>
                                <div class="form-group">
                                    <label>Fine uso</label>
                                    <input type="date" v-model="detailForm.data_fine_uso" />
                                </div>
                                <div class="modal-actions">
                                    <button type="submit" class="save-btn" :disabled="detailForm.processing">
                                        <span v-if="detailForm.processing" class="loader"></span>
                                        <span v-else>Salva</span>
                                    </button>
                                    <button type="button" class="cancel-btn" @click="showAddDetail = false">Annulla</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <table class="pezzi-table">
                        <thead>
                            <tr>
                            <th>Seriale</th>
                            <th>Colore</th>
                            <th>RAM</th>
                            <th>Altro</th>
                            <th>Stato</th>
                            <th>Inizio uso</th>
                            <th>Fine uso</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="pezzo in dettaglio_pezzi" :key="pezzo.id">
                            <td>{{ pezzo.seriale }}</td>
                            <td>{{ pezzo.colore }}</td>
                            <td>{{ pezzo.ram }}</td>
                            <td>{{ pezzo.altro }}</td>
                            <td>
                                <span :class="{
                                    'status available': pezzo.stato === 'disponibile',
                                    'status in-attesa': pezzo.stato === 'in_attesa',
                                    'status in-uso': pezzo.stato === 'in_uso'
                                }">
                                    {{ pezzo.stato }}
                                </span>
                            </td>
                            <td>{{ pezzo.data_inizio_uso || '-' }}</td>
                            <td>{{ pezzo.data_fine_uso || '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

</template>

<script setup>
import { Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
const props = defineProps({
    item: Object,
    dettaglio_pezzi: Array,
    disponibili: Number,
    in_uso: Number,
    in_attesa: Number,
});

const showAddDetail = ref(false);
const detailForm = useForm({
    seriale: '',
    colore: '',
    ram: '',
    altro: '',
    stato: 'disponibile',
    data_inizio_uso: '',
    data_fine_uso: '',
});

function submitDetail() {
    detailForm.post(`/admin/items/${props.item.id}/details`, {
        onSuccess: () => {
            detailForm.reset();
            showAddDetail.value = false;
        },
        onError: (errors) => {
            // Gli errori sono già gestiti da form.errors
        },
    });
}
</script>

<style scoped>
.item-show {
  margin: 2rem auto;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
  padding: 2rem 2.5rem;
}
.item-stats {
  display: flex;
  gap: 2rem;
  margin: 1.5rem 0;
}
.pezzi-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 1.5rem;
}
.pezzi-table th, .pezzi-table td {
  padding: 0.7rem 1rem;
  text-align: center;
  border-bottom: 1px solid #f0f0f0;
}
.pezzi-table thead {
  background: #f7f8fa;
}
</style>
<style scoped>
.pezzo-form {
    background: #f7f8fa;
    border-radius: 8px;
    padding: 1.2rem 1.5rem;
    margin: 1.5rem 0;
    max-width: 500px;
}
.form-row {
    margin-bottom: 1rem;
    display: flex;
    flex-direction: column;
}
.form-row label {
    font-weight: 600;
    margin-bottom: 0.3rem;
}
.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
}
.save-btn {
    background: #4fd1c5;
    color: #fff;
    font-weight: 600;
    border-radius: 8px;
    padding: 0.6rem 2rem;
    font-size: 1rem;
    border: none;
    transition: background 0.2s;
    cursor: pointer;
}
.save-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}
.cancel-btn {
    color: #e53e3e;
    font-weight: 600;
    background: none;
    border: none;
    font-size: 1rem;
    cursor: pointer;
}
.error {
    color: #e53e3e;
    font-size: 0.98rem;
    margin-top: 0.2rem;
}
.loader {
    display: inline-block;
    width: 18px;
    height: 18px;
    border: 3px solid #4fd1c5;
    border-radius: 50%;
    border-top: 3px solid #fff;
    animation: spin 1s linear infinite;
    vertical-align: middle;
}
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
<style scoped>
.status.available {
    color: #319795;
    font-weight: bold;
}
.status.in-attesa {
    color: #b7791f;
    font-weight: bold;
}
.status.in-uso {
    color: #c53030;
    font-weight: bold;
}
</style>
<style scoped>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0,0,0,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}
.modal-content {
    background: #fff;
    border-radius: 12px;
    padding: 2rem 2.5rem;
    box-shadow: 0 2px 16px rgba(0,0,0,0.12);
    width: 50%;
}
.modal-title{
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;
    font-size: x-large;
    margin-bottom: 1.5rem;
}
.form-group {
    margin-bottom: 1.2rem;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}
.form-group label {
    font-weight: 600;
    margin-bottom: 0.4rem;
}
.form-group input, .form-group select {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    font-size: 1rem;
    width: 100%;
}
.modal-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 1.5rem;
}
</style>
