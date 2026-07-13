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
            <path d="M8 1l1.5 3 3.5.5-2.5 2.5.5 3.5L8 9l-3 1.5.5-3.5L3 4.5 6.5 4z" />
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
        <a class="nav-item active">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <circle cx="5" cy="5" r="2" />
            <circle cx="11" cy="5" r="2" />
            <circle cx="5" cy="11" r="2" />
            <circle cx="11" cy="11" r="2" />
          </svg>
          Categorias
        </a>
        <router-link class="nav-item" to="/admin/projectos">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <path d="M2 13L6 4l4 6 3-3 3 4" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          Projectos
        </router-link>
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
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16"><path d="M2 4h12M2 8h12M2 12h12" stroke-linecap="round" /></svg>
        </button>
        <div class="search-wrap">
          <svg width="15" height="15" fill="none" stroke="#8A9490" stroke-width="1.8" viewBox="0 0 16 16">
            <circle cx="7" cy="7" r="5" />
            <path d="M12 12l3 3" stroke-linecap="round" />
          </svg>
          <input type="text" placeholder="Pesquisar categorias…" v-model="f.pesquisa" />
        </div>
        <div class="topbar-spacer"></div>
        <AdminNotificationPanel />
        <AdminProfilePanel />
      </header>

      <!-- CONTENT -->
      <main class="content">

        <div class="page-title-row">
          <div>
            <h1>Categorias</h1>
            <p>Gerencie as categorias de ocorrências ambientais do sistema.</p>
          </div>
          <button class="btn-nova" @click="openNew">
            <svg width="14" height="14" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 14 14">
              <path d="M7 2v10M2 7h10" stroke-linecap="round" />
            </svg>
            Nova Categoria
          </button>
        </div>

        <!-- FILTER CARD -->
        <div class="filter-card">
          <div class="filter-row">
            <div class="filter-group">
              <label>
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 12 12">
                  <circle cx="5.5" cy="5.5" r="4" />
                  <path d="M9.5 9.5l2 2" stroke-linecap="round" />
                </svg>
                Pesquisar
              </label>
              <input type="text" placeholder="Nome ou descrição…" v-model="f.pesquisa" />
            </div>
            <div class="filter-group">
              <label>Estado</label>
              <select v-model="f.estado">
                <option value="">Todas</option>
                <option value="ativa">Ativas</option>
                <option value="inativa">Inativas</option>
              </select>
            </div>
            <div class="filter-group">
              <label>Ícone</label>
              <select v-model="f.icone">
                <option value="">Todos os Ícones</option>
                <option v-for="(_, key) in ICON_DEFS" :key="key" :value="key">{{ ICON_LABELS[key] }}</option>
              </select>
            </div>
            <div class="filter-actions">
              <button class="btn-limpar" @click="limpar">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 14 14">
                  <path d="M2 2l10 10M12 2L2 12" stroke-linecap="round" />
                </svg>
                Limpar
              </button>
            </div>
          </div>
        </div>

        <!-- Loading -->
        <div class="loading-row" v-if="loading">
          <span class="spin"></span> A carregar categorias…
        </div>

        <!-- GRID -->
        <div class="cat-grid" v-else>
          <div v-for="cat in filteredCategorias" :key="cat.id" class="cat-card" :class="{ inativa: !cat.is_active }">
            <div class="cat-card-header">
              <div class="cat-icon-wrap"
                :style="{ background: (cat.color || '#52B788') + '22', borderColor: (cat.color || '#52B788') + '55' }">
                <svg width="22" height="22" fill="none" :stroke="cat.color || '#52B788'" stroke-width="1.7"
                  viewBox="0 0 22 22">
                  <path :d="ICON_DEFS[cat.icon || 'fauna']" />
                </svg>
              </div>
              <div class="cat-meta">
                <span class="cat-code">{{ cat.code }}</span>
              </div>
              <div class="cat-actions">
                <button class="btn-icon-sm" @click="openEdit(cat)" title="Editar">
                  <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 14 14">
                    <path d="M9.5 2.5l2 2L4 12H2v-2L9.5 2.5z" stroke-linejoin="round" />
                  </svg>
                </button>
                <button class="btn-icon-sm btn-icon-delete" @click="confirmDeleteCategory(cat)" title="Apagar">
                  <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 14 14">
                    <path d="M2 3.5h10M5.5 3.5V2.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v1M3.5 3.5l.5 8h6l.5-8" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                </button>

              </div>
            </div>

            <div class="cat-name">{{ cat.name }}</div>
            <div class="cat-desc" v-if="cat.description">{{ cat.description }}</div>

            <div class="cat-tags" v-if="cat.tags?.length">
              <span class="tag" v-for="t in cat.tags" :key="t">{{ t }}</span>
            </div>

            <div class="cat-footer">
              <span class="cat-stat">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 12 12">
                  <path d="M2 10l3-6 3 4 2-2 2 3" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                {{ cat.occurrences_count ?? 0 }} ocorrências
              </span>

              <span class="badge-ativa" :class="cat.is_active ? 'ativa' : 'inativa-badge'">
                {{ cat.is_active ? 'Ativa' : 'Inativa' }}
              </span>
            </div>
          </div>

          <div v-if="filteredCategorias.length === 0" class="empty-grid">
            Nenhuma categoria encontrada.
          </div>
        </div>

      </main>

      <footer class="dash-footer">
        <span>© 2026 BIOFUND Admin · Sistema de Gestão Ambiental de Moçambique</span>
        <div><a href="#">Suporte Técnico</a><a href="#">Privacidade</a></div>
      </footer>
    </div>

    <!-- ── MODAL CRIAR / EDITAR ── -->
    <transition name="fade">
      <div v-if="modalOpen" class="modal-overlay" @click.self="modalOpen = false"></div>
    </transition>
    <transition name="modal-pop">
      <div v-if="modalOpen" class="modal">
        <div class="modal-hd">
          <div>
            <h3>{{ editingId ? 'Editar Categoria' : 'Nova Categoria' }}</h3>
            <p>{{ editingId ? 'Actualize os dados da categoria.' : 'Preencha os dados da nova categoria.' }}</p>
          </div>
          <button class="btn-close" @click="modalOpen = false">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 13 13">
              <path d="M2 2l9 9M11 2L2 11" stroke-linecap="round" />
            </svg>
          </button>
        </div>

        <div class="modal-body">
          <!-- Erro API -->
          <div class="api-error" v-if="formError">{{ formError }}</div>

          <div class="f-row">
            <div class="f-group">
              <label>Código <span class="req">*</span></label>
              <input type="text" v-model="form.code" placeholder="Ex: FAU" maxlength="20"
                :class="{ 'f-err': fieldErrors.code }" />
              <span class="err-msg" v-if="fieldErrors.code">{{ fieldErrors.code }}</span>
              <span class="f-hint">Identificador único, máx. 20 caracteres</span>
            </div>
            <div class="f-group">
              <label>Estado</label>
              <select v-model="form.is_active">
                <option :value="true">Ativa</option>
                <option :value="false">Inativa</option>
              </select>
            </div>
          </div>

          <div class="f-group">
            <label>Nome da Categoria <span class="req">*</span></label>
            <input type="text" v-model="form.name" placeholder="Ex: Fauna" :class="{ 'f-err': fieldErrors.name }" />
            <span class="err-msg" v-if="fieldErrors.name">{{ fieldErrors.name }}</span>
          </div>

          <div class="f-group">
            <label>Descrição</label>
            <textarea v-model="form.description" placeholder="Descreva brevemente esta categoria…"></textarea>
          </div>

          <div class="f-row">
            <div class="f-group">
              <label>Ícone</label>
              <select v-model="form.icon">
                <option v-for="(_, key) in ICON_DEFS" :key="key" :value="key">{{ ICON_LABELS[key] }}</option>
              </select>
            </div>
            <div class="f-group">
              <label>Pré-visualização</label>
              <div class="icon-preview" :style="{ background: form.color + '22', borderColor: form.color + '88' }">
                <svg width="28" height="28" fill="none" :stroke="form.color" stroke-width="1.7" viewBox="0 0 22 22">
                  <path :d="ICON_DEFS[form.icon]" />
                </svg>
              </div>
            </div>
          </div>

          <div class="f-group">
            <label>Cor</label>
            <div class="color-options">
              <button v-for="c in COLOR_OPTIONS" :key="c" class="color-swatch" :class="{ selected: form.color === c }"
                :style="{ background: c }" @click="form.color = c" type="button"></button>
            </div>
          </div>

          <div class="f-group">
            <label>Tags <span class="f-hint-inline">(separadas por vírgula)</span></label>
            <input type="text" v-model="form.tagsStr" placeholder="Ex: animais, biodiversidade, ilegal" />
          </div>

          <div class="f-group">
            <label>Subcategorias <span class="f-hint-inline">(opcional)</span></label>

            <div class="sub-add-row">
              <input type="text" v-model="subsModal.newName" placeholder="Ex: Caça furtiva"
                :disabled="subsModal.saving"
                @keyup.enter="addSubcategoria" />
              <button type="button" class="btn-add-sub" :disabled="subsModal.saving || !subsModal.newName.trim()"
                @click="addSubcategoria">
                + Adicionar
              </button>
            </div>

            <!-- Nova categoria: subcategorias pendentes, criadas após guardar -->
            <div class="sub-list" v-if="!editingId">
              <div class="sub-empty" v-if="pendingSubcategorias.length === 0">
                Ainda não foram adicionadas subcategorias.
              </div>
              <div class="sub-item" v-for="(name, idx) in pendingSubcategorias" :key="idx">
                <span class="sub-name">{{ name }}</span>
                <button type="button" class="btn-sub-cancel" @click="removePendingSubcategoria(idx)">Remover</button>
              </div>
            </div>

            <!-- Categoria existente: subcategorias já guardadas -->
            <div class="sub-list" v-else>
              <div class="sub-empty" v-if="subsModal.loading">A carregar subcategorias…</div>
              <div class="sub-empty" v-else-if="subsModal.list.length === 0">
                Ainda não existem subcategorias.
              </div>
              <div class="sub-item" v-for="sub in subsModal.list" :key="sub.id">
                <template v-if="subsModal.editingId === sub.id">
                  <input type="text" class="sub-edit-input" v-model="subsModal.editName" @keyup.enter="saveSubEdit(sub)" />
                  <button type="button" class="btn-sub-save" @click="saveSubEdit(sub)">Guardar</button>
                  <button type="button" class="btn-sub-cancel" @click="subsModal.editingId = null">Cancelar</button>
                </template>
                <template v-else>
                  <span class="sub-name">{{ sub.name }}</span>
                  <span class="sub-count">{{ sub.occurrences_count ?? 0 }} ocorrências</span>
                  <span class="badge-ativa sub-badge" :class="sub.is_active ? 'ativa' : 'inativa-badge'"
                    @click="toggleSubActive(sub)" title="Clique para activar/desactivar" style="cursor: pointer">
                    {{ sub.is_active ? 'Ativa' : 'Inativa' }}
                  </span>
                  <button type="button" class="btn-icon-sm" @click="startSubEdit(sub)" title="Editar">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 14 14">
                      <path d="M9.5 2.5l2 2L4 12H2v-2L9.5 2.5z" stroke-linejoin="round" />
                    </svg>
                  </button>
                </template>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-ft">
          <button class="btn-cancelar" @click="modalOpen = false">Cancelar</button>
          <button class="btn-guardar" @click="guardar" :disabled="saving">
            <svg v-if="saving" class="spin-sm" width="14" height="14" fill="none" stroke="#fff" stroke-width="2.2"
              viewBox="0 0 16 16">
              <path d="M8 2a6 6 0 0 1 6 6" stroke-linecap="round" />
            </svg>
            <svg v-else width="14" height="14" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 14 14">
              <path d="M2 7l4 4 6-6" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            {{ saving ? 'A guardar…' : 'Guardar' }}
          </button>
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
          <h3>Apagar Categoria</h3>
          <p class="confirm-desc">Tem a certeza que deseja apagar a categoria <strong>{{ deleteModal.target?.name }}</strong>?
            Esta acção não pode ser desfeita.</p>
          <div class="confirm-actions">
            <button class="btn-confirm-cancel" @click="deleteModal.show = false">Cancelar</button>
            <button class="btn-confirm-delete" @click="doRemoveCategory" :disabled="deleteModal.loading">
              {{ deleteModal.loading ? 'A apagar…' : 'Apagar' }}
            </button>
          </div>
        </div>
      </div>
    </transition>

    <!-- ── TOAST ── -->
    <transition name="toast-up">
      <div v-if="toast.show" class="toast" :class="toast.type">
        <svg v-if="toast.type === 'success'" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"
          viewBox="0 0 15 15">
          <circle cx="7.5" cy="7.5" r="6" />
          <path d="M5 7.5l2 2 3-4" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <svg v-else width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 15 15">
          <circle cx="7.5" cy="7.5" r="6" />
          <path d="M7.5 5v3M7.5 10h.01" stroke-linecap="round" />
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
const sidebarOpen = ref(false)

