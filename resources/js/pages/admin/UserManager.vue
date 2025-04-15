<template>
    <div class="p-6">
      <h1 class="text-2xl font-bold mb-4">使用者管理</h1>
  
      <input
        v-model="search"
        @input="fetchUsers"
        type="text"
        placeholder="搜尋姓名或 Email"
        class="border px-4 py-2 rounded w-full mb-4"
      />
  
      <table class="w-full table-auto border-collapse">
        <thead class="bg-gray-200">
          <tr>
            <th class="p-2 border">姓名</th>
            <th class="p-2 border">Email</th>
            <th class="p-2 border">角色</th>
            <th class="p-2 border">性別</th>
            <th class="p-2 border">生日</th>
            <th class="p-2 border">建立時間</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id" class="text-center">
            <td class="border p-2">{{ user.name }}</td>
            <td class="border p-2">{{ user.email }}</td>
            <td class="border p-2">{{ user.role }}</td>
            <td class="border p-2">{{ user.gender || '-' }}</td>
            <td class="border p-2">{{ user.birthday || '-' }}</td>
            <td class="border p-2">{{ formatDate(user.created_at) }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, watch } from 'vue'
  import axios from 'axios'
  
  const users = ref([])
  const search = ref('')
  
  const fetchUsers = async () => {
    const res = await axios.get('/api/admin/users', {
      params: { search: search.value }
    })
    users.value = res.data
  }
  
  const formatDate = (date) => {
    return new Date(date).toLocaleDateString()
  }
  
  onMounted(fetchUsers)
  </script>
  