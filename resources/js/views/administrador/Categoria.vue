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
            <path d="M8 1l1.5 3 3.5.5-2.5 2.5.5 3.5L8 9l-3 1.5.5-3.5L3 4.5 6.5 4z"/>
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
        <a class="nav-item active">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <circle cx="5" cy="5" r="2"/><circle cx="11" cy="5" r="2"/>
            <circle cx="5" cy="11" r="2"/><circle cx="11" cy="11" r="2"/>
          </svg>
          Categorias
        </a>
        <router-link class="nav-item" to="/admin/projectos">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <path d="M2 13L6 4l4 6 3-3 3 4" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Projectos
        </router-link>
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
          <input type="text" placeholder="Pesquisar categorias…"/>
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

        <!-- Page header -->
        <div class="page-title-row">
          <div>
            <h1>Categorias</h1>
            <p>Gerencie as categorias de ocorrências ambientais do sistema.</p>
          </div>
          <button class="btn-nova" @click="openNew">
            <svg width="14" height="14" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 14 14">
              <path d="M7 2v10M2 7h10" stroke-linecap="round"/>
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
                  <circle cx="5.5" cy="5.5" r="4"/><path d="M9.5 9.5l2 2" stroke-linecap="round"/>
                </svg>
                Pesquisar
              </label>
              <input type="text" placeholder="Nome ou descrição…" v-model="f.pesquisa"/>
            </div>
            <div class="filter-group">
              <label>
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 12 12">
                  <path d="M1 2h10l-4 5v4l-2-1V7z" stroke-linejoin="round"/>
                </svg>
                Estado
              </label>
              <select v-model="f.estado">
                <option value="">Todas</option>
                <option value="ativa">Ativas</option>
                <option value="inativa">Inativas</option>
              </select>
            </div>
            <div class="filter-group">
              <label>
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 12 12">
                  <circle cx="5" cy="5" r="2"/><circle cx="9" cy="5" r="2"/>
                  <circle cx="5" cy="9" r="2"/><circle cx="9" cy="9" r="2"/>
                </svg>
                Ícone
              </label>
              <select v-model="f.icone">
                <option value="">Todos os Ícones</option>
                <option value="fauna">Fauna</option>
                <option value="flora">Flora</option>
                <option value="agua">Água / Poluição Hídrica</option>
                <option value="fogo">Queimadas / Fogo</option>
                <option value="pesca">Pesca Ilegal</option>
                <option value="lixo">Resíduos / Lixo</option>
                <option value="ar">Poluição Atmosférica</option>
                <option value="caca">Caça Furtiva</option>
              </select>
            </div>
            <div class="filter-actions">
              <button class="btn-limpar" @click="limpar">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 14 14">
                  <path d="M2 2l10 10M12 2L2 12" stroke-linecap="round"/>
                </svg>
                Limpar
              </button>
            </div>
          </div>
        </div>

        <!-- GRID -->
        <div class="cat-grid">
          <div
            v-for="cat in filteredCategorias"
            :key="cat.id"
            class="cat-card"
            :class="{ inativa: !cat.ativa }"
          >
            <div class="cat-card-header">
              <div class="cat-icon-wrap" :style="{ background: cat.cor + '22', borderColor: cat.cor + '55' }">
                <svg width="22" height="22" fill="none" :stroke="cat.cor" stroke-width="1.7" viewBox="0 0 22 22">
                  <path :d="ICON_DEFS[cat.icone]"/>
                </svg>
              </div>
              <div class="cat-actions">
                <button class="btn-icon-sm" @click="openEdit(cat)" title="Editar">
                  <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 14 14">
                    <path d="M9.5 2.5l2 2L4 12H2v-2L9.5 2.5z" stroke-linejoin="round"/>
                  </svg>
                </button>
                <button class="btn-icon-sm danger" @click="confirmDelete(cat)" title="Eliminar">
                  <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 14 14">
                    <path d="M2 4h10M5 4V2.5h4V4M5.5 7v4M8.5 7v4M3 4l.8 8h6.4L11 4" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </button>
              </div>
            </div>

            <div class="cat-name">{{ cat.nome }}</div>
            <div class="cat-desc">{{ cat.descricao }}</div>

            <div class="cat-tags">
              <span class="tag" v-for="t in cat.tags" :key="t">{{ t }}</span>
            </div>

            <div class="cat-footer">
              <span class="cat-stat">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 12 12">
                  <path d="M2 10l3-6 3 4 2-2 2 3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                {{ cat.ocorrencias }} ocorrências
              </span>
              <span class="badge-ativa" :class="cat.ativa ? 'ativa' : 'inativa-badge'">
                {{ cat.ativa ? 'Ativa' : 'Inativa' }}
              </span>
            </div>
          </div>

          <div v-if="filteredCategorias.length === 0" class="empty-grid">
            Nenhuma categoria encontrada.
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

    <!-- ── MODAL EDITAR / CRIAR ── -->
    <transition name="fade">
      <div v-if="modalOpen" class="modal-overlay" @click.self="modalOpen = false"></div>
    </transition>
    <transition name="modal-pop">
      <div v-if="modalOpen" class="modal">
        <div class="modal-hd">
          <div>
            <h3>{{ editingId ? 'Editar Categoria' : 'Nova Categoria' }}</h3>
            <p>{{ editingId ? 'Atualize os dados da categoria.' : 'Preencha os dados da nova categoria.' }}</p>
          </div>
          <button class="btn-close" @click="modalOpen = false">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 13 13">
              <path d="M2 2l9 9M11 2L2 11" stroke-linecap="round"/>
            </svg>
          </button>
        </div>

        <div class="modal-body">
          <div class="f-group">
            <label>Nome da Categoria</label>
            <input type="text" v-model="form.nome" placeholder="Ex: Desmatamento Ilegal"/>
          </div>
          <div class="f-group">
            <label>Descrição</label>
            <textarea v-model="form.descricao" placeholder="Descreva brevemente esta categoria…"></textarea>
          </div>
          <div class="f-row">
            <div class="f-group">
              <label>Ícone</label>
              <select v-model="form.icone">
                <option value="fauna">Fauna</option>
                <option value="flora">Flora</option>
                <option value="agua">Água / Poluição Hídrica</option>
                <option value="fogo">Queimadas / Fogo</option>
                <option value="pesca">Pesca Ilegal</option>
                <option value="lixo">Resíduos / Lixo</option>
                <option value="ar">Poluição Atmosférica</option>
                <option value="caca">Caça Furtiva</option>
              </select>
            </div>
            <div class="f-group">
              <label>Estado</label>
              <select v-model="form.ativa">
                <option :value="true">Ativa</option>
                <option :value="false">Inativa</option>
              </select>
            </div>
          </div>
          <div class="f-group">
            <label>Cor</label>
            <div class="color-options">
              <button
                v-for="c in COLOR_OPTIONS" :key="c"
                class="color-swatch"
                :class="{ selected: form.cor === c }"
                :style="{ background: c }"
                @click="form.cor = c"
              ></button>
            </div>
          </div>
          <div class="f-group">
            <label>Tags (separadas por vírgula)</label>
            <input type="text" v-model="form.tagsStr" placeholder="Ex: floresta, biodiversidade, ilegal"/>
          </div>
        </div>

        <div class="modal-ft">
          <button class="btn-cancelar" @click="modalOpen = false">Cancelar</button>
          <button class="btn-guardar" @click="guardar">
            <svg width="14" height="14" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 14 14">
              <path d="M2 7l4 4 6-6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Guardar
          </button>
        </div>
      </div>
    </transition>

    <!-- ── MODAL ELIMINAR ── -->
    <transition name="fade">
      <div v-if="deleteTarget" class="modal-overlay" @click.self="deleteTarget = null"></div>
    </transition>
    <transition name="modal-pop">
      <div v-if="deleteTarget" class="modal modal-sm">
        <div class="modal-hd">
          <h3>Eliminar Categoria</h3>
          <button class="btn-close" @click="deleteTarget = null">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 13 13">
              <path d="M2 2l9 9M11 2L2 11" stroke-linecap="round"/>
            </svg>
          </button>
        </div>
        <div class="modal-body">
          <p class="delete-msg">
            Tem a certeza que deseja eliminar a categoria <strong>{{ deleteTarget?.nome }}</strong>?
            Esta ação não pode ser desfeita.
          </p>
        </div>
        <div class="modal-ft">
          <button class="btn-cancelar" @click="deleteTarget = null">Cancelar</button>
          <button class="btn-eliminar" @click="doDelete">
            <svg width="13" height="13" fill="none" stroke="#fff" stroke-width="1.8" viewBox="0 0 14 14">
              <path d="M2 4h10M5 4V2.5h4V4M5.5 7v4M8.5 7v4M3 4l.8 8h6.4L11 4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Eliminar
          </button>
        </div>
      </div>
    </transition>

    <!-- ── TOAST ── -->
    <transition name="toast-up">
      <div v-if="toast.show" class="toast" :class="toast.type">
        <svg v-if="toast.type==='success'" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 15 15">
          <circle cx="7.5" cy="7.5" r="6"/><path d="M5 7.5l2 2 3-4" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <svg v-else width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 15 15">
          <circle cx="7.5" cy="7.5" r="6"/><path d="M7.5 5v3M7.5 10h.01" stroke-linecap="round"/>
        </svg>
        {{ toast.msg }}
      </div>
    </transition>

  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'

