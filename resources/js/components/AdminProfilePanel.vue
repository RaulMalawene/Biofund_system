<template>
  <!-- Topbar widget (substitui o bloco admin-info em cada tela) -->
  <div class="admin-info" @click="openPanel" title="Ver perfil">
    <div class="admin-text">
      <div class="admin-name">{{ auth.user?.name ?? 'Utilizador' }}</div>
      <div class="admin-role">{{ auth.user?.role_label ?? auth.user?.role ?? '' }}</div>
    </div>
    <div class="admin-avatar">
      <img v-if="auth.user?.avatar_url" :src="auth.user.avatar_url" class="admin-avatar-img" alt="avatar" />
      <span v-else>{{ auth.userInitials }}</span>
    </div>
  </div>

  <!-- Painel renderizado no body via Teleport (evita z-index em layouts aninhados) -->
  <Teleport to="body">
    <transition name="pp-fade">
      <div v-if="open" class="pp-overlay" @click="closePanel"></div>
    </transition>

    <transition name="pp-slide">
      <div v-if="open" class="pp-panel" @click.stop>

        <!-- Cabeçalho -->
        <div class="pp-header">
          <div>
            <h2 class="pp-title">O Meu Perfil</h2>
            <p class="pp-subtitle">Visualize e edite os seus dados pessoais</p>
          </div>
          <button class="pp-close" @click="closePanel">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 16 16">
              <path d="M3 3l10 10M13 3L3 13" stroke-linecap="round"/>
            </svg>
          </button>
        </div>

        <!-- Corpo com scroll -->
        <div class="pp-body">

          <!-- Avatar -->
          <div class="pp-avatar-section">
            <div class="pp-avatar-wrap">
              <img v-if="avatarPreview" :src="avatarPreview" class="pp-avatar-img" alt="avatar" />
              <div v-else class="pp-avatar-initials">{{ auth.userInitials }}</div>
              <button class="pp-avatar-edit" @click="avatarInput?.click()" title="Alterar imagem">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 16 16">
                  <path d="M11 2.5a2.121 2.121 0 0 1 3 3L5.5 14 1 15l1-4.5L11 2.5z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </button>
            </div>
            <input ref="avatarInput" type="file" accept="image/*" class="pp-file-hidden" @change="handleAvatarChange" />
            <span class="pp-avatar-hint">Clique no ícone para alterar · máx. 3 MB</span>
          </div>

          <!-- Badge do perfil -->
          <div class="pp-role-badge">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
              <circle cx="8" cy="6" r="3"/><path d="M2 14c0-2.761 2.686-5 6-5s6 2.239 6 5" stroke-linecap="round"/>
            </svg>
            {{ auth.user?.role_label ?? auth.user?.role ?? '-' }}
          </div>

          <!-- Dados pessoais -->
          <div class="pp-field">
            <label>Nome Completo</label>
            <input v-model="pf.name" type="text" placeholder="Nome completo" />
          </div>
          <div class="pp-field">
            <label>E-mail</label>
            <input v-model="pf.email" type="email" placeholder="email@biofund.org" />
          </div>
          <div class="pp-field">
            <label>Telefone</label>
            <input v-model="pf.phone" type="tel" placeholder="+258 8x xxx xxxx" />
          </div>

          <!-- Senha actual -->
          <div class="pp-field">
            <label>Senha Actual <span class="pp-label-hint">(preencha para alterar a senha)</span></label>
            <div class="pp-pw-wrap">
              <input v-model="pw.current" :type="showPw.current ? 'text' : 'password'" placeholder="••••••••" autocomplete="off" />
              <button class="pp-pw-eye" @click="showPw.current = !showPw.current" type="button">
                <svg v-if="!showPw.current" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16"><ellipse cx="8" cy="8" rx="6" ry="4"/><circle cx="8" cy="8" r="1.8" fill="currentColor" stroke="none"/></svg>
                <svg v-else width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16"><path d="M2 2l12 12M6.5 6.7A3 3 0 0 0 9.3 9.5" stroke-linecap="round"/><path d="M4.2 4.8C2.8 5.8 2 7 2 8c0 1 1.5 3.5 6 3.5a8 8 0 0 0 2.8-.5M7 4.6A8 8 0 0 1 8 4.5c4.5 0 6 2.5 6 3.5 0 .6-.4 1.4-1.2 2.1" stroke-linecap="round"/></svg>
              </button>
            </div>
          </div>

          <!-- Nova senha (só mostra se senha actual preenchida) -->
          <template v-if="pw.current">
            <div class="pp-field">
              <label>Nova Senha</label>
              <div class="pp-pw-wrap">
                <input v-model="pw.password" :type="showPw.password ? 'text' : 'password'" placeholder="Mín. 8 caracteres" autocomplete="off" />
                <button class="pp-pw-eye" @click="showPw.password = !showPw.password" type="button">
                  <svg v-if="!showPw.password" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16"><ellipse cx="8" cy="8" rx="6" ry="4"/><circle cx="8" cy="8" r="1.8" fill="currentColor" stroke="none"/></svg>
                  <svg v-else width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16"><path d="M2 2l12 12M6.5 6.7A3 3 0 0 0 9.3 9.5" stroke-linecap="round"/><path d="M4.2 4.8C2.8 5.8 2 7 2 8c0 1 1.5 3.5 6 3.5a8 8 0 0 0 2.8-.5M7 4.6A8 8 0 0 1 8 4.5c4.5 0 6 2.5 6 3.5 0 .6-.4 1.4-1.2 2.1" stroke-linecap="round"/></svg>
                </button>
              </div>
              <div class="pp-pw-strength" v-if="pw.password">
                <div class="pp-strength-bar"><span :style="{ width: pwStrengthPct + '%', background: pwStrengthColor }"></span></div>
                <span class="pp-strength-label" :style="{ color: pwStrengthColor }">{{ pwStrengthLabel }}</span>
              </div>
            </div>
            <div class="pp-field">
              <label>Confirmar Nova Senha</label>
              <div class="pp-pw-wrap">
                <input v-model="pw.confirmation" :type="showPw.confirmation ? 'text' : 'password'" placeholder="Repita a nova senha" autocomplete="off" />
                <button class="pp-pw-eye" @click="showPw.confirmation = !showPw.confirmation" type="button">
                  <svg v-if="!showPw.confirmation" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16"><ellipse cx="8" cy="8" rx="6" ry="4"/><circle cx="8" cy="8" r="1.8" fill="currentColor" stroke="none"/></svg>
                  <svg v-else width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16"><path d="M2 2l12 12M6.5 6.7A3 3 0 0 0 9.3 9.5" stroke-linecap="round"/><path d="M4.2 4.8C2.8 5.8 2 7 2 8c0 1 1.5 3.5 6 3.5a8 8 0 0 0 2.8-.5M7 4.6A8 8 0 0 1 8 4.5c4.5 0 6 2.5 6 3.5 0 .6-.4 1.4-1.2 2.1" stroke-linecap="round"/></svg>
                </button>
              </div>
            </div>
          </template>

          <div class="pp-readonly-row" v-if="auth.user?.last_login_at">
            <span class="pp-ro-label">Último acesso</span>
            <span class="pp-ro-val">{{ auth.user.last_login_at }}</span>
          </div>

          <!-- Feedback unificado -->
          <transition name="pp-fade">
            <div v-if="pfSuccess || pwSuccess" class="pp-success-msg">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 16 16">
                <circle cx="8" cy="8" r="6"/><path d="M5.5 8l2 2 3.5-4" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              {{ pwSuccess ? 'Senha alterada com sucesso!' : 'Perfil actualizado com sucesso!' }}
            </div>
            <div v-else-if="pfError || pwError" class="pp-error-msg">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 16 16">
                <circle cx="8" cy="8" r="6"/><path d="M8 5v3M8 10.5v.5" stroke-linecap="round"/>
              </svg>
              {{ pfError || pwError }}
            </div>
          </transition>

          <div style="height:8px"></div>
        </div>

        <!-- Footer com botões -->
        <div class="pp-footer">
          <button class="pp-btn-save" @click="saveAll" :disabled="pfSaving || pwSaving">
            <svg v-if="pfSaving || pwSaving" class="pp-spin" width="14" height="14" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 16 16">
              <path d="M8 2a6 6 0 0 1 6 6" stroke-linecap="round"/>
            </svg>
            <svg v-else width="14" height="14" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 16 16">
              <path d="M2 8l4 4 8-8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            {{ (pfSaving || pwSaving) ? 'A guardar…' : 'Guardar' }}
          </button>
          <button class="pp-btn-cancel" @click="closePanel">Cancelar</button>
        </div>

      </div>
    </transition>
  </Teleport>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { InternalService } from '@/api/services/internal.service'
