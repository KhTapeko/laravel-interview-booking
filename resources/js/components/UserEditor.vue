<template>
    <tr class="bg-white border-y border-gray-200">
      <td colspan="999" class="p-4">
        <div class="p-6 bg-gray-50 rounded-xl shadow-inner">
          <form @submit.prevent="submit" class="grid grid-cols-2 gap-4">
            <FormGroup label="名字" :error="errors.name">
              <input v-model="form.name" type="text" class="form-input" />
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
              <input v-model="form.email" type="email" class="form-input" />
            </FormGroup>
  
            <FormGroup label="角色" :error="errors.role">
              <select v-model="form.role" class="form-input">
                <option value="admin">管理員</option>
                <option value="employee">員工</option>
                <option value="candidate">應徵者</option>
              </select>
            </FormGroup>
  
            <div class="col-span-2 flex justify-end gap-4 mt-4">
              <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded shadow">
                儲存
              </button>
              <button type="button" @click="$emit('close')" class="bg-gray-400 hover:bg-gray-500 text-white font-bold px-4 py-2 rounded shadow">
                取消
              </button>
            </div>
          </form>
        </div>
      </td>
    </tr>
  </template>
  
  <script setup>
  import { ref, reactive, watch } from 'vue'
  import axios from 'axios'
  import FormGroup from '@/components/FormGroup.vue'
  
  const props = defineProps({ user: Object })
  const emit = defineEmits(['saved', 'close'])
  
  const form = reactive({
    name: '',
    gender: '',
    birthday: '',
    email: '',
    role: ''
  })
  
  const errors = ref({})
  
  watch(() => props.user, (u) => {
    Object.assign(form, {
      name: u.name,
      gender: u.gender,
      birthday: u.birthday,
      email: u.email,
      role: u.role
    })
  }, { immediate: true })
  
  const submit = async () => {
    errors.value = {}
  
    try {
      await axios.put(`/api/admin/users/${props.user.id}`, form)
      emit('saved')
    } catch (err) {
      if (err?.response?.status === 422) {
        errors.value = err.response.data.errors || {}
  
        if (errors.value.birthday?.[0]?.includes('The birthday field is required')) {
          errors.value.birthday[0] = '生日為必填欄位'
        }
        if (errors.value.gender?.[0]?.includes('The gender field is required')) {
          errors.value.gender[0] = '性別為必填欄位'
        }
        if (errors.value.name?.[0]?.includes('The name field is required')) {
          errors.value.name[0] = '姓名為必填欄位'
        }
        if (errors.value.email?.[0]?.includes('The email field must be a valid email address')) {
          errors.value.email[0] = '請輸入有效的電子郵件地址'
        }
        if (errors.value.role?.[0]?.includes('The selected role is invalid')) {
          errors.value.role[0] = '角色不正確'
        }
      }
    }
  }
  </script>
  
  <style scoped>
  .form-input {
    @apply w-full border rounded px-3 py-2 outline-blue-400 shadow-sm;
  }
  </style>
  