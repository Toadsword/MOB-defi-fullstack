<template>
  <div>
    <h2>Analytics</h2>

    <div>
      <label>Group By: </label>
      <select v-model="groupBy">
        <option value="none">None</option>
        <option value="day">Day</option>
        <option value="month">Month</option>
        <option value="year">Year</option>
      </select>

      <button @click="load">Load</button>
    </div>

    <table v-if="items.length">
      <thead>
        <tr>
          <th>Analytic Code</th>
          <th>Total Distance</th>
          <th>Period Start</th>
          <th>Period End</th>
          <th>Group</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="(item, i) in items" :key="i">
          <td>{{ item.analyticCode }}</td>
          <td>{{ item.totalDistanceKm }}</td>
          <td>{{ item.periodStart }}</td>
          <td>{{ item.periodEnd }}</td>
          <td>{{ item.group }}</td>
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
const groupBy = ref("none");
const error = ref(null);

async function load() {
  error.value = null;

  try {
    const res = await fetchAnalytics({ groupBy: groupBy.value });
    items.value = res.items;
  } catch (err) {
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