import { AuthService } from '@/api/services/auth.service'

const auth = useAuthStore()

// ── Estado do painel ──────────────────────────────────────────
const open       = ref(false)
const avatarInput = ref(null)
const avatarPreview = ref(null)
const avatarFile    = ref(null)

// Dados pessoais
const pf        = reactive({ name: '', email: '', phone: '' })
const pfSaving  = ref(false)
const pfError   = ref('')
const pfSuccess = ref(false)

// Senha
const pw        = reactive({ current: '', password: '', confirmation: '' })
const showPw    = reactive({ current: false, password: false, confirmation: false })
const pwSaving  = ref(false)
const pwError   = ref('')
const pwSuccess = ref(false)

// ── Força da senha ────────────────────────────────────────────
const pwStrengthPct = computed(() => {
  const p = pw.password
  if (!p) return 0
  let score = 0
  if (p.length >= 8)  score += 25
  if (p.length >= 12) score += 15
  if (/[A-Z]/.test(p)) score += 20
  if (/[0-9]/.test(p)) score += 20
  if (/[^A-Za-z0-9]/.test(p)) score += 20
  return Math.min(score, 100)
})
const pwStrengthColor = computed(() => {
  const s = pwStrengthPct.value
  if (s < 35) return '#FC8181'
  if (s < 65) return '#F4A52A'
  return '#52B788'
})
const pwStrengthLabel = computed(() => {
  const s = pwStrengthPct.value
  if (s < 35) return 'Fraca'
  if (s < 65) return 'Razoável'
  if (s < 85) return 'Boa'
  return 'Forte'
})

