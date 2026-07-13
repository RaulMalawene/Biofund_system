<template>
  <div class="app-shell">

    <!-- ── SIDEBAR ── -->
    <aside class="sidebar" :class="{ 'sidebar-open': sidebarOpen }">
      <router-link to="/" class="sidebar-logo">
        <img src="../../Imagem/logotipo.png" alt="Biofund" class="sidebar-logo-img" />
      </router-link>
      <button class="sidebar-close-btn" @click="sidebarOpen = false" aria-label="Fechar menu">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 16 16"><path d="M3 3l10 10M13 3L3 13" stroke-linecap="round"/></svg>
      </button>
      <nav class="sidebar-nav" @click="sidebarOpen = false">
        <router-link class="nav-item" to="/admin/dashboard">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <rect x="1" y="1" width="6" height="6" rx="1.5" />
            <rect x="9" y="1" width="6" height="6" rx="1.5" />
            <rect x="1" y="9" width="6" height="6" rx="1.5" />
            <rect x="9" y="9" width="6" height="6" rx="1.5" />
          </svg>
          Dashboard
        </router-link>
        <router-link class="nav-item" to="/admin/validacao">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <rect x="2" y="1" width="12" height="14" rx="1.5" />
            <path d="M5 6h6M5 9h4" stroke-linecap="round" />
            <path d="M9 1v3h4" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          Validação
        </router-link>
        <router-link class="nav-item" to="/admin/utilizadores">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <circle cx="8" cy="6" r="3" />
            <path d="M2 14c0-2.761 2.686-5 6-5s6 2.239 6 5" stroke-linecap="round" />
          </svg>
          Utilizadores
        </router-link>
        <router-link class="nav-item" to="/admin/historico">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <rect x="2" y="1" width="10" height="14" rx="1.5" />
            <path d="M5 5h4M5 8h4M5 11h2" stroke-linecap="round" />
            <path d="M10 1v4h4" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          Histórico de Ocorrências
        </router-link>
        <router-link class="nav-item" to="/admin/categorias">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <circle cx="5" cy="5" r="2" />
            <circle cx="11" cy="5" r="2" />
            <circle cx="5" cy="11" r="2" />
            <circle cx="11" cy="11" r="2" />
          </svg>
          Categorias
        </router-link>
        <a class="nav-item active">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <path d="M2 13L6 4l4 6 3-3 3 4" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          Projectos
        </a>
      </nav>
      <div class="sidebar-footer">
        <button class="btn-logout" @click="handleLogout">
          <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <path d="M6 14H3a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h3M10 11l3-3-3-3M13 8H6" stroke-linecap="round"
              stroke-linejoin="round" />
          </svg>
          Terminar Sessão
        </button>
      </div>
    </aside>
    <div class="sidebar-backdrop" v-if="sidebarOpen" @click="sidebarOpen = false"></div>

    <!-- ── MAIN ── -->
    <div class="main">

      <!-- TOPBAR -->
      <header class="topbar">
        <button class="topbar-menu-btn" @click="sidebarOpen = true" aria-label="Abrir menu">
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16"><path d="M2 4h12M2 8h12M2 12h12" stroke-linecap="round"/></svg>
        </button>
        <div class="search-wrap">
          <svg width="15" height="15" fill="none" stroke="#8A9490" stroke-width="1.8" viewBox="0 0 16 16">
            <circle cx="7" cy="7" r="5" />
            <path d="M12 12l3 3" stroke-linecap="round" />
          </svg>
          <input type="text" placeholder="Pesquisar projectos…" v-model="search" />
        </div>
        <div class="topbar-spacer"></div>
        <AdminNotificationPanel />
        <AdminProfilePanel />
      </header>

      <!-- CONTENT -->
      <main class="content">

        <div class="breadcrumb">Gestão de Sistema</div>

        <div class="page-title-row">
          <div>
            <h1>Projectos</h1>
            <p>Administre os projectos para classificar as ocorrências ambientais em todo o território nacional.</p>
          </div>
          <button class="btn-green-sm" @click="openAdd">
            <svg width="14" height="14" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 14 14">
              <path d="M7 2v10M2 7h10" stroke-linecap="round" />
            </svg>
            Adicionar Projecto
          </button>
        </div>

        <!-- Toolbar -->
        <div class="toolbar">
          <div class="search-proj">
            <svg width="14" height="14" fill="none" stroke="#8A9490" stroke-width="1.8" viewBox="0 0 16 16">
              <circle cx="7" cy="7" r="5" />
              <path d="M12 12l3 3" stroke-linecap="round" />
            </svg>
            <input type="text" placeholder="Pesquisar por nome, código ou descrição…" v-model="search" />
          </div>
        </div>

        <!-- Loading -->
        <div class="loading-row" v-if="loading">
          <span class="spin"></span> A carregar projectos…
        </div>

        <!-- Project grid -->
        <div class="proj-grid" v-else>
          <div class="proj-card" v-for="p in filteredProjects" :key="p.id">
            <div class="card-top">
              <div class="card-icon-wrap" :style="{ background: iconColor(p.icon) + '22' }">
                <svg width="24" height="24" fill="none" :stroke="iconColor(p.icon)" stroke-width="1.7"
                  viewBox="0 0 24 24" v-html="ICON_PATHS[p.icon || 'nature']"></svg>
              </div>
              <div class="card-top-right">
                <span class="proj-code">{{ p.code }}</span>
                <span :class="p.is_active ? 'badge-ativa' : 'badge-inativa'">
                  {{ p.is_active ? 'Ativa' : 'Inativa' }}
                </span>
              </div>
            </div>
            <div class="proj-name">{{ p.name }}</div>
            <div class="proj-desc">{{ p.description ?? '-' }}</div>
            <div class="card-footer">
              <div>
                <div class="occ-label">Ocorrências</div>
                <div class="occ-val">{{ p.occurrences_count ?? 0 }}</div>
              </div>
              <div class="card-actions">
                <button class="btn-card-action btn-card-edit" @click="openEdit(p)" title="Editar">
                  <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 14 14">
                    <path d="M9.5 2.5l2 2L4 12H2v-2z" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                </button>
                <button class="btn-card-action btn-card-delete" @click="confirmDeleteProject(p)" title="Apagar">
                  <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 14 14">
                    <path d="M2 3.5h10M5.5 3.5V2.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v1M3.5 3.5l.5 8h6l.5-8" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <!-- New project card -->
          <div class="new-proj-card" @click="openAdd">
            <div class="new-proj-icon">
              <svg width="20" height="20" fill="none" stroke="#8A9490" stroke-width="2" viewBox="0 0 20 20">
                <path d="M10 4v12M4 10h12" stroke-linecap="round" />
              </svg>
            </div>
            <span class="new-proj-label">Novo projecto</span>
          </div>
        </div>

        <!-- Notice -->
        <div class="notice" v-if="!loading">
          <div class="notice-icon">
            <svg width="16" height="16" fill="none" stroke="#8A9490" stroke-width="1.7" viewBox="0 0 16 16">
              <circle cx="8" cy="8" r="6" />
              <path d="M8 7v4M8 5.5h.01" stroke-linecap="round" stroke-width="1.8" />
            </svg>
          </div>
          <div>
            <div class="notice-title">Nota sobre Taxonomia</div>
            <div class="notice-desc">A alteração do nome de um projecto irá actualizar automaticamente todos os registos
              históricos de ocorrências associadas. Recomenda-se cautela ao desactivar projectos que possuam um elevado
              número de ocorrências.</div>
          </div>
        </div>

      </main>

      <footer class="dash-footer">
        <span>© 2026 BIOFUND Admin · Sistema de Gestão Ambiental de Moçambique</span>
        <div><a href="#">Suporte Técnico</a><a href="#">Privacidade</a></div>
      </footer>
    </div>

    <!-- ── ADD / EDIT MODAL ── -->
    <transition name="fade">
      <div v-if="modal.show" class="modal-overlay" @click.self="modal.show = false">
        <div class="modal-panel" @click.stop>
          <div class="modal-hd">
            <div>
              <h3>{{ modal.editing ? 'Editar Projecto' : 'Novo Projecto' }}</h3>
              <p>{{ modal.editing ? 'Actualize os dados do projecto.' : 'Preencha os dados para criar um novo projecto.'
                }}</p>
            </div>
            <button class="btn-close" @click="modal.show = false">
              <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 13 13">
                <path d="M2 2l9 9M11 2L2 11" stroke-linecap="round" />
              </svg>
            </button>
          </div>

          <!-- Erro API -->
          <div class="api-error" v-if="formError">{{ formError }}</div>

          <div class="modal-row">
            <div class="mfield">
              <label>Código <span class="req">*</span></label>
              <input type="text" v-model="mf.code" placeholder="Ex: MOZ-001" maxlength="20"
                :class="{ 'f-err': fieldErrors.code }" />
              <span class="err-msg" v-if="fieldErrors.code">{{ fieldErrors.code }}</span>
              <span class="f-hint">Único, máx. 20 caracteres</span>
            </div>
            <div class="mfield">
              <label>Estado</label>
              <select v-model="mf.is_active">
                <option :value="true">Ativo</option>
                <option :value="false">Inativo</option>
              </select>
            </div>
          </div>

          <div class="mfield">
            <label>Nome do Projecto <span class="req">*</span></label>
            <input type="text" v-model="mf.name" placeholder="Ex: MozRural" :class="{ 'f-err': fieldErrors.name }" />
            <span class="err-msg" v-if="fieldErrors.name">{{ fieldErrors.name }}</span>
          </div>

          <div class="mfield">
            <label>Descrição</label>
            <textarea v-model="mf.description" placeholder="Descreva o âmbito do projecto…"></textarea>
          </div>

          <div class="mfield">
            <label>Ícone</label>
            <div class="icon-grid">
              <div class="icon-opt" :class="{ sel: mf.icon === key }" v-for="(path, key) in ICON_PATHS" :key="key"
                @click="mf.icon = key" :title="ICON_LABELS[key]">
                <svg width="18" height="18" fill="none" :stroke="mf.icon === key ? iconColor(key) : '#8A9490'"
                  stroke-width="1.7" viewBox="0 0 24 24" v-html="path"></svg>
              </div>
            </div>
          </div>

          <div class="modal-actions">
            <button class="btn-mc" @click="modal.show = false">Cancelar</button>
            <button class="btn-ms" @click="saveProject" :disabled="saving || !mf.name.trim() || !mf.code.trim()">
              <span v-if="saving" class="spin-sm"></span>
              {{ saving ? 'A guardar…' : (modal.editing ? 'Guardar' : 'Criar Projecto') }}
            </button>
          </div>
        </div>
      </div>
    </transition>

    <!-- ── CONFIRMAR REMOÇÃO ── -->
    <transition name="fade">
      <div v-if="deleteModal.show" class="confirm-overlay" @click.self="deleteModal.show = false">
        <div class="confirm-modal" @click.stop>
          <div class="confirm-icon">
            <svg width="22" height="22" fill="none" stroke="#E53E3E" stroke-width="2" viewBox="0 0 24 24">
              <path d="M3 6h18M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2M5 6l1 14a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-14"
                stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </div>
          <h3>Apagar Projecto</h3>
          <p class="confirm-desc">Tem a certeza que deseja apagar o projecto <strong>{{ deleteModal.target?.name }}</strong>?
            Esta acção não pode ser desfeita.</p>
          <div class="confirm-actions">
            <button class="btn-confirm-cancel" @click="deleteModal.show = false">Cancelar</button>
            <button class="btn-confirm-delete" @click="doRemoveProject" :disabled="deleteModal.loading">
              {{ deleteModal.loading ? 'A apagar…' : 'Apagar' }}
            </button>
          </div>
        </div>
      </div>
    </transition>

    <!-- TOAST -->
    <transition name="toast-slide">
      <div v-if="toast.show" class="toast" :class="{ red: toast.red }">
        <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 16 16">
          <circle cx="8" cy="8" r="6" />
          <path d="M5.5 8l2 2 3.5-4" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        {{ toast.msg }}
      </div>
    </transition>

  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { InternalService } from '@/api/services/internal.service'
