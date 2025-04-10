import { createRouter, createWebHistory } from 'vue-router';
import Home from '../pages/Home.vue';
import JobDetail from '../pages/JobDetail.vue';

const routes = [
    { path: '/', component: Home },
    { path: '/jobs/:id', component: JobDetail },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