const f = reactive({ pesquisa: '', estado: '', icone: '' })
const modalOpen  = ref(false)
const editingId  = ref(null)
const deleteTarget = ref(null)

const toast = reactive({ show: false, msg: '', type: 'success' })

function showToast(msg, type = 'success') {
  toast.msg = msg; toast.type = type; toast.show = true
  setTimeout(() => { toast.show = false }, 3000)
}

const ICON_DEFS = {
  fauna: 'M11 3c-1 0-2 .5-2.5 1.5C8 3.5 7 3 6 3 4.5 3 3 4.5 3 6.5c0 3.5 8 9.5 8 9.5s8-6 8-9.5C19 4.5 17.5 3 16 3c-1 0-2 .5-2.5 1.5C13 3.5 12 3 11 3z',
  flora: 'M11 21V11M11 11C11 6 6 3 3 5c3 1 5 4 5 7M11 11c0-5 5-8 8-6-3 1-5 4-5 7',
  agua:  'M11 2S6 8 6 13a5 5 0 0 0 10 0c0-5-5-11-5-11z',
  fogo:  'M12 2c0 6-6 8-6 14a6 6 0 0 0 12 0c0-3-1.5-5-3-7-1 3-3 4-3 7',
  pesca: 'M2 16c2-4 6-6 10-4M18 10a6 6 0 0 1-6 6M4 10h.01M20 6l-2 4-4-2',
  lixo:  'M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 11v6M14 11v6',
  ar:    'M3 8h12a3 3 0 0 1 0 6H9M3 14h8a3 3 0 0 0 0-6',
  caca:  'M12 2L8 8H4l4 4-2 8 6-4 6 4-2-8 4-4h-4z',
}

