<template>
  <div class="pt-24 max-w-3xl mx-auto py-8 px-6 bg-white shadow-md rounded-2xl">
    <h2 class="text-2xl font-semibold mb-6 border-b pb-2 text-gray-700">
      {{ job ? '編輯職缺' : '新增職缺' }}
    </h2>

    <form @submit.prevent="handleSubmit" class="space-y-6">
      <!-- 職缺名稱 -->
      <div>
        <label class="block font-medium text-gray-600 capitalize mb-1">職缺名稱</label>
        <input type="text" v-model="form.title" class="form-input block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm">
      </div>

      <!-- 公司名稱 -->
      <div>
        <label class="block font-medium text-gray-600 capitalize mb-1">公司名稱</label>
        <input type="text" v-model="form.company" class="form-input block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm">
      </div>

      <!-- 工作地點 -->
      <div>
        <label class="block font-medium text-gray-600 capitalize mb-1">工作地點</label>
        <input type="text" v-model="form.location" class="form-input block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm">
      </div>

      <!-- 面試類型 + 面試時間 + 招募人數 -->
      <div class="flex flex-col sm:flex-row flex-wrap gap-4">
        <div class="flex-1 min-w-[180px]">
          <label class="block font-medium text-gray-600 capitalize mb-1">面試類型</label>
          <select v-model="form.interview_type" class="form-select w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm">
            <option value="individual">個人面試</option>
            <option value="group">團體面試</option>
          </select>
        </div>
        <div class="flex-1 min-w-[180px]">
          <label class="block font-medium text-gray-600 capitalize mb-1">每場面試時間（分鐘）</label>
          <input
            type="number"
            v-model.number="form.duration_minutes"
            min="30"
            max="300"
            step="30"
            class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm"
          >
        </div>
        <div class="flex-1 min-w-[180px]">
          <label class="block font-medium text-gray-600 capitalize mb-1">預計招募人數</label>
          <input
            type="number"
            v-model.number="form.target_applicants"
            min="1"
            max="20"
            step="1"
            class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm"
          >
        </div>
      </div>

      <!-- 薪資下限 + 上限 + 補充說明 -->
      <div class="flex flex-col sm:flex-row flex-wrap gap-4">
        <div class="flex-1 min-w-[180px]">
          <label class="block font-medium text-gray-600 capitalize mb-1">薪資上限</label>
          <input
            type="number"
            v-model.number="form.salary_max"
            :min="form.salary_min || 0"
            step="100"
            class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm"
          >
          <span class="text-sm text-gray-500">{{ (form.salary_max / 1000).toFixed(1) }}k</span>
        </div>
        
        <div class="flex-1 min-w-[180px]">
          <label class="block font-medium text-gray-600 capitalize mb-1">薪資下限</label>
          <input
            type="number"
            v-model.number="form.salary_min"
            min="0"
            :max="form.salary_max || undefined"
            step="100"
            class="form-input w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm"
          >
          <span class="text-sm text-gray-500">{{ (form.salary_min / 1000).toFixed(1) }}k</span>
        </div>

        <div class="flex-1 min-w-[180px]">
          <label class="block font-medium text-gray-600 capitalize mb-1">薪資補充說明</label>
          <select v-model="form.salary_note" class="form-select w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm">
            <option value="面議">面議</option>
            <option value="依經驗調整">依經驗調整</option>
            <option value="保障年薪">保障年薪</option>
            <option value="底薪＋獎金">底薪＋獎金</option>
            <option value="其他">其他</option>
          </select>
        </div>
      </div>

      <!-- 職務性質 + 上班時段 -->
      <div class="flex flex-col sm:flex-row flex-wrap gap-4">
        <div class="flex-1 min-w-[180px]">
          <label class="block font-medium text-gray-600 capitalize mb-1">職務性質</label>
          <select v-model="form.job_type" class="form-select w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm">
            <option value="全職">全職</option>
            <option value="兼職">兼職</option>
            <option value="派遣">派遣</option>
            <option value="實習">實習</option>
            <option value="約聘">約聘</option>
          </select>
        </div>
        <div class="flex-1 min-w-[180px]">
          <label class="block font-medium text-gray-600 capitalize mb-1">上班時段</label>
          <select v-model="form.work_schedule" class="form-select w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm">
            <option value="日班">日班</option>
            <option value="晚班">晚班</option>
            <option value="大夜班">大夜班</option>
            <option value="輪班制">輪班制</option>
            <option value="彈性工時">彈性工時</option>
            <option value="其他">其他</option>
          </select>
        </div>
      </div>

      <!-- 可遠端工作 + 需要出差 -->
      <div class="flex flex-col sm:flex-row flex-wrap gap-4">
        <label class="inline-flex items-center flex-1 min-w-[180px]">
          <input type="checkbox" v-model="form.remote_option" class="form-checkbox rounded text-blue-600">
          <span class="ml-2 text-gray-700">可遠端工作</span>
        </label>
        <label class="inline-flex items-center flex-1 min-w-[180px]">
          <input type="checkbox" v-model="form.travel_required" class="form-checkbox rounded text-blue-600">
          <span class="ml-2 text-gray-700">需要出差</span>
        </label>
      </div>

      <!-- 條件要求 -->
      <div>
        <label class="block font-medium text-gray-600 capitalize mb-1">條件要求</label>
        <textarea v-model="form.requirement" rows="4" class="form-textarea block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm"></textarea>
      </div>

      <!-- 經歷需求 + 學歷要求 -->
      <div class="flex flex-col sm:flex-row flex-wrap gap-4">
        <div class="flex-1 min-w-[180px]">
          <label class="block font-medium text-gray-600 capitalize mb-1">經歷需求</label>
          <select v-model="form.experience_required" class="form-select w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm">
            <option value="無經驗可">無經驗可</option>
            <option value="1年">1年</option>
            <option value="2年">2年</option>
            <option value="3年">3年</option>
            <option value="5年以上">5年以上</option>
          </select>
        </div>
        <div class="flex-1 min-w-[180px]">
          <label class="block font-medium text-gray-600 capitalize mb-1">學歷要求</label>
          <select v-model="form.education_level" class="form-select w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm">
            <option value="不限">不限</option>
            <option value="專科">專科</option>
            <option value="大學">大學</option>
            <option value="碩士">碩士</option>
          </select>
        </div>
      </div>

      <!-- 福利制度 -->
      <div>
        <label class="block font-medium text-gray-600 capitalize mb-1">福利制度</label>
        <textarea v-model="form.benefits" rows="4" class="form-textarea block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm"></textarea>
      </div>

      <!-- 聯絡資訊 -->
      <div>
        <label class="block font-medium text-gray-600 capitalize mb-1">聯絡資訊</label>
        <textarea v-model="form.contact_info" rows="3" class="form-textarea block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm"></textarea>
      </div>

      <div class="text-right">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow transition duration-200">
          {{ job ? '儲存修改' : '新增職缺' }}
        </button>
      </div>
    </form>
  </div>
</template>


<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  job: Object,
});

const defaultForm = {
  title: '',
  company: '',
  location: '',
  description: '',
  interview_type: 'individual',
  duration_minutes: 30,
  target_applicants: 1,
  salary_min: null,
  salary_max: null,
  salary_note: '面議',
  requirement: '',
  experience_required: '無經驗可',
  education_level: '不限',
  benefits: '',
  contact_info: '',
  job_type: '全職',
  work_schedule: '日班',
  remote_option: false,
  travel_required: false,
};

const form = ref({ ...defaultForm });

watch(
  () => props.job,
  (newJob) => {
    if (newJob) {
      form.value = { ...newJob };
    }
  },
  { immediate: true }
);

function handleSubmit() {
  console.log(form.value);
  // TODO: 呼叫 API 送出資料
}
</script>
