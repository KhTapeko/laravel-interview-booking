<template>
    <div class="max-w-md mx-auto mt-24 p-6 bg-white rounded shadow">
      <h2 class="text-2xl font-bold mb-4 text-center">註冊帳號</h2>
  
      <form @submit.prevent="submit">
        <div class="mb-4">
          <label class="block font-bold mb-1">Email</label>
          <input type="email" v-model="form.email" class="w-full border px-3 py-2 rounded" />
          <p v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email[0] }}</p>
        </div>
  
        <div class="mb-4">
          <label class="block font-bold mb-1">密碼</label>
          <input type="password" v-model="form.password" class="w-full border px-3 py-2 rounded" />
          <p v-if="errors.password" class="text-red-500 text-sm mt-1">{{ errors.password[0] }}</p>
        </div>
  
        <div class="mb-4">
          <label class="block font-bold mb-1">再次輸入密碼</label>
          <input type="password" v-model="form.password_confirmation" class="w-full border px-3 py-2 rounded" />
          <p v-if="errors.password_confirmation" class="text-red-500 text-sm mt-1">{{ errors.password_confirmation[0] }}</p>
        </div>
  
        <p v-if="passwordMismatch" class="text-red-500 text-sm mb-2">兩次輸入的密碼不一致</p>
  
        <button type="submit" :disabled="loading" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
          {{ loading ? '註冊中...' : '註冊' }}
        </button>
      </form>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  import axios from 'axios'
  import { useRouter } from 'vue-router'
  
  const router = useRouter()
  
  const form = ref({
    email: '',
    password: '',
    password_confirmation: ''
  })
  
  const errors = ref({})
  const loading = ref(false)
  const passwordMismatch = ref(false)
  
  const submit = async () => {
    errors.value = {}
    passwordMismatch.value = false
  
    // 前端密碼一致性驗證
    if (form.value.password !== form.value.password_confirmation) {
      passwordMismatch.value = true
      return
    }
  
    try {
      loading.value = true
      await axios.post('/register', form.value)
      alert('註冊成功！請登入')
      router.push('/login')
    } catch (error) {
      if (error.response && error.response.status === 422) {
        errors.value = error.response.data.errors
      }
    } finally {
      loading.value = false
    }
  }
  </script>
  