const COLOR_OPTIONS = [
  '#52B788', '#2D6A4F', '#4299E1', '#9F7AEA',
  '#ED8936', '#E53E3E', '#38B2AC', '#D69E2E',
]

const form = reactive({ nome: '', descricao: '', icone: 'fauna', cor: '#52B788', ativa: true, tagsStr: '' })

let nextId = 9
const categorias = ref([
  { id: 1, nome: 'Fauna',                 descricao: 'Ocorrências relacionadas com animais silvestres e biodiversidade faunística.',   icone: 'fauna', cor: '#52B788', ativa: true,  ocorrencias: 312, tags: ['animais', 'vida selvagem', 'biodiversidade'] },
  { id: 2, nome: 'Flora',                 descricao: 'Danos à vegetação nativa, árvores protegidas e ecossistemas florestais.',         icone: 'flora', cor: '#2D6A4F', ativa: true,  ocorrencias: 198, tags: ['vegetação', 'floresta', 'árvores'] },
  { id: 3, nome: 'Poluição Hídrica',      descricao: 'Contaminação de rios, lagos, aquíferos e zonas costeiras.',                       icone: 'agua',  cor: '#4299E1', ativa: true,  ocorrencias: 245, tags: ['água', 'rio', 'poluição'] },
  { id: 4, nome: 'Queimadas Descontroladas', descricao: 'Incêndios de origem humana ou natural com impacto ambiental significativo.',   icone: 'fogo',  cor: '#ED8936', ativa: true,  ocorrencias: 167, tags: ['fogo', 'incêndio', 'queimadas'] },
  { id: 5, nome: 'Pesca Ilegal',          descricao: 'Captura ilegal de espécies aquáticas em zonas protegidas ou fora de época.',       icone: 'pesca', cor: '#38B2AC', ativa: true,  ocorrencias: 134, tags: ['pesca', 'ilegal', 'aquático'] },
  { id: 6, nome: 'Resíduos e Lixo',       descricao: 'Deposição ilegal de resíduos sólidos ou líquidos em áreas naturais.',             icone: 'lixo',  cor: '#9F7AEA', ativa: true,  ocorrencias: 89,  tags: ['lixo', 'resíduos', 'deposição'] },
  { id: 7, nome: 'Poluição Atmosférica',  descricao: 'Emissão de gases poluentes, fumo industrial ou queima de resíduos tóxicos.',      icone: 'ar',    cor: '#D69E2E', ativa: false, ocorrencias: 54,  tags: ['ar', 'gases', 'emissões'] },
  { id: 8, nome: 'Caça Furtiva',          descricao: 'Abate ilegal de animais silvestres protegidos por lei nacional e internacional.',  icone: 'caca',  cor: '#E53E3E', ativa: true,  ocorrencias: 211, tags: ['caça', 'ilegal', 'animais'] },
])