async function handleLogout() {
  await auth.logout()
  router.push('/acessoRestrito')
}

// ── Constantes visuais ────────────────────────────────────────
const ICON_DEFS = {
  fauna: 'M11 3c-1 0-2 .5-2.5 1.5C8 3.5 7 3 6 3 4.5 3 3 4.5 3 6.5c0 3.5 8 9.5 8 9.5s8-6 8-9.5C19 4.5 17.5 3 16 3c-1 0-2 .5-2.5 1.5C13 3.5 12 3 11 3z',
  flora: 'M11 21V11M11 11C11 6 6 3 3 5c3 1 5 4 5 7M11 11c0-5 5-8 8-6-3 1-5 4-5 7',
  agua: 'M11 2S6 8 6 13a5 5 0 0 0 10 0c0-5-5-11-5-11z',
  fogo: 'M12 2c0 6-6 8-6 14a6 6 0 0 0 12 0c0-3-1.5-5-3-7-1 3-3 4-3 7',
  pesca: 'M2 16c2-4 6-6 10-4M18 10a6 6 0 0 1-6 6M4 10h.01M20 6l-2 4-4-2',
  lixo: 'M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v6M14 11v6',
  ar: 'M3 8h12a3 3 0 0 1 0 6H9M3 14h8a3 3 0 0 0 0-6',
  caca: 'M12 2L8 8H4l4 4-2 8 6-4 6 4-2-8 4-4h-4z',
}

