<template>
  <div class="p-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800 border-b pb-2 flex items-center">
      👥 使用者管理
    </h1>

    <div class="relative mb-4">
      <input
        v-model="search"
        @input="fetchUsers"
        type="text"
        placeholder="搜尋姓名或 Email"
        class="border px-4 py-2 pl-10 rounded-lg w-full shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
      />
      <div class="absolute left-3 top-2.5 text-gray-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
        </svg>
      </div>
    </div>

    <table class="w-full table-auto border-collapse text-sm text-gray-700">
      <thead class="bg-gray-100 text-gray-600">
        <tr>
          <th class="p-2 border">姓名</th>
          <th class="p-2 border">Email</th>
          <th class="p-2 border">角色</th>
          <th class="p-2 border">性別</th>
          <th class="p-2 border">生日</th>
          <th class="p-2 border">建立時間</th>
          <th class="p-2 border">操作</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        <template v-for="(user, index) in users" :key="user.id">
          <tr
            @click="editUser(user.id)"
            class="hover:bg-blue-50 cursor-pointer"
            :class="{ 'bg-gray-50': index % 2 === 0 }"
          >
            <td class="border p-2">{{ user.name }}</td>
            <td class="border p-2">{{ user.email }}</td>
            <td class="border p-2">{{ user.role }}</td>
            <td class="border p-2">{{ user.gender || '-' }}</td>
            <td class="border p-2">{{ user.birthday || '-' }}</td>
            <td class="border p-2">{{ formatDate(user.created_at) }}</td>
            <td class="border p-2">
              <button @click.stop="editUser(user.id)" class="text-blue-600 hover:underline mr-2">
                {{ editingId === user.id ? '收合' : '編輯' }}
              </button>
              <button @click.stop="deleteUser(user.id)" class="text-red-600 hover:underline">刪除</button>
            </td>
          </tr>

          <UserEditor
            v-if="editingId === user.id"
            :user="user"
            @close="editingId = null"
            @saved="refreshAfterEdit"
          />
        </template>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import UserEditor from '@/components/UserEditor.vue'

const users = ref([])
const search = ref('')
const editingId = ref(null)

const fetchUsers = async () => {
  const res = await axios.get('/api/admin/users', {
    params: { search: search.value }
  })
  users.value = res.data
}

const deleteUser = async (id) => {
  if (!confirm('確定要刪除這位使用者？')) return

  try {
    await axios.delete(`/api/admin/users/${id}`)
    alert('✅ 使用者已成功刪除')
    await fetchUsers()
  } catch (err) {
    const message = err?.response?.data?.message || '❌ 刪除失敗，請稍後再試'
    alert(message)
    console.error('[刪除使用者錯誤]', err)
  }
}


const editUser = (id) => {
  editingId.value = editingId.value === id ? null : id
}

const refreshAfterEdit = async () => {
  await fetchUsers()
  editingId.value = null
}

const formatDate = (date) => new Date(date).toLocaleDateString()

onMounted(fetchUsers)
</script>

<style scoped>
</style>