const filteredCategorias = computed(() => {
  let list = categorias.value
  if (f.pesquisa.trim()) {
    const q = f.pesquisa.toLowerCase()
    list = list.filter(c => c.nome.toLowerCase().includes(q) || c.descricao.toLowerCase().includes(q))
  }
  if (f.estado === 'ativa')   list = list.filter(c => c.ativa)
  if (f.estado === 'inativa') list = list.filter(c => !c.ativa)
  if (f.icone) list = list.filter(c => c.icone === f.icone)
  return list
})

function limpar() { Object.assign(f, { pesquisa: '', estado: '', icone: '' }) }

function openNew() {
  editingId.value = null
  Object.assign(form, { nome: '', descricao: '', icone: 'fauna', cor: '#52B788', ativa: true, tagsStr: '' })
  modalOpen.value = true
}

function openEdit(cat) {
  editingId.value = cat.id
  Object.assign(form, {
    nome: cat.nome, descricao: cat.descricao,
    icone: cat.icone, cor: cat.cor, ativa: cat.ativa,
    tagsStr: cat.tags.join(', '),
  })
  modalOpen.value = true
}

function guardar() {
  if (!form.nome.trim()) return
  const tags = form.tagsStr.split(',').map(t => t.trim()).filter(Boolean)
  if (editingId.value) {
    const idx = categorias.value.findIndex(c => c.id === editingId.value)
    if (idx !== -1) Object.assign(categorias.value[idx], { nome: form.nome, descricao: form.descricao, icone: form.icone, cor: form.cor, ativa: form.ativa, tags })
    showToast('Categoria atualizada com sucesso.')
  } else {
    categorias.value.push({ id: nextId++, nome: form.nome, descricao: form.descricao, icone: form.icone, cor: form.cor, ativa: form.ativa, ocorrencias: 0, tags })
    showToast('Categoria criada com sucesso.')
  }
  modalOpen.value = false
}

function confirmDelete(cat) { deleteTarget.value = cat }

function doDelete() {
  categorias.value = categorias.value.filter(c => c.id !== deleteTarget.value.id)
  showToast('Categoria eliminada.', 'error')
  deleteTarget.value = null
}
</script>

<style scoped>
.app-shell {
  display: flex; width: 100%; height: 100vh;
  overflow: hidden; background: #fff;
}

