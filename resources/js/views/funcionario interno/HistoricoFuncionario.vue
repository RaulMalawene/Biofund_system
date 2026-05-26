<template>
  <div class="app-shell">

    <!-- ── SIDEBAR ── -->
    <aside class="sidebar">
      <router-link to="/funcionario/reclamacao" class="sidebar-logo">
        <img src="../../Imagem/logotipoBiofund.jpeg" alt="Biofund" class="sidebar-logo-img" />
        <div>
          <div class="sidebar-logo-text">BioFund Portal</div>
        </div>
      </router-link>

      <nav class="sidebar-nav">
        <router-link class="nav-item" to="/funcionario/reclamacao">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <rect x="2" y="1" width="12" height="14" rx="1.5" />
            <path d="M5 5h6M5 8h6M5 11h4" stroke-linecap="round" />
          </svg>
          Reclamações
        </router-link>
        <router-link class="nav-item" to="/funcionario/historico">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <rect x="2" y="1" width="10" height="14" rx="1.5" />
            <path d="M5 5h4M5 8h4M5 11h2" stroke-linecap="round" />
            <path d="M10 1v4h4" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          Histórico
        </router-link>
      </nav>

      <div class="sidebar-footer">
        <button class="btn-logout" @click="handleLogout">
          <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <path d="M6 14H3a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h3M10 11l3-3-3-3M13 8H6"
              stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          Terminar Sessão
        </button>
      </div>
    </aside>

    <!-- ── MAIN ── -->
    <div class="main">

      <!-- TOPBAR -->
      <header class="topbar">
        <div class="search-wrap">
          <svg width="15" height="15" fill="none" stroke="#8A9490" stroke-width="1.8" viewBox="0 0 16 16">
            <circle cx="7" cy="7" r="5" />
            <path d="M12 12l3 3" stroke-linecap="round" />
          </svg>
          <input type="text" placeholder="Pesquisar por código ou descrição…" v-model="topSearch"
            @keyup.enter="aplicarFiltros" />
        </div>
        <div class="topbar-spacer"></div>
        <AdminNotificationPanel />
        <AdminProfilePanel />
      </header>

      <!-- CONTENT -->
      <main class="content">

        <!-- Page header -->
        <div class="page-title-row">
          <div>
            <h1>Histórico de Ocorrências</h1>
            <p>Reclamações resolvidas, rejeitadas e encerradas que registou.</p>
          </div>
        </div>

        <!-- FILTER CARD -->
        <div class="filter-card">
          <div class="filter-row">
            <div class="filter-group">
              <label>
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 12 12">
                  <path d="M1 2h10l-4 5v4l-2-1V7z" stroke-linejoin="round" />
                </svg>
                Estado
              </label>
              <select v-model="filters.status">
                <option value="">Todos os Terminais</option>
                <option value="resolvido">Resolvidas</option>
                <option value="nao_validado">Não Validadas</option>
                <option value="encerrado">Encerradas</option>
              </select>
            </div>
            <div class="filter-group">
              <label>
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 12 12">
                  <path d="M1 2h10l-4 5v4l-2-1V7z" stroke-linejoin="round" />
                </svg>
                Categoria
              </label>
              <select v-model="filters.category_id">
                <option value="">Todas as Categorias</option>
                <option v-for="c in refCategories" :key="c.id" :value="c.id">{{ c.name }}</option>
              </select>
            </div>
            <div class="filter-group">
              <label>
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 12 12">
                  <rect x="1" y="1.5" width="10" height="9.5" rx="1.5" />
                  <path d="M4 1v1.5M8 1v1.5M1 5h10" stroke-linecap="round" />
                </svg>
                Data de Submissão
              </label>
              <input type="date" v-model="filters.date_from" />
            </div>
            <div class="filter-actions">
              <button class="btn-limpar" @click="limparFiltros">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 14 14">
                  <path d="M2 2l10 10M12 2L2 12" stroke-linecap="round" />
                </svg>
                Limpar
              </button>
              <button class="btn-filtrar" @click="aplicarFiltros" :disabled="loading">
                <svg width="13" height="13" fill="none" stroke="#fff" stroke-width="1.8" viewBox="0 0 14 14">
                  <path d="M1 2h12l-5 6v5l-2-1V8z" stroke-linejoin="round" />
                </svg>
                Filtrar
              </button>
            </div>
          </div>
        </div>

        <!-- STAT BAR -->
        <div class="stat-bar">
          <span class="stat-label">
            <span v-if="loading" class="stat-spin"></span>
            {{ loading ? 'A carregar…' : `${meta.total} resultado${meta.total !== 1 ? 's' : ''}` }}
          </span>
          <span class="badge-status resolvido">Resolvidas · {{ countByStatus('resolvido') }}</span>
          <span class="badge-status nao_validado">Não Validadas · {{ countByStatus('nao_validado') }}</span>
        </div>

        <!-- TABLE CARD -->
        <div class="table-card">
          <div class="table-overflow">
            <table>
              <thead>
                <tr>
                  <th>Código</th>
                  <th>Data</th>
                  <th>Província</th>
                  <th>Categoria</th>
                  <th>Canal</th>
                  <th>Responsável</th>
                  <th>Projecto</th>
                  <th>Estado</th>
                  <th>Acção</th>
                </tr>
              </thead>
              <tbody>

                <!-- Skeleton loading -->
                <template v-if="loading">
                  <tr v-for="n in 5" :key="'sk-' + n" class="skeleton-row">
                    <td colspan="9"><div class="skeleton-bar"></div></td>
                  </tr>
                </template>

                <!-- Rows -->
                <template v-if="!loading">
                  <tr v-for="r in rows" :key="r.id" @click="openDetail(r)">
                    <td>
                      <span class="id-link">{{ r.tracking_code }}</span>
                      <div class="id-sub">{{ r.subject ?? '—' }}</div>
                    </td>
                    <td class="td-muted">{{ r.submitted_at }}</td>
                    <td>{{ r.province?.name ?? '—' }}</td>
                    <td class="td-small">{{ r.category?.name ?? '—' }}</td>
                    <td class="td-small">{{ r.submission_channel_label ?? '—' }}</td>
                    <td>
                      <div class="resp-cell" v-if="r.assigned_to?.name">
                        <div class="resp-avatar">{{ r.assigned_to.name[0] }}</div>
                        <span class="td-small">{{ r.assigned_to.name }}</span>
                      </div>
                      <span v-else class="resp-none">Sem responsável</span>
                    </td>
                    <td class="td-muted td-small">{{ r.project?.name ?? '—' }}</td>
                    <td>
                      <span class="badge-status" :class="r.status">{{ r.status_label }}</span>
                    </td>
                    <td>
                      <button class="btn-detail" @click.stop="openDetail(r)">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 14 14">
                          <circle cx="7" cy="7" r="5.5" />
                          <path d="M7 5v4M7 10h.01" stroke-linecap="round" />
                        </svg>
                        Detalhes
                      </button>
                    </td>
                  </tr>

                  <tr v-if="rows.length === 0">
                    <td colspan="9" class="empty-row">
                      Nenhuma ocorrência encontrada com os filtros aplicados.
                    </td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="pagination-bar">
            <span class="pagination-info">
              Mostrando {{ paginationInfo }} de {{ meta.total }} ocorrências
            </span>
            <div class="pagination-btns">
              <button class="pg-btn" :disabled="meta.current_page <= 1 || loading"
                @click="goToPage(meta.current_page - 1)">Anterior</button>
              <button v-for="p in visiblePages" :key="p" class="pg-btn"
                :class="{ active: meta.current_page === p }" @click="goToPage(p)">{{ p }}</button>
              <button class="pg-btn" :disabled="meta.current_page >= meta.last_page || loading"
                @click="goToPage(meta.current_page + 1)">Próximo</button>
            </div>
          </div>
        </div>

      </main>

      <!-- FOOTER -->
      <footer class="dash-footer">
        <span>© 2026 BioFund · Sistema de Gestão Ambiental de Moçambique</span>
        <div>
          <a href="#">Suporte Técnico</a>
          <a href="#">Privacidade</a>
        </div>
      </footer>
    </div>

    <!-- ── DETAIL DRAWER ── -->
    <transition name="fade">
      <div v-if="selected" class="drawer-overlay" @click.self="selected = null"></div>
    </transition>
    <transition name="slide-right">
      <div v-if="selected" class="drawer">
        <div class="drawer-hd">
          <div>
            <h3>{{ selected.tracking_code }}</h3>
            <p>{{ selected.subject ?? 'Sem assunto' }}</p>
          </div>
          <button class="btn-close" @click="selected = null">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 13 13">
              <path d="M2 2l9 9M11 2L2 11" stroke-linecap="round" />
            </svg>
          </button>
        </div>

        <div class="drawer-loading" v-if="detailLoading">
          <span class="stat-spin"></span> A carregar detalhes…
        </div>

        <div class="drawer-body" v-else>
          <div class="drawer-status-row">
            <span class="badge-status" :class="selected.status">{{ selected.status_label }}</span>
          </div>

          <div class="detail-row">
            <span class="detail-key">Data de Submissão</span>
            <span class="detail-val">{{ selected.submitted_at }}</span>
          </div>
          <div class="detail-row" v-if="selected.occurrence_date">
            <span class="detail-key">Data da Ocorrência</span>
            <span class="detail-val">{{ selected.occurrence_date }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-key">Província</span>
            <span class="detail-val">{{ selected.province?.name ?? '—' }}</span>
          </div>
          <div class="detail-row" v-if="selected.district?.name">
            <span class="detail-key">Distrito</span>
            <span class="detail-val">{{ selected.district.name }}</span>
          </div>
          <div class="detail-row" v-if="selected.location_detail">
            <span class="detail-key">Localização</span>
            <span class="detail-val">{{ selected.location_detail }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-key">Categoria</span>
            <span class="detail-val">{{ selected.category?.name ?? '—' }}</span>
          </div>
          <div class="detail-row" v-if="selected.submission_channel_label">
            <span class="detail-key">Canal de Entrada</span>
            <span class="detail-val">{{ selected.submission_channel_label }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-key">Responsável</span>
            <span class="detail-val">{{ selected.assigned_to?.name ?? 'Sem responsável' }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-key">Projecto</span>
            <span class="detail-val">{{ selected.project?.name ?? '—' }}</span>
          </div>

          <div class="drawer-section">
            <div class="drawer-section-label">Descrição</div>
            <div class="drawer-desc">{{ selected.description ?? '—' }}</div>
          </div>

          <div class="drawer-section" v-if="selected.attachments?.length">
            <div class="drawer-section-label">Anexos ({{ selected.attachments.length }})</div>
            <div class="attach-list">
              <button v-for="a in selected.attachments" :key="a.id" class="attach-item"
                @click="downloadAttachment(a)">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 16 16">
                  <rect x="2" y="1" width="10" height="14" rx="1.5" />
                  <path d="M5 5h4M5 8h4M5 11h2" stroke-linecap="round" />
                  <path d="M10 1v4h4" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span>{{ a.name }}</span>
                <span class="attach-size">{{ a.size }}</span>
                <svg class="dl-icon" width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 14 14">
                  <path d="M7 2v8M3 7l4 5 4-5" stroke-linecap="round" stroke-linejoin="round" />
                  <path d="M2 12h10" stroke-linecap="round" />
                </svg>
              </button>
            </div>
          </div>

          <div class="drawer-section">
            <div class="drawer-section-label">Histórico de Estados</div>
            <div class="timeline" v-if="selected.history?.length">
              <div class="timeline-item" v-for="(ev, i) in selected.history" :key="i">
                <div class="tl-dot" :style="{ borderColor: ev.to_color, background: ev.to_color + '22' }"></div>
                <div class="tl-content">
                  <div class="tl-title">{{ ev.to }}</div>
                  <div class="tl-comment" v-if="ev.comment">{{ ev.comment }}</div>
                  <div class="tl-date">{{ ev.date }} · {{ ev.changed_by }}</div>
                </div>
              </div>
            </div>
            <p v-else class="tl-empty">Sem histórico disponível.</p>
          </div>
        </div>
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
const auth   = useAuthStore()

async function handleLogout() {
  await auth.logout()
  router.push('/')
}

// ── Estado ────────────────────────────────────────────────────
const loading       = ref(false)
const detailLoading = ref(false)
const topSearch     = ref('')
const selected      = ref(null)
const rows          = ref([])

const meta = reactive({
  total: 0, last_page: 1, current_page: 1, per_page: 15,
})

// ── Dados de referência ───────────────────────────────────────
const refCategories = ref([])

// ── Filtros ───────────────────────────────────────────────────
const filters = reactive({
  status: '', category_id: '', date_from: '',
})

// ── Init ──────────────────────────────────────────────────────
onMounted(() => {
  InternalService.getFormData()
    .then(data => { refCategories.value = data.categories ?? [] })
    .catch(err => console.error('Erro ao carregar filtros:', err))

  loadOccurrences()
})

// ── Carregar histórico (terminal) do funcionário ──────────────
async function loadOccurrences(page = 1) {
  loading.value = true
  try {
    const params = {
      per_page: meta.per_page,
      page,
      only_mine: true,
      history: 1,
    }
    if (topSearch.value.trim()) params.search      = topSearch.value.trim()
    if (filters.status)         params.status      = filters.status
    if (filters.category_id)    params.category_id = filters.category_id
    if (filters.date_from)      params.date_from   = filters.date_from

    const response = await InternalService.getOccurrences(params)
    rows.value = response.data ?? []
    Object.assign(meta, {
      total:        response.meta?.total        ?? 0,
      last_page:    response.meta?.last_page    ?? 1,
      current_page: response.meta?.current_page ?? 1,
      per_page:     response.meta?.per_page     ?? 15,
    })
  } catch (err) {
    console.error('Erro ao carregar histórico:', err)
    rows.value = []
  } finally {
    loading.value = false
  }
}

// ── Filtros ───────────────────────────────────────────────────
function aplicarFiltros() { loadOccurrences(1) }

function limparFiltros() {
  Object.assign(filters, { status: '', category_id: '', date_from: '' })
  topSearch.value = ''
  loadOccurrences(1)
}

// ── Paginação ─────────────────────────────────────────────────
function goToPage(p) {
  if (p < 1 || p > meta.last_page) return
  loadOccurrences(p)
}

const visiblePages = computed(() => {
  const pages = []
  const cur = meta.current_page, last = meta.last_page
  for (let i = Math.max(1, cur - 2); i <= Math.min(last, cur + 2); i++) pages.push(i)
  return pages
})

const paginationInfo = computed(() => {
  if (!meta.total) return '0'
  const start = (meta.current_page - 1) * meta.per_page + 1
  const end   = Math.min(meta.current_page * meta.per_page, meta.total)
  return `${start}–${end}`
})

function countByStatus(s) {
  return rows.value.filter(r => r.status === s).length
}

// ── Abrir detalhe ─────────────────────────────────────────────
async function openDetail(row) {
  selected.value = { ...row, history: null, attachments: null }
  detailLoading.value = true
  try {
    const response = await InternalService.getOccurrence(row.id)
    selected.value = response.data ?? response
  } catch (err) {
    console.error('Erro ao carregar detalhe:', err)
  } finally {
    detailLoading.value = false
  }
}

// ── Descarregar anexo ─────────────────────────────────────────
async function downloadAttachment(a) {
  try {
    const blobUrl = await InternalService.getAttachmentBlobUrl(selected.value.id, a.id)
    const link = document.createElement('a')
    link.href = blobUrl
    link.download = a.name
    link.click()
    setTimeout(() => URL.revokeObjectURL(blobUrl), 60000)
  } catch {}
}
</script>

<style scoped>
.app-shell {
  display: flex; width: 100%; height: 100vh;
  overflow: hidden; background: #EDF2EF;
}

/* ── SIDEBAR ─────────────────────────────── */
.sidebar {
  width: 220px; flex-shrink: 0; background: #fff;
  display: flex; flex-direction: column; height: 100vh;
  position: fixed; top: 0; left: 0; z-index: 50;
  border-right: 1px solid var(--border);
  box-shadow: 2px 0 16px rgba(0,0,0,0.06);
}
.sidebar-logo {
  display: flex; align-items: center; gap: 10px;
  padding: 20px 18px 18px; border-bottom: 1px solid var(--border);
  text-decoration: none;
}
.sidebar-logo-img { width: 34px; height: 34px; object-fit: contain; border-radius: 8px; flex-shrink: 0; }
.sidebar-logo-text { font-size: 13px; font-weight: 800; color: var(--green-dark); line-height: 1.2; letter-spacing: 0.2px; }
.sidebar-nav { flex: 1; padding: 16px 10px; overflow-y: auto; }
.nav-item {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 12px; border-radius: 9px; margin-bottom: 3px;
  font-size: 13px; font-weight: 500; color: var(--text-gray);
  cursor: pointer; transition: background 0.15s, color 0.15s;
  text-decoration: none; border-left: 3px solid transparent;
}
.nav-item:hover { background: var(--green-bg); color: var(--green-mid); }
.nav-item.router-link-exact-active {
  background: var(--green-bg); color: var(--green-mid);
  font-weight: 700; border-left-color: #52B788; padding-left: 9px;
}
.nav-item svg { flex-shrink: 0; opacity: 0.7; }
.nav-item:hover svg, .nav-item.router-link-exact-active svg { opacity: 1; }
.sidebar-footer { padding: 14px 10px; border-top: 1px solid var(--border); }
.btn-logout {
  display: flex; align-items: center; gap: 9px; width: 100%;
  background: none; border: none; cursor: pointer; padding: 10px 12px;
  border-radius: 9px; font-family: 'Poppins', sans-serif;
  font-size: 13px; font-weight: 500; color: #E53E3E;
  transition: background 0.15s, color 0.15s;
}
.btn-logout:hover { background: #FFF5F5; color: #C53030; }

/* ── MAIN ───────────────────────────────── */
.main { margin-left: 220px; flex: 1; display: flex; flex-direction: column; height: 100vh; overflow: hidden; }

/* ── TOPBAR ─────────────────────────────── */
.topbar {
  display: flex; align-items: center; gap: 14px;
  padding: 0 28px; height: 62px; background: var(--white);
  box-shadow: 0 2px 12px rgba(0,0,0,0.04);
  border-bottom: 1px solid var(--border); flex-shrink: 0; position: relative; z-index: 10;
}
.search-wrap {
  flex: 1; display: flex; align-items: center; gap: 10px;
  background: #F4F6F5; border: 1.5px solid var(--border);
  border-radius: 9px; padding: 8px 14px; max-width: 380px; transition: border-color 0.2s;
}
.search-wrap:focus-within { border-color: var(--green-mid); }
.search-wrap input {
  border: none; outline: none; background: transparent;
  font-family: 'Poppins', sans-serif; font-size: 13px; color: var(--text-dark); width: 100%;
}
.search-wrap input::placeholder { color: var(--text-light); }
.topbar-spacer { flex: 1; }

/* ── CONTENT ────────────────────────────── */
.content { flex: 1; overflow-y: auto; padding: 26px 30px 36px; background: #EDF2EF; }
.content::-webkit-scrollbar { width: 5px; }
.content::-webkit-scrollbar-thumb { background: #C8D8CE; border-radius: 99px; }

.page-title-row { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 20px; }
.page-title-row h1 {
  font-size: 22px; font-weight: 800; margin-bottom: 4px; color: #162119;
  display: flex; align-items: center; gap: 10px;
}
.page-title-row h1::before {
  content: ''; display: inline-block; width: 4px; height: 22px;
  background: linear-gradient(180deg, #52B788 0%, #2D6A4F 100%);
  border-radius: 99px; flex-shrink: 0;
}
.page-title-row p { font-size: 13px; color: var(--text-gray); }

/* ── FILTER CARD ────────────────────────── */
.filter-card {
  background: var(--white); border-radius: 16px; padding: 18px 20px 20px;
  margin-bottom: 18px; box-shadow: 0 1px 3px rgba(0,0,0,.05), 0 6px 20px rgba(0,0,0,.07);
}
.filter-row {
  display: grid; grid-template-columns: 1fr 1fr 1fr auto;
  gap: 12px; align-items: end;
}
.filter-group { display: flex; flex-direction: column; gap: 5px; }
.filter-group label {
  display: flex; align-items: center; gap: 5px;
  font-size: 11.5px; font-weight: 600; color: var(--text-gray);
}
.filter-group input,
.filter-group select {
  font-family: 'Poppins', sans-serif; font-size: 13px;
  color: var(--text-dark); background: var(--white);
  border: 1.5px solid var(--border); border-radius: 8px;
  padding: 9px 30px 9px 12px; outline: none; width: 100%;
  appearance: none; -webkit-appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%238A9490' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
  background-repeat: no-repeat; background-position: right 10px center; transition: border-color 0.2s;
}
.filter-group input { background-image: none; padding-right: 12px; }
.filter-group input:focus, .filter-group select:focus {
  border-color: var(--green-light); box-shadow: 0 0 0 3px rgba(82,183,136,.12);
}
.filter-actions { display: flex; gap: 8px; align-items: flex-end; }
.btn-limpar {
  display: inline-flex; align-items: center; gap: 6px; height: 39px;
  background: var(--white); color: var(--text-gray); border: 1.5px solid var(--border);
  border-radius: 8px; padding: 0 14px; font-family: 'Poppins', sans-serif;
  font-size: 12.5px; font-weight: 600; cursor: pointer; white-space: nowrap;
  transition: border-color 0.2s, color 0.2s;
}
.btn-limpar:hover { border-color: var(--text-gray); color: var(--text-dark); }
.btn-filtrar {
  display: inline-flex; align-items: center; gap: 7px; height: 39px;
  background: #40916C; color: #fff; border: none; border-radius: 10px;
  padding: 0 22px; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 700;
  cursor: pointer; white-space: nowrap; transition: background 0.2s, transform 0.15s;
  box-shadow: 0 2px 8px rgba(64,145,108,0.3);
}
.btn-filtrar:hover:not(:disabled) { background: #2D6A4F; transform: translateY(-1px); }
.btn-filtrar:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

/* ── STAT BAR ───────────────────────────── */
.stat-bar { display: flex; align-items: center; gap: 10px; margin-bottom: 14px; }
.stat-label { font-size: 12px; font-weight: 600; color: var(--text-light); display: flex; align-items: center; gap: 6px; }

/* ── SKELETON ───────────────────────────── */
.skeleton-row td { padding: 14px 16px; }
.skeleton-bar {
  height: 14px; border-radius: 6px;
  background: linear-gradient(90deg, #f0f4f2 25%, #e0eae5 50%, #f0f4f2 75%);
  background-size: 200% 100%; animation: shimmer 1.2s infinite;
}
@keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
.stat-spin {
  display: inline-block; width: 12px; height: 12px;
  border: 2px solid #C8D8CE; border-top-color: var(--green-mid);
  border-radius: 50%; animation: spin 0.7s linear infinite; flex-shrink: 0;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ── TABLE ──────────────────────────────── */
.table-card {
  background: var(--white); border-radius: 16px; overflow: hidden;
  box-shadow: 0 1px 3px rgba(0,0,0,.05), 0 6px 20px rgba(0,0,0,.07); margin-bottom: 18px;
}
.table-overflow { overflow-x: auto; -webkit-overflow-scrolling: touch; }
table { width: 100%; border-collapse: collapse; min-width: 820px; }
thead th {
  padding: 11px 16px; font-size: 10.5px; font-weight: 700;
  color: #5A7A69; text-align: left; background: #F4FAF7;
  border-bottom: 1.5px solid #D8EDE2; text-transform: uppercase;
  letter-spacing: 0.6px; white-space: nowrap;
}
tbody tr { transition: background 0.12s; cursor: pointer; }
tbody tr:hover { background: #F3FAF6; }
tbody td { padding: 13px 16px; font-size: 13px; border-bottom: 1px solid var(--border); vertical-align: middle; }
tbody tr:last-child td { border-bottom: none; }
.td-muted { color: var(--text-gray); font-size: 12.5px; }
.td-small { font-size: 12.5px; }
.id-link { color: var(--green-mid); font-weight: 700; font-size: 12.5px; display: block; }
.id-sub { font-size: 11px; color: var(--text-light); margin-top: 2px; max-width: 160px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.resp-cell { display: flex; align-items: center; gap: 8px; }
.resp-avatar {
  width: 26px; height: 26px; border-radius: 50%; flex-shrink: 0;
  background: var(--green-bg); color: var(--green-mid);
  display: flex; align-items: center; justify-content: center;
  font-size: 11px; font-weight: 800;
}
.resp-none { color: var(--text-light); font-size: 12.5px; font-style: italic; }

.badge-status {
  display: inline-block; padding: 3px 10px; border-radius: 99px;
  font-size: 11.5px; font-weight: 700; border-width: 1.5px; border-style: solid;
}
.badge-status.nao_validado, .badge-status.rejected { color: #E53E3E; border-color: #FC8181; background: #FFF5F5; }
.badge-status.resolvido, .badge-status.resolved { color: var(--green-mid); border-color: #68D391; background: var(--green-pale); }
.badge-status.encerrado, .badge-status.closed { color: #C05621; border-color: #F6AD55; background: #FFFAF0; }

.btn-detail {
  display: inline-flex; align-items: center; gap: 5px;
  background: var(--green-bg); color: var(--green-mid); border: none;
  border-radius: 7px; padding: 6px 12px; font-family: 'Poppins', sans-serif;
  font-size: 12px; font-weight: 600; cursor: pointer; transition: background 0.15s;
}
.btn-detail:hover { background: var(--green-pale); }

.pagination-bar {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 20px; border-top: 1px solid var(--border);
}
.pagination-info { font-size: 12.5px; color: var(--text-light); }
.pagination-btns { display: flex; align-items: center; gap: 6px; }
.pg-btn {
  height: 32px; min-width: 32px; border-radius: 7px;
  display: inline-flex; align-items: center; justify-content: center;
  font-family: 'Poppins', sans-serif; font-size: 12.5px; font-weight: 600;
  cursor: pointer; border: 1.5px solid var(--border); background: var(--white);
  color: var(--text-gray); padding: 0 10px;
  transition: border-color 0.15s, background 0.15s, color 0.15s;
}
.pg-btn:hover { border-color: var(--green-mid); color: var(--green-mid); }
.pg-btn.active { background: var(--green-mid); border-color: var(--green-mid); color: #fff; }
.pg-btn:disabled { opacity: 0.45; cursor: not-allowed; }
.empty-row { text-align: center; padding: 36px; color: var(--text-light); font-size: 13px; }

/* ── FOOTER ─────────────────────────────── */
.dash-footer {
  display: flex; align-items: center; justify-content: space-between;
  padding: 12px 28px; background: var(--white);
  box-shadow: 0 -1px 10px rgba(0,0,0,.06); font-size: 11.5px; color: var(--text-light); flex-shrink: 0;
}
.dash-footer a { color: var(--text-light); text-decoration: none; margin-left: 16px; transition: color 0.2s; }
.dash-footer a:hover { color: var(--green-mid); }

/* ── DETAIL DRAWER ──────────────────────── */
.drawer-overlay { position: fixed; inset: 0; background: rgba(15,28,22,0.4); z-index: 200; }
.drawer {
  position: fixed; top: 0; right: 0; bottom: 0; width: 480px; max-width: 95vw;
  background: var(--white); z-index: 201; display: flex; flex-direction: column;
  box-shadow: -6px 0 32px rgba(0,0,0,.14); overflow: hidden;
}
.drawer-hd {
  display: flex; align-items: center; justify-content: space-between;
  padding: 20px 24px 18px; border-bottom: 1px solid var(--border); flex-shrink: 0;
}
.drawer-hd h3 { font-size: 15px; font-weight: 800; margin-bottom: 2px; }
.drawer-hd p  { font-size: 12px; color: var(--text-light); }
.btn-close {
  width: 32px; height: 32px; background: #F4F6F5; border: 1.5px solid var(--border);
  border-radius: 8px; display: flex; align-items: center; justify-content: center;
  cursor: pointer; transition: background 0.2s; flex-shrink: 0;
}
.btn-close:hover { background: #FFF5F5; border-color: #FC8181; }
.drawer-loading { display: flex; align-items: center; gap: 10px; padding: 28px 24px; font-size: 13px; color: var(--text-gray); }
.drawer-body { flex: 1; overflow-y: auto; padding: 22px 24px; }
.drawer-body::-webkit-scrollbar { width: 4px; }
.drawer-body::-webkit-scrollbar-thumb { background: #C8D8CE; border-radius: 99px; }
.drawer-status-row { display: flex; align-items: center; margin-bottom: 18px; }
.detail-row { display: flex; justify-content: space-between; align-items: flex-start; padding: 10px 0; border-bottom: 1px solid #F0F4F2; }
.detail-key { font-size: 12px; font-weight: 600; color: var(--text-light); min-width: 140px; }
.detail-val { font-size: 13px; color: var(--text-dark); text-align: right; flex: 1; }
.drawer-section { margin-top: 20px; }
.drawer-section-label { font-size: 11.5px; font-weight: 700; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 10px; }
.drawer-desc { background: #F4F6F5; border-radius: 9px; padding: 14px; font-size: 13px; color: var(--text-gray); line-height: 1.7; }
.attach-list { display: flex; flex-direction: column; gap: 6px; }
.attach-item {
  display: flex; align-items: center; gap: 8px; padding: 8px 12px;
  background: var(--green-bg); border: 1px solid #C3E6CE; border-radius: 8px;
  color: var(--green-dark); font-family: 'Poppins', sans-serif; font-size: 12.5px; font-weight: 500;
  cursor: pointer; transition: background 0.2s; width: 100%; text-align: left;
}
.attach-item:hover { background: var(--green-pale); }
.attach-size { margin-left: auto; font-size: 11px; color: var(--text-light); }
.dl-icon { flex-shrink: 0; opacity: 0.5; }
.timeline { display: flex; flex-direction: column; }
.timeline-item { display: flex; align-items: flex-start; gap: 12px; padding-bottom: 16px; position: relative; }
.timeline-item:not(:last-child)::before { content: ''; position: absolute; left: 5px; top: 14px; bottom: 0; width: 1.5px; background: var(--border); }
.tl-dot { width: 12px; height: 12px; border-radius: 50%; flex-shrink: 0; margin-top: 2px; border: 2px solid; }
.tl-content { flex: 1; }
.tl-title   { font-size: 13px; font-weight: 600; color: var(--text-dark); }
.tl-comment { font-size: 12.5px; color: var(--text-gray); margin-top: 3px; line-height: 1.5; }
.tl-date    { font-size: 11.5px; color: var(--text-light); margin-top: 3px; }
.tl-empty   { font-size: 13px; color: var(--text-light); font-style: italic; }

/* ── TRANSITIONS ────────────────────────── */
.fade-enter-active, .fade-leave-active { transition: opacity 0.25s ease; }
.fade-enter-from,  .fade-leave-to      { opacity: 0; }
.slide-right-enter-active, .slide-right-leave-active { transition: transform 0.3s cubic-bezier(.16,1,.3,1); }
.slide-right-enter-from, .slide-right-leave-to { transform: translateX(100%); }
</style>
