<template>
  <div class="pt-24 max-w-4xl mx-auto py-10 px-6 bg-white shadow-xl rounded-3xl animate-fade-in">
    <h1 class="text-3xl font-bold text-gray-800 mb-8 flex items-center gap-2">
      <Briefcase class="w-6 h-6" /> {{ job ? '編輯職缺' : '新增職缺' }}
    </h1>

    <form @submit.prevent="submitForm" class="space-y-8">
      <!-- 基本資料區 -->
      <section>
        <h2 class="text-xl font-semibold text-gray-700 border-b pb-3 mb-4">基本資料</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="form-label">職缺名稱</label>
            <input v-model="form.title" type="text" class="form-input" />
            <p v-if="errors.title" class="text-red-600 text-sm mt-1 animate-shake">{{ errors.title }}</p>
          </div>
          <div>
            <label class="form-label">公司名稱</label>
            <input v-model="form.company" type="text" class="form-input" />
            <p v-if="errors.company" class="text-red-600 text-sm mt-1 animate-shake">{{ errors.company }}</p>
          </div>
          <div>
            <label class="form-label">工作地點</label>
            <input v-model="form.location" type="text" class="form-input" />
            <p v-if="errors.location" class="text-red-600 text-sm mt-1 animate-shake">{{ errors.location }}</p>
          </div>
          <div>
            <label class="form-label">面試型態</label>
            <select v-model="form.interview_type" class="form-input">
              <option value="individual">個別面試</option>
              <option value="group">團體面試</option>
            </select>
          </div>
          <div>
            <label class="form-label">每場面試時間（分鐘）</label>
            <input v-model.number="form.duration_minutes" type="number" min="30" max="300" step="30" class="form-input" />
          </div>
          <div>
            <label class="form-label">預計招募人數</label>
            <input v-model.number="form.target_applicants" type="number" min="1" max="20" step="1" class="form-input" />
          </div>
        </div>
      </section>

      <!-- 薪資區段 -->
      <section>
        <h2 class="text-xl font-semibold text-gray-700 border-b pb-3 mb-4">薪資資訊</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="flex-1 min-w-[180px]">
            <label class="form-label">薪資下限</label>
            <input
              type="number"
              v-model.number="form.salary_min"
              min="0"
              :max="form.salary_max || undefined"
              step="100"
              class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm"
            />
            <p v-if="errors.salary_min" class="text-red-600 text-sm mt-1 animate-shake">{{ errors.salary_min }}</p>
            <span class="text-sm text-gray-500">{{ (form.salary_min / 1000).toFixed(1) }}k</span>
          </div>
          <div class="flex-1 min-w-[180px]">
            <label class="form-label">薪資上限</label>
            <input
              type="number"
              v-model.number="form.salary_max"
              :min="form.salary_min || 0"
              step="100"
              class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm"
            />
            <p v-if="errors.salary_max" class="text-red-600 text-sm mt-1 animate-shake">{{ errors.salary_max }}</p>
            <span class="text-sm text-gray-500">{{ (form.salary_max / 1000).toFixed(1) }}k</span>
          </div>
          <div>
            <label class="form-label">薪資補充說明</label>
            <select v-model="form.salary_note" class="form-input">
              <option value="面議">面議</option>
              <option value="依經驗調整">依經驗調整</option>
              <option value="保障年薪">保障年薪</option>
              <option value="底薪＋獎金">底薪＋獎金</option>
              <option value="其他">其他</option>
            </select>
          </div>
        </div>
      </section>

      <!-- 其他條件 -->
      <section>
        <h2 class="text-xl font-semibold text-gray-700 border-b pb-3 mb-4">工作條件</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="form-label">職務性質</label>
            <select v-model="form.job_type" class="form-input">
              <option value="全職">全職</option>
              <option value="兼職">兼職</option>
              <option value="派遣">派遣</option>
              <option value="實習">實習</option>
              <option value="約聘">約聘</option>
            </select>
          </div>
          <div>
            <label class="form-label">上班時段</label>
            <select v-model="form.work_schedule" class="form-input">
              <option value="日班">日班</option>
              <option value="晚班">晚班</option>
              <option value="大夜班">大夜班</option>
              <option value="輪班制">輪班制</option>
              <option value="彈性工時">彈性工時</option>
              <option value="其他">其他</option>
            </select>
          </div>
          <div>
            <label class="form-label">經歷需求</label>
            <select v-model="form.experience_required" class="form-input">
              <option value="無經驗可">無經驗可</option>
              <option value="1年">1年</option>
              <option value="2年">2年</option>
              <option value="3年">3年</option>
              <option value="5年以上">5年以上</option>
            </select>
          </div>
          <div>
            <label class="form-label">學歷要求</label>
            <select v-model="form.education_level" class="form-input">
              <option value="不限">不限</option>
              <option value="專科">專科</option>
              <option value="大學">大學</option>
              <option value="碩士">碩士</option>
            </select>
          </div>
          <div class="flex items-center gap-2">
            <input type="checkbox" v-model="form.remote_option" class="form-checkbox rounded text-green-600" />
            <label class="text-gray-700">可遠端工作</label>
          </div>
          <div class="flex items-center gap-2">
            <input type="checkbox" v-model="form.travel_required" class="form-checkbox rounded text-green-600" />
            <label class="text-gray-700">需要出差</label>
          </div>
        </div>
      </section>

      <!-- 職缺內容區 -->
      <section>
        <h2 class="text-xl font-semibold text-gray-700 border-b pb-3 mb-4">職缺說明</h2>
        <div class="space-y-4">
          <div>
            <label class="form-label">工作內容</label>
            <textarea v-model="form.description" rows="4" class="form-textarea"></textarea>
            <p v-if="errors.description" class="text-red-600 text-sm mt-1 animate-shake">{{ errors.description }}</p>
          </div>
          <div>
            <label class="form-label">條件要求</label>
            <textarea v-model="form.requirement" rows="3" class="form-textarea"></textarea>
          </div>
          <div>
            <label class="form-label">福利制度</label>
            <textarea v-model="form.benefits" rows="3" class="form-textarea"></textarea>
          </div>
        </div>
      </section>

      <!-- 聯絡方式 -->
      <section>
        <h2 class="text-xl font-semibold text-gray-700 border-b pb-3 mb-4">聯絡資訊</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div>
            <label class="form-label">聯絡人姓名</label>
            <input v-model="form.contact_person" type="text" class="form-input" />
            <p v-if="errors.contact_person" class="text-red-600 text-sm mt-1 animate-shake">{{ errors.contact_person }}</p>
          </div>
          <div>
            <label class="form-label">聯絡 Email</label>
            <input v-model="form.contact_email" type="email" class="form-input" />
            <p v-if="errors.contact_email" class="text-red-600 text-sm mt-1 animate-shake">{{ errors.contact_email }}</p>
          </div>
          <div>
            <label class="form-label">聯絡電話</label>
            <input v-model="form.contact_phone" type="text" class="form-input" />
            <p v-if="errors.contact_phone" class="text-red-600 text-sm mt-1 animate-shake">{{ errors.contact_phone }}</p>
          </div>
        </div>
      </section>

      <div class="text-right pt-6">
        <button
          type="submit"
          class="px-6 py-3 bg-green-600 text-white font-semibold rounded-xl shadow hover:bg-green-700 transition-all duration-200">
          <Save class="w-5 h-5 inline mr-2" /> 儲存職缺
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Save, Briefcase } from 'lucide-vue-next'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const route = useRoute()
const jobId = route.params.id || null