import AdminProfilePanel from '@/components/AdminProfilePanel.vue'
import AdminNotificationPanel from '@/components/AdminNotificationPanel.vue'

const router = useRouter()
const auth = useAuthStore()

async function handleLogout() {
  await auth.logout()
  router.push('/acessoRestrito')
}

// ── Constantes visuais ────────────────────────────────────────
const ICON_PATHS = {
  nature: '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M2 12h2M20 12h2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" stroke-linecap="round"/>',
  tree: '<path d="M12 2L4 12h5v8h6v-8h5z" stroke-linecap="round" stroke-linejoin="round"/>',
  water: '<path d="M12 2C12 2 4 10 4 15a8 8 0 0 0 16 0c0-5-8-13-8-13z" stroke-linecap="round"/>',
  fire: '<path d="M12 2C12 2 8 8 8 13a4 4 0 0 0 8 0C16 9 12 2 12 2z" stroke-linecap="round"/><path d="M12 22v-6" stroke-linecap="round"/>',
  drop: '<path d="M12 2C12 2 5 11 5 16a7 7 0 0 0 14 0C19 11 12 2 12 2z" stroke-linecap="round"/>',
  person: '<circle cx="12" cy="8" r="4"/><path d="M5 20c0-4 3.134-7 7-7s7 3 7 7" stroke-linecap="round"/>',
  leaf: '<path d="M17 8C17 8 16 2 6 2C6 12 12 16 12 16" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 16C12 16 8 12 6 6" stroke-linecap="round"/>',
  fish: '<path d="M2 12s3-6 10-6 10 6 10 6-3 6-10 6S2 12 2 12z"/><circle cx="16" cy="12" r="1.5" fill="currentColor" stroke="none"/>',
}

