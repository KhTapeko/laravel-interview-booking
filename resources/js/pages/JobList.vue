<template>
  <div class="max-w-6xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">所有職缺</h1>

    <!-- 搜尋欄 -->
    <div class="mb-6">
      <div class="relative">
        <input
          v-model="search"
          type="text"
          placeholder=" 搜尋職缺或公司名稱..."
          class="w-full pl-10 pr-4 py-3 border rounded-full shadow-sm focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition placeholder-gray-400 text-gray-700"
        />
        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M18 10a8 8 0 11-16 0 8 8 0 0116 0z" />
          </svg>
        </div>
      </div>
    </div>

    <h2 class="text-2xl font-bold text-gray-700 mt-8">✨ 所有職缺</h2>
    <FeaturedJobs :jobs="jobs" />
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'
import FeaturedJobs from '@/components/FeaturedJobs.vue'

const jobs = ref([])
const search = ref('')

// 抓資料 function
async function fetchJobs(keyword = '') {
  try {
    const res = await axios.get('/api/jobs', {
      params: { search: keyword }
    })
    jobs.value = res.data
  } catch (err) {
    console.error('載入職缺失敗', err)
  }
}

// 初次載入
onMounted(() => {
  fetchJobs()
})

// 搜尋時重新抓資料
watch(search, (newKeyword) => {
  fetchJobs(newKeyword)
})
</script>
