<template>
  <div class="auth-container">
    <div v-if="!showRegister">
      <h2>Login</h2>
      <form @submit.prevent="handleLogin">
        <div>
          <label>Email:</label>
          <input v-model="email" type="email" required />
        </div>
        <div>
          <label>Password:</label>
          <input v-model="password" type="password" required />
        </div>
        <button type="submit" :disabled="loading">Login</button>
        <p><a href="#" @click.prevent="showRegister = true">Create account</a></p>
        <p v-if="error" style="color: red">{{ error }}</p>
      </form>
    </div>

    <div v-else>
      <h2>Register</h2>
      <form @submit.prevent="handleRegister">
        <div>
          <label>Email:</label>
          <input v-model="email" type="email" required />
        </div>
        <div>
          <label>Password:</label>
          <input v-model="password" type="password" required />
        </div>
        <div>
          <label>Confirm Password:</label>
          <input v-model="passwordConfirm" type="password" required />
        </div>
        <button type="submit" :disabled="loading">Register</button>
        <p><a href="#" @click.prevent="showRegister = false">Back to login</a></p>
        <p v-if="error" style="color: red">{{ error }}</p>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '../composables/userAuth.js';

const router = useRouter();
const { login, register, loading, error: authError } = useAuth();

const email = ref('');
const password = ref('');
const passwordConfirm = ref('');
const showRegister = ref(false);

async function handleLogin() {
  const success = await login(email.value, password.value);
  if (success) {
    router.push('/');
  }
}

async function handleRegister() {
  const success = await register(email.value, password.value, passwordConfirm.value);
  if (success) {
    router.push('/');
  }
}
</script>

<style scoped>
.auth-container {
  max-width: 300px;
  margin: 50px auto;
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 8px;
}

form div {
  margin-bottom: 15px;
}

label {
  display: block;
  margin-bottom: 5px;
}

input {
  width: 100%;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

button {
  width: 100%;
  padding: 10px;
  background: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button:hover:not(:disabled) {
  background: #0056b3;
}

button:disabled {
  background: #ccc;
  cursor: not-allowed;
}

a {
  color: #007bff;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}
</style>