const ICON_COLORS = {
  nature: '#F59E0B', tree: '#2D6A4F', water: '#3B82F6', fire: '#EF4444',
  drop: '#06B6D4', person: '#8B5CF6', leaf: '#52B788', fish: '#0EA5E9',
}

const ICON_LABELS = {
  nature: 'Natureza', tree: 'Árvore', water: 'Água', fire: 'Fogo',
  drop: 'Gota', person: 'Pessoas', leaf: 'Folha', fish: 'Peixe',
}

function iconColor(icon) {
  return ICON_COLORS[icon] ?? '#52B788'
}

// ── Estado ────────────────────────────────────────────────────
const sidebarOpen = ref(false)
const loading = ref(false)
const saving = ref(false)
const search = ref('')
const projects = ref([])
const formError = ref('')
const fieldErrors = reactive({})

const toast = reactive({ show: false, msg: '', red: false })

function showToast(msg, red = false) {
  toast.msg = msg; toast.red = red; toast.show = true
  setTimeout(() => { toast.show = false }, 3000)
}

// ── Filtro ────────────────────────────────────────────────────
const filteredProjects = computed(() => {
  const q = search.value.toLowerCase().trim()
  if (!q) return projects.value
  return projects.value.filter(p =>
    p.name.toLowerCase().includes(q) ||
    p.code.toLowerCase().includes(q) ||
    p.description?.toLowerCase().includes(q)
  )
})

