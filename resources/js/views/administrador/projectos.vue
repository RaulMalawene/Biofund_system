<template>
  <div class="app-shell">

    <!-- ── SIDEBAR ── -->
    <aside class="sidebar">
      <router-link to="/" class="sidebar-logo">
        <img src="../../Imagem/logotipoBiofund.jpeg" alt="Biofund" class="sidebar-logo-img"/>
        <div>
          <div class="sidebar-logo-text">BioFund Admin</div>
        </div>
      </router-link>

      <nav class="sidebar-nav">
        <router-link class="nav-item" to="/admin/dashboard">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <rect x="1" y="1" width="6" height="6" rx="1.5"/><rect x="9" y="1" width="6" height="6" rx="1.5"/>
            <rect x="1" y="9" width="6" height="6" rx="1.5"/><rect x="9" y="9" width="6" height="6" rx="1.5"/>
          </svg>
          Dashboard
        </router-link>
        <router-link class="nav-item" to="/admin/validacao">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <rect x="2" y="1" width="12" height="14" rx="1.5"/>
            <path d="M5 6h6M5 9h4" stroke-linecap="round"/>
            <path d="M9 1v3h4" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Validação
        </router-link>
        <router-link class="nav-item" to="/admin/utilizadores">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <circle cx="8" cy="6" r="3"/><path d="M2 14c0-2.761 2.686-5 6-5s6 2.239 6 5" stroke-linecap="round"/>
          </svg>
          Utilizadores
        </router-link>
        <router-link class="nav-item" to="/admin/historico">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <rect x="2" y="1" width="10" height="14" rx="1.5"/>
            <path d="M5 5h4M5 8h4M5 11h2" stroke-linecap="round"/>
            <path d="M10 1v4h4" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Histórico de Ocorrências
        </router-link>
        <router-link class="nav-item" to="/admin/categorias">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <circle cx="5" cy="5" r="2"/><circle cx="11" cy="5" r="2"/>
            <circle cx="5" cy="11" r="2"/><circle cx="11" cy="11" r="2"/>
          </svg>
          Categorias
        </router-link>
        <a class="nav-item active">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <path d="M2 13L6 4l4 6 3-3 3 4" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Projectos
        </a>
      </nav>

      <div class="sidebar-footer">
        <button class="btn-logout" @click="$router.push('/acessoRestrito')">
          <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <path d="M6 14H3a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h3M10 11l3-3-3-3M13 8H6" stroke-linecap="round" stroke-linejoin="round"/>
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
            <circle cx="7" cy="7" r="5"/><path d="M12 12l3 3" stroke-linecap="round"/>
          </svg>
          <input type="text" placeholder="Pesquisar reclamações ou utilizador" v-model="topSearch"/>
        </div>
        <div class="topbar-spacer"></div>
        <div class="notif-btn">
          <svg width="16" height="16" fill="none" stroke="#555B5A" stroke-width="1.7" viewBox="0 0 16 16">
            <path d="M8 2a5 5 0 0 0-5 5v3l-1.5 2h13L13 10V7a5 5 0 0 0-5-5z"/>
            <path d="M6.5 13.5a1.5 1.5 0 0 0 3 0" stroke-linecap="round"/>
          </svg>
          <span class="notif-dot"></span>
        </div>
        <div class="admin-info">
          <div class="admin-text">
            <div class="admin-name">Admin Central</div>
            <div class="admin-role">Ministério da Terra e Ambiente</div>
          </div>
          <div class="admin-avatar">AC</div>
        </div>
      </header>

      <!-- CONTENT -->
      <main class="content">

        <!-- Breadcrumb -->
        <div class="breadcrumb">Gestão de Sistema</div>

        <!-- Page header -->
        <div class="page-title-row">
          <div>
            <h1>Projectos</h1>
            <p>Administre os projectos para classificar as reclamações ambientais em todo o território nacional.</p>
          </div>
          <button class="btn-green-sm" @click="openAdd">
            <svg width="14" height="14" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 14 14">
              <path d="M7 2v10M2 7h10" stroke-linecap="round"/>
            </svg>
            Adicionar Projecto
          </button>
        </div>

        <!-- Toolbar -->
        <div class="toolbar">
          <div class="search-proj">
            <svg width="14" height="14" fill="none" stroke="#8A9490" stroke-width="1.8" viewBox="0 0 16 16">
              <circle cx="7" cy="7" r="5"/><path d="M12 12l3 3" stroke-linecap="round"/>
            </svg>
            <input type="text" placeholder="Pesquisar por nome ou descrição..." v-model="search"/>
          </div>
          <button class="btn-tool">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 14 14">
              <rect x="1" y="1" width="5" height="5" rx="1"/><rect x="8" y="1" width="5" height="5" rx="1"/>
              <rect x="1" y="8" width="5" height="5" rx="1"/><rect x="8" y="8" width="5" height="5" rx="1"/>
            </svg>
            Vista Grade
          </button>
          <button class="btn-tool">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 14 14">
              <path d="M1 4h12M3 7h8M5 10h4" stroke-linecap="round"/>
            </svg>
            Ordenar: Mais Ativas
          </button>
        </div>

        <!-- Project grid -->
        <div class="proj-grid">

          <div class="proj-card" v-for="p in filteredProjects" :key="p.id">
            <div class="card-top">
              <div class="card-icon-wrap">
                <svg width="24" height="24" fill="none" :stroke="p.iconColor" stroke-width="1.7" viewBox="0 0 24 24" v-html="p.iconPath"></svg>
              </div>
              <span :class="p.ativa ? 'badge-ativa' : 'badge-inativa'">{{ p.ativa ? 'Ativa' : 'Inativa' }}</span>
            </div>
            <div class="proj-name">{{ p.nome }}</div>
            <div class="proj-desc">{{ p.descricao }}</div>
            <div class="card-footer">
              <div>
                <div class="occ-label">Ocorrências</div>
                <div class="occ-val">{{ p.ocorrencias }}</div>
              </div>
              <div class="card-actions">
                <button class="btn-card-action btn-card-edit" @click="openEdit(p)" title="Editar">
                  <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 14 14">
                    <path d="M9.5 2.5l2 2L4 12H2v-2z" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </button>
                <button class="btn-card-action btn-card-del" @click="openDel(p)" title="Eliminar">
                  <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 14 14">
                    <path d="M2 3.5h10M5.5 3.5V2.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v1M3.5 3.5l.5 8h6l.5-8" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <!-- New project card -->
          <div class="new-proj-card" @click="openAdd">
            <div class="new-proj-icon">
              <svg width="20" height="20" fill="none" stroke="#8A9490" stroke-width="2" viewBox="0 0 20 20">
                <path d="M10 4v12M4 10h12" stroke-linecap="round"/>
              </svg>
            </div>
            <span class="new-proj-label">Novo projecto</span>
          </div>

        </div>

        <!-- Taxonomy notice -->
        <div class="notice">
          <div class="notice-icon">
            <svg width="16" height="16" fill="none" stroke="#8A9490" stroke-width="1.7" viewBox="0 0 16 16">
              <circle cx="8" cy="8" r="6"/><path d="M8 7v4M8 5.5h.01" stroke-linecap="round" stroke-width="1.8"/>
            </svg>
          </div>
          <div>
            <div class="notice-title">Nota sobre Taxonomia</div>
            <div class="notice-desc">A alteração do nome de um projecto irá atualizar automaticamente todos os registos históricos de reclamações associadas. Recomenda-se cautela ao remover projectos que possuam um elevado número de ocorrências.</div>
          </div>
        </div>

      </main>

      <!-- FOOTER -->
      <footer class="dash-footer">
        <span>© 2026 BioFund Admin · Sistema de Gestão Ambiental de Moçambique</span>
        <div>
          <a href="#">Suporte Técnico</a>
          <a href="#">Privacidade</a>
        </div>
      </footer>
    </div>

    <!-- ── ADD / EDIT MODAL ── -->
    <transition name="fade">
      <div v-if="modal.show" class="modal-overlay" @click.self="modal.show = false">
        <div class="modal-panel" @click.stop>
          <div class="modal-hd">
            <div>
              <h3>{{ modal.editing ? 'Editar Projecto' : 'Novo Projecto' }}</h3>
              <p>{{ modal.editing ? 'Actualize os dados do projecto.' : 'Preencha os dados para criar um novo projecto.' }}</p>
            </div>
            <button class="btn-close" @click="modal.show = false">
              <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 13 13">
                <path d="M2 2l9 9M11 2L2 11" stroke-linecap="round"/>
              </svg>
            </button>
          </div>

          <div class="mfield">
            <label>Nome do Projecto</label>
            <input type="text" v-model="mf.nome" placeholder="ex: MozRural"/>
          </div>
          <div class="mfield">
            <label>Descrição</label>
            <textarea v-model="mf.descricao" placeholder="Descreva o âmbito do projecto..."></textarea>
          </div>
          <div class="modal-row">
            <div class="mfield">
              <label>Ícone</label>
              <div class="icon-grid">
                <div class="icon-opt" :class="{ sel: mf.icon === i }" v-for="i in iconOptions" :key="i" @click="mf.icon = i">
                  <svg width="18" height="18" fill="none" :stroke="i === mf.icon ? '#2D6A4F' : '#8A9490'" stroke-width="1.7" viewBox="0 0 24 24" v-html="iconPaths[i]"></svg>
                </div>
              </div>
            </div>
            <div class="mfield">
              <label>Estado</label>
              <select v-model="mf.ativa">
                <option :value="true">Ativa</option>
                <option :value="false">Inativa</option>
              </select>
            </div>
          </div>

          <div class="modal-actions">
            <button class="btn-mc" @click="modal.show = false">Cancelar</button>
            <button class="btn-ms" @click="saveProject" :disabled="!mf.nome.trim()">
              {{ modal.editing ? 'Guardar' : 'Criar Projecto' }}
            </button>
          </div>
        </div>
      </div>
    </transition>

    <!-- ── DELETE MODAL ── -->
    <transition name="fade">
      <div v-if="delModal.show" class="modal-overlay" @click.self="delModal.show = false">
        <div class="modal-panel modal-sm" @click.stop>
          <div class="del-icon-wrap">
            <svg width="22" height="22" fill="none" stroke="#E53E3E" stroke-width="1.9" viewBox="0 0 22 22">
              <path d="M3 5.5h16M8 5.5V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v1.5M5 5.5l1 14a1 1 0 0 0 1 .9h8a1 1 0 0 0 1-.9l1-14" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <h3>Eliminar Projecto</h3>
          <p class="del-desc">Tem a certeza que deseja eliminar <strong>{{ delModal.target?.nome }}</strong>? Esta acção actualizará <strong>{{ delModal.target?.ocorrencias }}</strong> ocorrências associadas.</p>
          <div class="modal-actions">
            <button class="btn-mc" @click="delModal.show = false">Cancelar</button>
            <button class="btn-ms danger" @click="doDelete">Eliminar</button>
          </div>
        </div>
      </div>
    </transition>

    <!-- TOAST -->
    <transition name="toast-slide">
      <div v-if="toast.show" class="toast" :class="{ red: toast.red }">
        <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 16 16">
          <circle cx="8" cy="8" r="6"/><path d="M5.5 8l2 2 3.5-4" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        {{ toast.msg }}
      </div>
    </transition>

  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'

