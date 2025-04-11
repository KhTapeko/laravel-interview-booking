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

<template>
  <section v-if="job" class="max-w-3xl mx-auto p-6">
    <h1 class="text-3xl font-bold mb-2">{{ job.title }}</h1>
    <p class="text-gray-600 mb-2">{{ job.company }} · {{ job.location }}</p>
    <p class="text-sm mb-4">
      <span v-if="job.interview_type === 'individual'">👤 單人面試</span>
      <span v-else>👥 團體面試</span>
    </p>
    <div class="text-gray-800 whitespace-pre-line">
      {{ job.description }}
    </div>
  </section>
</template>