const ICON_LABELS = {
  fauna: 'Fauna', flora: 'Flora', agua: 'Água / Poluição Hídrica',
  fogo: 'Queimadas / Fogo', pesca: 'Pesca Ilegal', lixo: 'Resíduos / Lixo',
  ar: 'Poluição Atmosférica', caca: 'Caça Furtiva',
}

const COLOR_OPTIONS = [
  '#52B788', '#2D6A4F', '#4299E1', '#9F7AEA',
  '#ED8936', '#E53E3E', '#38B2AC', '#D69E2E',
]

// ── Estado ────────────────────────────────────────────────────
const loading = ref(false)
const saving = ref(false)
const modalOpen = ref(false)
const editingId = ref(null)
const formError = ref('')
const fieldErrors = reactive({})
const categorias = ref([])

const toast = reactive({ show: false, msg: '', type: 'success' })

function showToast(msg, type = 'success') {
  toast.msg = msg; toast.type = type; toast.show = true
  setTimeout(() => { toast.show = false }, 3200)
}

// ── Filtros ───────────────────────────────────────────────────
const f = reactive({ pesquisa: '', estado: '', icone: '' })

const filteredCategorias = computed(() => {
  let list = categorias.value
  if (f.pesquisa.trim()) {
    const q = f.pesquisa.toLowerCase()
    list = list.filter(c =>
      c.name.toLowerCase().includes(q) ||
      c.description?.toLowerCase().includes(q) ||
      c.code.toLowerCase().includes(q)
    )
  }
  if (f.estado === 'ativa') list = list.filter(c => c.is_active)
  if (f.estado === 'inativa') list = list.filter(c => !c.is_active)
  if (f.icone) list = list.filter(c => c.icon === f.icone)
  return list
})

