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
            <div class="item-form-container">
                <h1>Aggiungi Articolo</h1>
                <form @submit.prevent="submit">
                <div class="form-group">
                    <label>Nome</label>
                    <input v-model="form.name" type="text" required />
                    <span v-if="form.errors.name" class="error">{{ form.errors.name }}</span>
                </div>
                <div class="form-group">
                    <label>Descrizione</label>
                    <textarea v-model="form.description" rows="2" />
                    <span v-if="form.errors.description" class="error">{{ form.errors.description }}</span>
                </div>
                <div class="form-group">
                    <label>Categoria</label>
                    <select v-model="form.category_id" required>
                    <option value="" disabled>Seleziona categoria</option>
                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                    </select>
                    <span v-if="form.errors.category_id" class="error">{{ form.errors.category_id }}</span>
                </div>
                <div class="form-group">
                    <label>Quantità</label>
                    <input v-model.number="form.quantity" type="number" min="0" required />
                </div>
                <div class="form-actions">
                    <button type="submit" class="save-btn" :disabled="form.processing">
                    <span v-if="form.processing" class="loader"></span>
                    <span v-else>Salva</span>
                    </button>
                    <Link href="/admin/items" class="cancel-btn">Annulla</Link>
                </div>
                </form>
            </div>
        </main>
    </div>

</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

import { useForm } from '@inertiajs/vue3';
const logoutForm = useForm({});
function logout() {
  logoutForm.post('/logout');
}

const props = defineProps({
  categories: Array,
});

const form = useForm({
  name: '',
  description: '',
  category_id: '',
  status: 'available',
  quantity: 1,
});

function submit() {
  form.post('/admin/items', {
    onSuccess: () => {
      form.reset();
    },
    onError: (errors) => {
      console.error(errors);
    },
  });
}
</script>

<style scoped>
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
.item-form-container {
  margin: 2rem auto;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
  padding: 2rem 2.5rem;
}
h1 {
  font-size: 1.5rem;
  margin-bottom: 1.5rem;
  color: #222e3c;
}
.form-group {
  margin-bottom: 1.2rem;
  display: flex;
  flex-direction: column;
}
label {
  font-weight: 600;
  margin-bottom: 0.3rem;
}
input, textarea, select {
  border: 1px solid #e2e8f0;
  border-radius: 6px;
  padding: 0.6rem 0.8rem;
  font-size: 1rem;
  background: #f7f8fa;
}
.form-actions {
  display: flex;
  justify-content: end;
  gap: 1rem;
  margin-top: 1.5rem;
}
.save-btn {
  background: #4fd1c5;
  color: #fff;
  font-weight: 600;
  border-radius: 8px;
  padding: 0.7rem 2.2rem;
  font-size: 1.1rem;
  border: none;
  transition: background 0.2s;
  cursor: pointer;
}
.save-btn:hover {
  background: #38b2ac;
}
.cancel-btn {
  color: #c53030;
  border-radius: 8px;
  padding: 0.7rem 2.2rem;
  font-weight: 600;
  border: 1px solid #c53030;
  font-size: 1.1rem;
  align-self: center;
}
</style>