// ── Carregar projectos ────────────────────────────────────────
onMounted(loadProjects)

async function loadProjects() {
  loading.value = true
  try {
    const data = await InternalService.getProjects()
    projects.value = data.projects ?? []
  } catch (err) {
    showToast('Erro ao carregar projectos.', true)
  } finally {
    loading.value = false
  }
}

// ── Modal ─────────────────────────────────────────────────────
const modal = reactive({ show: false, editing: false, id: null })
const mf = reactive({ code: '', name: '', description: '', icon: 'nature', is_active: true })

function openAdd() {
  modal.editing = false
  modal.id = null
  formError.value = ''
  Object.keys(fieldErrors).forEach(k => delete fieldErrors[k])
  Object.assign(mf, { code: '', name: '', description: '', icon: 'nature', is_active: true })
  modal.show = true
}

function openEdit(p) {
  modal.editing = true
  modal.id = p.id
  formError.value = ''
  Object.keys(fieldErrors).forEach(k => delete fieldErrors[k])
  Object.assign(mf, {
    code: p.code,
    name: p.name,
    description: p.description ?? '',
    icon: p.icon ?? 'nature',
    is_active: p.is_active,
  })
  modal.show = true
}

async function saveProject() {
  formError.value = ''
  Object.keys(fieldErrors).forEach(k => delete fieldErrors[k])

  if (!mf.code.trim()) { fieldErrors.code = 'O código é obrigatório.'; return }
  if (!mf.name.trim()) { fieldErrors.name = 'O nome é obrigatório.'; return }

  const payload = {
    code: mf.code.trim().toUpperCase(),
    name: mf.name.trim(),
    description: mf.description.trim() || null,
    icon: mf.icon,
    is_active: mf.is_active,
  }

  saving.value = true
  try {
    if (modal.editing) {
      const result = await InternalService.updateProject(modal.id, payload)
      const idx = projects.value.findIndex(p => p.id === modal.id)
      if (idx !== -1) projects.value[idx] = { ...projects.value[idx], ...result.project }
      showToast('Projecto actualizado com sucesso!')
    } else {
      const result = await InternalService.createProject(payload)
      projects.value.push({ ...result.project, occurrences_count: 0 })
      showToast('Projecto criado com sucesso!')
    }
    modal.show = false
  } catch (err) {
    if (err.response?.status === 422) {
      const errors = err.response.data.errors ?? {}
      Object.entries(errors).forEach(([field, msgs]) => { fieldErrors[field] = msgs[0] })
      formError.value = 'Corrija os erros e tente novamente.'
    } else {
      formError.value = err.response?.data?.message ?? 'Erro ao guardar. Tente novamente.'
    }
  } finally {
    saving.value = false
  }
}

// ── Apagar ────────────────────────────────────────────────────
const deleteModal = reactive({ show: false, loading: false, target: null })

