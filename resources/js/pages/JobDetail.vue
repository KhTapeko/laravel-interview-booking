<template>
  <section v-if="job" class="max-w-3xl mx-auto px-4 pt-28 pb-36">
    <!-- 標題與公司資訊 -->
    <div class="mb-4">
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1">
        <h1 class="text-3xl font-bold text-blue-800">{{ job.title }}</h1>
        <span class="text-sm text-gray-500 sm:ml-4 whitespace-nowrap">
          {{ new Date(job.updated_at).toLocaleDateString('zh-TW', { month: '2-digit', day: '2-digit' }) }} 更新
        </span>
      </div>
      <p class="text-gray-600 text-base mt-1">{{ job.company }} · {{ job.location }}</p>
    </div>

    <!-- 薪資區塊 -->
    <div class="mb-4">
      <p class="text-green-600 font-semibold text-xl">
        💰 {{ job.salary_min }} ~ {{ job.salary_max }} 元
      </p>
      <p class="text-sm text-gray-500">{{ job.salary_note }}</p>
    </div>

    <!-- 標籤列 -->
    <div class="flex flex-wrap gap-2 mb-6">
      <span class="bg-blue-100 text-blue-700 text-sm px-3 py-1 rounded-full">
        {{ job.interview_type === 'individual' ? '👤 單人面試' : '👥 團體面試' }}
      </span>
      <span class="bg-gray-100 text-gray-700 text-sm px-3 py-1 rounded-full">
        🕒 面試時間約：{{ job.duration_minutes }} 分鐘
      </span>
      <span class="bg-gray-100 text-gray-700 text-sm px-3 py-1 rounded-full">
        🎯 招募人數：{{ job.target_applicants ?? '未設定' }}
      </span>
      <span class="bg-yellow-100 text-yellow-700 text-sm px-3 py-1 rounded-full">
        📥 報名人數：{{ applicantRange }}
      </span>
    </div>

    <!-- 內容分段區 -->
    <div class="space-y-8 text-gray-800 leading-relaxed">
      <!-- 工作內容 -->
      <div>
        <h2 class="text-xl font-semibold text-blue-700 mb-2">💼 工作內容</h2>
        <p class="whitespace-pre-line mb-3">{{ job.description }}</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-gray-700">
          <div>📌 職務性質：{{ job.job_type }}</div>
          <div>🕘 上班時段：{{ job.work_schedule ?? '未提供說明' }}</div>
          <div>💻 可遠端：{{ job.remote_option ? '✅ 是' : '❌ 否' }}</div>
          <div>✈️ 需出差：{{ job.travel_required ? '✅ 是' : '❌ 否' }}</div>
        </div>
      </div>

      <!-- 條件要求 -->
      <div>
        <h2 class="text-xl font-semibold text-blue-700 mb-2">🎯 條件要求</h2>
        <p class="whitespace-pre-line mb-3">{{ job.requirement }}</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-gray-700">
          <div>📌 經歷需求：{{ job.experience_required }}</div>
          <div>📌 學歷要求：{{ job.education_level }}</div>
        </div>
      </div>

      <!-- 福利制度 -->
      <div>
        <h2 class="text-xl font-semibold text-blue-700 mb-2">🎁 福利制度</h2>
        <p class="whitespace-pre-line">{{ job.benefits }}</p>
      </div>

      <!-- 聯絡資訊 -->
      <div>
        <h2 class="text-xl font-semibold text-blue-700 mb-2">📞 聯絡方式</h2>
        <p class="whitespace-pre-line">{{ job.contact_info }}</p>
      </div>
    </div>
  </section>

  <!-- 我要應徵 / 編輯 / 刪除 按鈕（固定底部） -->
  <div class="fixed bottom-0 inset-x-0 bg-white shadow-lg p-4 z-50 border-t">
    <div class="max-w-3xl mx-auto flex gap-4 items-center">
      <!-- 員工或管理員可以看到三個按鈕 -->
      <template v-if="userRole === 'admin' || userRole === 'employee'">
        <div class="flex gap-3 w-full">
          <!-- 📝 我要應徵 -->
          <button
            class="basis-4/5 py-3 px-6 rounded-xl text-white font-semibold text-base
                  bg-gradient-to-r from-blue-500 to-indigo-600 hover:shadow-lg hover:scale-[1.02] active:scale-[0.98] transition"
            :disabled="hasApplied || !canApply"
            @click="applyJob"
          >
            <template v-if="hasApplied">✅ 已應徵</template>
            <template v-else>📝 我要應徵</template>
          </button>

          <!-- ✏️ 編輯 -->
          <button
            class="basis-1/10 py-3 px-4 rounded-xl text-gray-700 font-semibold text-sm
                  bg-gray-100 hover:bg-gray-200 hover:shadow-sm transition"
            @click="editJob"
          >
            編輯
          </button>

          <!-- 🗑 刪除 -->
          <button
            class="basis-1/10 py-3 px-4 rounded-xl text-red-600 font-semibold text-sm
                  bg-red-100 hover:bg-red-200 hover:shadow-sm transition"
            @click="deleteJob"
          >
            刪除
          </button>
        </div>
      </template>

      <!-- 應徵者僅能看到應徵按鈕 -->
      <template v-else>
        <button
          class="w-full flex items-center justify-center gap-2 py-3 px-6 rounded-full text-white text-lg font-semibold 
                bg-gradient-to-r from-blue-500 to-indigo-600 shadow-md hover:shadow-lg transition-all duration-200
                hover:scale-[1.02] active:scale-[0.98]"
          :disabled="hasApplied || !canApply"
          @click="applyJob"
        >
          <template v-if="hasApplied">✅ 已應徵</template>
          <template v-else>📝 我要應徵</template>
        </button>
      </template>
    </div>
  </div>

