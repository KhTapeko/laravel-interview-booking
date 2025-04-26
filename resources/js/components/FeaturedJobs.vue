<template>
  <section class="mt-8 pb-16">
    <div v-if="jobs.length === 0" class="text-gray-500 text-center mt-4">
      ❗ 找不到職缺
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      <router-link
        v-for="job in jobs"
        :key="job.id"
        :to="`/jobs/${job.id}`"
        class="p-4 border rounded-lg shadow hover:shadow-lg transform hover:scale-105 transition block"
      >
        <h3 class="text-xl font-bold text-gray-800 flex items-center">
          {{ job.title }}
          <span
            v-if="isNewJob(job.created_at)"
            class="ml-2 text-xs bg-green-200 text-green-800 px-2 py-0.5 rounded-full"
          >
            NEW
          </span>
        </h3>
        <p class="text-gray-500 text-sm">{{ job.company }} · {{ job.location }}</p>
        <p v-if="job.salary_min && job.salary_max" class="text-sm text-gray-600">
          💰 薪資：{{ job.salary_min }} ~ {{ job.salary_max }} 元／月
        </p>
        <p class="text-gray-600 mt-2 line-clamp-3">{{ job.description }}</p>
        <p class="text-sm mt-2 font-medium">
          <span v-if="job.interview_type === 'individual'">👤 單人面試</span>
          <span v-else>👥 團體面試</span>
        </p>
      </router-link>
    </div>
  </section>
</template>

<script setup>
import { defineProps } from 'vue'

const props = defineProps({
  jobs: {
    type: Array,
    required: true
  }
})

function isNewJob(createdAt) {
  const postDate = new Date(createdAt)
  const now = new Date()
  const diffTime = Math.abs(now - postDate)
  const diffDays = diffTime / (1000 * 60 * 60 * 24)
  return diffDays <= 7 // 7天內算新職缺
}
</script>
