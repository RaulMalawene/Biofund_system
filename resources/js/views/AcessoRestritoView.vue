<template>
  <div class="page">
    <AppNavbar />

    <div class="page-body">

      <!-- Botanical deco left -->
      <svg class="deco deco-left" width="340" height="520" viewBox="0 0 340 520" fill="none">
        <ellipse cx="80" cy="260" rx="70" ry="200" transform="rotate(-20 80 260)" fill="#2D6A4F" />
        <path d="M80 60 Q160 180 80 460" stroke="#1B4332" stroke-width="3" fill="none" />
        <ellipse cx="200" cy="120" rx="45" ry="130" transform="rotate(30 200 120)" fill="#52B788" />
        <path d="M200 -10 Q240 80 200 250" stroke="#2D6A4F" stroke-width="2" fill="none" />
        <ellipse cx="260" cy="400" rx="30" ry="90" transform="rotate(-10 260 400)" fill="#2D6A4F" />
      </svg>

      <!-- Botanical deco right -->
      <svg class="deco deco-right" width="340" height="520" viewBox="0 0 340 520" fill="none">
        <ellipse cx="80" cy="260" rx="70" ry="200" transform="rotate(-20 80 260)" fill="#2D6A4F" />
        <path d="M80 60 Q160 180 80 460" stroke="#1B4332" stroke-width="3" fill="none" />
        <ellipse cx="200" cy="120" rx="45" ry="130" transform="rotate(30 200 120)" fill="#52B788" />
        <path d="M200 -10 Q240 80 200 250" stroke="#2D6A4F" stroke-width="2" fill="none" />
        <ellipse cx="260" cy="400" rx="30" ry="90" transform="rotate(-10 260 400)" fill="#2D6A4F" />
      </svg>

      <!-- Header -->
      <div class="login-header">
        <div class="shield-wrap">
          <svg width="28" height="28" fill="none" stroke="#2D6A4F" stroke-width="1.8" viewBox="0 0 28 28">
            <path d="M14 3 L24 7.5 V14 C24 19.5 19.5 24 14 26 C8.5 24 4 19.5 4 14 V7.5 Z" stroke-linejoin="round" />
            <path d="M10 14 L13 17 L18 11" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </div>
        <h1>Acesso Restrito</h1>
        <p>Portal administrativo para funcionários e gestores da BIOFUND.</p>
      </div>

      <!-- Login card -->
      <div class="login-card">
        <div class="card-title">Credenciais de Acesso</div>
        <div class="card-sub">Introduza os seus dados institucionais para continuar.</div>

        <!-- Erro geral da API -->
        <div class="error-msg" v-if="loginError">
          <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <circle cx="8" cy="8" r="6" />
            <path d="M8 5v3M8 11h.01" stroke-linecap="round" />
          </svg>
          {{ loginError }}
        </div>

        <!-- Email -->
        <div class="field-group">
          <label>E-mail Institucional</label>
          <div class="input-wrap" :class="{ error: emailError }">
            <div class="input-icon">
              <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                <rect x="1" y="3" width="14" height="10" rx="1.5" />
                <path d="M1 5l7 5 7-5" stroke-linecap="round" />
              </svg>
            </div>
            <input type="email" v-model="email" placeholder="exemplo@biofund.org.mz" @blur="validateEmail"
              @input="emailError = ''; loginError = ''" @keyup.enter="submitLogin" />
          </div>
          <span v-if="emailError" class="field-error">{{ emailError }}</span>
        </div>

        <!-- Palavra-passe -->
        <div class="field-group">
          <label>Palavra-passe</label>
          <div class="input-wrap" :class="{ error: passError }">
            <div class="input-icon">
              <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                <rect x="3" y="7" width="10" height="8" rx="1.5" />
                <path d="M5 7V5a3 3 0 0 1 6 0v2" stroke-linecap="round" />
                <circle cx="8" cy="11" r="1.2" fill="currentColor" />
              </svg>
            </div>
            <input :type="showPass ? 'text' : 'password'" v-model="password" placeholder="••••••••"
              @input="passError = ''; loginError = ''" @keyup.enter="submitLogin" />
            <button class="btn-eye" type="button" @click="showPass = !showPass">
              <svg v-if="!showPass" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.7"
                viewBox="0 0 16 16">
                <path d="M1 8s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z" />
                <circle cx="8" cy="8" r="2" />
              </svg>
              <svg v-else width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.7"
                viewBox="0 0 16 16">
                <path d="M1 8s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z" />
                <line x1="1" y1="1" x2="15" y2="15" stroke-linecap="round" />
              </svg>
            </button>
          </div>
          <span v-if="passError" class="field-error">{{ passError }}</span>
        </div>

        <!-- Esqueceu senha -->
        <div class="forgot-row">
          <span class="forgot-link" @click="showForgot = true">Esqueceu a sua senha?</span>
        </div>

        <!-- Submit -->
        <button class="btn-submit" :disabled="loading" @click="submitLogin">
          <div class="btn-inner">
            <svg v-if="loading" class="btn-spin" width="16" height="16" fill="none" stroke="#fff" stroke-width="2.2"
              viewBox="0 0 16 16">
              <path d="M8 2a6 6 0 0 1 6 6" stroke-linecap="round" />
            </svg>
            {{ loading ? 'A autenticar…' : 'Entrar no Sistema' }}
          </div>
        </button>

        <!-- Voltar -->
        <router-link to="/" class="back-link">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 14 14">
            <path d="M9 2L4 7l5 5" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          Voltar para o Início
        </router-link>
      </div>

      <!-- Aviso de segurança -->
      <div class="security-notice">
        <p>Este sistema é monitorizado para fins de segurança. O acesso não autorizado é estritamente proibido e sujeito
          a sanções legais.</p>
        <div class="security-tags">
          <span>Segurança SSL</span>
          <span class="dot">•</span>
          <span>BIOFUND 2026</span>
        </div>
      </div>

    </div>

    <!-- Modal recuperar senha -->
    <div class="modal-overlay" v-if="showForgot" @click.self="showForgot = false">
      <div class="modal-card">
        <div class="modal-icon-wrap">
          <svg width="20" height="20" fill="none" stroke="#2D6A4F" stroke-width="1.8" viewBox="0 0 20 20">
            <rect x="2" y="7" width="16" height="12" rx="2" />
            <path d="M6 7V5a4 4 0 0 1 8 0v2" stroke-linecap="round" />
            <circle cx="10" cy="13" r="1.5" fill="#2D6A4F" />
          </svg>
        </div>
        <h3>Recuperar Palavra-passe</h3>
        <p>Insira o seu e-mail institucional. Enviaremos instruções para redefinir a sua palavra-passe.</p>

        <div class="field-group">
          <label>E-mail Institucional</label>
          <div class="input-wrap" :class="{ error: forgotError }">
            <div class="input-icon">
              <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                <rect x="1" y="3" width="14" height="10" rx="1.5" />
                <path d="M1 5l7 5 7-5" stroke-linecap="round" />
              </svg>
            </div>
            <input type="email" v-model="forgotEmail" placeholder="exemplo@biofund.org.mz" />
          </div>
          <span v-if="forgotError" class="field-error">{{ forgotError }}</span>
          <span v-if="forgotSuccess" class="field-success">{{ forgotSuccess }}</span>
        </div>

        <div class="modal-actions">
          <button class="btn-modal-cancel" @click="showForgot = false">Cancelar</button>
          <button class="btn-modal-send" :disabled="forgotLoading" @click="sendForgot">
            {{ forgotLoading ? 'A enviar…' : 'Enviar Link' }}
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import AppNavbar from '@/components/AppNavbar.vue'
import { useAuthStore } from '@/stores/auth'
import { AuthService } from '@/api/services/auth.service'

