<template>
  <div v-if="authenticated">
    <h2>Analytics</h2>

    <div>
      <label>From Date: </label>
      <input type="date" v-model="fromDate" />

      <label>To Date: </label>
      <input type="date" v-model="toDate" />

      <button @click="load">Load</button>
    </div>

    <table v-if="items.length">
      <thead>
        <tr>
          <th>Route Taken Count</th>
          <th>From Station</th>
          <th>To Station</th>
          <th>Total Distance</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="(item, i) in items" :key="i">
          <td>{{ item.routeTakenCount }}</td>
          <td>{{ item.fromStation }}</td>
          <td>{{ item.toStation }}</td>
          <td>{{ item.totalDistanceKm }}km</td>
        </tr>
      </tbody>
    </table>

    <p v-if="error" style="color: red">{{ error }}</p>
  </div>
  <div v-else>
    <p>Please <router-link to="/login">login</router-link> to view analytics.</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAuth } from '../composables/userAuth.js';
import { fetchAnalytics } from '../api.js';

const { authenticated } = useAuth();
const items = ref([]);
const fromDate = ref('');
const toDate = ref('');
const error = ref(null);

async function load() {
  error.value = null;

  try {
    const res = await fetchAnalytics({
      fromDate: fromDate.value,
      toDate: toDate.value
    });

    if (res && res.items) {
      items.value = res.items;
    } else {
      throw new Error('Invalid response format');
    }
  } catch (err) {
    console.error(err);
    error.value = 'Failed to load analytics';
  }
}

onMounted(load);
</script>

<style scoped>
table {
  margin-top: 20px;
  width: 100%;
  border-collapse: collapse;
}
td, th {
  border: 1px solid #ddd;
  padding: 8px;
}
</style>