// ── Abrir / fechar ────────────────────────────────────────────
function openPanel() {
  pf.name    = auth.user?.name  ?? ''
  pf.email   = auth.user?.email ?? ''
  pf.phone   = auth.user?.phone ?? ''
  avatarPreview.value = auth.user?.avatar_url ?? null
  avatarFile.value    = null
  pfError.value  = ''; pfSuccess.value = false
  Object.assign(pw, { current: '', password: '', confirmation: '' })
  pwError.value  = ''; pwSuccess.value = false
  open.value = true
}
function closePanel() { open.value = false }

// ── Avatar ────────────────────────────────────────────────────
function handleAvatarChange(e) {
  const file = e.target.files?.[0]
  if (!file) return
  if (file.size > 3 * 1024 * 1024) { pfError.value = 'A imagem não pode exceder 3 MB.'; return }
  avatarFile.value    = file
  avatarPreview.value = URL.createObjectURL(file)
  pfError.value = ''
}

// ── Guardar dados pessoais ────────────────────────────────────
async function saveProfile() {
  if (pfSaving.value) return
  pfError.value = ''; pfSuccess.value = false
  const fd = new FormData()
  if (pf.name.trim())  fd.append('name',  pf.name.trim())
  if (pf.email.trim()) fd.append('email', pf.email.trim())
  fd.append('phone', pf.phone.trim())
  if (avatarFile.value) fd.append('avatar', avatarFile.value)
  pfSaving.value = true
  try {
    const res = await InternalService.updateProfile(fd)
    auth.updateUser(res.user)
    avatarPreview.value = res.user?.avatar_url ?? avatarPreview.value
    avatarFile.value = null
    pfSuccess.value = true
    setTimeout(() => { pfSuccess.value = false }, 3500)
  } catch (e) {
    const errs = e?.response?.data?.errors
    pfError.value = errs ? Object.values(errs).flat()[0] : 'Erro ao actualizar o perfil.'
  } finally {
    pfSaving.value = false
  }
}

