import { defineStore } from 'pinia'
import axios from '@/bootstrap'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    _didFetch: false
  }),

  actions: {
    async fetchUser() {
      try {
        const { data } = await axios.get('/me')
    
        // 根據後端新的格式處理
        this.user = data?.isLoggedIn ? data.user : null
        return true
      } catch (err) {
        this.user = null
        return false
      }
    },

    async login(email, password) {
      await axios.get('/sanctum/csrf-cookie')
      const { data } = await axios.post('/login', { email, password })
      this.user = data
    },

    async logout() {
      await axios.post('/logout')
      this.user = null
      window.location.reload()
    },
  },
})
