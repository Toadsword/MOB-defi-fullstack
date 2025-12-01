<template>
  <div>
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
          <td>{{ item.totalDistanceKm }}</td>
        </tr>
      </tbody>
    </table>

    <p v-if="error" style="color: red">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { fetchAnalytics } from "../api.js";

const items = ref([]);
const fromDate = ref(""); // New ref for from date
const toDate = ref("");   // New ref for to date
const error = ref(null);

async function load() {
  error.value = null;

  try {
    const res = await fetchAnalytics({ 
      fromDate: fromDate.value, // Include fromDate
      toDate: toDate.value      // Include toDate
    });

    if (res && res.items) {  // Check if response is valid
      items.value = res.items;
    } else {
      throw new Error("Invalid response format");  // Throw error if response is not as expected
    }
  } catch (err) {
    console.error(err);  // Log the error for debugging
    error.value = "Failed to load analytics";
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
