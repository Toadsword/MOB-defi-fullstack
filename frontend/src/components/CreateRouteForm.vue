<template>
  <div>
    <h2>Create Route</h2>

    <form @submit.prevent="submitForm">
      <label>From Station ID:
        <select v-model="fromStationId">
          <option value="" disabled>Select station</option>
          <option v-for="s in stations" :key="s.shortname" :value="s.shortname">
            {{ s.longname }} ({{ s.shortname }})
          </option>
        </select>
      </label>

      <label>To Station ID:
        <select v-model="toStationId">
          <option value="" disabled>Select station</option>
          <option v-for="s in stations" :key="s.shortname" :value="s.shortname">
            {{ s.longname }} ({{ s.shortname }})
          </option>
        </select>
      </label>

      <label>Analytic Code:</label>
      <input v-model="analyticCode" required />

      <button type="submit">Create Route</button>
    </form>

    <p v-if="response">Created route ID: {{ response.id }}</p>
    <p v-if="error" style="color: red">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { createRoute } from "../api.js";

const stations = ref([]);
const fromStationId = ref("");
const toStationId = ref("");
const analyticCode = ref("");

const response = ref(null);
const error = ref(null);

onMounted(async () => {
  try {
    const res = await fetch("/api/stations");
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    
    const text = await res.text();    
    const data = JSON.parse(text);
    stations.value = Array.isArray(data) ? data : data.stations || [];
  } catch (err) {
    console.error("Failed to load stations:", err);
    error.value = "Failed to load stations: " + err.message;
  }
});

async function submitForm() {
  error.value = null;
  response.value = null;

  try {
    const res = await createRoute({
      fromStationId: fromStationId.value,
      toStationId: toStationId.value,
      analyticCode: analyticCode.value
    });

    if (res.code) {
      error.value = res.message;
    } else {
      response.value = res;
    }
  } catch (err) {
    error.value = "Network or server error";
  }
}
</script>

<style scoped>
form {
  display: flex;
  flex-direction: column;
  gap: 8px;
}
button {
  width: 150px;
  margin-top: 8px;
}
</style>