function limpar() { Object.assign(f, { pesquisa: '', estado: '', icone: '' }) }

// ── Carregar categorias ───────────────────────────────────────
onMounted(loadCategories)

async function loadCategories() {
  loading.value = true
  try {
    const data = await InternalService.getCategories()
    categorias.value = data.categories ?? []
  } catch (err) {
    showToast('Erro ao carregar categorias.', 'error')
  } finally {
    loading.value = false
  }
}

// ── Formulário ────────────────────────────────────────────────
const form = reactive({
  code: '', name: '', description: '',
  icon: 'fauna', color: '#52B788', is_active: true, tagsStr: '',
})

function openNew() {
  editingId.value = null
  formError.value = ''
  Object.keys(fieldErrors).forEach(k => delete fieldErrors[k])
  Object.assign(form, {
    code: '', name: '', description: '',
    icon: 'fauna', color: '#52B788', is_active: true, tagsStr: '',
  })
  subsModal.list = []
  subsModal.newName = ''
  subsModal.editingId = null
  pendingSubcategorias.value = []
  modalOpen.value = true
}

async function openEdit(cat) {
  editingId.value = cat.id
  formError.value = ''
  Object.keys(fieldErrors).forEach(k => delete fieldErrors[k])
  Object.assign(form, {
    code: cat.code,
    name: cat.name,
    description: cat.description ?? '',
    icon: cat.icon ?? 'fauna',
    color: cat.color ?? '#52B788',
    is_active: cat.is_active,
    tagsStr: (cat.tags ?? []).join(', '),
  })
  subsModal.newName = ''
  subsModal.editingId = null
  pendingSubcategorias.value = []
  modalOpen.value = true

  subsModal.list = []
  subsModal.loading = true
  try {
    const data = await InternalService.getSubcategories(cat.id)
    subsModal.list = data.subcategories ?? []
  } catch {
    showToast('Erro ao carregar subcategorias.', 'error')
  } finally {
    subsModal.loading = false
  }
}

