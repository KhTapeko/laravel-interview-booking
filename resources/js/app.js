import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from '@/App.vue';
import router from '@/router';
import axios from 'axios'
axios.defaults.withCredentials = true

const app = createApp(App)
  .use(createPinia())
  .use(router);

app.mount('#app');
