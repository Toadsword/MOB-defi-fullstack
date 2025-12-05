<template>
  <div id="app">
    <nav>
      <router-link to="/">Home</router-link>
      <router-link to="/analytics">Analytics</router-link>
      <div v-if="authenticated" class="user-section">
        <span>{{ user.email }}</span>
        <button @click="handleLogout">Logout</button>
      </div>
      <div v-else>
        <router-link to="/login">Login</router-link>
      </div>
    </nav>
    <main>
      <router-view />
    </main>
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from './composables/userAuth.js';

const router = useRouter();
const { user, authenticated, initAuth, logout } = useAuth();

onMounted(() => {
  initAuth();
});

async function handleLogout() {
  await logout();
  router.push('/login');
}
</script>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

nav {
  display: flex;
  gap: 20px;
  padding: 20px;
  border-bottom: 1px solid #ddd;
  align-items: center;
  background: #f8f9fa;
}

nav a {
  text-decoration: none;
  color: #007bff;
  font-weight: 500;
}

nav a:hover {
  text-decoration: underline;
}

.user-section {
  margin-left: auto;
  display: flex;
  gap: 15px;
  align-items: center;
}

button {
  padding: 8px 16px;
  background: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 500;
}

button:hover {
  background: #0056b3;
}

main {
  padding: 20px;
  max-width: 1200px;
  margin: 0 auto;
}
</style>