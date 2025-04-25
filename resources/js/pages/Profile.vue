<template>
  <div class="pt-24">
    <div class="max-w-3xl mx-auto py-10 px-4">
      <h1 class="text-2xl font-bold mb-6 flex items-center gap-2">
        <UserIcon class="w-6 h-6 text-blue-500" />
        個人檔案
      </h1>

      <!-- ✅ 動態切換元件 -->
      <component :is="isEditing ? ProfileEdit : ProfileView" @done="handleDone" />

      <div class="mt-6 flex gap-4">
        <button
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
          @click="isEditing = !isEditing"
        >
          {{ isEditing ? '取消編輯' : '✏️ 修改個人資料' }}
        </button>

        <button
          class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
          @click="confirmDelete"
        >
          🗑 刪除帳號
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { storeToRefs } from 'pinia'
import axios from '@/bootstrap'

import ProfileView from '../components/ProfileView.vue'
import ProfileEdit from '../components/ProfileEdit.vue'

import { UserIcon } from 'lucide-vue-next'

const auth = useAuthStore()
const { user } = storeToRefs(auth)

onMounted(() => {
  auth.loadFullProfile()
})

const isEditing = ref(false)

function handleDone() {
  isEditing.value = false
  auth.loadFullProfile()
}

const confirmDelete = async () => {
  if (!confirm('確定要刪除帳號嗎？此操作無法復原。')) return

  try {
    await axios.delete('/profile/delete') // ✅ Laravel 已內含 logout 與刪除邏輯
    auth.user = null
    alert('帳號已刪除')
    window.location.href = '/'
  } catch (err) {
    // ✅ 顯示 Laravel 後端傳來的錯誤訊息（message）
    const msg = err.response?.data?.message || '刪除失敗，請稍後再試'
    alert(msg)
    console.error(err)
  }
}


</script>
