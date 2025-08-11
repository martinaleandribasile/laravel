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
      <h3 class="sidebar-title"> Benvenuto: {{ userName }}</h3>
      <nav>
        <ul>
          <li><Link href="/admin/items">Inventario</Link></li>
          <li><a href="#richieste">Richieste</a></li>
          <li><a href="#statistiche">Statistiche</a></li>
        </ul>
      </nav>
    </aside>
    <main class="main-content">
      <header class="header">
        <h1>Dashboard Amministratore</h1>
      </header>
    </main>
  </div>
</template>


<script setup>
import { computed } from 'vue';
import { usePage, useForm, Link } from '@inertiajs/vue3';

const page = usePage();
const userName = computed(() => page.props.auth?.user?.name || '');
const csrfToken = computed(() => page.props.value.csrf_token || document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

const form = useForm({});
function logout() {
  form.post('/logout');
}
</script>

<style scoped>
.header {
  border-bottom: 1px solid #e2e8f0;
  margin-bottom: 2rem;
}
.header h1 {
  font-size: 2rem;
  color: #222e3c;
}
.section {
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
  padding: 1.5rem 2rem;
  margin-bottom: 2rem;
}
.section h2 {
  margin-bottom: 0.5rem;
  color: #2d3748;
}
</style>
