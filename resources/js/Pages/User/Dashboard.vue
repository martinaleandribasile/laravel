<template>
  <div class="user-dashboard">
    <nav class="navbar">
      <div class="logo">
        <span class="logo-w">W</span><span class="logo-e">ea</span><span class="logo-r">r</span><span class="logo-h">h</span><span class="logo-o">ou</span><span class="logo-s">s</span><span class="logo-e2">e</span>
      </div>
      <div class="profile-menu">
        <span class="profile-name">{{ userName }}</span>
        <div class="dropdown">
          <button class="profile-btn">Profilo ▾</button>
          <div class="dropdown-content">
            <a href="/profile">Profilo</a>
            <button @click="logout" class="logout-btn">Logout</button>
          </div>
        </div>
      </div>
    </nav>
    <aside class="sidebar">
      <h2>Utente</h2>
      <nav>
        <ul>
          <li><a href="#richieste">Le mie richieste</a></li>
          <li><a href="#storico">Storico</a></li>
          <li><a href="#profilo">Profilo</a></li>
        </ul>
      </nav>
    </aside>
    <main class="main-content">
      <header class="header">
        <h1>Dashboard Utente</h1>
      </header>
    </main>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { usePage, useForm } from '@inertiajs/vue3';

const page = usePage();
const userName = computed(() => page.props.auth?.user?.name || '');

const form = useForm({});
function logout() {
  form.post('/logout');
}
</script>

<style scoped>
.user-dashboard {
  display: flex;
  min-height: 100vh;
  background: #f7f8fa;
}
.navbar {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  height: 64px;
  background: #fff;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 2rem;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
  z-index: 10;
}
.logo {
  font-family: 'Montserrat', 'Segoe UI', Arial, sans-serif;
  font-size: 2rem;
  font-weight: 900;
  letter-spacing: 2px;
  display: flex;
  align-items: center;
}
.logo-w { color: #4fd1c5; }
.logo-e { color: #222e3c; }
.logo-r { color: #fbbf24; }
.logo-h { color: #222e3c; }
.logo-o { color: #4fd1c5; }
.logo-s { color: #fbbf24; }
.logo-e2 { color: #222e3c; }
.profile-menu {
  display: flex;
  align-items: center;
  gap: 1rem;
}
.profile-name {
  font-weight: 400;
  font-style: oblique;
  color: #222e3c;
  margin-right: 0.5rem;
}
.profile-btn {
  background: #4fd1c5;
  color: #fff;
  border: none;
  border-radius: 4px;
  padding: 0.5rem 1rem;
  cursor: pointer;
  font-weight: 500;
  transition: background 0.2s;
}
.profile-btn:hover {
  background: #38b2ac;
}
.dropdown {
  position: relative;
  display: inline-block;
}
.dropdown-content {
  display: none;
  position: absolute;
  right: 0;
  background: #fff;
  min-width: 120px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  border-radius: 6px;
  z-index: 100;
}
.dropdown:hover .dropdown-content {
  display: block;
}
.dropdown-content a,
.dropdown-content .logout-btn {
  color: #222e3c;
  padding: 0.7rem 1rem;
  text-decoration: none;
  display: block;
  background: none;
  border: none;
  width: 100%;
  text-align: left;
  cursor: pointer;
  font-size: 1rem;
}
.dropdown-content a:hover,
.dropdown-content .logout-btn:hover {
  background: #f7f8fa;
}
.logout-btn {
  color: #e53e3e;
  font-weight: 600;
}
.sidebar {
  width: 220px;
  background: #222e3c;
  color: #fff;
  padding: 2rem 1rem;
  min-height: 100vh;
  margin-top: 64px;
}
.sidebar h2 {
  margin-bottom: 2rem;
  font-size: 1.5rem;
  letter-spacing: 1px;
}
.sidebar ul {
  list-style: none;
  padding: 0;
}
.sidebar li {
  margin-bottom: 1.2rem;
}
.sidebar a {
  color: #fff;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.2s;
}
.sidebar a:hover {
  color: #4fd1c5;
}
.main-content {
  flex: 1;
  padding: 2rem 3rem;
  margin-top: 64px;
}
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
