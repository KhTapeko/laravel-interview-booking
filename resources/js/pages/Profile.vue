<template>
    <div class="pt-24">
      <div class="max-w-3xl mx-auto py-10 px-4">
        <h1 class="text-2xl font-bold mb-6 flex items-center gap-2">
          <UserIcon class="w-6 h-6 text-blue-500" />
          個人檔案
        </h1>
  
        <div class="bg-white shadow rounded-lg p-6 space-y-4">
          <div class="flex items-center gap-3">
            <UserIcon class="w-5 h-5 text-gray-500" />
            <div>
              <label class="text-gray-600 font-medium">姓名：</label>
              <div class="text-lg">{{ user.name }}</div>
            </div>
          </div>
  
          <div class="flex items-center gap-3">
            <VenetianMask class="w-5 h-5 text-gray-500" />
            <div>
              <label class="text-gray-600 font-medium">性別：</label>
              <div class="text-lg">{{ formatGender(user.gender) }}</div>
            </div>
          </div>
  
          <div class="flex items-center gap-3">
            <CalendarIcon class="w-5 h-5 text-gray-500" />
            <div>
              <label class="text-gray-600 font-medium">生日：</label>
              <div class="text-lg">{{ user.birthday || '未填寫' }}</div>
            </div>
          </div>
  
          <div class="flex items-center gap-3">
            <MailIcon class="w-5 h-5 text-gray-500" />
            <div>
              <label class="text-gray-600 font-medium">Email：</label>
              <div class="text-lg">{{ user.email }}</div>
            </div>
          </div>
  
          <div class="flex items-center gap-3">
            <ShieldCheckIcon class="w-5 h-5 text-gray-500" />
            <div>
              <label class="text-gray-600 font-medium">角色：</label>
              <div class="text-lg capitalize">{{ user.role }}</div>
            </div>
          </div>
        </div>
  
        <!-- 📌 預留未來串接職缺／預約紀錄區塊 -->
        <div class="mt-10">
          <h2 class="text-xl font-semibold mb-2">我的預約 / 申請職缺</h2>
          <p class="text-gray-500">（此區塊將串接 user &lt;-&gt; jobs 關聯，尚未實作）</p>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { onMounted } from 'vue'
  import { useAuthStore } from '@/stores/auth'
  import { storeToRefs } from 'pinia'
  
  // ✅ Lucide icons（推薦使用 Lucide，全 icon 都有 tailwind 支援）
  import { UserIcon, CalendarIcon, MailIcon, ShieldCheckIcon, VenetianMask } from 'lucide-vue-next'
  
  const auth = useAuthStore()
  const { user } = storeToRefs(auth)
  
  onMounted(() => {
    auth.loadFullProfile()
  })
  
  function formatGender(gender) {
    switch (gender) {
      case 'female':
        return '女 (female)'
      case 'male':
        return '男 (male)'
      case 'other':
        return '其他 (other)'
      default:
        return '未填寫'
    }
  }
  </script>
  