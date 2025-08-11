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
            <li><Link href="/admin/dashboard">Home</Link></li>
            <li><Link href="/admin/items">Inventario</Link></li>
            <li><Link :href="route('admin.requests.index')">Richieste</Link></li>
            <li><Link href="/admin/statistiche">Statistiche</Link></li>
        </ul>
      </nav>
    </aside>
    <main class="main-content">
      <h1 class="text-2xl font-bold mb-6">Statistiche Magazzino (2025)</h1>
      <div class="stats-section">
        <h2>Totale richieste per mese (2025)</h2>
        <BarChart v-if="barDataYear.labels.length" :data="barDataYear" :options="barOptions" height="80" />
        <div v-else>Nessun dato disponibile.</div>
      </div>
      <div class="stats-section">
        <h2>Top prodotto per mese (2025)</h2>
        <BarChart v-if="barTopProductYear.labels.length" :data="barTopProductYear" :options="barTopProductOptions" height="80" />
        <div v-else>Nessun dato disponibile.</div>
      </div>
      <div class="stats-section">
        <h2>Richieste mensili per categoria (2025)</h2>
        <LineChart v-if="categoryDataYear.labels.length" :data="categoryDataYear" :options="lineOptions" height="80" />
        <div v-else>Nessun dato disponibile.</div>
      </div>
    </main>
  </div>
</template>

<script setup>
const props = defineProps({
  topItemsYear: Object,
  monthlyRequestsYear: Object,
  categoryMonthlyYear: Object,
  categories: Array,
});

import { Line as LineChartRaw, Bar as BarChartRaw } from 'vue-chartjs';
import {
  Chart,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  Title,
  Tooltip,
  Legend,
  ArcElement
} from 'chart.js';
import { computed, defineAsyncComponent } from 'vue';
import { usePage, useForm, Link } from '@inertiajs/vue3';
Chart.register(CategoryScale, LinearScale, PointElement, LineElement, BarElement, Title, Tooltip, Legend, ArcElement);

const LineChart = defineAsyncComponent(() => Promise.resolve(LineChartRaw));
const BarChart = defineAsyncComponent(() => Promise.resolve(BarChartRaw));

const barOptions = {
  responsive: true,
  plugins: { legend: { display: false }, title: { display: true, text: 'Richieste per mese' } },
};


const months = computed(() => {
  if (props.monthlyRequestsYear && typeof props.monthlyRequestsYear === 'object') {
    return Object.keys(props.monthlyRequestsYear);
  }
  return [];
});

const barDataYear = computed(() => {
  const m = months.value;
  if (!m.length) return { labels: [], datasets: [] };
  return {
    labels: m,
    datasets: [{
      label: 'Totale richieste',
      data: m.map(mm => props.monthlyRequestsYear[mm] || 0),
      backgroundColor: '#4fd1c5',
    }]
  };
});

const categoryDataYear = computed(() => {
  const m = months.value;
  if (!m.length || !Array.isArray(props.categories)) return { labels: [], datasets: [] };
  const datasets = props.categories.map((cat, i) => ({
    label: cat.name,
    data: m.map(mm => {
      const found = (props.categoryMonthlyYear[mm] || []).find(r => r.category_id === cat.id);
      return found ? found.total : 0;
    }),
    borderColor: `hsl(${i * 60}, 70%, 50%)`,
    fill: false,
    tension: 0.3,
  }));
  return { labels: m, datasets };
});

const barTopProductYear = computed(() => {
  const m = months.value;
  if (!m.length || !props.topItemsYear) return { labels: [], datasets: [] };
  // Per ogni mese prendi il top prodotto (se esiste)
  const labels = m.map(mm => {
    const top = (props.topItemsYear[mm] && props.topItemsYear[mm][0]) || null;
    return top && top.item_detail && top.item_detail.item ? top.item_detail.item.name : '';
  });
  const data = m.map(mm => {
    const top = (props.topItemsYear[mm] && props.topItemsYear[mm][0]) || null;
    return top ? top.total : 0;
  });
  return {
    labels,
    datasets: [{
      label: 'Richieste top prodotto',
      data,
      backgroundColor: '#f6ad55',
    }]
  };
});
const barTopProductOptions = {
  responsive: true,
  plugins: {
    legend: { display: false },
    title: { display: true, text: 'Top prodotto per mese' }
  },
  scales: {
    x: {
      ticks: { display: false },
      grid: { display: false }
    }
  }
};

const page = usePage();
const userName = computed(() => page.props.auth?.user?.name || '');
const logoutForm = useForm({});
function logout() {
  logoutForm.post('/logout');
}

const lineOptions = {
  responsive: true,
  plugins: { legend: { display: true } },
};


</script>

<style scoped>
.stats-section {
  margin-bottom: 2.5rem;
  width: 75%;
}
.stats-table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 1.5rem;
}
.stats-table th, .stats-table td {
  padding: 0.7rem 1rem;
  text-align: center;
  border-bottom: 1px solid #f0f0f0;
}
.stats-table thead {
  background: #f7f8fa;
}
</style>
