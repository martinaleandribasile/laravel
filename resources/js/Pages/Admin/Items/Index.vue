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
            <li><Link href="/admin/dashboard">Home</Link></li>
            <li><Link href="/admin/items">Inventario</Link></li>
            <li><Link :href="route('admin.requests.index')">Richieste</Link></li>
           <li><Link href="/admin/statistiche">Statistiche</Link></li>
          </ul>
        </nav>
      </aside>
      <main class="main-content">
        <div class="items-index">
          <div class="header-row">
            <h1>Gestione Articoli</h1>
          </div>
          <div class="header-row-end">
            <Link href="/admin/items/create" class="add-btn">+ Nuovo Articolo</Link>
          </div>
          <table class="items-table">
            <thead>
              <tr>
                <th>Nome</th>
                <th>Categoria</th>
                <th>Quantità</th>
                <th>Disponibili</th>
                <th>In uso</th>
                <th>In attesa</th>
                <th>Status</th>
                <th>Dettaglio</th>
                <th>Modifica</th>
                <th>Elimina</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in items" :key="item.id">
                <td>{{ item.name }}</td>
                <td>{{ item.category?.name }}</td>
                <td>{{ item.quantity }}</td>
                <td>{{ item.disponibili }}</td>
                <td>{{ item.in_uso }}</td>
                <td>{{ item.in_attesa }}</td>
                <td>
                  <span v-if="item.disponibili > 0" class="status available">Richiedibile</span>
                  <span v-else class="status unavailable" style="color:#e53e3e">Non richiedibile</span>
                </td>
                <td>
                  <Link :href="`/admin/items/${item.id}`" class="action-btn" title="Dettaglio">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"><path stroke="#4fd1c5" stroke-width="2" d="M12 5c-7 0-9 7-9 7s2 7 9 7 9-7 9-7-2-7-9-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/></svg>
                  </Link>
                </td>
                <td>
                  <button @click="openEditModal(item)" class="action-btn" title="Modifica">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"><path stroke="#4fd1c5" stroke-width="2" d="M16.862 5.487a2.06 2.06 0 0 1 2.915 2.914l-9.193 9.193-3.09.343.343-3.09 9.193-9.193Z"/></svg>
                  </button>
                </td>
                <td>
                  <button @click="deleteItem(item.id)" class="action-btn delete" title="Elimina">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24"><path stroke="#e53e3e" stroke-width="2" d="M6 7h12M9 7V5a3 3 0 0 1 6 0v2m2 0v12a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V7h12Z"/></svg>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </main>
    </div>
    <div v-if="showEditModal" class="modal-overlay">
      <div class="modal-content">
        <h2 class="modal-title">Modifica Articolo {{ editItem.name }}</h2>
        <form @submit.prevent="submitEdit">
          <div class="form-group">
            <label>Nome</label>
            <input v-model="editItem.name" required />
          </div>
          <div class="form-group">
            <label>Categoria</label>
            <input v-model="editItem.category.name" required />
          </div>
          <div class="form-group">
            <label>Quantità</label>
            <input type="number" v-model="editItem.quantity" min="0" required />
          </div>
          <div class="modal-actions">
            <button type="submit" class="add-btn">Salva</button>
            <button type="button" class="add-btn" @click="closeEditModal" style="background:#e53e3e">Annulla</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>


<script setup>
import { Link, router, useForm } from '@inertiajs/vue3';
import { defineProps, ref, reactive } from 'vue';

// Inertia: useForm e Link sono forniti da Inertia.js per la gestione dei form e dei link SPA
const logoutForm = useForm({});

/**
 * Esegue il logout dell'utente tramite una richiesta POST usando Inertia.js
 */
function logout() {
  logoutForm.post('/logout');
}

// Props: lista degli articoli da visualizzare
const props = defineProps({
  items: Array,
});

// Stato per la modale di modifica
const showEditModal = ref(false);
const editItem = reactive({ id: null, name: '', category: { name: '' }, status: '', quantity: 0 });

/**
 * Apre la modale di modifica e popola i dati dell'articolo selezionato
 * @param {Object} item - L'articolo da modificare
 */
function openEditModal(item) {
  editItem.id = item.id;
  editItem.name = item.name;
  editItem.category = { ...item.category };
  editItem.status = item.status;
  editItem.quantity = item.quantity;
  showEditModal.value = true;
}

/**
 * Chiude la modale di modifica
 */
function closeEditModal() {
  showEditModal.value = false;
}

/**
 * Invia la richiesta di modifica articolo tramite Inertia.js (router.put)
 * Aggiorna i dati lato server e chiude la modale al successo
 */
function submitEdit() {
  router.put(`/admin/items/${editItem.id}`, {
    name: editItem.name,
    category_id: editItem.category.id,
    status: editItem.status,
    quantity: editItem.quantity,
  }, {
    onSuccess: () => closeEditModal(),
  });
}

/**
 * Elimina un articolo tramite Inertia.js (router.delete) dopo conferma utente
 * @param {Number} id - L'id dell'articolo da eliminare
 */
function deleteItem(id) {
  if (confirm('Sei sicuro di voler eliminare questo articolo?')) {
    router.delete(`/admin/items/${id}`);
  }
}
</script>

<style scoped>
.items-index {
  margin: 2rem auto;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
  padding: 2rem 2.5rem;
}

.items-table {
    width: 100%;
    border-collapse: collapse;
}
.items-table th, .items-table td {
    padding: 0.8rem 1rem;
    text-align: center;
}
.items-table thead {
    background: #f7f8fa;
}
.status {
    padding: 0.3rem 0.8rem;
    border-radius: 6px;
    font-size: 0.98rem;
    font-weight: 600;
    text-transform: capitalize;
}
.status.available {
    background: #e6fffa;
    color: #319795;
}
.status.unavailable {
    background: #fed7d7;
    color: #c53030;
}
.status.maintenance {
    background: #fefcbf;
    color: #b7791f;
}
  .action-btn {
    width: 100%;
    background: none;
    border: none;
    color: #4fd1c5;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    justify-content: center;
  }

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
    min-width: 50%;
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
.action-btn.delete {
    width: 100%;
    color: #e53e3e;
}
.action-btn:hover {
    color: #222e3c;
}
</style>
