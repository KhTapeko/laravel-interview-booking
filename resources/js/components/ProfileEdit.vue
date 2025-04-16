<template>
    <form @submit.prevent="submit" class="bg-white shadow rounded-lg p-6 space-y-4">
        <FormGroup label="姓名" :error="errors.name">
    <input v-model="form.name" type="text" class="form-input w-full" required />
    </FormGroup>

    <FormGroup label="性別" :error="errors.gender">
    <select v-model="form.gender" class="form-select w-full" required>
        <option value="male">男 (male)</option>
        <option value="female">女 (female)</option>
        <option value="other">其他 (other)</option>
    </select>
    </FormGroup>

    <FormGroup label="生日" :error="errors.birthday">
    <input v-model="form.birthday" type="date" class="form-input w-full" required />
    </FormGroup>

    <FormGroup label="Email" :error="errors.email">
    <input v-model="form.email" type="email" class="form-input w-full" required />
    </FormGroup>

    <details class="border border-gray-200 rounded p-4">
    <summary class="cursor-pointer text-sm text-gray-600">修改密碼</summary>

    <div class="mt-4 space-y-4">
        <FormGroup label="目前密碼" :error="errors.current_password">
        <input v-model="form.current_password" type="password" class="form-input w-full" />
        </FormGroup>

        <FormGroup label="新密碼" :error="errors.new_password">
        <input v-model="form.new_password" type="password" class="form-input w-full" />
        </FormGroup>

        <FormGroup label="確認新密碼" :error="errors.new_password_confirmation">
        <input v-model="form.new_password_confirmation" type="password" class="form-input w-full" />
        </FormGroup>

        <p v-if="passwordMismatch" class="text-red-500 text-sm">兩次輸入的密碼不一致</p>
        <p v-if="passwordInvalid" class="text-red-500 text-sm">密碼須包含英文與數字</p>
    </div>
    </details>

      <div class="pt-4">
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
          儲存變更
        </button>
      </div>
    </form>
  </template>
  
  <script setup>
  import { ref, computed } from 'vue'
  import axios from '@/bootstrap'
  import { useAuthStore } from '@/stores/auth'
  import { storeToRefs } from 'pinia'
  import FormGroup from '@/components/FormGroup.vue'
  
  const auth = useAuthStore()
  const { user } = storeToRefs(auth)
  
  const emit = defineEmits(['done'])
  
  const form = ref({
    name: user.value.name,
    gender: user.value.gender,
    birthday: user.value.birthday,
    email: user.value.email,
    current_password: '',
    new_password: '',
    new_password_confirmation: '',
  })
  
  const errors = ref({})
  
  // ✅ 前端驗證：兩次密碼不一致
  const passwordMismatch = computed(() =>
    form.value.new_password &&
    form.value.new_password_confirmation &&
    form.value.new_password !== form.value.new_password_confirmation
  )
  
  // ✅ 前端驗證：密碼須包含英文與數字，且長度 ≥ 6
  const passwordInvalid = computed(() =>
    form.value.new_password &&
    !/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/.test(form.value.new_password)
  )
  
  const submit = async () => {
    // 前端驗證失敗時不送出
    if (passwordMismatch.value || passwordInvalid.value) {
      return
    }
  
    const payload = { ...form.value }
  
    // ✅ 清掉空密碼欄位，避免觸發不必要驗證
    if (!payload.new_password) {
      delete payload.current_password
      delete payload.new_password
      delete payload.new_password_confirmation
    }
  
    errors.value = {}
  
    try {
      await axios.put('/profile/update', payload)
      alert('個人資料已更新')
      emit('done')
    } catch (err) {
      if (err.response?.status === 422) {
        errors.value = err.response.data.errors || {}
      } else {
        alert('更新失敗')
        console.error(err)
      }
    }
  }
  </script>
  
  <style scoped>
  .form-input, .form-select {
    @apply border rounded px-3 py-2 mt-1 block w-full shadow-sm focus:ring-blue-500 focus:border-blue-500;
  }
  </style>