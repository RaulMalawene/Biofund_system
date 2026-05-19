import './assets/main.css';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from './routers';
import App from './App.vue';
import { useAuthStore } from './stores/auth';

const app = createApp(App);
const pinia = createPinia();
app.use(pinia);
app.use(router);

// Validate any token from a previous session; clear it if the server rejects it
const auth = useAuthStore();
if (auth.token) {
    auth.fetchMe().catch(() => auth.clearSession());
}

app.mount('#app');
