<template>
  <div>
    <h2>Create Route</h2>

    <form @submit.prevent="submitForm">
      <label>From Station ID:</label>
      <input v-model="fromStationId" required />

      <label>To Station ID:</label>
      <input v-model="toStationId" required />

      <label>Analytic Code:</label>
      <input v-model="analyticCode" required />

      <button type="submit">Create Route</button>
    </form>

    <p v-if="response">Created route ID: {{ response.id }}</p>
    <p v-if="error" style="color: red">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { createRoute } from "../api.js";

const fromStationId = ref("");
const toStationId = ref("");
const analyticCode = ref("");

const response = ref(null);
const error = ref(null);

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
      emit("created", res);
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