const router = useRouter()
const route = useRoute()
const auth = useAuthStore()

// ── Campos do formulário ──────────────────────────────────────
const email = ref('')
const password = ref('')
const showPass = ref(false)

// ── Estado UI ─────────────────────────────────────────────────
const loading = ref(false)
const loginError = ref('')
const emailError = ref('')
const passError = ref('')

// ── Recuperar senha ───────────────────────────────────────────
const showForgot = ref(false)
const forgotEmail = ref('')
const forgotLoading = ref(false)
const forgotError = ref('')
const forgotSuccess = ref('')

// ── Validações client-side ────────────────────────────────────
function validateEmail() {
  if (!email.value.trim()) {
    emailError.value = 'O e-mail é obrigatório.'
    return false
  }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
    emailError.value = 'Formato de e-mail inválido.'
    return false
  }
  emailError.value = ''
  return true
}

function validatePass() {
  if (!password.value) {
    passError.value = 'A palavra-passe é obrigatória.'
    return false
  }
  passError.value = ''
  return true
}

// ── Submit de login ───────────────────────────────────────────
async function submitLogin() {
  loginError.value = ''
  const eOk = validateEmail()
  const pOk = validatePass()
  if (!eOk || !pOk) return

  loading.value = true
  try {
    await auth.login(email.value.trim(), password.value)

    // Redireciona para a rota solicitada (se vier de um guard) ou para o dashboard
    const redirectTo = route.query.redirect ?? auth.dashboardRoute
    router.push(redirectTo)

  } catch (err) {
    if (err.response?.status === 401) {
      loginError.value = 'Credenciais inválidas. Verifique o e-mail e a palavra-passe.'
    } else if (err.response?.status === 403) {
      loginError.value = 'A sua conta está desactivada. Contacte o administrador.'
    } else if (!err.response) {
      loginError.value = 'Sem ligação ao servidor. Verifique a sua ligação à internet.'
    } else {
      loginError.value = err.response?.data?.message ?? 'Erro ao autenticar. Tente novamente.'
    }
  } finally {
    loading.value = false
  }
}