</template>


<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth.js'

const route = useRoute()
const router = useRouter()
const auth = useAuthStore()
const user = auth.user
const userRole = user?.role

const job = ref(null)
const hasApplied = ref(false)

onMounted(async () => {
  const res = await axios.get(`/api/jobs/${route.params.id}`)
  job.value = res.data
  hasApplied.value = res.data.has_applied === true
})

const applicantRange = computed(() => {
  const count = job.value?.applicants_count ?? 0
  if (count === 0) return '目前無人應徵'
  if (count <= 5) return '1–5 人'
  if (count <= 10) return '6–10 人'
  if (count <= 20) return '11–20 人'
  return '20 人以上'
})

const canApply = computed(() => {
  return ['candidate', 'employee'].includes(user?.role)
})

const applyJob = async () => {
  if (!user?.id) {
    alert('請先登入')
    return router.push('/login')
  }

  try {
    await axios.post(`/api/jobs/${route.params.id}/apply`)
    hasApplied.value = true
    alert('應徵成功！')
  } catch (err) {
    alert(err.response?.data?.message || '應徵失敗')
  }
}

const editJob = () => {
  router.push(`/jobs/${route.params.id}/edit`)
}

const deleteJob = async () => {
  if (!confirm('確定要刪除此職缺嗎？')) return
  try {
    await axios.delete(`/api/jobs/${route.params.id}`)
    alert('刪除成功')
    router.push('/jobs')
  } catch (err) {
    alert(err.response?.data?.message || '刪除失敗')
  }
}
</script>

<style scoped>
.form-label {
  @apply block text-sm font-medium text-gray-700 mb-1;
}
.form-input {
  @apply w-full rounded-xl border border-gray-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition;
}
.form-textarea {
  @apply w-full rounded-xl border border-gray-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition;
}
@keyframes shake {
  0%, 100% { transform: translateX(0); }
  20%, 60% { transform: translateX(-5px); }
  40%, 80% { transform: translateX(5px); }
}
.animate-shake {
  animation: shake 0.3s ease;
}
.animate-fade-in {
  animation: fade-in 0.5s ease forwards;
  opacity: 0;
}
@keyframes fade-in {
  to { opacity: 1; }
}
</style>
