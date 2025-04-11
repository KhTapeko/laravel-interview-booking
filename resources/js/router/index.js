import { createRouter, createWebHistory } from 'vue-router';
import Home from '../pages/Home.vue';
import JobDetail from '@/pages/JobDetail.vue'

const routes = [
    // Home 頁
    { path: '/', component: Home },
    // 職缺詳情頁
    { path: '/jobs/:id', component: JobDetail },
  ];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