// ── Recuperar senha ───────────────────────────────────────────
async function sendForgot() {
  forgotError.value = ''
  forgotSuccess.value = ''

  if (!forgotEmail.value.trim()) {
    forgotError.value = 'Insira o seu e-mail.'
    return
  }

  forgotLoading.value = true
  try {
    const result = await AuthService.forgotPassword(forgotEmail.value.trim())
    forgotSuccess.value = result.message ?? 'Link enviado! Verifique o seu e-mail.'
    forgotEmail.value = ''
  } catch (err) {
    forgotError.value = err.response?.data?.message ?? 'Erro ao enviar. Tente novamente.'
  } finally {
    forgotLoading.value = false
  }
}
</script>

<style scoped>
.page {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.page-body {
  flex: 1;
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 60px 24px 48px;
  overflow: hidden;
  background: var(--offwhite);
}

.deco {
  position: absolute;
  pointer-events: none;
  opacity: 0.07;
}

.deco-left {
  left: -40px;
  top: 50%;
  transform: translateY(-55%);
}

.deco-right {
  right: -40px;
  top: 50%;
  transform: translateY(-45%) scaleX(-1);
}

.login-header {
  text-align: center;
  margin-bottom: 28px;
  animation: fadeUp 0.5s ease both;
}

.shield-wrap {
  width: 64px;
  height: 64px;
  background: var(--green-bg);
  border: 1.5px solid #C3E6CE;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 18px;
}

.login-header h1 {
  font-size: 26px;
  font-weight: 800;
  margin-bottom: 8px;
}

.login-header p {
  font-size: 13.5px;
  color: var(--text-gray);
  line-height: 1.65;
  max-width: 300px;
}

.login-card {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 16px;
  padding: 36px 40px 28px;
  width: 100%;
  max-width: 380px;
  box-shadow: 0 4px 32px rgba(0, 0, 0, 0.08);
  animation: fadeUp 0.5s 0.06s ease both;
}

@keyframes fadeUp {
  from {
    opacity: 0;
    transform: translateY(18px);
  }

  to {
    opacity: 1;
    transform: none;
  }
}

.card-title {
  font-size: 16px;
  font-weight: 700;
  margin-bottom: 5px;
}

.card-sub {
  font-size: 12.5px;
  color: var(--text-gray);
  margin-bottom: 26px;
  line-height: 1.5;
}

.field-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
  margin-bottom: 18px;
}

.field-group label {
  font-size: 12.5px;
  font-weight: 600;
  color: var(--text-dark);
}

.input-wrap {
  display: flex;
  align-items: center;
  border: 1.5px solid var(--border);
  border-radius: 9px;
  background: var(--white);
  transition: border-color 0.2s, box-shadow 0.2s;
  overflow: hidden;
}

.input-wrap:focus-within {
  border-color: var(--green-light);
  box-shadow: 0 0 0 3px rgba(82, 183, 136, 0.18);
}

.input-wrap.error {
  border-color: #E53E3E;
  box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.12);
}

.input-icon {
  padding: 0 12px;
  display: flex;
  align-items: center;
  color: var(--text-light);
  flex-shrink: 0;
}

