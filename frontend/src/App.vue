<template>
  <div class="container">
    <h1>Transport Distance App</h1>

    <CreateRouteForm @created="onRouteCreated" />

    <hr />

    <AnalyticsView />
  </div>
</template>

<script setup>
import CreateRouteForm from "./components/CreateRouteForm.vue";
import AnalyticsView from "./components/AnalyticsView.vue";

function onRouteCreated(route) {
  console.log("Route created:", route);
}
</script>

<style>
body {
  font-family: Arial, sans-serif;
}
.container {
  max-width: 800px;
  margin: auto;
  padding: 20px;
}
</style>
