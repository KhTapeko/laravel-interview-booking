import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import Home from '@/pages/Home.vue';
import Login from '@/pages/Login.vue';
import Register from '@/pages/Register.vue';
import JobDetail from '@/pages/JobDetail.vue'
import UserManager from '@/pages/admin/UserManager.vue'

const routes = [
  // Home 頁
  { path: '/',          component: Home },
  // 職缺詳情頁
  { path: '/jobs/:id', component: JobDetail },
  // 登入頁
  { path: '/login',     component: Login,    meta: { guestOnly: true } },
  // 註冊頁
  { path: '/register',  component: Register, meta: { guestOnly: true } },
  // 使用者管理
  { path: '/admin/users', component: UserManager, meta: { roles: ['admin'] } },

  /* 權限頁面 */
  //{ path: '/a', component: PageA, meta: { roles: ['admin'] } },
  //{ path: '/b', component: PageB, meta: { roles: ['admin', 'employee'] } },
  //{ path: '/c', component: PageC, meta: { roles: ['admin', 'employee', 'candidate'] } },

  { path: '/:pathMatch(.*)*', redirect: '/' },   // 404 → 首頁
];


const router = createRouter({
    history: createWebHistory(),
    routes,
});

/* ---------- 全域前置守衛 ---------- */
router.beforeEach(async (to) => {
  const auth = useAuthStore();

  // 首次導航嘗試還原登入
  if (auth.user === null && !auth._didFetch) {
    auth._didFetch = true
    try {
      await auth.fetchUser()
    } catch (e) {
      // 完全吞掉錯誤
    }
  }

  // 已登入者禁止進 guestOnly
  if (to.meta.guestOnly && auth.user) return '/';

  // 權限判斷
  if (to.meta.roles) {
    if (!auth.user) return '/login';                    // 未登入
    if (!to.meta.roles.includes(auth.user.role)) return '/'; // 無權限 → 回首頁
  }
});

export default router;
