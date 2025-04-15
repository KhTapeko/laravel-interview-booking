<template>
  <div class="max-w-lg mx-auto mt-20 p-8 bg-white rounded-2xl shadow-lg space-y-6">
    <h2 class="text-3xl font-bold text-center text-gray-800">註冊新帳號</h2>

    <form @submit.prevent="submit" class="space-y-4">
      <FormGroup label="名字" :error="errors.name">
        <input v-model="form.name" type="text" class="form-input" placeholder="請輸入名字" />
      </FormGroup>

      <FormGroup label="性別" :error="errors.gender">
        <select v-model="form.gender" class="form-input">
          <option value="">請選擇性別</option>
          <option value="male">男性</option>
          <option value="female">女性</option>
          <option value="other">其他</option>
        </select>
      </FormGroup>

      <FormGroup label="生日" :error="errors.birthday">
        <input v-model="form.birthday" type="date" class="form-input" />
      </FormGroup>

      <FormGroup label="Email" :error="errors.email">
        <input v-model="form.email" type="email" class="form-input" placeholder="example@email.com" />
      </FormGroup>

      <FormGroup label="密碼" :error="errors.password">
        <input v-model="form.password" type="password" class="form-input" placeholder="至少6碼，包含英文與數字" />
      </FormGroup>

      <FormGroup label="確認密碼" :error="errors.password_confirmation">
        <input v-model="form.password_confirmation" type="password" class="form-input" />
      </FormGroup>

      <p v-if="passwordMismatch" class="text-red-500 text-sm">兩次輸入的密碼不一致</p>
      <p v-if="passwordInvalid" class="text-red-500 text-sm">密碼須包含英文與數字</p>
      <p v-if="error" class="text-red-500 text-sm">{{ error }}</p>

      <button type="submit" :disabled="loading" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-xl transition">
        {{ loading ? '註冊中...' : '註冊' }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import FormGroup from '@/components/FormGroup.vue'

const router = useRouter()

const form = ref({
  name: '',
  gender: '',
  birthday: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const errors = ref({})
const error = ref('')
const loading = ref(false)
const passwordMismatch = ref(false)
const passwordInvalid = ref(false)

const isPasswordStrong = (pwd) => {
  return /[a-zA-Z]/.test(pwd) && /[0-9]/.test(pwd)
}

const submit = async () => {
  errors.value = {}
  error.value = ''
  passwordMismatch.value = false
  passwordInvalid.value = false

  if (form.value.password !== form.value.password_confirmation) {
    passwordMismatch.value = true
    return
  }

  if (!isPasswordStrong(form.value.password)) {
    passwordInvalid.value = true
    return
  }

  try {
    loading.value = true
    await axios.post('/register', form.value)
    alert('註冊成功！請登入')
    router.push('/login')
  } catch (err) {
    const code = err?.response?.status
    const raw = err?.response?.data?.message ?? ''
    let msg = '註冊失敗 (Registration failed)'

    if (code === 422) {
      errors.value = err.response.data.errors || {}
      if (errors.value.email?.[0]?.includes('already been taken')) {
        errors.value.email[0] = '此 Email 已被使用（' + errors.value.email[0] + '）'
      }
    } else if (code === 419) {
      msg = '表單逾時，請重新整理（CSRF token mismatch）'
    } else if (code >= 500) {
      msg = '伺服器錯誤，請稍後再試（Server error）'
    } else if (err.code === 'ERR_NETWORK') {
      msg = '無法連線，請檢查網路（Network error）'
    }

    error.value = msg
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.form-input {
  @apply w-full border rounded px-3 py-2 outline-blue-400;
}
</style>
