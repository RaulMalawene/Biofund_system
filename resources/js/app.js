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

// Validate token against server at most once per 5 minutes.
// User data already loaded from localStorage - fetchMe is only needed
// to confirm the token is still accepted server-side.
const auth = useAuthStore();
if (auth.token) {
    const FIVE_MIN = 5 * 60 * 1000;
    const lastValidated = parseInt(localStorage.getItem('mdr_validated_at') ?? '0', 10);
    if (Date.now() - lastValidated > FIVE_MIN) {
        auth.fetchMe()
            .then(() => localStorage.setItem('mdr_validated_at', String(Date.now())))
            .catch(() => auth.clearSession());
    }
}

app.mount('#app');