function confirmDeleteProject(p) {
  deleteModal.target = p
  deleteModal.show = true
}

async function doRemoveProject() {
  if (!deleteModal.target) return
  const target = deleteModal.target

  deleteModal.loading = true
  try {
    await InternalService.deleteProject(target.id)
    projects.value = projects.value.filter(x => x.id !== target.id)
    showToast('Projecto apagado com sucesso!')
  } catch (err) {
    showToast(err.response?.data?.message ?? 'Erro ao apagar projecto.', true)
  } finally {
    deleteModal.loading = false
    deleteModal.show = false
    deleteModal.target = null
  }
}
</script>

<style scoped>
.app-shell {
  display: flex;
  width: 100%;
  height: 100vh;
  overflow: hidden;
  background: #fff;
}

/* SIDEBAR */
.sidebar {
  width: 210px;
  flex-shrink: 0;
  background: var(--white);
  box-shadow: 2px 0 18px rgba(0, 0, 0, .07);
  display: flex;
  flex-direction: column;
  height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
  z-index: 50;
}

.sidebar-logo {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px 16px 18px;
  border-bottom: 1px solid rgba(0, 0, 0, .06);
  text-decoration: none;
}

.sidebar-logo-img {
  height: 56px;
  width: auto;
  max-width: 100%;
  object-fit: contain;
}

.sidebar-nav {
  flex: 1;
  padding: 14px 10px;
  overflow-y: auto;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  border-radius: 9px;
  margin-bottom: 2px;
  font-size: 13px;
  font-weight: 500;
  color: var(--text-gray);
  cursor: pointer;
  transition: background 0.15s, color 0.15s;
  text-decoration: none;
}

.nav-item:hover {
  background: var(--green-bg);
  color: var(--green-mid);
}

.nav-item.active,
.nav-item.router-link-exact-active {
  background: var(--green-bg);
  color: var(--green-mid);
  font-weight: 700;
}

.nav-item svg {
  flex-shrink: 0;
  opacity: 0.75;
}

.nav-item.active svg,
.nav-item.router-link-exact-active svg {
  opacity: 1;
}

.sidebar-footer {
  padding: 14px 10px;
  border-top: 1px solid rgba(0, 0, 0, .06);
}

.btn-logout {
  display: flex;
  align-items: center;
  gap: 9px;
  width: 100%;
  background: none;
  border: none;
  cursor: pointer;
  padding: 10px 12px;
  border-radius: 9px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 500;
  color: #E53E3E;
  transition: background 0.15s;
}

.btn-logout:hover {
  background: #FFF5F5;
}

/* MAIN */
.main {
  margin-left: 210px;
  flex: 1;
  display: flex;
  flex-direction: column;
  height: 100vh;
  overflow: hidden;
}

/* TOPBAR */
.topbar {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 0 28px;
  height: 58px;
  background: var(--white);
  box-shadow: 0 1px 12px rgba(0, 0, 0, .07);
  flex-shrink: 0;
  z-index: 10;
}

.search-wrap {
  flex: 1;
  display: flex;
  align-items: center;
  gap: 10px;
  background: #F4F6F5;
  border: 1.5px solid var(--border);
  border-radius: 9px;
  padding: 8px 14px;
  max-width: 420px;
}

.search-wrap input {
  border: none;
  outline: none;
  background: transparent;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  color: var(--text-dark);
  width: 100%;
}

.search-wrap input::placeholder {
  color: var(--text-light);
}

.topbar-spacer {
  flex: 1;
}