const topSearch = ref('')
const search    = ref('')

const ICON_PATHS = {
  nature: '<circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M2 12h2M20 12h2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" stroke-linecap="round"/>',
  tree:   '<path d="M12 2L4 12h5v8h6v-8h5z" stroke-linecap="round" stroke-linejoin="round"/>',
  water:  '<path d="M12 2C12 2 4 10 4 15a8 8 0 0 0 16 0c0-5-8-13-8-13z" stroke-linecap="round"/>',
  fire:   '<path d="M12 2C12 2 8 8 8 13a4 4 0 0 0 8 0C16 9 12 2 12 2z" stroke-linecap="round"/><path d="M12 22v-6" stroke-linecap="round"/>',
  drop:   '<path d="M12 2C12 2 5 11 5 16a7 7 0 0 0 14 0C19 11 12 2 12 2z" stroke-linecap="round"/>',
  person: '<circle cx="12" cy="8" r="4"/><path d="M5 20c0-4 3.134-7 7-7s7 3 7 7" stroke-linecap="round"/>',
  leaf:   '<path d="M17 8C17 8 16 2 6 2C6 12 12 16 12 16" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 16C12 16 8 12 6 6" stroke-linecap="round"/>',
  fish:   '<path d="M2 12s3-6 10-6 10 6 10 6-3 6-10 6S2 12 2 12z"/><circle cx="16" cy="12" r="1.5" fill="currentColor" stroke="none"/>',
}

