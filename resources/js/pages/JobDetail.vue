<template>
  <section v-if="job" class="max-w-3xl mx-auto mt-24 p-6 bg-white rounded shadow">
    <!-- 標題 -->
    <h1 class="text-3xl font-bold mb-2">{{ job.title }}</h1>

    <!-- 公司與地點 -->
    <p class="text-gray-600 mb-2">{{ job.company }} · {{ job.location }}</p>

    <!-- 面試類型 -->
    <p class="text-sm mb-2">
      <span v-if="job.interview_type === 'individual'">👤 單人面試</span>
      <span v-else>👥 團體面試</span>
    </p>

    <!-- 💰 薪資範圍 -->
    <p class="text-sm mb-4 text-gray-700">
      💰 薪資：{{ job.salary_min }} ~ {{ job.salary_max }} 元／月
    </p>

    <!-- 📝 工作內容 -->
    <div class="text-gray-800 whitespace-pre-line mb-6">
      {{ job.description }}
    </div>

    <!-- ✅ 條件要求 -->
    <div class="mb-6">
      <h2 class="text-lg font-semibold text-gray-800 mb-1">條件要求</h2>
      <p class="text-gray-700 whitespace-pre-line text-sm">{{ job.requirement }}</p>
    </div>

    <!-- 🎁 福利制度 -->
    <div class="mb-6">
      <h2 class="text-lg font-semibold text-gray-800 mb-1">福利制度</h2>
      <p class="text-gray-700 whitespace-pre-line text-sm">{{ job.benefits }}</p>
    </div>

    <!-- 📞 聯絡方式 -->
    <div class="mb-6">
      <h2 class="text-lg font-semibold text-gray-800 mb-1">聯絡方式</h2>
      <p class="text-gray-700 whitespace-pre-line text-sm">{{ job.contact_info }}</p>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRoute } from 'vue-router'

const route = useRoute()
const job = ref(null)

onMounted(async () => {
  const res = await axios.get(`/api/jobs/${route.params.id}`)
  job.value = res.data
})
</script>