.notif-btn {
  position: relative;
  width: 38px;
  height: 38px;
  background: #F4F6F5;
  border: 1.5px solid var(--border);
  border-radius: 9px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.notif-dot {
  position: absolute;
  top: 7px;
  right: 8px;
  width: 7px;
  height: 7px;
  border-radius: 50%;
  background: #E53E3E;
  border: 1.5px solid var(--white);
}

.admin-info {
  display: flex;
  align-items: center;
  gap: 10px;
}

.admin-name {
  font-size: 13px;
  font-weight: 700;
  color: var(--text-dark);
}

.admin-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: linear-gradient(135deg, #52B788, #1B4332);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 13px;
  font-weight: 800;
  color: #fff;
  flex-shrink: 0;
}

/* CONTENT */
.content {
  flex: 1;
  overflow-y: auto;
  padding: 24px 28px 32px;
  background: #F2F6F4;
}

.content::-webkit-scrollbar {
  width: 5px;
}

.content::-webkit-scrollbar-thumb {
  background: #C8D8CE;
  border-radius: 99px;
}

.breadcrumb {
  font-size: 11.5px;
  font-weight: 600;
  color: var(--green-mid);
  margin-bottom: 6px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.page-title-row {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  margin-bottom: 20px;
}

.page-title-row h1 {
  font-size: 22px;
  font-weight: 800;
  margin-bottom: 5px;
}

.page-title-row p {
  font-size: 13px;
  color: var(--text-gray);
  max-width: 480px;
  line-height: 1.55;
}

.btn-green-sm {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  background: var(--green-mid);
  color: #fff;
  border: none;
  border-radius: 9px;
  padding: 11px 20px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.2s;
  flex-shrink: 0;
}

.btn-green-sm:hover {
  background: var(--green-dark);
}

/* TOOLBAR */
.toolbar {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 20px;
}

.search-proj {
  flex: 1;
  display: flex;
  align-items: center;
  gap: 9px;
  background: var(--white);
  border: 1.5px solid var(--border);
  border-radius: 9px;
  padding: 9px 14px;
  transition: border-color 0.2s;
}

.search-proj:focus-within {
  border-color: var(--green-light);
  box-shadow: 0 0 0 3px rgba(82, 183, 136, .12);
}

.search-proj input {
  border: none;
  outline: none;
  background: transparent;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  color: var(--text-dark);
  width: 100%;
}

.search-proj input::placeholder {
  color: var(--text-light);
}

/* LOADING */
.loading-row {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 40px;
  font-size: 13px;
  color: var(--text-gray);
  justify-content: center;
}

.spin {
  display: inline-block;
  width: 16px;
  height: 16px;
  border: 2px solid #C8D8CE;
  border-top-color: var(--green-mid);
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
}

.spin-sm {
  display: inline-block;
  width: 13px;
  height: 13px;
  border: 2px solid rgba(255, 255, 255, .35);
  border-top-color: #fff;
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

/* PROJECT GRID */
.proj-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
  margin-bottom: 20px;
}

.proj-card {
  background: var(--white);
  border-radius: 16px;
  padding: 20px 20px 18px;
  display: flex;
  flex-direction: column;
  box-shadow: 0 1px 3px rgba(0, 0, 0, .05), 0 6px 20px rgba(0, 0, 0, .07);
  transition: box-shadow 0.2s, transform 0.2s;
}

.proj-card:hover {
  box-shadow: 0 6px 24px rgba(0, 0, 0, .12);
  transform: translateY(-2px);
}

.card-top {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  margin-bottom: 14px;
}

.card-icon-wrap {
  width: 46px;
  height: 46px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.card-top-right {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 5px;
}

.proj-code {
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 1px;
  color: var(--text-light);
  text-transform: uppercase;
  background: #F4F6F5;
  border-radius: 5px;
  padding: 2px 7px;
}

.badge-ativa {
  font-size: 11px;
  font-weight: 700;
  color: var(--green-mid);
  background: var(--green-pale);
  border: 1.5px solid #68D391;
  border-radius: 99px;
  padding: 2px 10px;
}

.badge-inativa {
  font-size: 11px;
  font-weight: 700;
  color: #744210;
  background: #FEFCBF;
  border: 1.5px solid #F6D860;
  border-radius: 99px;
  padding: 2px 10px;
}

.proj-name {
  font-size: 16px;
  font-weight: 800;
  margin-bottom: 6px;
  color: var(--text-dark);
}

.proj-desc {
  font-size: 12px;
  color: var(--text-gray);
  line-height: 1.6;
  flex: 1;
  margin-bottom: 16px;
}

.card-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-top: 1px solid var(--border);
  padding-top: 14px;
  margin-top: auto;
}

.occ-label {
  font-size: 9.5px;
  font-weight: 700;
  letter-spacing: 0.7px;
  color: var(--text-light);
  text-transform: uppercase;
  margin-bottom: 2px;
}

.occ-val {
  font-size: 20px;
  font-weight: 800;
  color: var(--text-dark);
}

.card-actions {
  display: flex;
  gap: 8px;
}

.btn-card-action {
  width: 30px;
  height: 30px;
  border-radius: 7px;
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background 0.15s;
}

.btn-card-edit {
  background: var(--green-bg);
  color: var(--green-mid);
}

.btn-card-edit:hover {
  background: var(--green-pale);
}

.btn-card-delete {
  background: #FDEDED;
  color: #E53E3E;
}

.btn-card-delete:hover {
  background: #FBD5D5;
}

.new-proj-card {
  background: var(--white);
  border: 2px dashed var(--border);
  border-radius: 16px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 12px;
  cursor: pointer;
  padding: 40px 20px;
  transition: border-color 0.2s, background 0.2s;
  min-height: 200px;
}

.new-proj-card:hover {
  border-color: var(--green-light);
  background: var(--green-bg);
}

.new-proj-icon {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background: #F4F6F5;
  border: 1.5px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.2s, border-color 0.2s;
}

.new-proj-card:hover .new-proj-icon {
  background: var(--green-pale);
  border-color: var(--green-light);
}

.new-proj-label {
  font-size: 13px;
  font-weight: 600;
  color: var(--text-gray);
}

.new-proj-card:hover .new-proj-label {
  color: var(--green-mid);
}

/* NOTICE */
.notice {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  background: var(--white);
  border-radius: 16px;
  padding: 16px 18px;
  margin-top: 6px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, .05), 0 6px 20px rgba(0, 0, 0, .07);
}

.notice-icon {
  width: 34px;
  height: 34px;
  border-radius: 8px;
  background: #F4F6F5;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  margin-top: 1px;
}

.notice-title {
  font-size: 13px;
  font-weight: 700;
  margin-bottom: 4px;
}

.notice-desc {
  font-size: 12.5px;
  color: var(--text-gray);
  line-height: 1.65;
}

/* FOOTER */
.dash-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 28px;
  background: var(--white);
  box-shadow: 0 -1px 10px rgba(0, 0, 0, .06);
  font-size: 11.5px;
  color: var(--text-light);
  flex-shrink: 0;
}