.input-wrap input {
  flex: 1;
  border: none;
  outline: none;
  font-family: 'Poppins', sans-serif;
  font-size: 13.5px;
  color: var(--text-dark);
  padding: 12px 0 12px 2px;
  background: transparent;
}

.input-wrap input::placeholder {
  color: var(--text-light);
}

.btn-eye {
  background: none;
  border: none;
  cursor: pointer;
  padding: 0 12px;
  color: var(--text-light);
  display: flex;
  align-items: center;
  transition: color 0.2s;
}

.btn-eye:hover {
  color: var(--green-mid);
}

.field-error {
  font-size: 11.5px;
  color: #C53030;
  margin-top: 2px;
}

.field-success {
  font-size: 11.5px;
  color: var(--green-mid);
  margin-top: 2px;
  font-weight: 600;
}

.forgot-row {
  display: flex;
  justify-content: flex-end;
  margin-top: -10px;
  margin-bottom: 24px;
}

.forgot-link {
  font-size: 12.5px;
  font-weight: 600;
  color: var(--green-mid);
  cursor: pointer;
  transition: color 0.2s;
}

.forgot-link:hover {
  color: var(--green-dark);
  text-decoration: underline;
}

.error-msg {
  background: #FFF5F5;
  border: 1px solid #FED7D7;
  border-radius: 8px;
  padding: 10px 14px;
  font-size: 12.5px;
  color: #C53030;
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 16px;
  animation: fadeUp 0.3s ease;
}

.btn-submit {
  width: 100%;
  background: var(--green-mid);
  color: var(--white);
  border: none;
  border-radius: 10px;
  padding: 14px;
  font-family: 'Poppins', sans-serif;
  font-size: 14.5px;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.2s, transform 0.15s;
  margin-bottom: 18px;
}

.btn-submit:hover {
  background: var(--green-dark);
  transform: translateY(-1px);
}

.btn-submit:disabled {
  background: #A0C4B0;
  cursor: not-allowed;
  transform: none;
}

.btn-inner {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.btn-spin {
  animation: spin 0.8s linear infinite;
}

.back-link {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  font-size: 13px;
  color: var(--text-gray);
  text-decoration: none;
  cursor: pointer;
  transition: color 0.2s;
}

.back-link:hover {
  color: var(--text-dark);
}

.security-notice {
  text-align: center;
  margin-top: 28px;
  animation: fadeUp 0.5s 0.12s ease both;
}

.security-notice p {
  font-size: 12px;
  color: var(--text-light);
  line-height: 1.65;
  max-width: 340px;
  margin: 0 auto 10px;
}

.security-tags {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  font-size: 10.5px;
  font-weight: 700;
  letter-spacing: 1px;
  text-transform: uppercase;
  color: var(--text-light);
}

.security-tags .dot {
  font-size: 14px;
  line-height: 1;
}

/* MODAL */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.4);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 998;
  animation: fadeIn 0.25s ease;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }

  to {
    opacity: 1;
  }
}

.modal-card {
  background: var(--white);
  border-radius: 16px;
  padding: 36px 36px 28px;
  max-width: 360px;
  width: 90%;
  animation: scaleIn 0.25s ease;
}

@keyframes scaleIn {
  from {
    opacity: 0;
    transform: scale(0.88);
  }

  to {
    opacity: 1;
    transform: scale(1);
  }
}

.modal-icon-wrap {
  width: 44px;
  height: 44px;
  background: var(--green-bg);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 14px;
}

.modal-card h3 {
  font-size: 17px;
  font-weight: 800;
  margin-bottom: 6px;
}

.modal-card p {
  font-size: 13px;
  color: var(--text-gray);
  line-height: 1.6;
  margin-bottom: 22px;
}

.modal-actions {
  display: flex;
  gap: 10px;
  margin-top: 8px;
}

.btn-modal-cancel {
  flex: 1;
  background: var(--white);
  color: var(--text-gray);
  border: 1.5px solid var(--border);
  border-radius: 9px;
  padding: 11px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: border-color 0.2s;
}

.btn-modal-cancel:hover {
  border-color: var(--text-gray);
}

.btn-modal-send {
  flex: 1;
  background: var(--green-mid);
  color: var(--white);
  border: none;
  border-radius: 9px;
  padding: 11px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-modal-send:hover {
  background: var(--green-dark);
}

.btn-modal-send:disabled {
  background: #A0C4B0;
  cursor: not-allowed;
}
</style>