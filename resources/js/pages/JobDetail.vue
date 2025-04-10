<template>
    <div class="container mx-auto px-4 py-10">
      <h1 class="text-2xl font-bold text-blue-700 mb-4">職缺詳細頁</h1>
      <p class="text-gray-700">這是職缺 ID：{{ jobId }}</p>
    </div>
  </template>
  
  <script setup>
  import { useRoute } from 'vue-router'
  const route = useRoute()
  const jobId = route.params.id
  </script>
  