.dash-footer a {
  color: var(--text-light);
  text-decoration: none;
  margin-left: 16px;
}

.dash-footer a:hover {
  color: var(--green-mid);
}

/* MODAL */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.45);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 200;
  padding: 16px;
}

.modal-panel {
  background: var(--white);
  border-radius: 16px;
  padding: 30px 32px 26px;
  width: 500px;
  max-width: 95vw;
  box-shadow: 0 16px 60px rgba(0, 0, 0, .18);
}

.modal-hd {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  margin-bottom: 20px;
}

.modal-hd h3 {
  font-size: 16px;
  font-weight: 800;
}

.modal-hd p {
  font-size: 12.5px;
  color: var(--text-gray);
  margin-top: 3px;
}

.btn-close {
  width: 32px;
  height: 32px;
  background: #F4F6F5;
  border: 1.5px solid var(--border);
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.btn-close:hover {
  background: #FFF5F5;
  border-color: #FC8181;
}

.modal-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
  margin-bottom: 0;
}

.mfield {
  display: flex;
  flex-direction: column;
  gap: 6px;
  margin-bottom: 16px;
}

.mfield label {
  font-size: 12.5px;
  font-weight: 600;
  color: var(--text-dark);
}

.mfield input,
.mfield select,
.mfield textarea {
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  color: var(--text-dark);
  border: 1.5px solid var(--border);
  border-radius: 8px;
  padding: 10px 13px;
  outline: none;
  transition: border-color 0.2s, box-shadow 0.2s;
  width: 100%;
  background: var(--white);
}

.mfield textarea {
  min-height: 80px;
  resize: vertical;
}

.mfield input:focus,
.mfield select:focus,
.mfield textarea:focus {
  border-color: var(--green-light);
  box-shadow: 0 0 0 3px rgba(82, 183, 136, .12);
}

.f-err {
  border-color: #FC8181 !important;
}

.err-msg {
  font-size: 11.5px;
  color: #E53E3E;
}

.req {
  color: #E53E3E;
  margin-left: 2px;
}

.f-hint {
  font-size: 11px;
  color: var(--text-light);
}

.api-error {
  background: #FFF5F5;
  border: 1px solid #FEB2B2;
  border-radius: 8px;
  padding: 10px 14px;
  font-size: 12.5px;
  color: #C53030;
  margin-bottom: 14px;
}

.icon-grid {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
  margin-top: 4px;
}

.icon-opt {
  width: 40px;
  height: 40px;
  border-radius: 9px;
  background: #F4F6F5;
  border: 2px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background 0.15s, border-color 0.15s;
}

.icon-opt:hover,
.icon-opt.sel {
  background: var(--green-bg);
  border-color: var(--green-mid);
}

.modal-actions {
  display: flex;
  gap: 10px;
  margin-top: 8px;
}

.btn-mc {
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
}

.btn-mc:hover {
  border-color: var(--text-gray);
}

.btn-ms {
  flex: 1;
  background: var(--green-mid);
  color: #fff;
  border: none;
  border-radius: 9px;
  padding: 11px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  transition: background 0.2s;
}

.btn-ms:hover:not(:disabled) {
  background: var(--green-dark);
}