async function guardar() {
  formError.value = ''
  Object.keys(fieldErrors).forEach(k => delete fieldErrors[k])

  if (!form.code.trim()) { fieldErrors.code = 'O código é obrigatório.'; return }
  if (!form.name.trim()) { fieldErrors.name = 'O nome é obrigatório.'; return }

  const payload = {
    code: form.code.trim().toUpperCase(),
    name: form.name.trim(),
    description: form.description.trim() || null,
    icon: form.icon,
    color: form.color,
    is_active: form.is_active,
    tags: form.tagsStr.split(',').map(t => t.trim()).filter(Boolean),
  }

  saving.value = true
  try {
    let result
    if (editingId.value) {
      result = await InternalService.updateCategory(editingId.value, payload)
      const idx = categorias.value.findIndex(c => c.id === editingId.value)
      if (idx !== -1) categorias.value[idx] = { ...categorias.value[idx], ...result.category }
      showToast('Categoria actualizada com sucesso.')
    } else {
      result = await InternalService.createCategory(payload)
      const newCat = { ...result.category, occurrences_count: 0, subcategories: [] }
      for (const name of pendingSubcategorias.value) {
        try {
          const subData = await InternalService.createSubcategory(newCat.id, { name })
          newCat.subcategories.push(subData.subcategory)
        } catch {}
      }
      categorias.value.push(newCat)
      showToast('Categoria criada com sucesso.')
    }
    modalOpen.value = false
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

function confirmDeleteCategory(cat) {
  deleteModal.target = cat
  deleteModal.show = true
}

async function doRemoveCategory() {
  if (!deleteModal.target) return
  const target = deleteModal.target

  deleteModal.loading = true
  try {
    await InternalService.deleteCategory(target.id)
    categorias.value = categorias.value.filter(c => c.id !== target.id)
    showToast('Categoria apagada com sucesso.')
  } catch (err) {
    showToast(err.response?.data?.message ?? 'Erro ao apagar categoria.', 'error')
  } finally {
    deleteModal.loading = false
    deleteModal.show = false
    deleteModal.target = null
  }
}

// ── Subcategorias (campo do modal de categoria) ────────────────
const subsModal = reactive({
  loading: false, saving: false,
  list: [],
  newName: '', editingId: null, editName: '',
})

// Subcategorias por criar quando a categoria ainda não existe (nova categoria)
const pendingSubcategorias = ref([])

function removePendingSubcategoria(idx) {
  pendingSubcategorias.value.splice(idx, 1)
}

async function addSubcategoria() {
  const name = subsModal.newName.trim()
  if (!name) return

  if (!editingId.value) {
    if (!pendingSubcategorias.value.includes(name)) {
      pendingSubcategorias.value.push(name)
    }
    subsModal.newName = ''
    return
  }

  subsModal.saving = true
  try {
    const data = await InternalService.createSubcategory(editingId.value, { name })
    subsModal.list.push({ ...data.subcategory, occurrences_count: 0 })
    subsModal.newName = ''
    showToast('Subcategoria adicionada.')
    // Actualizar contador no card
    const cat = categorias.value.find(c => c.id === editingId.value)
    if (cat) cat.subcategories = [...(cat.subcategories ?? []), data.subcategory]
  } catch (err) {
    showToast(err.response?.data?.message ?? 'Erro ao adicionar subcategoria.', 'error')
  } finally {
    subsModal.saving = false
  }
}

function startSubEdit(sub) {
  subsModal.editingId = sub.id
  subsModal.editName = sub.name
}

async function saveSubEdit(sub) {
  const name = subsModal.editName.trim()
  if (!name) return
  try {
    const data = await InternalService.updateSubcategory(sub.id, { name, is_active: sub.is_active })
    const idx = subsModal.list.findIndex(s => s.id === sub.id)
    if (idx !== -1) subsModal.list[idx] = { ...subsModal.list[idx], ...data.subcategory }
    subsModal.editingId = null
    showToast('Subcategoria actualizada.')
  } catch {
    showToast('Erro ao actualizar subcategoria.', 'error')
  }
}

async function toggleSubActive(sub) {
  try {
    const data = await InternalService.updateSubcategory(sub.id, {
      name: sub.name,
      is_active: !sub.is_active,
    })
    const idx = subsModal.list.findIndex(s => s.id === sub.id)
    if (idx !== -1) subsModal.list[idx] = { ...subsModal.list[idx], ...data.subcategory }
    showToast(`Subcategoria ${!sub.is_active ? 'activada' : 'desactivada'}.`)
  } catch {
    showToast('Erro ao alterar estado.', 'error')
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
  transition: border-color 0.2s;
}

.search-wrap:focus-within {
  border-color: var(--green-light);
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

.page-title-row {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  margin-bottom: 20px;
}

.page-title-row h1 {
  font-size: 22px;
  font-weight: 800;
  margin-bottom: 4px;
}

.page-title-row p {
  font-size: 13px;
  color: var(--text-gray);
}

.btn-nova {
  display: flex;
  align-items: center;
  gap: 7px;
  background: var(--green-mid);
  color: #fff;
  border: none;
  border-radius: 9px;
  padding: 10px 20px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-nova:hover {
  background: var(--green-dark);
}

/* FILTER */
.filter-card {
  background: var(--white);
  border-radius: 16px;
  padding: 18px 20px 20px;
  margin-bottom: 18px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, .05), 0 6px 20px rgba(0, 0, 0, .07);
}

.filter-row {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr auto;
  gap: 12px;
  align-items: end;
}

.filter-group {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.filter-group label {
  display: flex;
  align-items: center;
  gap: 5px;
  font-size: 11.5px;
  font-weight: 600;
  color: var(--text-gray);
}

.filter-group input,
.filter-group select {
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  color: var(--text-dark);
  background: var(--white);
  border: 1.5px solid var(--border);
  border-radius: 8px;
  padding: 9px 12px;
  outline: none;
  width: 100%;
  appearance: none;
  -webkit-appearance: none;
  transition: border-color 0.2s;
}

.filter-group select {
  background-image: url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%238A9490' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 10px center;
  padding-right: 30px;
}

.filter-group input:focus,
.filter-group select:focus {
  border-color: var(--green-light);
  box-shadow: 0 0 0 3px rgba(82, 183, 136, .12);
}

.filter-actions {
  display: flex;
  align-items: flex-end;
}

.btn-limpar {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  height: 39px;
  background: var(--white);
  color: var(--text-gray);
  border: 1.5px solid var(--border);
  border-radius: 8px;
  padding: 0 14px;
  font-family: 'Poppins', sans-serif;
  font-size: 12.5px;
  font-weight: 600;
  cursor: pointer;
  white-space: nowrap;
  transition: border-color 0.2s;
}

.btn-limpar:hover {
  border-color: var(--text-gray);
  color: var(--text-dark);
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
  width: 14px;
  height: 14px;
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

/* CATEGORY GRID */
.cat-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 16px;
}

.cat-card {
  background: var(--white);
  border-radius: 16px;
  padding: 20px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, .05), 0 6px 20px rgba(0, 0, 0, .07);
  transition: box-shadow 0.25s, transform 0.25s;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.cat-card:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, .1), 0 16px 40px rgba(0, 0, 0, .1);
  transform: translateY(-2px);
}

.cat-card.inativa {
  opacity: 0.55;
}

.cat-card-header {
  display: flex;
  align-items: center;
  gap: 10px;
}

.cat-icon-wrap {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  border: 1.5px solid;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.cat-meta {
  flex: 1;
}

.cat-code {
  font-size: 10.5px;
  font-weight: 700;
  letter-spacing: 1px;
  color: var(--text-light);
  text-transform: uppercase;
  background: #F4F6F5;
  border-radius: 5px;
  padding: 2px 7px;
}

.cat-actions {
  display: flex;
  gap: 6px;
}

.btn-icon-sm {
  width: 30px;
  height: 30px;
  border-radius: 7px;
  background: #F4F6F5;
  border: 1.5px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: var(--text-gray);
  transition: all 0.15s;
}

.btn-icon-sm:hover {
  background: var(--green-bg);
  border-color: var(--green-mid);
  color: var(--green-mid);
}

.btn-icon-delete:hover {
  background: #FDEDED;
  border-color: #E53E3E;
  color: #E53E3E;
}

.cat-name {
  font-size: 14px;
  font-weight: 800;
  color: var(--text-dark);
}

.cat-desc {
  font-size: 12.5px;
  color: var(--text-gray);
  line-height: 1.6;
}

.cat-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
}

.tag {
  font-size: 11px;
  font-weight: 600;
  padding: 3px 9px;
  border-radius: 99px;
  background: var(--green-bg);
  color: var(--green-mid);
  border: 1px solid var(--green-pale);
}

.cat-footer {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-top: 4px;
  flex-wrap: wrap;
}

.cat-stat {
  display: flex;
  align-items: center;
  gap: 5px;
  font-size: 12px;
  color: var(--text-light);
  font-weight: 600;
}

.badge-ativa {
  font-size: 11px;
  font-weight: 700;
  padding: 3px 10px;
  border-radius: 99px;
  border: 1.5px solid;
  margin-left: auto;
}

.badge-ativa.ativa {
  color: var(--green-mid);
  border-color: #68D391;
  background: var(--green-pale);
}

.badge-ativa.inativa-badge {
  color: #C05621;
  border-color: #F6AD55;
  background: #FFFAF0;
}

.empty-grid {
  grid-column: 1/-1;
  text-align: center;
  padding: 48px;
  color: var(--text-light);
  font-size: 13px;
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
  background: rgba(15, 28, 22, .4);
  z-index: 200;
}

.modal {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 560px;
  max-width: 95vw;
  max-height: 90vh;
  background: var(--white);
  z-index: 201;
  border-radius: 16px;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  box-shadow: 0 20px 60px rgba(0, 0, 0, .18);
}

.modal-subs {
  width: 500px;
}

.modal-hd {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px 24px 18px;
  border-bottom: 1px solid var(--border);
  flex-shrink: 0;
}

.modal-hd h3 {
  font-size: 16px;
  font-weight: 800;
  margin-bottom: 2px;
}

.modal-hd p {
  font-size: 12px;
  color: var(--text-light);
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

.modal-body {
  flex: 1;
  overflow-y: auto;
  padding: 22px 24px;
}

.modal-ft {
  display: flex;
  gap: 10px;
  justify-content: flex-end;
  padding: 16px 24px;
  border-top: 1px solid var(--border);
  flex-shrink: 0;
}

/* Form */
.f-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
  margin-bottom: 16px;
}

.f-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}

.f-row .f-group {
  margin-bottom: 0;
}

.f-group label {
  font-size: 12.5px;
  font-weight: 600;
  color: var(--text-dark);
}

.f-group input,
.f-group select,
.f-group textarea {
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  color: var(--text-dark);
  background: var(--white);
  border: 1.5px solid var(--border);
  border-radius: 9px;
  padding: 10px 13px;
  outline: none;
  width: 100%;
  appearance: none;
  -webkit-appearance: none;
  transition: border-color 0.2s, box-shadow 0.2s;
}

.f-group select {
  background-image: url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%238A9490' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 13px center;
  padding-right: 36px;
  cursor: pointer;
}

.f-group textarea {
  resize: vertical;
  min-height: 80px;
}

.f-group input:focus,
.f-group select:focus,
.f-group textarea:focus {
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

.f-hint-inline {
  font-size: 11px;
  color: var(--text-light);
  font-weight: 400;
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

.icon-preview {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  border: 1.5px solid;
  display: flex;
  align-items: center;
  justify-content: center;
}

.color-options {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  padding: 4px 0;
}

.color-swatch {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  border: 3px solid transparent;
  cursor: pointer;
  transition: transform 0.15s, border-color 0.15s;
}

.color-swatch:hover {
  transform: scale(1.15);
}

.color-swatch.selected {
  border-color: var(--text-dark);
  transform: scale(1.15);
}

.btn-cancelar {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  background: transparent;
  color: var(--text-gray);
  border: 1.5px solid var(--border);
  border-radius: 9px;
  padding: 9px 20px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
}

.btn-cancelar:hover {
  border-color: var(--text-gray);
  color: var(--text-dark);
}

.btn-guardar {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  background: var(--green-mid);
  color: #fff;
  border: none;
  border-radius: 9px;
  padding: 10px 22px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-guardar:hover:not(:disabled) {
  background: var(--green-dark);
}

.btn-guardar:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* Subcategorias */
.sub-add-row {
  display: flex;
  gap: 10px;
  margin-bottom: 18px;
}

.sub-add-row input {
  flex: 1;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  color: var(--text-dark);
  border: 1.5px solid var(--border);
  border-radius: 9px;
  padding: 10px 13px;
  outline: none;
  transition: border-color 0.2s;
}

.sub-add-row input:focus {
  border-color: var(--green-light);
  box-shadow: 0 0 0 3px rgba(82, 183, 136, .12);
}

.btn-add-sub {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  background: var(--green-mid);
  color: #fff;
  border: none;
  border-radius: 9px;
  padding: 10px 18px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  white-space: nowrap;
  transition: background 0.2s;
}

.btn-add-sub:hover:not(:disabled) {
  background: var(--green-dark);
}

.btn-add-sub:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.sub-list {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.sub-item {
  display: flex;
  align-items: center;
  gap: 10px;
  background: #F7F9F8;
  border: 1px solid var(--border);
  border-radius: 9px;
  padding: 10px 14px;
}

.sub-name {
  flex: 1;
  font-size: 13px;
  font-weight: 500;
  color: var(--text-dark);
}

.sub-count {
  font-size: 11.5px;
  color: var(--text-light);
  white-space: nowrap;
}

.sub-badge {
  margin-left: 0;
  flex-shrink: 0;
}

.sub-edit-input {
  flex: 1;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  color: var(--text-dark);
  border: 1.5px solid var(--green-light);
  border-radius: 7px;
  padding: 7px 10px;
  outline: none;
  box-shadow: 0 0 0 3px rgba(82, 183, 136, .12);
}

.btn-sub-save {
  background: var(--green-mid);
  color: #fff;
  border: none;
  border-radius: 7px;
  padding: 6px 10px;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
}

.btn-sub-cancel {
  background: #F4F6F5;
  color: var(--text-gray);
  border: 1.5px solid var(--border);
  border-radius: 7px;
  padding: 6px 10px;
  font-size: 13px;
  cursor: pointer;
}

.sub-empty {
  font-size: 13px;
  color: var(--text-light);
  font-style: italic;
  text-align: center;
  padding: 20px 0;
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
  right: 28px;
  z-index: 400;
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px 20px;
  border-radius: 10px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 600;
  box-shadow: 0 8px 24px rgba(0, 0, 0, .15);
}

.toast.success {
  background: #EEF7F1;
  border: 1px solid #C3E6CE;
  color: #2D6A4F;
}

.toast.error {
  background: #FFF5F5;
  border: 1px solid #FC8181;
  color: #C53030;
}

/* TRANSITIONS */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.25s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.modal-pop-enter-active,
.modal-pop-leave-active {
  transition: opacity 0.22s ease, transform 0.25s cubic-bezier(.4, 0, .2, 1);
}

.modal-pop-enter-from,
.modal-pop-leave-to {
  opacity: 0;
  transform: translate(-50%, -46%) scale(0.96);
}

.toast-up-enter-active,
.toast-up-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.toast-up-enter-from,
.toast-up-leave-to {
  opacity: 0;
  transform: translateY(12px);
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

  .filter-row {
    grid-template-columns: 1fr 1fr;
  }

  .filter-actions {
    grid-column: 1 / -1;
  }
}

@media (max-width: 640px) {
  .content {
    padding: 16px 14px 24px;
  }

  .topbar {
    padding: 0 14px;
    gap: 10px;
  }

  .search-wrap {
    max-width: none;
  }

  .page-title-row {
    flex-direction: column;
    align-items: stretch;
    gap: 12px;
  }

  .btn-nova {
    justify-content: center;
    width: 100%;
  }

  .filter-row {
    grid-template-columns: 1fr;
  }

  .filter-actions {
    grid-column: auto;
  }

  .btn-limpar {
    width: 100%;
    justify-content: center;
  }

  .f-row {
    grid-template-columns: 1fr;
    gap: 0;
  }

  .modal {
    width: auto;
    max-width: calc(100vw - 24px);
    max-height: calc(100vh - 24px);
  }

  .modal-hd {
    padding: 16px 18px 14px;
  }

  .modal-body {
    padding: 18px;
  }

  .modal-ft {
    padding: 14px 18px;
    flex-wrap: wrap;
  }

  .modal-ft button {
    flex: 1;
    justify-content: center;
  }

  .sub-add-row {
    flex-direction: column;
  }

  .btn-add-sub {
    justify-content: center;
  }

  .confirm-modal {
    width: auto;
    max-width: calc(100vw - 24px);
    max-height: calc(100vh - 24px);
    overflow-y: auto;
    padding: 24px 20px 20px;
  }

  .dash-footer {
    flex-direction: column;
    gap: 6px;
    padding: 12px 14px;
    text-align: center;
  }

  .dash-footer div {
    margin: 0;
  }

  .toast {
    left: 14px;
    right: 14px;
    bottom: 14px;
  }
}
</style>