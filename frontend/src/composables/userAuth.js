import { ref, computed } from 'vue';
import { checkAuth, loginUser, registerUser, logoutUser } from '../api.js';

const user = ref(null);
const loading = ref(false);
const error = ref(null);

export function useAuth() {
  const authenticated = computed(() => user.value !== null);

  async function initAuth() {
    loading.value = true;
    try {
      const res = await checkAuth();
      if (res.authenticated) {
        user.value = {
          email: res.email,
          is_admin: res.is_admin
        };
      }
    } catch (err) {
      console.error("Auth check failed:", err);
    } finally {
      loading.value = false;
    }
  }

  async function login(email, password) {
    loading.value = true;
    error.value = null;
    try {
      const res = await loginUser({ email, password });
      if (res.success) {
        user.value = { email, is_admin: false };
        return true;
      } else {
        error.value = res.error || 'Login failed';
        return false;
      }
    } catch (err) {
      error.value = 'Login error';
      return false;
    } finally {
      loading.value = false;
    }
  }

  async function register(email, password, passwordConfirm) {
    loading.value = true;
    error.value = null;
    try {
      const res = await registerUser({ email, password, passwordConfirm });
      if (res.success) {
        user.value = { email, is_admin: false };
        return true;
      } else {
        error.value = res.error || 'Registration failed';
        return false;
      }
    } catch (err) {
      error.value = 'Registration error';
      return false;
    } finally {
      loading.value = false;
    }
  }

  async function logout() {
    loading.value = true;
    try {
      await logoutUser();
      user.value = null;
    } catch (err) {
      console.error("Logout error:", err);
    } finally {
      loading.value = false;
    }
  }

  return {
    user,
    authenticated,
    loading,
    error,
    initAuth,
    login,
    register,
    logout
  };
}