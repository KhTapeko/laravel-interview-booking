<template>
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
  </template>
  
  <script setup>
  import { storeToRefs } from 'pinia'
  import { useAuthStore } from '@/stores/auth'
  import {
    UserIcon,
    CalendarIcon,
    MailIcon,
    ShieldCheckIcon,
    VenetianMask
  } from 'lucide-vue-next'
  
  const { user } = storeToRefs(useAuthStore())
  
  function formatGender(gender) {
    switch (gender) {
      case 'female': return '女 (female)'
      case 'male': return '男 (male)'
      case 'other': return '其他 (other)'
      default: return '未填寫'
    }
  }
  </script>
  