const form = ref({
  title: '',
  company: '',
  location: '',
  interview_type: 'individual',
  duration_minutes: 30,
  target_applicants: 1,
  salary_min: 0,
  salary_max: 0,
  salary_note: '面議',
  job_type: '全職',
  work_schedule: '日班',
  experience_required: '無經驗可',
  education_level: '不限',
  remote_option: false,
  travel_required: false,
  description: '',
  requirement: '',
  benefits: '',
  contact_person: '',
  contact_email: '',
  contact_phone: ''
})

const errors = ref({})

onMounted(async () => {
  if (jobId) {
    try {
      const res = await axios.get(`/api/jobs/${jobId}`)
      const job = res.data

      // 拆解 contact_info
      let person = '', email = '', phone = ''
      if (job.contact_info) {
        const lines = job.contact_info.split('\n')
        for (const line of lines) {
          if (line.startsWith('姓名: ')) person = line.slice(4).trim()
          if (line.startsWith('Email: ')) email = line.slice(7).trim()
          if (line.startsWith('電話: ')) phone = line.slice(5).trim()
        }
      }

      form.value = {
        ...form.value,
        ...job,
        remote_option: !!job.remote_option,
        travel_required: !!job.travel_required,
        requirements: job.requirement ?? '',
        contact_person: person,
        contact_email: email,
        contact_phone: phone?.toString() ?? '',
      }
    } catch (e) {
      alert('載入職缺失敗')
    }
  }
})


const submitForm = async () => {
  errors.value = {}
  const f = form.value

  if (!f.title) errors.value.title = '職缺名稱為必填'
  if (!f.company) errors.value.company = '公司名稱為必填'
  if (!f.location) errors.value.location = '工作地點為必填'
  if (!f.description) errors.value.description = '工作內容為必填'
  if (!f.contact_person) errors.value.contact_person = '聯絡人為必填'

  const emailValid = f.contact_email && /.+@.+\..+/.test(f.contact_email)
  const phoneValid = f.contact_phone && /^\d{6,15}$/.test(f.contact_phone)

  if (!emailValid && !phoneValid) {
    errors.value.contact_email = '請填寫有效 Email 或電話，至少填寫一個'
    errors.value.contact_phone = '請填寫有效電話或 Email，至少填寫一個'
  }

  if (f.salary_min === 0 || f.salary_max === 0) {
    errors.value.salary_min = '薪資下限不可為 0'
    errors.value.salary_max = '薪資上限不可為 0'
  }

  if (Object.keys(errors.value).length === 0) {
    const payload = { ...f }
    const contactLines = [`姓名: ${f.contact_person}`]
    if (f.contact_email) contactLines.push(`Email: ${f.contact_email}`)
    if (f.contact_phone) contactLines.push(`電話: ${f.contact_phone}`)
    payload.contact_info = contactLines.join('\n')
    delete payload.contact_person
    delete payload.contact_email
    delete payload.contact_phone

    try {
      if (jobId) {
        await axios.put(`/api/jobs/${jobId}`, payload)
        alert('更新成功')
      } else {
        await axios.post('/api/jobs', payload)
        alert('新增成功')
      }
      router.push('/jobs')
    } catch (e) {
      alert('送出失敗')
    }
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