/* ── SIDEBAR ── */
.sidebar {
  width: 210px; flex-shrink: 0;
  background: var(--white); border-right: none;
  box-shadow: 2px 0 18px rgba(0,0,0,.07);
  display: flex; flex-direction: column;
  height: 100vh; position: fixed; top: 0; left: 0; z-index: 50;
}
.sidebar-logo {
  display: flex; align-items: center; gap: 9px;
  padding: 18px 16px 16px; border-bottom: 1px solid rgba(0,0,0,.06);
  text-decoration: none;
}
.sidebar-logo-img { width: 32px; height: 32px; object-fit: contain; border-radius: 6px; flex-shrink: 0; }
.sidebar-logo-text { font-size: 13.5px; font-weight: 800; color: var(--green-dark); line-height: 1.2; }
.sidebar-nav { flex: 1; padding: 14px 10px; overflow-y: auto; }
.nav-item {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 12px; border-radius: 9px; margin-bottom: 2px;
  font-size: 13px; font-weight: 500; color: var(--text-gray);
  cursor: pointer; transition: background 0.15s, color 0.15s; text-decoration: none;
}
.nav-item:hover { background: var(--green-bg); color: var(--green-mid); }
.nav-item.active { background: var(--green-bg); color: var(--green-mid); font-weight: 700; }
.nav-item svg { flex-shrink: 0; opacity: 0.75; }
.nav-item.active svg { opacity: 1; }
.sidebar-footer { padding: 14px 10px; border-top: 1px solid rgba(0,0,0,.06); }
.btn-logout {
  display: flex; align-items: center; gap: 9px; width: 100%;
  background: none; border: none; cursor: pointer;
  padding: 10px 12px; border-radius: 9px;
  font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 500;
  color: #E53E3E; transition: background 0.15s;
}
.btn-logout:hover { background: #FFF5F5; }

/* ── MAIN ── */
.main { margin-left: 210px; flex: 1; display: flex; flex-direction: column; height: 100vh; overflow: hidden; }

/* ── TOPBAR ── */
.topbar {
  display: flex; align-items: center; gap: 14px;
  padding: 0 28px; height: 58px;
  background: var(--white); box-shadow: 0 1px 12px rgba(0,0,0,.07);
  flex-shrink: 0; position: relative; z-index: 10;
}
.search-wrap {
  flex: 1; display: flex; align-items: center; gap: 10px;
  background: #F4F6F5; border: 1.5px solid var(--border);
  border-radius: 9px; padding: 8px 14px; max-width: 420px; transition: border-color 0.2s;
}
.search-wrap:focus-within { border-color: var(--green-light); }
.search-wrap input {
  border: none; outline: none; background: transparent;
  font-family: 'Poppins', sans-serif; font-size: 13px; color: var(--text-dark); width: 100%;
}
.search-wrap input::placeholder { color: var(--text-light); }
.topbar-spacer { flex: 1; }
.notif-btn {
  position: relative; width: 38px; height: 38px;
  background: #F4F6F5; border: 1.5px solid var(--border);
  border-radius: 9px; display: flex; align-items: center; justify-content: center;
  cursor: pointer; transition: border-color 0.2s;
}
.notif-btn:hover { border-color: var(--green-light); }
.notif-dot {
  position: absolute; top: 7px; right: 8px;
  width: 7px; height: 7px; border-radius: 50%;
  background: #E53E3E; border: 1.5px solid var(--white);
}
.admin-info { display: flex; align-items: center; gap: 10px; }
.admin-text { text-align: right; }
.admin-name { font-size: 13px; font-weight: 700; color: var(--text-dark); }
.admin-role { font-size: 11px; color: var(--text-light); }
.admin-avatar {
  width: 36px; height: 36px; border-radius: 50%;
  background: linear-gradient(135deg, #52B788, #1B4332);
  display: flex; align-items: center; justify-content: center;
  font-size: 13px; font-weight: 800; color: #fff; flex-shrink: 0;
}

/* ── CONTENT ── */
.content { flex: 1; overflow-y: auto; padding: 24px 28px 32px; background: #F2F6F4; }
.content::-webkit-scrollbar { width: 5px; }
.content::-webkit-scrollbar-track { background: transparent; }
.content::-webkit-scrollbar-thumb { background: #C8D8CE; border-radius: 99px; }

.page-title-row {
  display: flex; align-items: flex-start;
  justify-content: space-between; margin-bottom: 20px;
}
.page-title-row h1 { font-size: 22px; font-weight: 800; margin-bottom: 4px; }
.page-title-row p  { font-size: 13px; color: var(--text-gray); }

.btn-nova {
  display: flex; align-items: center; gap: 7px;
  background: var(--green-mid); color: #fff; border: none;
  border-radius: 9px; padding: 10px 20px;
  font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 700;
  cursor: pointer; transition: background 0.2s; flex-shrink: 0;
}
.btn-nova:hover { background: var(--green-dark); }

/* ── FILTER CARD ── */
.filter-card {
  background: var(--white); border-radius: 16px;
  padding: 18px 20px 20px; margin-bottom: 18px;
  box-shadow: 0 1px 3px rgba(0,0,0,.05), 0 6px 20px rgba(0,0,0,.07);
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
  background-repeat: no-repeat; background-position: right 10px center;
  transition: border-color 0.2s;
}
.filter-group input { background-image: none; padding-right: 12px; }
.filter-group input:focus,
.filter-group select:focus { border-color: var(--green-light); box-shadow: 0 0 0 3px rgba(82,183,136,.12); }
.filter-actions { display: flex; gap: 8px; align-items: flex-end; }
.btn-limpar {
  display: inline-flex; align-items: center; gap: 6px; height: 39px;
  background: var(--white); color: var(--text-gray);
  border: 1.5px solid var(--border); border-radius: 8px;
  padding: 0 14px; font-family: 'Poppins', sans-serif;
  font-size: 12.5px; font-weight: 600; cursor: pointer; white-space: nowrap;
  transition: border-color 0.2s, color 0.2s;
}
.btn-limpar:hover { border-color: var(--text-gray); color: var(--text-dark); }

/* ── CATEGORY GRID ── */
.cat-grid {
  display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 16px;
}
.cat-card {
  background: var(--white); border-radius: 16px; padding: 20px;
  box-shadow: 0 1px 3px rgba(0,0,0,.05), 0 6px 20px rgba(0,0,0,.07);
  transition: box-shadow 0.25s, transform 0.25s;
  display: flex; flex-direction: column; gap: 10px;
}
.cat-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.1), 0 16px 40px rgba(0,0,0,.1); transform: translateY(-2px); }
.cat-card.inativa { opacity: 0.6; }

.cat-card-header { display: flex; align-items: center; justify-content: space-between; }
.cat-icon-wrap {
  width: 44px; height: 44px; border-radius: 12px; border: 1.5px solid;
  display: flex; align-items: center; justify-content: center;
}
.cat-actions { display: flex; gap: 6px; }
.btn-icon-sm {
  width: 30px; height: 30px; border-radius: 7px;
  background: #F4F6F5; border: 1.5px solid var(--border);
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; color: var(--text-gray); transition: all 0.15s;
}
.btn-icon-sm:hover { background: var(--green-bg); border-color: var(--green-mid); color: var(--green-mid); }
.btn-icon-sm.danger:hover { background: #FFF5F5; border-color: #FC8181; color: #E53E3E; }

.cat-name { font-size: 14px; font-weight: 800; color: var(--text-dark); }
.cat-desc { font-size: 12.5px; color: var(--text-gray); line-height: 1.6; }

.cat-tags { display: flex; flex-wrap: wrap; gap: 6px; }
.tag {
  font-size: 11px; font-weight: 600; padding: 3px 9px; border-radius: 99px;
  background: var(--green-bg); color: var(--green-mid);
  border: 1px solid var(--green-pale);
}

.cat-footer { display: flex; align-items: center; justify-content: space-between; margin-top: 4px; }
.cat-stat { display: flex; align-items: center; gap: 5px; font-size: 12px; color: var(--text-light); font-weight: 600; }
.badge-ativa {
  font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 99px;
  border: 1.5px solid;
}
.badge-ativa.ativa          { color: var(--green-mid); border-color: #68D391; background: var(--green-pale); }
.badge-ativa.inativa-badge  { color: #C05621; border-color: #F6AD55; background: #FFFAF0; }

.empty-grid { grid-column: 1/-1; text-align: center; padding: 48px; color: var(--text-light); font-size: 13px; }

/* ── FOOTER ── */
.dash-footer {
  display: flex; align-items: center; justify-content: space-between;
  padding: 12px 28px; background: var(--white);
  box-shadow: 0 -1px 10px rgba(0,0,0,.06);
  font-size: 11.5px; color: var(--text-light); flex-shrink: 0;
}
.dash-footer a { color: var(--text-light); text-decoration: none; margin-left: 16px; transition: color 0.2s; }
.dash-footer a:hover { color: var(--green-mid); }

/* ── MODAL ── */
.modal-overlay { position: fixed; inset: 0; background: rgba(15,28,22,.4); z-index: 200; }
.modal {
  position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
  width: 560px; max-width: 95vw; max-height: 90vh;
  background: var(--white); z-index: 201;
  border-radius: 16px; overflow: hidden;
  display: flex; flex-direction: column;
  box-shadow: 0 20px 60px rgba(0,0,0,.18);
}
.modal-sm { width: 420px; }
.modal-hd {
  display: flex; align-items: center; justify-content: space-between;
  padding: 20px 24px 18px; border-bottom: 1px solid var(--border); flex-shrink: 0;
}
.modal-hd h3 { font-size: 16px; font-weight: 800; margin-bottom: 2px; }
.modal-hd p  { font-size: 12px; color: var(--text-light); }
.btn-close {
  width: 32px; height: 32px; background: #F4F6F5;
  border: 1.5px solid var(--border); border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; transition: background 0.2s; flex-shrink: 0;
}
.btn-close:hover { background: #FFF5F5; border-color: #FC8181; }
.modal-body { flex: 1; overflow-y: auto; padding: 22px 24px; }
.modal-body::-webkit-scrollbar { width: 4px; }
.modal-body::-webkit-scrollbar-thumb { background: #C8D8CE; border-radius: 99px; }
.modal-ft {
  display: flex; gap: 10px; justify-content: flex-end;
  padding: 16px 24px; border-top: 1px solid var(--border); flex-shrink: 0;
}

/* form inside modal */
.f-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px; }
.f-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.f-row .f-group { margin-bottom: 0; }
.f-group label { font-size: 12.5px; font-weight: 600; color: var(--text-dark); }
.f-group input, .f-group select, .f-group textarea {
  font-family: 'Poppins', sans-serif; font-size: 13px; color: var(--text-dark);
  background: var(--white); border: 1.5px solid var(--border); border-radius: 9px;
  padding: 10px 13px; outline: none; width: 100%;
  appearance: none; -webkit-appearance: none; transition: border-color 0.2s, box-shadow 0.2s;
}
.f-group select {
  cursor: pointer; padding-right: 36px;
  background-image: url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%238A9490' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
  background-repeat: no-repeat; background-position: right 13px center;
}
.f-group textarea { resize: vertical; min-height: 80px; }
.f-group input:focus, .f-group select:focus, .f-group textarea:focus {
  border-color: var(--green-light); box-shadow: 0 0 0 3px rgba(82,183,136,.12);
}

.color-options { display: flex; gap: 10px; flex-wrap: wrap; padding: 4px 0; }
.color-swatch {
  width: 28px; height: 28px; border-radius: 50%; border: 3px solid transparent;
  cursor: pointer; transition: transform 0.15s, border-color 0.15s;
}
.color-swatch:hover { transform: scale(1.15); }
.color-swatch.selected { border-color: var(--text-dark); transform: scale(1.15); }

.btn-cancelar {
  display: inline-flex; align-items: center; gap: 7px;
  background: transparent; color: var(--text-gray);
  border: 1.5px solid var(--border); border-radius: 9px;
  padding: 9px 20px; font-family: 'Poppins', sans-serif;
  font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.2s;
}
.btn-cancelar:hover { border-color: var(--text-gray); color: var(--text-dark); }
.btn-guardar {
  display: inline-flex; align-items: center; gap: 7px;
  background: var(--green-mid); color: #fff; border: none;
  border-radius: 9px; padding: 10px 22px; font-family: 'Poppins', sans-serif;
  font-size: 13px; font-weight: 700; cursor: pointer; transition: background 0.2s;
}
.btn-guardar:hover { background: var(--green-dark); }
.btn-eliminar {
  display: inline-flex; align-items: center; gap: 7px;
  background: #E53E3E; color: #fff; border: none;
  border-radius: 9px; padding: 10px 22px; font-family: 'Poppins', sans-serif;
  font-size: 13px; font-weight: 700; cursor: pointer; transition: background 0.2s;
}
.btn-eliminar:hover { background: #C53030; }

.delete-msg { font-size: 13.5px; color: var(--text-dark); line-height: 1.7; }
.delete-msg strong { color: #E53E3E; }

/* ── TOAST ── */
.toast {
  position: fixed; bottom: 24px; right: 28px; z-index: 400;
  display: flex; align-items: center; gap: 10px;
  padding: 12px 20px; border-radius: 10px;
  font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600;
  box-shadow: 0 8px 24px rgba(0,0,0,.15);
}
.toast.success { background: #EEF7F1; border: 1px solid #C3E6CE; color: #2D6A4F; }
.toast.error   { background: #FFF5F5; border: 1px solid #FC8181; color: #C53030; }

/* ── TRANSITIONS ── */
.fade-enter-active, .fade-leave-active { transition: opacity 0.25s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.modal-pop-enter-active, .modal-pop-leave-active {
  transition: opacity 0.22s ease, transform 0.25s cubic-bezier(.4,0,.2,1);
}
.modal-pop-enter-from, .modal-pop-leave-to {
  opacity: 0; transform: translate(-50%, -46%) scale(0.96);
}

.toast-up-enter-active, .toast-up-leave-active { transition: opacity 0.3s ease, transform 0.3s ease; }
.toast-up-enter-from, .toast-up-leave-to { opacity: 0; transform: translateY(12px); }
</style>
