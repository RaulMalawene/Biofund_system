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
        <a class="nav-item">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <path d="M8 1l1.5 3 3.5.5-2.5 2.5.5 3.5L8 9l-3 1.5.5-3.5L3 4.5 6.5 4z"/>
          </svg>
          Validação
        </a>
        <router-link class="nav-item" to="/admin/utilizadores">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <circle cx="8" cy="6" r="3"/><path d="M2 14c0-2.761 2.686-5 6-5s6 2.239 6 5" stroke-linecap="round"/>
          </svg>
          Utilizadores
        </router-link>
        <a class="nav-item active">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <rect x="2" y="1" width="10" height="14" rx="1.5"/>
            <path d="M5 5h4M5 8h4M5 11h2" stroke-linecap="round"/>
            <path d="M10 1v4h4" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Histórico de Ocorrências
        </a>
        <a class="nav-item">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <circle cx="5" cy="5" r="2"/><circle cx="11" cy="5" r="2"/>
            <circle cx="5" cy="11" r="2"/><circle cx="11" cy="11" r="2"/>
          </svg>
          Categorias
        </a>
        <a class="nav-item">
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
          <input type="text" placeholder="Pesquisar por ID, descrição ou responsável…" v-model="topSearch"/>
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
            <h1>Histórico de Ocorrências</h1>
            <p>Consulte e filtre o registo completo de ocorrências ambientais submetidas.</p>
          </div>
          <button class="btn-export">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
              <path d="M3 13h10M8 2v8M5 7l3 3 3-3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Exportar
          </button>
        </div>

        <!-- FILTER CARD -->
        <div class="filter-card">
          <div class="filter-row">
            <div class="filter-group">
              <label>
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 12 12">
                  <path d="M6 1C3.791 1 2 2.791 2 5c0 3 4 7 4 7s4-4 4-7c0-2.209-1.791-4-4-4z"/><circle cx="6" cy="5" r="1.5"/>
                </svg>
                Província
              </label>
              <select v-model="filters.provincia">
                <option value="">Todas as Províncias</option>
                <option v-for="p in provincias" :key="p">{{ p }}</option>
              </select>
            </div>
            <div class="filter-group">
              <label>
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 12 12">
                  <path d="M2 13L6 4l4 6 3-3 3 4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Projecto
              </label>
              <select v-model="filters.projeto">
                <option value="">Todos os Projectos</option>
                <option>MozRural</option>
                <option>MozP</option>
                <option>MozBio</option>
                <option>MozAmbiente</option>
              </select>
            </div>
            <div class="filter-group">
              <label>
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 12 12">
                  <rect x="1" y="1.5" width="10" height="9.5" rx="1.5"/><path d="M4 1v1.5M8 1v1.5M1 5h10" stroke-linecap="round"/>
                </svg>
                Data de Submissão
              </label>
              <input type="date" v-model="filters.data"/>
            </div>
            <div class="filter-group">
              <label>
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 12 12">
                  <path d="M1 2h10l-4 5v4l-2-1V7z" stroke-linejoin="round"/>
                </svg>
                Categoria
              </label>
              <select v-model="filters.categoria">
                <option value="">Todas as Categorias</option>
                <option>Desmatamento Ilegal</option>
                <option>Poluição Hídrica</option>
                <option>Caça Furtiva</option>
                <option>Queimadas Descontroladas</option>
                <option>Mineração Ilegal</option>
                <option>Pesca Ilegal</option>
                <option>Flora</option>
                <option>Fauna</option>
              </select>
            </div>
          </div>
          <div class="filter-row">
            <div class="filter-group">
              <label>
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 12 12">
                  <path d="M2 3h8M2 6h5M2 9h3" stroke-linecap="round"/>
                </svg>
                Canal
              </label>
              <select v-model="filters.canal">
                <option value="">Todos os Canais</option>
                <option>Telefone</option>
                <option>Email</option>
                <option>SMS</option>
                <option>Reuniao</option>
                <option>Web</option>
              </select>
            </div>
            <div class="filter-group">
              <label>
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 12 12">
                  <rect x="1" y="1" width="10" height="10" rx="1.5"/><path d="M3 4h6M3 6h4M3 8h2" stroke-linecap="round"/>
                </svg>
                Código
              </label>
              <input type="text" placeholder="Ex: REC-2024-001" v-model="filters.codigo"/>
            </div>
            <div></div>
            <div class="filter-actions">
              <button class="btn-limpar" @click="limparFiltros">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 14 14">
                  <path d="M2 2l10 10M12 2L2 12" stroke-linecap="round"/>
                </svg>
                Limpar
              </button>
              <button class="btn-filtrar" @click="aplicarFiltros">
                <svg width="13" height="13" fill="none" stroke="#fff" stroke-width="1.8" viewBox="0 0 14 14">
                  <path d="M1 2h12l-5 6v5l-2-1V8z" stroke-linejoin="round"/>
                </svg>
                Filtrar
              </button>
            </div>
          </div>
        </div>

        <!-- KPI STRIP -->
        <div class="kpi-strip">
          <div class="kpi-card">
            <div class="kpi-icon green">
              <svg width="18" height="18" fill="none" stroke="#2D6A4F" stroke-width="1.8" viewBox="0 0 18 18">
                <rect x="2" y="1" width="11" height="15" rx="1.5"/><path d="M5 5h5M5 8h5M5 11h3" stroke-linecap="round"/>
                <path d="M11 1v4h4" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <div>
              <div class="kpi-label dark">Total</div>
              <div class="kpi-value dark">{{ filteredRows.length }}</div>
            </div>
          </div>
          <div class="kpi-card highlight">
            <div class="kpi-icon white">
              <svg width="18" height="18" fill="none" stroke="rgba(255,255,255,.9)" stroke-width="1.8" viewBox="0 0 18 18">
                <circle cx="9" cy="9" r="7"/><path d="M9 5v4l3 3" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <div>
              <div class="kpi-label light">Pendentes</div>
              <div class="kpi-value light">{{ countByStatus('Pendente') }}</div>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon blue">
              <svg width="18" height="18" fill="none" stroke="#2B6CB0" stroke-width="1.8" viewBox="0 0 18 18">
                <circle cx="9" cy="9" r="7"/><path d="M6 9h6M9 6v6" stroke-linecap="round"/>
              </svg>
            </div>
            <div>
              <div class="kpi-label dark">Em Análise</div>
              <div class="kpi-value dark">{{ countByStatus('Analise') }}</div>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon green">
              <svg width="18" height="18" fill="none" stroke="#2D6A4F" stroke-width="1.8" viewBox="0 0 18 18">
                <circle cx="9" cy="9" r="7"/><path d="M6 9l2.5 2.5 4-5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <div>
              <div class="kpi-label dark">Resolvidas</div>
              <div class="kpi-value dark">{{ countByStatus('Resolvido') }}</div>
            </div>
          </div>
          <div class="kpi-card">
            <div class="kpi-icon red">
              <svg width="18" height="18" fill="none" stroke="#E53E3E" stroke-width="1.8" viewBox="0 0 18 18">
                <circle cx="9" cy="9" r="7"/><path d="M6.5 6.5l5 5M11.5 6.5l-5 5" stroke-linecap="round"/>
              </svg>
            </div>
            <div>
              <div class="kpi-label dark">Rejeitadas</div>
              <div class="kpi-value dark">{{ countByStatus('Rejeitado') }}</div>
            </div>
          </div>
        </div>

        <!-- TABLE CARD -->
        <div class="table-card">
          <table>
            <thead>
              <tr>
                <th>ID / Código</th>
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
              <tr v-for="r in paginatedRows" :key="r.id" @click="openDetail(r)">
                <td>
                  <span class="id-link" @click.stop="openDetail(r)">{{ r.id }}</span>
                </td>
                <td class="td-muted">{{ r.data }}</td>
                <td>{{ r.provincia }}</td>
                <td class="td-small">{{ r.categoria }}</td>
                <td class="td-small">{{ r.canal }}</td>
                <td>
                  <div class="resp-cell" v-if="r.responsavel">
                    <div class="resp-avatar">{{ r.responsavel[0] }}</div>
                    <span class="td-small">{{ r.responsavel }}</span>
                  </div>
                  <span v-else class="resp-none">Sem responsável</span>
                </td>
                <td class="td-muted td-small">{{ r.projeto }}</td>
                <td>
                  <span class="badge-status" :class="r.status.toLowerCase()">{{ statusLabel(r.status) }}</span>
                </td>
                <td>
                  <button class="btn-detail" @click.stop="openDetail(r)">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 14 14">
                      <circle cx="7" cy="7" r="5.5"/><path d="M7 5v4M7 10h.01" stroke-linecap="round"/>
                    </svg>
                    Detalhes
                  </button>
                </td>
              </tr>
              <tr v-if="paginatedRows.length === 0">
                <td colspan="9" class="empty-row">
                  Nenhuma ocorrência encontrada com os filtros aplicados.
                </td>
              </tr>
            </tbody>
          </table>

          <!-- Pagination -->
          <div class="pagination-bar">
            <span class="pagination-info">
              Mostrando {{ paginationInfo }} de {{ filteredRows.length }} ocorrências
            </span>
            <div class="pagination-btns">
              <button class="pg-btn" :disabled="page === 1" @click="page--">Anterior</button>
              <button
                v-for="p in totalPages" :key="p"
                class="pg-btn" :class="{ active: page === p }"
                @click="page = p"
              >{{ p }}</button>
              <button class="pg-btn" :disabled="page === totalPages" @click="page++">Próximo</button>
            </div>
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

    <!-- ── DETAIL DRAWER ── -->
    <transition name="fade">
      <div v-if="selected" class="drawer-overlay" @click.self="selected = null"></div>
    </transition>
    <transition name="slide-right">
      <div v-if="selected" class="drawer">
        <div class="drawer-hd">
          <div>
            <h3>{{ selected.id }}</h3>
            <p>Detalhes da ocorrência ambiental</p>
          </div>
          <button class="btn-close" @click="selected = null">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 13 13">
              <path d="M2 2l9 9M11 2L2 11" stroke-linecap="round"/>
            </svg>
          </button>
        </div>

        <div class="drawer-body">
          <!-- Status badge -->
          <div class="drawer-status-row">
            <span class="badge-status" :class="selected.status.toLowerCase()">
              {{ statusLabel(selected.status) }}
            </span>
          </div>

          <!-- Details grid -->
          <div class="detail-row">
            <span class="detail-key">Data de Submissão</span>
            <span class="detail-val">{{ selected.data }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-key">Província</span>
            <span class="detail-val">{{ selected.provincia }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-key">Categoria</span>
            <span class="detail-val">{{ selected.categoria }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-key">Canal de entrada</span>
            <span class="detail-val">{{ selected.canal }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-key">Responsável</span>
            <span class="detail-val">{{ selected.responsavel || '—' }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-key">Projecto</span>
            <span class="detail-val">{{ selected.projeto }}</span>
          </div>

          <!-- Description -->
          <div class="drawer-section">
            <div class="drawer-section-label">Descrição</div>
            <div class="drawer-desc">{{ selected.descricao }}</div>
          </div>

          <!-- Timeline -->
          <div class="drawer-section">
            <div class="drawer-section-label">Histórico de Estados</div>
            <div class="timeline">
              <div class="timeline-item" v-for="(ev, i) in selected.timeline" :key="i">
                <div class="tl-dot" :class="ev.tipo"></div>
                <div class="tl-content">
                  <div class="tl-title">{{ ev.titulo }}</div>
                  <div class="tl-date">{{ ev.data }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>

  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'

const topSearch = ref('')
const selected  = ref(null)
const page      = ref(1)
const perPage   = 7

const provincias = [
  'Cabo Delgado', 'Gaza', 'Inhambane', 'Manica',
  'Maputo Cidade', 'Maputo Província', 'Nampula',
  'Niassa', 'Sofala', 'Tete', 'Zambézia'
]

const filters = reactive({ provincia: '', projeto: '', data: '', categoria: '', canal: '', codigo: '' })
const applied = reactive({ provincia: '', projeto: '', data: '', categoria: '', canal: '', codigo: '' })

const rows = ref([
  {
    id: 'REC-2024-001', data: '2024-05-15', provincia: 'Cabo Delgado',
    categoria: 'Desmatamento Ilegal', canal: 'Telefone', responsavel: 'Sara',
    projeto: 'MozRural', status: 'Rejeitado',
    descricao: 'Foram identificadas áreas de desflorestação ilegal na zona norte da reserva florestal. Estimativa de 5 hectares afetados.',
    timeline: [
      { titulo: 'Ocorrência submetida', data: '2024-05-15 09:12', tipo: 'neutro' },
      { titulo: 'Em análise por Sara', data: '2024-05-16 11:30', tipo: 'analise' },
      { titulo: 'Rejeitada — prova insuficiente', data: '2024-05-18 15:00', tipo: 'rejeitado' },
    ]
  },
  {
    id: 'REC-2024-002', data: '2024-05-14', provincia: 'Nampula',
    categoria: 'Poluição Hídrica', canal: 'Email', responsavel: null,
    projeto: 'MozP', status: 'Resolvido',
    descricao: 'Descarte de resíduos industriais no rio Ligonha. A situação foi controlada após intervenção da equipa técnica regional.',
    timeline: [
      { titulo: 'Ocorrência submetida', data: '2024-05-14 08:00', tipo: 'neutro' },
      { titulo: 'Resolvida com sucesso', data: '2024-05-17 14:20', tipo: 'resolvido' },
    ]
  },
  {
    id: 'REC-2024-003', data: '2024-05-12', provincia: 'Sofala',
    categoria: 'Caça Furtiva', canal: 'Email', responsavel: 'Sara',
    projeto: 'MozBio', status: 'Resolvido',
    descricao: 'Armadilhas ilegais detectadas na área tampão do Parque da Gorongosa. Autoridades notificadas e armadilhas removidas.',
    timeline: [
      { titulo: 'Ocorrência submetida', data: '2024-05-12 10:00', tipo: 'neutro' },
      { titulo: 'Atribuída a Sara', data: '2024-05-13 09:00', tipo: 'analise' },
      { titulo: 'Resolvida', data: '2024-05-15 17:00', tipo: 'resolvido' },
    ]
  },
  {
    id: 'REC-2024-004', data: '2024-05-10', provincia: 'Maputo',
    categoria: 'Queimadas Descontroladas', canal: 'Telefone', responsavel: 'Joao',
    projeto: 'MozAmbiente', status: 'Resolvido',
    descricao: 'Queimadas de grandes proporções na zona sul. Equipa de resposta rápida mobilizada. Incêndio controlado em 48h.',
    timeline: [
      { titulo: 'Ocorrência submetida', data: '2024-05-10 07:30', tipo: 'neutro' },
      { titulo: 'Equipa mobilizada', data: '2024-05-10 09:00', tipo: 'analise' },
      { titulo: 'Resolvida em 48h', data: '2024-05-12 08:00', tipo: 'resolvido' },
    ]
  },
  {
    id: 'REC-2024-005', data: '2024-05-09', provincia: 'Gaza',
    categoria: 'Mineração Ilegal', canal: 'Reuniao', responsavel: 'Joao',
    projeto: 'MozRural', status: 'Removido',
    descricao: 'Actividade de mineração ilegal detectada na margem do Rio Limpopo. Caso transferido para autoridades competentes.',
    timeline: [
      { titulo: 'Ocorrência submetida', data: '2024-05-09 11:00', tipo: 'neutro' },
      { titulo: 'Removida pelo admin', data: '2024-05-11 16:00', tipo: 'rejeitado' },
    ]
  },
  {
    id: 'REC-2024-006', data: '2024-05-07', provincia: 'Niassa',
    categoria: 'Pesca Ilegal', canal: 'SMS', responsavel: 'Maria',
    projeto: 'MozBio', status: 'Pendente',
    descricao: 'Redes de pesca ilegal no Lago Niassa. Autoridades lacustres informadas. Investigação em curso.',
    timeline: [
      { titulo: 'Ocorrência submetida', data: '2024-05-07 14:00', tipo: 'neutro' },
    ]
  },
  {
    id: 'REC-2024-007', data: '2024-05-05', provincia: 'Tete',
    categoria: 'Desmatamento Ilegal', canal: 'Web', responsavel: 'Sara',
    projeto: 'MozAmbiente', status: 'Analise',
    descricao: 'Corte ilegal de madeira detetado próximo da aldeia de Changara. Amostras recolhidas para análise laboratorial.',
    timeline: [
      { titulo: 'Ocorrência submetida', data: '2024-05-05 10:00', tipo: 'neutro' },
      { titulo: 'Em análise por Sara', data: '2024-05-06 09:30', tipo: 'analise' },
    ]
  },
  {
    id: 'REC-2024-008', data: '2024-05-03', provincia: 'Inhambane',
    categoria: 'Fauna', canal: 'Email', responsavel: null,
    projeto: 'MozBio', status: 'Rejeitado',
    descricao: 'Denúncia de captura de tartarugas marinhas em Inhassoro. Investigação concluída — denúncia considerada infundada.',
    timeline: [
      { titulo: 'Ocorrência submetida', data: '2024-05-03 08:00', tipo: 'neutro' },
      { titulo: 'Rejeitada — infundada', data: '2024-05-05 10:00', tipo: 'rejeitado' },
    ]
  },
])

const filteredRows = computed(() => {
  let list = rows.value
  if (topSearch.value.trim()) {
    const q = topSearch.value.toLowerCase()
    list = list.filter(r =>
      r.id.toLowerCase().includes(q) ||
      r.descricao.toLowerCase().includes(q) ||
      (r.responsavel && r.responsavel.toLowerCase().includes(q))
    )
  }
  if (applied.provincia) list = list.filter(r => r.provincia === applied.provincia)
  if (applied.projeto)   list = list.filter(r => r.projeto === applied.projeto)
  if (applied.data)      list = list.filter(r => r.data === applied.data)
  if (applied.categoria) list = list.filter(r => r.categoria === applied.categoria)
  if (applied.canal)     list = list.filter(r => r.canal === applied.canal)
  if (applied.codigo)    list = list.filter(r => r.id.toLowerCase().includes(applied.codigo.toLowerCase()))
  return list
})

const totalPages = computed(() => Math.max(1, Math.ceil(filteredRows.value.length / perPage)))

const paginatedRows = computed(() => {
  const start = (page.value - 1) * perPage
  return filteredRows.value.slice(start, start + perPage)
})

const paginationInfo = computed(() => {
  if (!filteredRows.value.length) return '0'
  const start = (page.value - 1) * perPage + 1
  const end   = Math.min(page.value * perPage, filteredRows.value.length)
  return `${start}–${end}`
})

function countByStatus(s) { return filteredRows.value.filter(r => r.status === s).length }

function statusLabel(s) {
  return { Pendente: 'Pendente', Analise: 'Em Análise', Resolvido: 'Resolvido', Rejeitado: 'Rejeitado', Removido: 'Removido' }[s] ?? s
}

function aplicarFiltros() { Object.assign(applied, { ...filters }); page.value = 1 }

function limparFiltros() {
  Object.assign(filters,  { provincia: '', projeto: '', data: '', categoria: '', canal: '', codigo: '' })
  Object.assign(applied,  { provincia: '', projeto: '', data: '', categoria: '', canal: '', codigo: '' })
  page.value = 1
}

function openDetail(r) { selected.value = r }
</script>

<style scoped>
.app-shell {
  display: flex;
  width: 100%;
  height: 100vh;
  overflow: hidden;
  background: #F4F6F5;
}

/* ── SIDEBAR ─────────────────────────────── */
.sidebar {
  width: 210px; flex-shrink: 0;
  background: var(--white);
  border-right: 1px solid var(--border);
  display: flex; flex-direction: column;
  height: 100vh; position: fixed;
  top: 0; left: 0; z-index: 50;
}
.sidebar-logo {
  display: flex; align-items: center; gap: 9px;
  padding: 18px 16px 16px;
  border-bottom: 1px solid var(--border);
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

.sidebar-footer { padding: 14px 10px; border-top: 1px solid var(--border); }
.btn-logout {
  display: flex; align-items: center; gap: 9px; width: 100%;
  background: none; border: none; cursor: pointer;
  padding: 10px 12px; border-radius: 9px;
  font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 500;
  color: #E53E3E; transition: background 0.15s;
}
.btn-logout:hover { background: #FFF5F5; }

/* ── MAIN ───────────────────────────────── */
.main {
  margin-left: 210px; flex: 1;
  display: flex; flex-direction: column;
  height: 100vh; overflow: hidden;
}

/* ── TOPBAR ─────────────────────────────── */
.topbar {
  display: flex; align-items: center; gap: 14px;
  padding: 0 28px; height: 58px;
  background: var(--white); border-bottom: 1px solid var(--border); flex-shrink: 0;
}
.search-wrap {
  flex: 1; display: flex; align-items: center; gap: 10px;
  background: #F4F6F5; border: 1.5px solid var(--border);
  border-radius: 9px; padding: 8px 14px; max-width: 420px;
  transition: border-color 0.2s;
}
.search-wrap:focus-within { border-color: var(--green-light); }
.search-wrap input {
  border: none; outline: none; background: transparent;
  font-family: 'Poppins', sans-serif; font-size: 13px;
  color: var(--text-dark); width: 100%;
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

/* ── CONTENT ────────────────────────────── */
.content { flex: 1; overflow-y: auto; padding: 24px 28px 32px; }
.content::-webkit-scrollbar { width: 5px; }
.content::-webkit-scrollbar-track { background: transparent; }
.content::-webkit-scrollbar-thumb { background: #C8D8CE; border-radius: 99px; }

/* page title */
.page-title-row {
  display: flex; align-items: flex-start;
  justify-content: space-between; margin-bottom: 20px;
}
.page-title-row h1 { font-size: 22px; font-weight: 800; margin-bottom: 4px; }
.page-title-row p  { font-size: 13px; color: var(--text-gray); }

.btn-export {
  display: flex; align-items: center; gap: 7px;
  background: var(--white); color: var(--text-dark);
  border: 1.5px solid var(--border); border-radius: 8px;
  padding: 9px 18px; font-family: 'Poppins', sans-serif;
  font-size: 13px; font-weight: 600; cursor: pointer;
  transition: border-color 0.2s, color 0.2s; flex-shrink: 0;
}
.btn-export:hover { border-color: var(--green-mid); color: var(--green-mid); }

/* ── KPI STRIP ──────────────────────────── */
.kpi-strip {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 12px; margin-bottom: 18px;
}
.kpi-card {
  background: var(--white); border: 1px solid var(--border);
  border-radius: 12px; padding: 16px 18px;
  display: flex; align-items: center; gap: 14px;
  transition: box-shadow 0.2s;
}
.kpi-card:hover { box-shadow: 0 4px 18px rgba(0,0,0,.07); }
.kpi-card.highlight { background: var(--green-mid); border-color: var(--green-mid); }

.kpi-icon {
  width: 36px; height: 36px; border-radius: 9px; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
}
.kpi-icon.green { background: var(--green-pale); }
.kpi-icon.white { background: rgba(255,255,255,.22); }
.kpi-icon.blue  { background: #EBF4FF; }
.kpi-icon.red   { background: #FFF5F5; }

.kpi-label { font-size: 10px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; margin-bottom: 3px; }
.kpi-label.dark  { color: var(--text-light); }
.kpi-label.light { color: rgba(255,255,255,.75); }
.kpi-value { font-size: 26px; font-weight: 800; line-height: 1; }
.kpi-value.dark  { color: var(--text-dark); }
.kpi-value.light { color: #fff; }

/* ── FILTER CARD ────────────────────────── */
.filter-card {
  background: var(--white); border: 1px solid var(--border);
  border-radius: 12px; padding: 18px 20px 20px; margin-bottom: 18px;
}
.filter-row {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr auto;
  gap: 12px; align-items: end; margin-bottom: 12px;
}
.filter-row:last-child { margin-bottom: 0; }

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

.btn-filtrar {
  display: inline-flex; align-items: center; gap: 7px; height: 39px;
  background: var(--green-mid); color: #fff; border: none;
  border-radius: 8px; padding: 0 22px; font-family: 'Poppins', sans-serif;
  font-size: 13px; font-weight: 700; cursor: pointer; white-space: nowrap;
  transition: background 0.2s;
}
.btn-filtrar:hover { background: var(--green-dark); }

/* ── TABLE CARD ─────────────────────────── */
.table-card {
  background: var(--white); border: 1px solid var(--border);
  border-radius: 12px; overflow: hidden;
}
table { width: 100%; border-collapse: collapse; }
thead th {
  padding: 11px 16px; font-size: 11px; font-weight: 700;
  color: var(--text-light); text-align: left;
  background: #F4F6F5; border-bottom: 1px solid var(--border);
  text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;
}
tbody tr { transition: background 0.15s; cursor: pointer; }
tbody tr:hover { background: #F9FBFA; }
tbody td {
  padding: 13px 16px; font-size: 13px;
  border-bottom: 1px solid var(--border); vertical-align: middle;
}
tbody tr:last-child td { border-bottom: none; }

.td-muted { color: var(--text-gray); font-size: 12.5px; }
.td-small { font-size: 12.5px; }

.id-link {
  color: var(--green-mid); font-weight: 600;
  font-size: 12.5px; cursor: pointer;
}
.id-link:hover { text-decoration: underline; }

.resp-cell { display: flex; align-items: center; gap: 8px; }
.resp-avatar {
  width: 26px; height: 26px; border-radius: 50%; flex-shrink: 0;
  background: var(--green-bg); color: var(--green-mid);
  display: flex; align-items: center; justify-content: center;
  font-size: 11px; font-weight: 800;
}
.resp-none { color: var(--text-light); font-size: 12.5px; font-style: italic; }

/* status badges */
.badge-status {
  display: inline-block; padding: 3px 10px; border-radius: 99px;
  font-size: 11.5px; font-weight: 700;
  border-width: 1.5px; border-style: solid;
}
.badge-status.rejeitado { color: #E53E3E; border-color: #FC8181; background: #FFF5F5; }
.badge-status.resolvido { color: var(--green-mid); border-color: #68D391; background: var(--green-pale); }
.badge-status.removido  { color: #C05621; border-color: #F6AD55; background: #FFFAF0; }
.badge-status.pendente  { color: #744210; border-color: #F6D860; background: #FEFCBF; }
.badge-status.analise   { color: #2B6CB0; border-color: #90CDF4; background: #EBF8FF; }

.btn-detail {
  display: inline-flex; align-items: center; gap: 5px;
  background: var(--green-bg); color: var(--green-mid);
  border: none; border-radius: 7px; padding: 6px 12px;
  font-family: 'Poppins', sans-serif; font-size: 12px; font-weight: 600;
  cursor: pointer; transition: background 0.15s;
}
.btn-detail:hover { background: var(--green-pale); }

/* pagination */
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
  cursor: pointer; border: 1.5px solid var(--border);
  background: var(--white); color: var(--text-gray); padding: 0 10px;
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
  border-top: 1px solid var(--border);
  font-size: 11.5px; color: var(--text-light); flex-shrink: 0;
}
.dash-footer a { color: var(--text-light); text-decoration: none; margin-left: 16px; transition: color 0.2s; }
.dash-footer a:hover { color: var(--green-mid); }

/* ── DETAIL DRAWER ──────────────────────── */
.drawer-overlay {
  position: fixed; inset: 0;
  background: rgba(15, 28, 22, 0.4);
  z-index: 200;
}
.drawer {
  position: fixed; top: 0; right: 0; bottom: 0;
  width: 460px; max-width: 95vw;
  background: var(--white); z-index: 201;
  display: flex; flex-direction: column;
  box-shadow: -6px 0 32px rgba(0,0,0,.14);
  overflow: hidden;
}
.drawer::-webkit-scrollbar { width: 4px; }
.drawer::-webkit-scrollbar-thumb { background: #C8D8CE; border-radius: 99px; }

.drawer-hd {
  display: flex; align-items: center; justify-content: space-between;
  padding: 20px 24px 18px; border-bottom: 1px solid var(--border);
  flex-shrink: 0; background: var(--white);
}
.drawer-hd h3 { font-size: 15px; font-weight: 800; margin-bottom: 2px; }
.drawer-hd p  { font-size: 12px; color: var(--text-light); }

.btn-close {
  width: 32px; height: 32px; background: #F4F6F5;
  border: 1.5px solid var(--border); border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; transition: background 0.2s; flex-shrink: 0;
}
.btn-close:hover { background: #FFF5F5; border-color: #FC8181; }

.drawer-body { flex: 1; overflow-y: auto; padding: 22px 24px; }
.drawer-body::-webkit-scrollbar { width: 4px; }
.drawer-body::-webkit-scrollbar-thumb { background: #C8D8CE; border-radius: 99px; }

.drawer-status-row { margin-bottom: 18px; }

.detail-row {
  display: flex; justify-content: space-between; align-items: flex-start;
  padding: 11px 0; border-bottom: 1px solid #F0F4F2;
}
.detail-row:last-of-type { border-bottom: none; }
.detail-key { font-size: 12px; font-weight: 600; color: var(--text-light); min-width: 140px; }
.detail-val { font-size: 13px; color: var(--text-dark); text-align: right; flex: 1; }

.drawer-section { margin-top: 20px; }
.drawer-section-label { font-size: 11.5px; font-weight: 700; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 10px; }
.drawer-desc {
  background: #F4F6F5; border-radius: 9px;
  padding: 14px; font-size: 13px; color: var(--text-gray); line-height: 1.7;
}

/* Timeline */
.timeline { display: flex; flex-direction: column; gap: 0; }
.timeline-item {
  display: flex; align-items: flex-start; gap: 12px;
  padding-bottom: 16px; position: relative;
}
.timeline-item:not(:last-child)::before {
  content: ''; position: absolute;
  left: 5px; top: 14px; bottom: 0;
  width: 1.5px; background: var(--border);
}
.tl-dot {
  width: 12px; height: 12px; border-radius: 50%; flex-shrink: 0; margin-top: 2px;
  border: 2px solid;
}
.tl-dot.neutro   { background: #F4F6F5; border-color: var(--border); }
.tl-dot.analise  { background: #EBF8FF; border-color: #90CDF4; }
.tl-dot.resolvido{ background: var(--green-pale); border-color: #68D391; }
.tl-dot.rejeitado{ background: #FFF5F5; border-color: #FC8181; }

.tl-title { font-size: 13px; font-weight: 600; color: var(--text-dark); }
.tl-date  { font-size: 11.5px; color: var(--text-light); margin-top: 2px; }

/* ── TRANSITIONS ────────────────────────── */
.fade-enter-active, .fade-leave-active { transition: opacity 0.25s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.slide-right-enter-active, .slide-right-leave-active {
  transition: transform 0.3s cubic-bezier(.16,1,.3,1);
}
.slide-right-enter-from, .slide-right-leave-to { transform: translateX(100%); }
</style>