const iconColors = {
  nature: '#F59E0B', tree: '#2D6A4F', water: '#3B82F6', fire: '#EF4444',
  drop: '#06B6D4', person: '#8B5CF6', leaf: '#52B788', fish: '#0EA5E9',
}

const iconOptions = Object.keys(ICON_PATHS)
const iconPaths   = ICON_PATHS

let nextId = 7

const projects = ref([
  { id: 1, nome: 'MozRural',    descricao: 'Relatos sobre caça ilegal, tráfico de animais silvestres e proteção de espécies ameaçadas.',        icon: 'nature', iconColor: '#52B788', iconPath: ICON_PATHS.nature, ocorrencias: 142, ativa: true },
  { id: 2, nome: 'Chelton',     descricao: 'Desmatamento não autorizado, corte de mangais e comercialização ilegal de madeira.',                icon: 'tree',   iconColor: '#2D6A4F', iconPath: ICON_PATHS.tree,   ocorrencias: 89,  ativa: true },
  { id: 3, nome: 'Pesca Moz',   descricao: 'Uso de redes proibidas, pesca em épocas de veda e em áreas de proteção marinha.',                  icon: 'water',  iconColor: '#3B82F6', iconPath: ICON_PATHS.water,  ocorrencias: 56,  ativa: true },
  { id: 4, nome: 'C Moz',       descricao: 'Incêndios descontrolados para abertura de machambas e queima de resíduos ao ar livre.',             icon: 'fire',   iconColor: '#EF4444', iconPath: ICON_PATHS.fire,   ocorrencias: 213, ativa: true },
  { id: 5, nome: 'A Moza',      descricao: 'Descarte de resíduos industriais e químicos em rios, lagos e zonas costeiras.',                    icon: 'drop',   iconColor: '#06B6D4', iconPath: ICON_PATHS.drop,   ocorrencias: 74,  ativa: true },
  { id: 6, nome: 'R Moz',       descricao: 'Acúmulo inadequado de lixo urbano, entulhos e criação de lixeiras não autorizadas.',               icon: 'person', iconColor: '#8B5CF6', iconPath: ICON_PATHS.person, ocorrencias: 121, ativa: true },
])

