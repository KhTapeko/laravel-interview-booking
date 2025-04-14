import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from '@/App.vue';
import router from '@/router';
import { useAuthStore } from '@/stores/auth';

const app=createApp(App).use(createPinia()).use(router);
await useAuthStore().fetchUser();
app.mount('#app');