// ── Alterar senha ─────────────────────────────────────────────
async function changePassword() {
  // Só muda a senha se ambos os campos estiverem preenchidos
  if (!pw.current.trim() || !pw.password.trim()) return true
  pwError.value = ''; pwSuccess.value = false
  if (pw.password.length < 8) { pwError.value = 'A nova senha deve ter pelo menos 8 caracteres.'; return false }
  if (pw.password !== pw.confirmation) { pwError.value = 'As senhas não coincidem.'; return false }
  pwSaving.value = true
  try {
    await AuthService.changePassword({
      current_password:      pw.current,
      password:              pw.password,
      password_confirmation: pw.confirmation,
    })
    pwSuccess.value = true
    Object.assign(pw, { current: '', password: '', confirmation: '' })
    setTimeout(() => { pwSuccess.value = false }, 4000)
    return true
  } catch (e) {
    const errs = e?.response?.data?.errors
    pwError.value = errs ? Object.values(errs).flat()[0] : 'Erro ao alterar a senha.'
    return false
  } finally {
    pwSaving.value = false
  }
}

// ── Guardar tudo de uma vez ───────────────────────────────────
async function saveAll() {
  if (pfSaving.value || pwSaving.value) return
  pfError.value = ''; pfSuccess.value = false
  pwError.value = ''; pwSuccess.value = false

  // 1. Guardar dados pessoais
  const fd = new FormData()
  if (pf.name.trim())  fd.append('name',  pf.name.trim())
  if (pf.email.trim()) fd.append('email', pf.email.trim())
  fd.append('phone', pf.phone.trim())
  if (avatarFile.value) fd.append('avatar', avatarFile.value)

  pfSaving.value = true
  try {
    const res = await InternalService.updateProfile(fd)
    auth.updateUser(res.user)
    avatarPreview.value = res.user?.avatar_url ?? avatarPreview.value
    avatarFile.value = null
  } catch (e) {
    const errs = e?.response?.data?.errors
    pfError.value = errs ? Object.values(errs).flat()[0] : 'Erro ao actualizar o perfil.'
    pfSaving.value = false
    return
  }
  pfSaving.value = false

  // 2. Alterar senha (só se senha actual E nova senha preenchidas)
  if (pw.current.trim() && pw.password.trim()) {
    const ok = await changePassword()
    if (!ok) return
  }

  // Feedback de sucesso
  if (!pwSuccess.value) {
    pfSuccess.value = true
    setTimeout(() => { pfSuccess.value = false }, 3500)
  }
}
</script>