const modal    = reactive({ show: false, editing: false })
const delModal = reactive({ show: false, target: null })
const toast    = reactive({ show: false, msg: '', red: false })
const mf       = reactive({ id: null, nome: '', descricao: '', icon: 'nature', ativa: true })

const filteredProjects = computed(() => {
  const q = search.value.toLowerCase()
  if (!q) return projects.value
  return projects.value.filter(p =>
    p.nome.toLowerCase().includes(q) ||
    p.descricao.toLowerCase().includes(q)
  )
})

function showToast(msg, red = false) {
  toast.msg = msg; toast.red = red; toast.show = true
  setTimeout(() => { toast.show = false }, 3000)
}

function openAdd() {
  Object.assign(mf, { id: null, nome: '', descricao: '', icon: 'nature', ativa: true })
  modal.editing = false; modal.show = true
}

function openEdit(p) {
  Object.assign(mf, { id: p.id, nome: p.nome, descricao: p.descricao, icon: p.icon, ativa: p.ativa })
  modal.editing = true; modal.show = true
}

function saveProject() {
  if (!mf.nome.trim()) return
  if (modal.editing) {
    const p = projects.value.find(x => x.id === mf.id)
    if (p) {
      p.nome = mf.nome; p.descricao = mf.descricao; p.icon = mf.icon
      p.ativa = mf.ativa; p.iconColor = iconColors[mf.icon] || '#52B788'
      p.iconPath = ICON_PATHS[mf.icon]
    }
    showToast('Projecto actualizado com sucesso!')
  } else {
    projects.value.push({
      id: nextId++, nome: mf.nome, descricao: mf.descricao, icon: mf.icon,
      iconColor: iconColors[mf.icon] || '#52B788', iconPath: ICON_PATHS[mf.icon],
      ocorrencias: 0, ativa: mf.ativa,
    })
    showToast('Projecto criado com sucesso!')
  }
  modal.show = false
}

