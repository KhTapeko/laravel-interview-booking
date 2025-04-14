<template>
  <main class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-50">
    <div
      class="w-full max-w-md p-8 bg-white/80 backdrop-blur rounded-2xl shadow-lg"
      :initial="{ opacity: 0, y: 20 }"
      :animate="{ opacity: 1, y: 0 }"
      transition="{ duration: 0.4 }"
    >
      <h1 class="text-2xl font-bold text-center text-blue-600 mb-6">
        帳號登入
      </h1>

      <form @submit.prevent="handleSubmit" class="space-y-5">
        <!-- Email -->
        <div>
          <label class="block text-sm mb-1 font-medium text-gray-700">Email</label>
          <input
            v-model.trim="email"
            type="email"
            placeholder="you@example.com"
            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            required
          />
        </div>

        <!-- Password -->
        <div>
          <label class="block text-sm mb-1 font-medium text-gray-700">密碼</label>
          <div class="relative">
            <input
              :type="showPwd ? 'text' : 'password'"
              v-model="password"
              placeholder="••••••••"
              class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 pr-10"
              required
            />
            <button
              type="button"
              class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700"
              @click="showPwd = !showPwd"
            >
              <Eye v-if="showPwd" class="w-5 h-5" />
              <EyeOff v-else class="w-5 h-5" />
            </button>
          </div>
        </div>

        <!-- Error -->
        <p v-if="error" class="text-red-600 text-center">{{ error }}</p>

        <!-- Submit -->
        <button
          type="submit"
          class="w-full flex justify-center items-center gap-2 py-2 rounded-lg text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-60"
          :disabled="loading"
        >
          <svg
            v-if="loading"
            class="animate-spin h-5 w-5 text-white"
            viewBox="0 0 24 24"
            fill="none"
          >
            <circle
              class="opacity-25"
              cx="12"
              cy="12"
              r="10"
              stroke="currentColor"
              stroke-width="4"
            />
            <path
              class="opacity-75"
              fill="currentColor"
              d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
            />
          </svg>
          <span>{{ loading ? '登入中…' : '登入' }}</span>
        </button>

        <!-- 註冊提示 -->
        <p class="text-center text-sm text-gray-600">
          還沒有帳號？
          <RouterLink to="/register" class="text-blue-600 hover:underline">
            立即註冊
          </RouterLink>
        </p>
      </form>
    </div>
  </main>
</template>


<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import { Eye, EyeOff } from 'lucide-vue-next';

const email = ref('');
const password = ref('');
const showPwd = ref(false);
const loading  = ref(false);
const error    = ref('');

const auth   = useAuthStore();
const router = useRouter();

async function handleSubmit () {
  if (!email.value || !password.value) return;

  loading.value = true;
  error.value   = '';

  try {
    await auth.login(email.value, password.value);
    router.push('/');
  } catch (err) {
    const raw  = err?.response?.data?.message ?? '';
    const code = err?.response?.status;
    let   msg  = '登入失敗 (Login failed)';

    /* ---------- 常見錯誤對照 ---------- */
    if (code === 422 || raw.toLowerCase().includes('invalid')) {
      msg = '帳號或密碼錯誤 (Invalid account)';
    } else if (code === 419) {
      msg = '表單逾時，請重新整理再登入 (CSRF token mismatch)';
    } else if (code === 401) {
      msg = '尚未授權，請重新登入 (Unauthorized)';
    } else if (code === 429) {
      msg = '嘗試次數過多，請稍候再試 (Too many attempts)';
    } else if (code >= 500) {
      msg = '伺服器發生錯誤，請稍後再試 (Server error)';
    } else if (err.code === 'ERR_NETWORK') {
      msg = '無法連線，請檢查網路 (Network error)';
    }

    error.value = msg;
  } finally {
    loading.value = false;
  }
}
</script>