.btn-ms:disabled {
  background: #A0C4B0;
  cursor: not-allowed;
}

/* CONFIRMAR REMOÇÃO */
.confirm-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.45);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 300;
  padding: 16px;
}

.confirm-modal {
  background: var(--white);
  border-radius: 16px;
  padding: 32px 32px 28px;
  width: 380px;
  max-width: 95vw;
  box-shadow: 0 16px 60px rgba(0, 0, 0, .18);
  text-align: left;
}

.confirm-icon {
  width: 52px;
  height: 52px;
  border-radius: 50%;
  background: #FFF5F5;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 14px;
}

.confirm-modal h3 {
  font-size: 16px;
  font-weight: 800;
  margin-bottom: 4px;
}

.confirm-desc {
  font-size: 13px;
  color: var(--text-gray);
  line-height: 1.6;
  margin-bottom: 22px;
}

.confirm-actions {
  display: flex;
  gap: 10px;
}

.btn-confirm-cancel {
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
}

.btn-confirm-cancel:hover {
  border-color: var(--text-gray);
}

.btn-confirm-delete {
  flex: 1;
  background: #E53E3E;
  color: #fff;
  border: none;
  border-radius: 9px;
  padding: 11px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
}

.btn-confirm-delete:hover:not(:disabled) {
  background: #C53030;
}

.btn-confirm-delete:disabled {
  background: #F1A9A9;
  cursor: not-allowed;
}

/* TOAST */
.toast {
  position: fixed;
  bottom: 24px;
  right: 24px;
  z-index: 500;
  background: var(--green-dark);
  color: #fff;
  border-radius: 12px;
  padding: 13px 18px;
  display: flex;
  align-items: center;
  gap: 9px;
  font-size: 13px;
  font-weight: 600;
  box-shadow: 0 8px 28px rgba(0, 0, 0, .18);
}

.toast.red {
  background: #C53030;
}

/* TRANSITIONS */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.toast-slide-enter-active,
.toast-slide-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.toast-slide-enter-from,
.toast-slide-leave-to {
  opacity: 0;
  transform: translateY(16px);
}

/* ── Responsive: off-canvas sidebar ─────────── */
.topbar-menu-btn {
  display: none;
  align-items: center;
  justify-content: center;
  width: 36px; height: 36px;
  background: #F4F6F5;
  border: 1.5px solid var(--border);
  border-radius: 8px;
  cursor: pointer;
  flex-shrink: 0;
  color: var(--text-dark);
}
.topbar-menu-btn:hover { border-color: var(--green-light); }

.sidebar-close-btn {
  display: none;
  position: absolute;
  top: 14px; right: 12px;
  width: 30px; height: 30px;
  align-items: center;
  justify-content: center;
  background: #F4F6F5;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  color: var(--text-gray);
  z-index: 1;
}

.sidebar-backdrop { display: none; }

@media (max-width: 1024px) {
  .sidebar {
    transform: translateX(-100%);
    transition: transform 0.25s ease;
    z-index: 300;
    box-shadow: 10px 0 32px rgba(0,0,0,0.14);
  }
  .sidebar.sidebar-open { transform: translateX(0); }
  .sidebar-close-btn { display: flex; }
  .sidebar-backdrop {
    display: block;
    position: fixed;
    inset: 0;
    background: rgba(10,20,15,0.45);
    z-index: 250;
  }
  .main { margin-left: 0; }
  .topbar-menu-btn { display: flex; }

  .proj-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 640px) {
  .content { padding: 16px 14px 24px; }

  .topbar { padding: 0 14px; gap: 10px; }
  .search-wrap { max-width: none; }

  .page-title-row {
    flex-direction: column;
    align-items: stretch;
    gap: 12px;
  }
  .page-title-row p { max-width: none; }
  .btn-green-sm { justify-content: center; width: 100%; }

  .proj-grid { grid-template-columns: 1fr; }

  .modal-row { grid-template-columns: 1fr; }

  .modal-panel {
    width: 100%;
    max-width: calc(100vw - 24px);
    max-height: calc(100vh - 24px);
    overflow-y: auto;
    padding: 22px 18px 20px;
  }

  .confirm-modal {
    width: 100%;
    max-width: calc(100vw - 24px);
    max-height: calc(100vh - 24px);
    overflow-y: auto;
    padding: 24px 20px 20px;
  }

  .icon-grid { gap: 6px; }
  .icon-opt { width: 36px; height: 36px; }

  .dash-footer {
    flex-direction: column;
    align-items: flex-start;
    gap: 6px;
    padding: 12px 14px;
  }
}
</style>