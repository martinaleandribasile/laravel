<template>
  <div class="item-form-container">
    <h1>Aggiungi Articolo</h1>
    <form @submit.prevent="submit">
      <div class="form-group">
        <label>Nome</label>
        <input v-model="form.name" type="text" required />
      </div>
      <div class="form-group">
        <label>Descrizione</label>
        <textarea v-model="form.description" rows="2" />
      </div>
      <div class="form-group">
        <label>Categoria</label>
        <select v-model="form.category_id" required>
          <option value="" disabled>Seleziona categoria</option>
          <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
        </select>
      </div>
      <div class="form-group">
        <label>Stato</label>
        <select v-model="form.status" required>
          <option value="available">Disponibile</option>
          <option value="unavailable">Non disponibile</option>
          <option value="maintenance">In manutenzione</option>
        </select>
      </div>
      <div class="form-group">
        <label>Quantità</label>
        <input v-model.number="form.quantity" type="number" min="0" required />
      </div>
      <div class="form-actions">
        <button type="submit" class="save-btn">Salva</button>
        <Link href="/admin/items" class="cancel-btn">Annulla</Link>
      </div>
    </form>
  </div>
</template>

<script setup>
import { reactive } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

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
  form.post('/admin/items');
}
</script>

<style scoped>
.item-form-container {
  max-width: 500px;
  margin: 2rem auto;
  background: #fff;
  border-radius: 12px;
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
  color: #4fd1c5;
  font-weight: 600;
  text-decoration: underline;
  font-size: 1.1rem;
  align-self: center;
}
</style>
