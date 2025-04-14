import { defineStore } from 'pinia';
import axios from '@/bootstrap';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    _didFetch: false
  }),

  actions: {
    async fetchUser() {
      try {
        const { data } = await axios.get('/me');
        this.user = data;
      } catch (err) {
        if (err.response?.status !== 401) console.error(err);
        this.user = null;
      }
    },

    async login(email, password) {
      await axios.get('/sanctum/csrf-cookie');
      const { data } = await axios.post('/login', { email, password });
      this.user = data;
    },

    async logout() {
      await axios.post('/logout');
      this.user = null;
    },
  },
});
