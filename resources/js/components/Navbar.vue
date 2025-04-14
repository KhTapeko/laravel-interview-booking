<template>
  <!-- 固定導覽列 -->
  <header class="bg-white shadow fixed top-0 left-0 right-0 z-50">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">

      <!-- Logo / 首頁 -->
      <RouterLink to="/" class="text-xl font-bold text-blue-600">
        InterviewBooking
      </RouterLink>

      <!-- 右側區塊：依登入狀態切換 -->
      <div v-if="auth.user" class="flex items-center space-x-4">
        <span class="text-gray-700">
          {{ auth.user.name }} ({{ auth.user.role }})
        </span>
        <button
          @click="auth.logout"
          class="text-red-600 hover:text-red-700"
        >
          登出
        </button>
      </div>

      <div v-else class="space-x-4">
        <RouterLink
          to="/login"
          class="text-gray-600 hover:text-blue-600"
        >
          登入
        </RouterLink>
        <RouterLink
          to="/register"
          class="text-gray-600 hover:text-blue-600"
        >
          註冊
        </RouterLink>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/stores/auth.js';
const auth = useAuthStore();
</script>