<style scoped>
/* ── Topbar widget ─────────────────────────────────────────── */
.admin-info {
  display: flex; align-items: center; gap: 10px;
  cursor: pointer;
}
.admin-info:hover .admin-avatar {
  box-shadow: 0 0 0 3px rgba(82,183,136,.35);
}
.admin-text { text-align: right; }
.admin-name { font-size: 13px; font-weight: 700; color: var(--text-dark); }
.admin-role { font-size: 11px; color: var(--text-light); }
.admin-avatar {
  width: 36px; height: 36px; border-radius: 50%;
  background: linear-gradient(135deg, #52B788, #1B4332);
  display: flex; align-items: center; justify-content: center;
  font-size: 13px; font-weight: 800; color: #fff; flex-shrink: 0;
  overflow: hidden; transition: box-shadow 0.18s;
}
.admin-avatar-img { width: 100%; height: 100%; object-fit: cover; display: block; border-radius: 50%; }

@media (max-width: 480px) {
  .admin-text { display: none; }
  .admin-info { gap: 0; }
}
</style>

<!-- Estilos globais para o painel (teleportado para o body, fora do scope) -->
<style>
.pp-overlay {
  position: fixed; inset: 0;
  background: rgba(0,0,0,.45);
  z-index: 200;
}
.pp-panel {
  position: fixed;
  top: 50%; left: 50%;
  transform: translate(-50%, -50%);
  width: 480px; max-width: 95vw;
  max-height: 88vh;
  background: #fff;
  z-index: 201;
  display: flex; flex-direction: column;
  box-shadow: 0 20px 60px rgba(0,0,0,.22);
  border-radius: 16px;
  overflow: hidden;
}
.pp-header {
  display: flex; align-items: flex-start; justify-content: space-between;
  padding: 22px 24px 18px;
  border-bottom: 1px solid #E2EAE6;
  flex-shrink: 0;
}
.pp-title { font-size: 17px; font-weight: 800; color: #1A2B22; margin: 0; }
.pp-subtitle { font-size: 12px; color: #8A9490; margin: 3px 0 0; }
.pp-close {
  width: 32px; height: 32px; border-radius: 8px;
  background: #F4F6F5; border: none; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  color: #555B5A; flex-shrink: 0;
}
.pp-close:hover { background: #E8EFEC; }
.pp-body {
  flex: 1; overflow-y: auto; padding: 0 0 12px;
}
.pp-body::-webkit-scrollbar { width: 4px; }
.pp-body::-webkit-scrollbar-thumb { background: #C8D8CE; border-radius: 99px; }

/* Avatar */
.pp-avatar-section {
  display: flex; flex-direction: column; align-items: center;
  gap: 8px; padding: 22px 0 14px;
}
.pp-avatar-wrap { position: relative; width: 90px; height: 90px; }
.pp-avatar-img {
  width: 90px; height: 90px; border-radius: 50%;
  object-fit: cover; border: 3px solid #52B788;
  display: block; box-sizing: border-box;
}
.pp-avatar-initials {
  width: 90px; height: 90px; border-radius: 50%;
  background: linear-gradient(135deg, #52B788, #1B4332);
  display: flex; align-items: center; justify-content: center;
  font-size: 28px; font-weight: 800; color: #fff;
  border: 3px solid #52B788;
}
.pp-avatar-edit {
  position: absolute; bottom: 2px; right: 2px;
  width: 28px; height: 28px; border-radius: 50%;
  background: #52B788; border: 2px solid #fff;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; color: #fff;
}
.pp-avatar-edit:hover { background: #2D6A4F; }
.pp-avatar-hint { font-size: 11px; color: #8A9490; }
.pp-file-hidden { display: none; }

/* Role badge */
.pp-role-badge {
  display: flex; align-items: center; gap: 6px;
  margin: 0 24px 18px;
  padding: 7px 14px; border-radius: 99px;
  background: #EDFAF2; color: #2D6A4F;
  font-size: 12px; font-weight: 700; text-transform: uppercase;
  letter-spacing: 0.5px; justify-content: center;
}

/* Sections */
.pp-section-title {
  font-size: 11px; font-weight: 700; text-transform: uppercase;
  letter-spacing: 0.8px; color: #8A9490;
  padding: 0 24px; margin-bottom: 12px;
}
.pp-divider {
  height: 1px; background: #E2EAE6;
  margin: 20px 24px;
}

/* Fields */
.pp-field { margin: 0 24px 14px; }
.pp-field label {
  display: block; font-size: 12px; font-weight: 600;
  color: #8A9490; margin-bottom: 5px;
}
.pp-field input {
  width: 100%; height: 42px; box-sizing: border-box;
  border: 1.5px solid #D1DDD6; border-radius: 9px;
  padding: 0 12px; font-size: 13px; color: #1A2B22;
  background: #FAFCFB; outline: none;
  transition: border-color 0.15s;
}
.pp-field input:focus { border-color: #52B788; background: #fff; }

/* Password fields */
.pp-pw-wrap { position: relative; }
.pp-pw-wrap input { padding-right: 38px; }
.pp-pw-eye {
  position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
  background: none; border: none; cursor: pointer; color: #8A9490; padding: 4px;
}
.pp-pw-eye:hover { color: #2D6A4F; }
.pp-pw-strength { display: flex; align-items: center; gap: 8px; margin-top: 6px; }
.pp-strength-bar {
  flex: 1; height: 4px; background: #E2EAE6; border-radius: 99px; overflow: hidden;
}
.pp-strength-bar span { display: block; height: 100%; border-radius: 99px; transition: width 0.3s, background 0.3s; }
.pp-strength-label { font-size: 11px; font-weight: 600; min-width: 52px; text-align: right; }

/* Readonly row */
.pp-readonly-row {
  display: flex; justify-content: space-between; align-items: center;
  margin: 0 24px 14px; padding: 8px 12px;
  background: #F4F6F5; border-radius: 8px;
}
.pp-ro-label { font-size: 11px; color: #8A9490; }
.pp-ro-val   { font-size: 12px; font-weight: 600; color: #1A2B22; }

/* Feedback */
.pp-success-msg, .pp-error-msg {
  display: flex; align-items: center; gap: 8px;
  margin: 0 24px 14px; padding: 10px 14px; border-radius: 9px;
  font-size: 13px; font-weight: 500;
}
.pp-success-msg { background: #F0FFF4; border: 1px solid #9AE6B4; color: #276749; }
.pp-error-msg   { background: #FFF5F5; border: 1px solid #FEB2B2; color: #C53030; }

/* Label hint */
.pp-label-hint { font-size: 11px; font-weight: 400; color: #A0ABA6; }

/* Footer */
.pp-footer {
  display: flex; gap: 10px;
  padding: 14px 24px;
  border-top: 1px solid #E2EAE6;
  flex-shrink: 0;
}
.pp-btn-save {
  flex: 1; display: flex; align-items: center; gap: 7px;
  height: 42px; border-radius: 10px;
  background: #2D6A4F; border: none; cursor: pointer;
  color: #fff; font-size: 13px; font-weight: 700;
  justify-content: center; transition: background 0.15s;
}
.pp-btn-save:hover:not(:disabled) { background: #1B4332; }
.pp-btn-save:disabled { opacity: .6; cursor: default; }
.pp-btn-cancel {
  height: 42px; padding: 0 20px; border-radius: 10px;
  background: #F4F6F5; border: 1.5px solid #D1DDD6;
  color: #555B5A; font-size: 13px; font-weight: 600; cursor: pointer;
  transition: background 0.15s;
}
.pp-btn-cancel:hover { background: #E8EFEC; }

/* Spinner */
.pp-spin { animation: pp-rotate 0.8s linear infinite; }
@keyframes pp-rotate { to { transform: rotate(360deg); } }

/* Transitions */
.pp-fade-enter-active, .pp-fade-leave-active { transition: opacity 0.2s; }
.pp-fade-enter-from, .pp-fade-leave-to { opacity: 0; }

.pp-slide-enter-active, .pp-slide-leave-active { transition: opacity 0.22s, transform 0.25s cubic-bezier(.4,0,.2,1); }
.pp-slide-enter-from, .pp-slide-leave-to { opacity: 0; transform: translate(-50%, -46%) scale(0.95); }
</style>
