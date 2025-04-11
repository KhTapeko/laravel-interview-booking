<template>
  <section class="mt-8 pb-16">
    <h2 class="text-2xl font-bold text-gray-700 mb-4">✨ 精選職缺</h2>

    <div v-if="jobs.length === 0" class="text-gray-500 text-center mt-4">
      ❗ 找不到職缺
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <router-link
        v-for="job in jobs"
        :key="job.id"
        :to="`/jobs/${job.id}`"
        class="p-4 border rounded-lg shadow hover:shadow-md transition block"
      >
        <h3 class="text-xl font-bold text-gray-800">{{ job.title }}</h3>
        <p class="text-gray-500 text-sm">{{ job.company }} · {{ job.location }}</p>
        <p class="text-gray-600 mt-2 line-clamp-3">{{ job.description }}</p>
        <p class="text-sm mt-2 font-medium">
          <span v-if="job.interview_type === 'individual'">👤 單人面試</span>
          <span v-else>👥 團體面試</span>
        </p>
      </router-link>
    </div>
  </section>
</template>

<script>
import { ref, watch, toRef } from 'vue'
import axios from 'axios'

export default {
  props: {
    search: String
  },
  setup(props, { expose }) {
    const jobs = ref([])

    const fetchJobs = async (keyword = '') => {
      try {
        const res = await axios.get('/api/jobs', {
          params: { search: keyword }
        })
        jobs.value = res.data
      } catch (err) {
        console.error('職缺載入失敗', err)
      }
    }

    const searchRef = toRef(props, 'search')

    // 自動搜尋（即時輸入）
    watch(searchRef, (newKeyword) => {
      fetchJobs(newKeyword)
    }, { immediate: true })

    // ✅ 將 fetchJobs 方法暴露給父層使用
    expose({
      fetchJobs
    })

    return {
      jobs
    }
  }
}
</script>
