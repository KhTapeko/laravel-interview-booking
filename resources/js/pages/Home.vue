<template>
  <div class="flex flex-col min-h-screen">

    <!-- 固定導覽列 -->
    <header class="bg-white shadow fixed top-0 left-0 right-0 z-50">
      <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-blue-600">InterviewBooking</h1>
        <div class="space-x-4">
          <RouterLink to="/login" class="text-gray-600 hover:text-blue-600">登入</RouterLink>
          <RouterLink to="/register" class="text-gray-600 hover:text-blue-600">註冊</RouterLink>
        </div>
      </div>
    </header>

    <!-- 主內容區 -->
    <main class="flex-grow pt-20 bg-gray-50"> <!-- pt-20 是為了避開 header -->
      <!-- 主視覺區塊＋搜尋 -->
      <section
        class="relative bg-cover bg-center bg-no-repeat"
        style="background-image: url('/images/bg-dev.jpg')"
      >
        <div class="bg-white/80 py-16 shadow-md">
          <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold text-blue-700 mb-4">找到你的下一份理想工作</h2>
            <p class="text-lg text-gray-600 mb-6">專為企業與求職者打造的面試預約平台</p>

            <!-- 搜尋欄元件 -->
            <JobSearchBar v-model="search" @submit="onSearchSubmit" />
          </div>
        </div>
      </section>

      <!-- 精選職缺卡片 -->
      <FeaturedJobs :search="search" />

    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-sm pt-0">
      <!-- 加入我們區塊 -->
      <section class="bg-cover bg-center" style="background-image: url('/images/green-image.jpg');">
        <div class="bg-black/50">
          <div class="container mx-auto text-center text-white py-10 px-4">
            <h3 class="text-2xl md:text-3xl font-bold">加入我們，享受工作與生活的平衡</h3>
            <p class="mt-3 text-base md:text-lg">我們重視每一位工程師的成長與生活品質</p>
          </div>
        </div>
      </section>
      <section class="bg-gray-800">
        <div class="container mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-6 py-10">
          <!-- 關於我們 -->
          <div>
            <h5 class="text-white font-semibold mb-2">關於我們</h5>
            <ul class="space-y-1">
              <li><RouterLink to="/" class="hover:underline">企業網站</RouterLink></li>
              <li><RouterLink to="/" class="hover:underline">新聞中心</RouterLink></li>
              <li><RouterLink to="/" class="hover:underline">合作提案</RouterLink></li>
              <li><RouterLink to="/" class="hover:underline">加入團隊</RouterLink></li>
              <li><RouterLink to="/" class="hover:underline">隱私中心</RouterLink></li>
            </ul>
          </div>

          <!-- 服務項目 -->
          <div>
            <h5 class="text-white font-semibold mb-2">服務項目</h5>
            <ul class="space-y-1">
              <li><RouterLink to="/" class="hover:underline">所有服務</RouterLink></li>
              <li><RouterLink to="/" class="hover:underline">LINE官方帳號</RouterLink></li>
              <li><RouterLink to="/" class="hover:underline">FaceBook官方帳號</RouterLink></li>
              <li><RouterLink to="/" class="hover:underline">求職服務</RouterLink></li>
              <li><RouterLink to="/" class="hover:underline">意見回饋</RouterLink></li>
            </ul>
          </div>

          <!-- 客服 -->
          <div>
            <h5 class="text-white font-semibold mb-2">客服專線</h5>
            <ul class="space-y-1">
              <li>02-1234-5678 轉9</li>
              <li>02-8765-4321 轉0</li>
              <li>週一至週五 24小時</li>
              <li>拒絕接聽</li>
            </ul>
          </div>

          <!-- 徵才服務 -->
          <div>
            <h5 class="text-white font-semibold mb-2">徵才服務</h5>
            <ul class="space-y-1">
              <li>意見回饋</li>
              <li>高雄：07-123-4567</li>
              <li>台中：04-123-4567</li>
              <li>台北：04-123-4567</li>
              <li>廣告行銷</li>
            </ul>
          </div>
        </div>
      </section>
    </footer>

  </div>
</template>


<script setup>
import { ref } from 'vue'
import JobSearchBar from '@/components/JobSearchBar.vue'
import FeaturedJobs from '@/components/FeaturedJobs.vue'

const search = ref('')
const featuredRef = ref()

// ✅ 點搜尋按鈕時，主動呼叫 fetchJobs
const onSearchSubmit = () => {
  if (featuredRef.value?.fetchJobs) {
    featuredRef.value.fetchJobs(search.value)
  }
}
</script>