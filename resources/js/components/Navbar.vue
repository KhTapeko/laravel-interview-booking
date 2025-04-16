<template>
  <!-- 固定導覽列 -->
  <header class="bg-white shadow fixed top-0 left-0 right-0 z-50">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">

      <!-- Logo / 首頁 -->
      <RouterLink to="/" class="text-xl font-bold text-blue-600">
        InterviewBooking
      </RouterLink>

      <!-- 手機版選單按鈕 -->
      <button @click="toggleMenu" class="md:hidden">
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>

      <!-- 桌面版導覽列內容 -->
      <div class="hidden md:flex items-center space-x-4">
        <template v-if="!auth.user">
          <RouterLink to="/login" class="text-gray-600 hover:text-blue-600">登入</RouterLink>
          <RouterLink to="/register" class="text-gray-600 hover:text-blue-600">註冊</RouterLink>
        </template>
        <template v-else>
          <RouterLink v-if="isCandidate" to="/my/applications" class="text-gray-700 hover:text-blue-600">我的申請</RouterLink>
          <RouterLink v-if="isEmployee" to="/employee/interviews" class="text-gray-700 hover:text-blue-600">面試管理</RouterLink>
          <RouterLink v-if="isAdmin" to="/admin/users" class="text-gray-700 hover:text-blue-600">用戶管理</RouterLink>
          <RouterLink v-if="isAdmin" to="/admin/jobs" class="text-gray-700 hover:text-blue-600">職缺管理</RouterLink>
          <RouterLink v-if="isAdmin" to="/admin/timeslots" class="text-gray-700 hover:text-blue-600">時段管理</RouterLink>

          <RouterLink to="/Profile" class="text-sm text-gray-500 hover:text-blue-600">
            {{ auth.user.name }} ({{ auth.user.role }})
          </RouterLink>
          <button @click="auth.logout" class="text-red-600 hover:text-red-700">登出</button>
        </template>
      </div>
    </div>

    <!-- 手機版展開導覽列（美化） -->
    <transition name="fade">
      <div
        v-if="isMenuOpen"
        class="md:hidden flex flex-col items-center space-y-3 px-6 pb-6 pt-4 bg-white shadow-lg border-t border-gray-200 rounded-b-lg z-50 text-center"
      >
        <template v-if="!auth.user">
          <RouterLink to="/login" class="text-gray-700 hover:text-blue-600 font-medium">登入</RouterLink>
          <RouterLink to="/register" class="text-gray-700 hover:text-blue-600 font-medium">註冊</RouterLink>
        </template>

        <template v-else>
          <!-- 使用者名子 + 身份 -->
          <RouterLink to="/Profile" class="text-sm text-gray-500 hover:text-blue-600">
            {{ auth.user.name }} ({{ auth.user.role }})
          </RouterLink>

          <!-- 功能選單 -->
          <RouterLink v-if="isCandidate" to="/my/applications" class="text-gray-700 hover:text-blue-600 font-medium">我的申請</RouterLink>
          <RouterLink v-if="isEmployee" to="/employee/interviews" class="text-gray-700 hover:text-blue-600 font-medium">面試管理</RouterLink>
          <RouterLink v-if="isAdmin" to="/admin/users" class="text-gray-700 hover:text-blue-600 font-medium">用戶管理</RouterLink>
          <RouterLink v-if="isAdmin" to="/admin/jobs" class="text-gray-700 hover:text-blue-600 font-medium">職缺管理</RouterLink>
          <RouterLink v-if="isAdmin" to="/admin/timeslots" class="text-gray-700 hover:text-blue-600 font-medium">時段管理</RouterLink>

          <!-- 登出 -->
          <button @click="auth.logout" class="text-red-600 hover:text-red-700 font-medium pt-2">登出</button>
        </template>
      </div>
    </transition>
  </header>
</template>

<script setup>
import { computed, ref } from 'vue'
import { useAuthStore } from '@/stores/auth.js'

const auth = useAuthStore()
const user = computed(() => auth.user)

const isAdmin = computed(() => user.value?.role === 'admin')
const isEmployee = computed(() => user.value?.role === 'employee')
const isCandidate = computed(() => user.value?.role === 'candidate')

const isMenuOpen = ref(false)
const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value
}
</script>

<style scoped>
@media (min-width: 768px) {
  .md\:flex {
    display: flex !important;
  }
  .md\:hidden {
    display: none !important;
  }
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