function openDel(p) { delModal.target = p; delModal.show = true }

function doDelete() {
  projects.value = projects.value.filter(p => p.id !== delModal.target.id)
  showToast(`"${delModal.target.nome}" eliminado.`, true)
  delModal.show = false
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

/* ── SIDEBAR ─────────────────────────────── */
.sidebar {
  width: 210px;
  flex-shrink: 0;
  background: var(--white);
  border-right: none;
  box-shadow: 2px 0 18px rgba(0,0,0,.07);
  display: flex;
  flex-direction: column;
  height: 100vh;
  position: fixed;
  top: 0; left: 0;
  z-index: 50;
}

.sidebar-logo {
  display: flex;
  align-items: center;
  gap: 9px;
  padding: 18px 16px 16px;
  border-bottom: 1px solid rgba(0,0,0,.06);
  text-decoration: none;
}

.sidebar-logo-img {
  width: 32px;
  height: 32px;
  object-fit: contain;
  border-radius: 6px;
  flex-shrink: 0;
}

.sidebar-logo-text {
  font-size: 13.5px;
  font-weight: 800;
  color: var(--green-dark);
  line-height: 1.2;
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
.nav-item:hover { background: var(--green-bg); color: var(--green-mid); }
.nav-item.active { background: var(--green-bg); color: var(--green-mid); font-weight: 700; }
.nav-item svg { flex-shrink: 0; opacity: 0.75; }
.nav-item.active svg { opacity: 1; }

.sidebar-footer {
  padding: 14px 10px;
  border-top: 1px solid rgba(0,0,0,.06);
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
.btn-logout:hover { background: #FFF5F5; }

/* ── MAIN AREA ───────────────────────────── */
.main {
  margin-left: 210px;
  flex: 1;
  display: flex;
  flex-direction: column;
  height: 100vh;
  overflow: hidden;
}

/* ── TOPBAR ──────────────────────────────── */
.topbar {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 0 28px;
  height: 58px;
  background: var(--white);
  border-bottom: none;
  box-shadow: 0 1px 12px rgba(0,0,0,.07);
  flex-shrink: 0;
  position: relative;
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
.search-wrap:focus-within { border-color: var(--green-light); }
.search-wrap input {
  border: none; outline: none; background: transparent;
  font-family: 'Poppins', sans-serif; font-size: 13px; color: var(--text-dark); width: 100%;
}
.search-wrap input::placeholder { color: var(--text-light); }

.topbar-spacer { flex: 1; }

.notif-btn {
  position: relative;
  width: 38px; height: 38px;
  background: #F4F6F5;
  border: 1.5px solid var(--border);
  border-radius: 9px;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer;
  transition: border-color 0.2s;
}
.notif-btn:hover { border-color: var(--green-light); }

.notif-dot {
  position: absolute;
  top: 7px; right: 8px;
  width: 7px; height: 7px;
  border-radius: 50%;
  background: #E53E3E;
  border: 1.5px solid var(--white);
}

.admin-info { display: flex; align-items: center; gap: 10px; }
.admin-text { text-align: right; }
.admin-name { font-size: 13px; font-weight: 700; color: var(--text-dark); }
.admin-role { font-size: 11px; color: var(--text-light); }

.admin-avatar {
  width: 36px; height: 36px;
  border-radius: 50%;
  background: linear-gradient(135deg, #52B788, #1B4332);
  display: flex; align-items: center; justify-content: center;
  font-size: 13px; font-weight: 800; color: #fff;
  flex-shrink: 0;
}

/* ── CONTENT ─────────────────────────────── */
.content {
  flex: 1;
  overflow-y: auto;
  padding: 24px 28px 32px;
  background: #F2F6F4;
}
.content::-webkit-scrollbar { width: 5px; }
.content::-webkit-scrollbar-track { background: transparent; }
.content::-webkit-scrollbar-thumb { background: #C8D8CE; border-radius: 99px; }

/* breadcrumb */
.breadcrumb {
  font-size: 11.5px;
  font-weight: 600;
  color: var(--green-mid);
  margin-bottom: 6px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* page title row */
.page-title-row {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  margin-bottom: 20px;
}
.page-title-row h1 { font-size: 22px; font-weight: 800; margin-bottom: 5px; }
.page-title-row p  { font-size: 13px; color: var(--text-gray); max-width: 480px; line-height: 1.55; }

.btn-green-sm {
  display: inline-flex; align-items: center; gap: 7px;
  background: var(--green-mid); color: #fff;
  border: none; border-radius: 9px; padding: 11px 20px;
  font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 700;
  cursor: pointer; transition: background 0.2s, transform 0.15s; flex-shrink: 0;
}
.btn-green-sm:hover { background: var(--green-dark); transform: translateY(-1px); }

/* toolbar */
.toolbar {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 20px;
}

.search-proj {
  flex: 1;
  display: flex; align-items: center; gap: 9px;
  background: var(--white); border: 1.5px solid var(--border);
  border-radius: 9px; padding: 9px 14px;
  transition: border-color 0.2s, box-shadow 0.2s;
}
.search-proj:focus-within { border-color: var(--green-light); box-shadow: 0 0 0 3px rgba(82,183,136,.12); }
.search-proj input {
  border: none; outline: none; background: transparent;
  font-family: 'Poppins', sans-serif; font-size: 13px; color: var(--text-dark); width: 100%;
}
.search-proj input::placeholder { color: var(--text-light); }

.btn-tool {
  display: inline-flex; align-items: center; gap: 7px;
  background: var(--white); color: var(--text-gray);
  border: 1.5px solid var(--border); border-radius: 8px;
  padding: 9px 14px;
  font-family: 'Poppins', sans-serif; font-size: 12.5px; font-weight: 600;
  cursor: pointer; white-space: nowrap; transition: border-color 0.2s, color 0.2s;
}
.btn-tool:hover { border-color: var(--green-mid); color: var(--green-mid); }

/* ── PROJECT GRID ────────────────────────── */
.proj-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
  margin-bottom: 20px;
}

.proj-card {
  background: var(--white);
  border: none;
  border-radius: 16px;
  padding: 20px 20px 18px;
  display: flex; flex-direction: column;
  box-shadow: 0 1px 3px rgba(0,0,0,.05), 0 6px 20px rgba(0,0,0,.07);
  transition: box-shadow 0.2s, transform 0.2s;
}
.proj-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,.12); transform: translateY(-2px); }

.card-top {
  display: flex; align-items: flex-start;
  justify-content: space-between; margin-bottom: 14px;
}

.card-icon-wrap {
  width: 46px; height: 46px;
  background: var(--green-bg);
  border-radius: 12px;
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}

.badge-ativa {
  font-size: 11px; font-weight: 700;
  color: var(--green-mid); background: var(--green-pale);
  border: 1.5px solid #68D391; border-radius: 99px; padding: 2px 10px;
}
.badge-inativa {
  font-size: 11px; font-weight: 700;
  color: #744210; background: #FEFCBF;
  border: 1.5px solid #F6D860; border-radius: 99px; padding: 2px 10px;
}

.proj-name { font-size: 16px; font-weight: 800; margin-bottom: 6px; color: var(--text-dark); }
.proj-desc { font-size: 12px; color: var(--text-gray); line-height: 1.6; flex: 1; margin-bottom: 16px; }

.card-footer {
  display: flex; align-items: center; justify-content: space-between;
  border-top: 1px solid var(--border); padding-top: 14px; margin-top: auto;
}

.occ-label { font-size: 9.5px; font-weight: 700; letter-spacing: 0.7px; color: var(--text-light); text-transform: uppercase; margin-bottom: 2px; }
.occ-val   { font-size: 20px; font-weight: 800; color: var(--text-dark); }

.card-actions { display: flex; gap: 8px; }

.btn-card-action {
  width: 30px; height: 30px; border-radius: 7px; border: none;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; transition: background 0.15s;
}
.btn-card-edit { background: var(--green-bg); color: var(--green-mid); }
.btn-card-edit:hover { background: var(--green-pale); }
.btn-card-del { background: #FFF5F5; color: #E53E3E; }
.btn-card-del:hover { background: #FED7D7; }

/* new project card */
.new-proj-card {
  background: var(--white);
  border: 2px dashed var(--border);
  border-radius: 16px;
  display: flex; flex-direction: column;
  align-items: center; justify-content: center; gap: 12px;
  cursor: pointer; padding: 40px 20px;
  transition: border-color 0.2s, background 0.2s;
  min-height: 200px;
}
.new-proj-card:hover { border-color: var(--green-light); background: var(--green-bg); }

.new-proj-icon {
  width: 44px; height: 44px; border-radius: 50%;
  background: #F4F6F5; border: 1.5px solid var(--border);
  display: flex; align-items: center; justify-content: center;
  transition: background 0.2s, border-color 0.2s;
}
.new-proj-card:hover .new-proj-icon { background: var(--green-pale); border-color: var(--green-light); }

.new-proj-label { font-size: 13px; font-weight: 600; color: var(--text-gray); }
.new-proj-card:hover .new-proj-label { color: var(--green-mid); }

/* notice */
.notice {
  display: flex; align-items: flex-start; gap: 12px;
  background: var(--white); border: none; border-radius: 16px;
  padding: 16px 18px; margin-top: 6px;
  box-shadow: 0 1px 3px rgba(0,0,0,.05), 0 6px 20px rgba(0,0,0,.07);
}
.notice-icon {
  width: 34px; height: 34px; border-radius: 8px;
  background: #F4F6F5;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0; margin-top: 1px;
}
.notice-title { font-size: 13px; font-weight: 700; margin-bottom: 4px; }
.notice-desc  { font-size: 12.5px; color: var(--text-gray); line-height: 1.65; }

/* ── FOOTER ──────────────────────────────── */
.dash-footer {
  display: flex; align-items: center; justify-content: space-between;
  padding: 12px 28px;
  background: var(--white); border-top: none;
  box-shadow: 0 -1px 10px rgba(0,0,0,.06);
  font-size: 11.5px; color: var(--text-light); flex-shrink: 0;
}
.dash-footer a {
  color: var(--text-light); text-decoration: none;
  margin-left: 16px; transition: color 0.2s;
}
.dash-footer a:hover { color: var(--green-mid); }

/* ── MODAL OVERLAY ───────────────────────── */
.modal-overlay {
  position: fixed; inset: 0;
  background: rgba(0, 0, 0, 0.45);
  display: flex; align-items: center; justify-content: center;
  z-index: 200; padding: 16px;
}

.modal-panel {
  background: var(--white); border-radius: 16px;
  padding: 30px 32px 26px;
  width: 480px; max-width: 95vw;
  box-shadow: 0 16px 60px rgba(0,0,0,.18);
}

.modal-sm { width: 380px; }

.modal-hd {
  display: flex; align-items: flex-start;
  justify-content: space-between; margin-bottom: 20px;
}
.modal-hd h3 { font-size: 16px; font-weight: 800; }
.modal-hd p  { font-size: 12.5px; color: var(--text-gray); margin-top: 3px; }

.btn-close {
  width: 32px; height: 32px;
  background: #F4F6F5; border: 1.5px solid var(--border);
  border-radius: 8px; display: flex; align-items: center; justify-content: center;
  cursor: pointer; transition: background 0.2s; flex-shrink: 0;
}
.btn-close:hover { background: #FFF5F5; border-color: #FC8181; }

.mfield { display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px; }
.mfield label { font-size: 12.5px; font-weight: 600; color: var(--text-dark); }
.mfield input,
.mfield select,
.mfield textarea {
  font-family: 'Poppins', sans-serif; font-size: 13px; color: var(--text-dark);
  border: 1.5px solid var(--border); border-radius: 8px;
  padding: 10px 13px; outline: none;
  transition: border-color 0.2s, box-shadow 0.2s;
  width: 100%; background: var(--white); resize: vertical;
}
.mfield input:focus,
.mfield select:focus,
.mfield textarea:focus {
  border-color: var(--green-light);
  box-shadow: 0 0 0 3px rgba(82,183,136,.12);
}
.mfield textarea { min-height: 80px; }

.modal-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

/* icon picker */
.icon-grid { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 4px; }
.icon-opt {
  width: 40px; height: 40px; border-radius: 9px;
  background: #F4F6F5; border: 2px solid var(--border);
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; transition: background 0.15s, border-color 0.15s;
}
.icon-opt:hover, .icon-opt.sel { background: var(--green-bg); border-color: var(--green-mid); }

.modal-actions { display: flex; gap: 10px; margin-top: 20px; }

.btn-mc {
  flex: 1; background: var(--white); color: var(--text-gray);
  border: 1.5px solid var(--border); border-radius: 9px; padding: 11px;
  font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600;
  cursor: pointer; transition: border-color 0.2s;
}
.btn-mc:hover { border-color: var(--text-gray); }

.btn-ms {
  flex: 1; background: var(--green-mid); color: #fff;
  border: none; border-radius: 9px; padding: 11px;
  font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 700;
  cursor: pointer; transition: background 0.2s;
}
.btn-ms:hover { background: var(--green-dark); }
.btn-ms.danger { background: #E53E3E; }
.btn-ms.danger:hover { background: #C53030; }
.btn-ms:disabled { background: #A0C4B0; cursor: not-allowed; }

/* delete modal */
.del-icon-wrap {
  width: 52px; height: 52px; border-radius: 50%; background: #FFF5F5;
  display: flex; align-items: center; justify-content: center; margin-bottom: 14px;
}
.modal-panel h3 { font-size: 16px; font-weight: 800; margin-bottom: 8px; }
.del-desc { font-size: 13px; color: var(--text-gray); line-height: 1.6; margin-bottom: 4px; }

/* ── TOAST ───────────────────────────────── */
.toast {
  position: fixed; bottom: 24px; right: 24px; z-index: 500;
  background: var(--green-dark); color: #fff; border-radius: 12px;
  padding: 13px 18px; display: flex; align-items: center; gap: 9px;
  font-size: 13px; font-weight: 600; box-shadow: 0 8px 28px rgba(0,0,0,.18);
}
.toast.red { background: #C53030; }

/* transitions */
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.toast-slide-enter-active, .toast-slide-leave-active { transition: opacity 0.3s ease, transform 0.3s ease; }
.toast-slide-enter-from, .toast-slide-leave-to { opacity: 0; transform: translateY(16px); }
</style>
