<template>
  <div class="app-shell">

    <!-- ── SIDEBAR ── -->
    <aside class="sidebar" :class="{ 'sidebar-open': sidebarOpen }">
      <router-link to="/" class="sidebar-logo">
        <img src="../../Imagem/logotipo.png" alt="Biofund" class="sidebar-logo-img"/>
      </router-link>
      <button class="sidebar-close-btn" @click="sidebarOpen = false" aria-label="Fechar menu">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 16 16"><path d="M3 3l10 10M13 3L3 13" stroke-linecap="round"/></svg>
      </button>

      <nav class="sidebar-nav" @click="sidebarOpen = false">
        <a class="nav-item active">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <rect x="1" y="1" width="6" height="6" rx="1.5"/><rect x="9" y="1" width="6" height="6" rx="1.5"/>
            <rect x="1" y="9" width="6" height="6" rx="1.5"/><rect x="9" y="9" width="6" height="6" rx="1.5"/>
          </svg>
          Dashboard
        </a>
        <router-link class="nav-item" to="/gestor/validacao">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <path d="M8 1l1.5 3 3.5.5-2.5 2.5.5 3.5L8 9l-3 1.5.5-3.5L3 4.5 6.5 4z"/>
          </svg>
          Validação
        </router-link>
        <router-link class="nav-item" to="/gestor/historico">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <rect x="2" y="1" width="10" height="14" rx="1.5"/>
            <path d="M5 5h4M5 8h4M5 11h2" stroke-linecap="round"/>
            <path d="M10 1v4h4" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Histórico
        </router-link>
      </nav>

      <div class="sidebar-footer">
        <button class="btn-logout" @click="handleLogout">
          <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <path d="M6 14H3a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h3M10 11l3-3-3-3M13 8H6" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Terminar Sessão
        </button>
      </div>
    </aside>
    <div class="sidebar-backdrop" v-if="sidebarOpen" @click="sidebarOpen = false"></div>

    <!-- ── MAIN ── -->
    <div class="main">

      <!-- TOP BAR -->
      <header class="topbar">
        <button class="topbar-menu-btn" @click="sidebarOpen = true" aria-label="Abrir menu">
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16"><path d="M2 4h12M2 8h12M2 12h12" stroke-linecap="round"/></svg>
        </button>
        <div class="search-wrap">
          <svg width="15" height="15" fill="none" stroke="#8A9490" stroke-width="1.8" viewBox="0 0 16 16">
            <circle cx="7" cy="7" r="5"/><path d="M12 12l3 3" stroke-linecap="round"/>
          </svg>
          <input type="text" placeholder="Pesquisar ocorrências" v-model="searchQ"/>
        </div>
        <div class="topbar-spacer"></div>
        <AdminNotificationPanel />
        <AdminProfilePanel />
      </header>

      <!-- CONTENT -->
      <main class="content">

        <!-- Título -->
        <div class="page-title-row">
          <div>
            <h1>Dashboard do Gestor</h1>
            <p>Visão geral das ocorrências nas suas províncias e projectos atribuídos.</p>
          </div>
          <div class="title-actions">
            <button class="btn-outline-sm" @click="modalRelatorio = true">
              <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 14 14">
                <rect x="1" y="2" width="12" height="11" rx="1.5"/>
                <path d="M4 1v2M10 1v2M1 6h12" stroke-linecap="round"/>
              </svg>
              Relatório Mensal
            </button>
            <button class="btn-green-sm" @click="drawerOpen = true">
              <svg width="13" height="13" fill="none" stroke="#fff" stroke-width="1.8" viewBox="0 0 14 14">
                <path d="M7 2C4.239 2 2 4.239 2 7c0 3.5 5 7 5 7s5-3.5 5-7c0-2.761-2.239-5-5-5z"/>
                <circle cx="7" cy="7" r="1.8"/>
              </svg>
              Nova Ocorrência
            </button>
          </div>
        </div>

        <!-- Âmbito do gestor -->
        <div class="scope-banner" v-if="scopeProvinces.length || scopeProjects.length">
          <div class="scope-section" v-if="scopeProvinces.length">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
              <path d="M8 1.5C5.515 1.5 3.5 3.515 3.5 6c0 3.75 4.5 9 4.5 9s4.5-5.25 4.5-9c0-2.485-2.015-4.5-4.5-4.5z"/>
              <circle cx="8" cy="6" r="1.8"/>
            </svg>
            <span class="scope-label">Províncias:</span>
            <span class="scope-tag" v-for="p in scopeProvinces" :key="p.id">{{ p.name }}</span>
          </div>
          <div class="scope-sep" v-if="scopeProvinces.length && scopeProjects.length"></div>
          <div class="scope-section" v-if="scopeProjects.length">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
              <path d="M2 13L6 4l4 6 3-3 3 4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="scope-label">Projectos:</span>
            <span class="scope-tag proj" v-for="p in scopeProjects" :key="p.id">{{ p.name }}</span>
          </div>
        </div>

        <!-- DASH FILTER BAR -->
        <div class="dash-filter-bar">
          <span class="dash-filter-title">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
              <path d="M2 4h12M4 8h8M6 12h4" stroke-linecap="round"/>
            </svg>
            Filtros
          </span>
          <div class="dash-filter-sep"></div>

          <div class="dash-filter-chip" :class="{ 'chip-active': dashFilter.year }">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
              <rect x="1" y="3" width="14" height="12" rx="1.5"/>
              <path d="M1 7h14M5 1v3M11 1v3" stroke-linecap="round"/>
            </svg>
            <select class="dash-filter-select" v-model="dashFilter.year">
              <option value="">Todos os Anos</option>
              <option v-for="y in filterYears" :key="y" :value="y">{{ y }}</option>
            </select>
          </div>

          <div class="dash-filter-chip" :class="{ 'chip-active': dashFilter.province_id }">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
              <path d="M8 1C5.791 1 4 2.791 4 5c0 3.5 4 10 4 10s4-6.5 4-10c0-2.209-1.791-4-4-4z"/>
              <circle cx="8" cy="5" r="1.5"/>
            </svg>
            <select class="dash-filter-select" v-model="dashFilter.province_id">
              <option value="">Todas as Províncias</option>
              <option v-for="p in scopeProvinces" :key="p.id" :value="p.id">{{ p.name }}</option>
            </select>
          </div>

          <div class="dash-filter-chip" :class="{ 'chip-active': dashFilter.project_id }">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
              <path d="M2 13L6 4l4 6 3-3 3 4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <select class="dash-filter-select" v-model="dashFilter.project_id">
              <option value="">Todos os Projectos</option>
              <option v-for="p in scopeProjects" :key="p.id" :value="p.id">{{ p.name }}</option>
            </select>
          </div>

          <div class="dash-filter-chip" :class="{ 'chip-active': dashFilter.category_id }">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
              <circle cx="5" cy="5" r="2"/><circle cx="11" cy="5" r="2"/>
              <circle cx="5" cy="11" r="2"/><circle cx="11" cy="11" r="2"/>
            </svg>
            <select class="dash-filter-select" v-model="dashFilter.category_id">
              <option value="">Todas as Categorias</option>
              <option v-for="c in refCategories" :key="c.id" :value="c.id">{{ c.name }}</option>
            </select>
          </div>

          <div class="dash-filter-chip" :class="{ 'chip-active': dashFilter.gender }">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
              <circle cx="8" cy="5" r="3"/>
              <path d="M8 8v6M5.5 11.5h5" stroke-linecap="round"/>
            </svg>
            <select class="dash-filter-select" v-model="dashFilter.gender">
              <option value="">Todos os Sexos</option>
              <option value="masculino">Masculino</option>
              <option value="feminino">Feminino</option>
            </select>
          </div>

          <div class="dash-filter-chip" :class="{ 'chip-active': dashFilter.age_range }">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
              <circle cx="8" cy="8" r="6.5"/>
              <path d="M8 4.5V8l2.5 1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <select class="dash-filter-select" v-model="dashFilter.age_range">
              <option value="">Todas as Idades</option>
              <option v-for="a in AGE_RANGES" :key="a" :value="a">{{ a }} anos</option>
            </select>
          </div>

          <button class="dash-filter-apply-btn" @click="applyDashFilter" :disabled="filterLoading">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 16 16">
              <path d="M2 4h12M4 8h8M6 12h4" stroke-linecap="round"/>
            </svg>
            Filtrar
          </button>

          <transition name="fade">
            <button v-if="hasActiveDashFilter" class="dash-filter-clear-btn" @click="clearDashFilter" :disabled="filterLoading">
              <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 12 12">
                <path d="M2 2l8 8M10 2L2 10" stroke-linecap="round"/>
              </svg>
              Limpar
            </button>
          </transition>

          <div class="dash-filter-spin" v-if="filterLoading">
            <svg class="spin" width="14" height="14" fill="none" stroke="#52B788" stroke-width="2.2" viewBox="0 0 16 16">
              <path d="M8 2a6 6 0 0 1 6 6" stroke-linecap="round"/>
            </svg>
          </div>
        </div>

        <!-- KPI CARDS -->
        <div class="kpi-grid" :class="{ 'kpi-loading': statsLoading || filterLoading }">
          <div class="kpi-card" @click="selectCard(null)" :class="{ 'card-active': activeFilter === null && !statsLoading }" :title="activeFilter ? 'Clique para remover o filtro' : 'Ver todos os dados'">
            <div class="kpi-top">
              <div class="kpi-icon green">
                <svg width="18" height="18" fill="none" stroke="#2D6A4F" stroke-width="1.8" viewBox="0 0 18 18">
                  <path d="M2 16l4-8 4 4 3-5 3 4"/>
                  <circle cx="15" cy="3" r="2" fill="#52B788" stroke="none"/>
                </svg>
              </div>
            </div>
            <div class="kpi-label dark">Total de Ocorrências</div>
            <div class="kpi-value dark">{{ statsLoading ? '-' : (rawTotals.all ?? 0) }}</div>
            <div class="kpi-sub dark">{{ rawReclamacoes }} reclamações · {{ rawElogios }} elogios · {{ rawSugestoes }} sugestões</div>
          </div>

          <div class="kpi-card kpi-orange" @click="selectCard('por_validar')" :class="{ 'card-active': activeFilter === 'por_validar' }" title="Filtrar por: Por Validar">
            <div class="kpi-top">
              <div class="kpi-icon white">
                <svg width="18" height="18" fill="none" stroke="rgba(255,255,255,.9)" stroke-width="1.8" viewBox="0 0 18 18">
                  <circle cx="9" cy="9" r="7"/>
                  <path d="M9 5v4l3 3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
              <span v-if="activeFilter === 'por_validar'" class="kpi-active-dot"></span>
            </div>
            <div class="kpi-label light">Por Validar</div>
            <div class="kpi-value light">{{ statsLoading ? '-' : (rawTotals.por_validar ?? 0) }}</div>
            <div class="kpi-sub light">Aguardando validação</div>
            <div class="kpi-sub light">{{ statsLoading ? '-' : (rawTotals.resolvendo ?? 0) }} em resolução</div>
          </div>

          <div class="kpi-card kpi-green" @click="selectCard('resolvido')" :class="{ 'card-active': activeFilter === 'resolvido' }" title="Filtrar por: Resolvidas">
            <div class="kpi-top">
              <div class="kpi-icon white">
                <svg width="18" height="18" fill="none" stroke="rgba(255,255,255,.9)" stroke-width="1.8" viewBox="0 0 18 18">
                  <circle cx="9" cy="9" r="7"/>
                  <path d="M6 9l2.5 2.5 4-5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
              <span v-if="activeFilter === 'resolvido'" class="kpi-active-dot"></span>
            </div>
            <div class="kpi-label light">Resolvidas</div>
            <div class="kpi-value light">{{ statsLoading ? '-' : (rawTotals.resolvido ?? 0) }}</div>
            <div class="kpi-sub light">{{ rawTotals.por_resolver ?? 0 }} por resolver</div>
          </div>

          <div class="kpi-card kpi-purple" @click="selectCard('nao_resolvida')" :class="{ 'card-active': activeFilter === 'nao_resolvida' }" title="Filtrar por: Não Resolvidas">
            <div class="kpi-top">
              <div class="kpi-icon white">
                <svg width="18" height="18" fill="none" stroke="rgba(255,255,255,.9)" stroke-width="1.8" viewBox="0 0 18 18">
                  <circle cx="9" cy="9" r="7"/>
                  <path d="M9 5.5v4M9 12.5v.5" stroke-linecap="round"/>
                </svg>
              </div>
              <span v-if="activeFilter === 'nao_resolvida'" class="kpi-active-dot"></span>
            </div>
            <div class="kpi-label light">Não Resolvidas</div>
            <div class="kpi-value light">{{ statsLoading ? '-' : (rawTotals.nao_resolvida ?? 0) }}</div>
            <div class="kpi-sub light">Sem actividade há mais de 5 dias</div>
          </div>

          <div class="kpi-card kpi-red" @click="selectCard('nao_validado')" :class="{ 'card-active': activeFilter === 'nao_validado' }" title="Filtrar por: Não Validadas">
            <div class="kpi-top">
              <div class="kpi-icon white">
                <svg width="18" height="18" fill="none" stroke="rgba(255,255,255,.9)" stroke-width="1.8" viewBox="0 0 18 18">
                  <circle cx="6" cy="6" r="2.5"/><circle cx="12" cy="6" r="2.5"/>
                  <path d="M1 15c0-2.209 2.239-4 5-4M9 15c0-2.209 1.343-4 4-4 2.761 0 5 1.791 5 4" stroke-linecap="round"/>
                </svg>
              </div>
              <span v-if="activeFilter === 'nao_validado'" class="kpi-active-dot"></span>
            </div>
            <div class="kpi-label light">Não Validadas</div>
            <div class="kpi-value light">{{ statsLoading ? '-' : (rawTotals.nao_validado ?? 0) }}</div>
            <div class="kpi-sub light">Processos concluídos</div>
          </div>
        </div>

        <!-- FILTER BAR -->
        <transition name="fade">
          <div class="filter-active-bar" v-if="activeFilter">
            <span class="filter-active-label">
              <svg width="14" height="14" fill="none" stroke="#2D6A4F" stroke-width="1.8" viewBox="0 0 16 16">
                <path d="M2 4h12M4 8h8M6 12h4" stroke-linecap="round"/>
              </svg>
              A mostrar dados filtrados por: <strong>{{ FILTER_LABELS[activeFilter] }}</strong>
            </span>
            <button class="filter-clear-btn" @click="selectCard(null)" :disabled="filterLoading">
              <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 12 12">
                <path d="M2 2l8 8M10 2L2 10" stroke-linecap="round"/>
              </svg>
              Limpar filtro
            </button>
          </div>
        </transition>

        <!-- CHARTS ROW -->
        <div class="charts-row" :class="{ 'section-dimmed': filterLoading }">
          <div class="chart-card">
            <div class="chart-title">Ocorrências por Província</div>
            <div class="chart-sub">Volume de submissões por região atribuída</div>
            <div class="chart-wrap" style="height:200px">
              <canvas ref="barChartRef"></canvas>
            </div>
          </div>

          <div class="chart-card">
            <div class="chart-title">Distribuição por Categoria</div>
            <div class="chart-sub">Tipologia predominante das infrações</div>
            <div class="chart-wrap" style="height:130px">
              <canvas ref="donutChartRef"></canvas>
            </div>
            <div class="donut-legend">
              <div class="legend-item" v-for="(item, i) in donutLegend" :key="i">
                <span class="legend-dot" :style="{ background: item.color }"></span>
                <span class="legend-label">{{ item.label }}</span>
                <span class="legend-pct">{{ item.pct }}%</span>
              </div>
            </div>
          </div>
        </div>

        <!-- BOTTOM ROW -->
        <div class="bottom-row" :class="{ 'section-dimmed': filterLoading }">
          <div class="chart-card">
            <div class="chart-title">Tendência de Submissões</div>
            <div class="chart-sub">Evolução mensal comparada com taxa de resolução</div>
            <div class="line-legend">
              <div class="line-legend-item">
                <span class="legend-dot" style="background:#52B788"></span>
                submissões
              </div>
              <div class="line-legend-item">
                <span class="legend-dot" style="background:#74C0FC"></span>
                resolvidas
              </div>
            </div>
            <div class="chart-wrap" style="height:200px">
              <canvas ref="lineChartRef"></canvas>
            </div>
          </div>

          <div class="chart-card">
            <div class="table-header">
              <div class="chart-title">Últimas Submissões</div>
              <a class="ver-todas" @click="router.push('/gestor/validacao')" style="cursor:pointer">
                Ver Todas
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 13 13">
                  <path d="M2 6.5h9M7 3l3.5 3.5L7 10" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </a>
            </div>
            <div class="table-sub">Últimas 10 ocorrências nas suas províncias</div>
            <div class="table-scroll">
            <table>
              <thead>
                <tr>
                  <th>Cidadão</th>
                  <th>Categoria</th>
                  <th>Província</th>
                  <th>Estado</th>
                  <th>Submetido</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="row in submissions" :key="row.code"
                    class="row-clickable"
                    @click="row._id && router.push({ path: '/gestor/validacao', query: { select: row._id } })"
                    title="Clique para ver detalhes">
                  <td>
                    <div class="citizen-cell">
                      <div class="citizen-avatar" :style="{ background: row.color }">{{ row.initials }}</div>
                      <div>
                        <div class="citizen-name">{{ row.name }}</div>
                        <div class="citizen-code">{{ row.code }}</div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="cat-cell">
                      <svg width="13" height="13" fill="none" :stroke="row.catColor" stroke-width="1.7" viewBox="0 0 14 14">
                        <path d="M7 1C4 1 2 4 2 7c0 4 5 7 5 7s5-3 5-7c0-3-2-6-5-6z"/>
                        <circle cx="7" cy="7" r="1.8"/>
                      </svg>
                      {{ row.categoria }}
                    </div>
                  </td>
                  <td class="td-provincia">{{ row.provincia }}</td>
                  <td>
                    <span class="badge-status" :class="row.statusClass">{{ row.status }}</span>
                  </td>
                  <td class="td-tempo">{{ row.tempo }}</td>
                </tr>
                <tr v-if="!statsLoading && submissions.length === 0">
                  <td colspan="5" class="empty-row">Sem ocorrências recentes.</td>
                </tr>
              </tbody>
            </table>
            </div>
          </div>
        </div>

        <!-- DEMOGRAPHICS ROW -->
        <div class="demo-row" :class="{ 'section-dimmed': filterLoading }">
          <div class="chart-card">
            <div class="chart-title">Distribuição por Sexo</div>
            <div class="chart-sub">Perfil de género dos reclamantes</div>
            <div class="pie-center-wrap">
              <canvas ref="pieChartRef"></canvas>
              <div class="donut-center" v-if="rawTotals.all">
                <div class="donut-total">{{ rawTotals.all }}</div>
                <div class="donut-total-label">total</div>
              </div>
            </div>
            <div class="gender-legend">
              <div class="gender-legend-item" v-for="item in genderLegend" :key="item.label">
                <span class="pie-dot" :style="{ background: item.color }"></span>
                <span class="gender-leg-label">{{ item.label }}</span>
                <span class="gender-leg-val">{{ item.count }}</span>
                <span class="gender-leg-pct">{{ item.pct }}%</span>
              </div>
              <div class="pie-legend-empty" v-if="!genderLegend.length">Sem dados de género disponíveis</div>
            </div>
          </div>

          <div class="chart-card">
            <div class="chart-title">Ocorrências por Faixa Etária</div>
            <div class="chart-sub">Distribuição etária dos reclamantes (dados informados)</div>
            <div class="chart-wrap" style="height:270px; margin-top:16px">
              <canvas ref="ageBarChartRef"></canvas>
            </div>
          </div>
        </div>

        <!-- CANAL & PROJECTOS ROW -->
        <div class="demo-row-2" :class="{ 'section-dimmed': filterLoading }">
          <div class="chart-card">
            <div class="chart-card-header">
              <div>
                <div class="chart-title">Canal de Submissão</div>
                <div class="chart-sub">Como as ocorrências chegam ao sistema</div>
              </div>
              <span class="total-chip" v-if="channelTotal">{{ channelTotal }} total</span>
            </div>
            <div class="polar-wrap">
              <canvas ref="channelChartRef"></canvas>
            </div>
            <div class="gender-legend">
              <div class="gender-legend-item" v-for="item in channelLegend" :key="item.label">
                <span class="pie-dot" :style="{ background: item.color }"></span>
                <span class="gender-leg-label">{{ item.label }}</span>
                <span class="gender-leg-val">{{ item.count }}</span>
                <span class="gender-leg-pct">{{ item.pct }}%</span>
              </div>
              <div class="pie-legend-empty" v-if="!channelLegend.length">Sem dados de canal disponíveis</div>
            </div>
          </div>

          <div class="chart-card">
            <div class="chart-title">Projecto com Mais Ocorrências</div>
            <div class="chart-sub">Ranking dos projectos por volume de submissões</div>
            <div class="project-leader" v-if="topProject">
              <svg width="14" height="14" fill="#52B788" stroke="#2D6A4F" stroke-width="1.2" viewBox="0 0 16 16">
                <path d="M8 1l1.5 3 3.5.5-2.5 2.5.5 3.5L8 9l-3 1.5.5-3.5L3 4.5 6.5 4z"/>
              </svg>
              <span><strong>{{ topProject.name }}</strong> lidera com {{ topProject.total }} ocorrências</span>
            </div>
            <div class="rank-list">
              <div class="rank-item" v-for="(p, i) in stats.byProject" :key="p.name">
                <div class="rank-badge" :class="'rank-' + (i + 1)">{{ i + 1 }}</div>
                <div class="rank-info">
                  <div class="rank-name-row">
                    <span class="rank-name">{{ p.name }}</span>
                    <span class="rank-count">{{ p.total }}</span>
                  </div>
                  <div class="rank-bar-track">
                    <div class="rank-bar-fill" :style="{ width: projectPct(p.total) + '%', background: PROJECT_COLORS[i % PROJECT_COLORS.length] }"></div>
                  </div>
                </div>
              </div>
              <div class="pie-legend-empty" v-if="!stats.byProject.length">Sem dados de projecto disponíveis</div>
            </div>
          </div>
        </div>

      </main>

      <footer class="dash-footer">
        <span>© 2026 BIOFUND Gestor · Sistema de Gestão Ambiental de Moçambique</span>
        <div>
          <a href="#">Suporte Técnico</a>
          <a href="#">Privacidade</a>
        </div>
      </footer>
    </div>

    <!-- ── DRAWER OVERLAY ── -->
    <transition name="fade">
      <div v-if="drawerOpen" class="drawer-overlay" @click="closeDrawer"></div>
    </transition>

    <!-- ── DRAWER PANEL ── -->
    <transition name="modal-pop">
      <div v-if="drawerOpen" class="drawer-panel">

        <div class="drawer-header">
          <div>
            <h2 class="drawer-title">Registar Ocorrência</h2>
            <p class="drawer-subtitle">Preencha os dados da nova ocorrência ambiental</p>
          </div>
          <button class="drawer-close" @click="closeDrawer">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 16 16">
              <path d="M3 3l10 10M13 3L3 13" stroke-linecap="round"/>
            </svg>
          </button>
        </div>

        <div class="drawer-body">

          <div class="error-banner" v-if="submitError">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 16 16">
              <circle cx="8" cy="8" r="6"/><path d="M8 5v3M8 11h.01" stroke-linecap="round"/>
            </svg>
            {{ submitError }}
          </div>

          <div class="f-row">
            <div class="f-group">
              <label>Projecto <span class="f-req">*</span></label>
              <select v-model="form.project_id" :class="{ 'f-err': errors.project_id }" @change="errors.project_id = ''">
                <option value="" disabled>Seleccione o projecto</option>
                <option v-for="p in scopeProjects" :key="p.id" :value="p.id">{{ p.name }}</option>
              </select>
              <span class="f-err-msg" v-if="errors.project_id">{{ errors.project_id }}</span>
            </div>
            <div class="f-group">
              <label>Categoria <span class="f-req">*</span></label>
              <select v-model="form.category_id" :class="{ 'f-err': errors.category_id }" @change="errors.category_id = ''">
                <option value="" disabled>Seleccione a categoria</option>
                <option v-for="c in refCategories" :key="c.id" :value="c.id">{{ c.name }}</option>
              </select>
              <span class="f-err-msg" v-if="errors.category_id">{{ errors.category_id }}</span>
            </div>
          </div>

          <div class="f-group">
            <label>Assunto <span class="f-req">*</span></label>
            <input type="text" v-model="form.subject" maxlength="255"
              :class="{ 'f-err': errors.subject }"
              placeholder="Ex: Poluição do rio Incomáti"
              @input="errors.subject = ''"/>
            <span class="f-err-msg" v-if="errors.subject">{{ errors.subject }}</span>
          </div>

          <div class="f-row">
            <div class="f-group">
              <label>Tipo de Ocorrência <span class="f-req">*</span></label>
              <select v-model="form.occurrence_type_id" :class="{ 'f-err': errors.occurrence_type_id }" @change="errors.occurrence_type_id = ''">
                <option value="" disabled>Seleccione o tipo</option>
                <option v-for="t in refTypes" :key="t.id" :value="t.id">{{ t.name }}</option>
              </select>
              <span class="f-err-msg" v-if="errors.occurrence_type_id">{{ errors.occurrence_type_id }}</span>
            </div>
            <div class="f-group">
              <label>Nível de Alerta <span class="f-req">*</span></label>
              <select v-model="form.alert_type" :class="{ 'f-err': errors.alert_type }" @change="errors.alert_type = ''">
                <option value="" disabled>Seleccione o nível</option>
                <option value="normal">Normal</option>
                <option value="urgent">Urgente</option>
                <option value="gbv">GBV - Violência de Género</option>
              </select>
              <span class="f-err-msg" v-if="errors.alert_type">{{ errors.alert_type }}</span>
            </div>
          </div>

          <div class="f-row">
            <div class="f-group">
              <label>Canal de Submissão <span class="f-req">*</span></label>
              <select v-model="form.submission_channel" :class="{ 'f-err': errors.submission_channel }" @change="errors.submission_channel = ''">
                <option value="" disabled>Seleccione o canal</option>
                <option value="green_line">Linha Verde</option>
                <option value="email">Email</option>
                <option value="phone">Telefone</option>
                <option value="community_meeting">Reunião Comunitária</option>
              </select>
              <span class="f-err-msg" v-if="errors.submission_channel">{{ errors.submission_channel }}</span>
            </div>
            <div class="f-group">
              <label>Data da Ocorrência</label>
              <div class="date-input-wrap">
                <input type="date" v-model="form.occurrence_date" :max="today" :class="{ 'is-empty': !form.occurrence_date }"/>
                <span class="date-placeholder" v-if="!form.occurrence_date">dia / mês / ano</span>
              </div>
            </div>
          </div>

          <div class="f-row">
            <div class="f-group">
              <label>Nome do Reclamante (Opcional)</label>
              <input type="text" v-model="form.complainant_name" placeholder="Nome completo ou pseudónimo"/>
            </div>
            <div class="f-group">
              <label>Email do Reclamante</label>
              <input type="email" v-model="form.complainant_email"
                :class="{ 'f-err': errors.complainant_email }"
                placeholder="email@exemplo.com"
                @input="errors.complainant_email = ''"/>
              <span class="f-err-msg" v-if="errors.complainant_email">{{ errors.complainant_email }}</span>
            </div>
          </div>

          <div class="f-row" style="margin-bottom:6px">
            <div class="f-group">
              <label>Telefone do Reclamante</label>
              <input type="tel" v-model="form.complainant_phone" placeholder="ex: +258 84 000 0000"/>
            </div>
            <div class="f-group contact-note">
              <svg width="14" height="14" fill="none" stroke="#888E8C" stroke-width="1.6" viewBox="0 0 16 16">
                <circle cx="8" cy="8" r="6"/><path d="M8 7v4M8 5h.01" stroke-linecap="round"/>
              </svg>
              Preencha pelo menos um contacto para que o reclamante possa ser notificado.
            </div>
          </div>

          <div class="f-row">
            <div class="f-group">
              <label>Sexo <span class="f-opt">(Opcional)</span></label>
              <select v-model="form.complainant_gender">
                <option value="">Não identificado</option>
                <option value="masculino">Masculino</option>
                <option value="feminino">Feminino</option>
              </select>
            </div>
            <div class="f-group">
              <label>Faixa Etária <span class="f-opt">(Opcional)</span></label>
              <select v-model="form.complainant_age">
                <option value="">Não informada</option>
                <option value="Menos de 18">Menos de 18 anos</option>
                <option value="18 - 25">18 - 25 anos</option>
                <option value="26 - 35">26 - 35 anos</option>
                <option value="36 - 45">36 - 45 anos</option>
                <option value="46 - 55">46 - 55 anos</option>
                <option value="56 - 65">56 - 65 anos</option>
                <option value="Mais de 65">Mais de 65 anos</option>
              </select>
            </div>
          </div>

          <div class="f-row">
            <div class="f-group">
              <label>Província <span class="f-req">*</span></label>
              <select v-model="form.province_id" :class="{ 'f-err': errors.province_id }" @change="handleProvinceChange">
                <option value="" disabled>Seleccione a província</option>
                <option v-for="p in scopeProvinces" :key="p.id" :value="p.id">{{ p.name }}</option>
              </select>
              <span class="f-err-msg" v-if="errors.province_id">{{ errors.province_id }}</span>
            </div>
            <div class="f-group">
              <label>Distrito</label>
              <select v-model="form.district_id" :disabled="!form.province_id || loadingDistricts">
                <option value="">{{ loadingDistricts ? 'A carregar…' : 'Seleccione o distrito' }}</option>
                <option v-for="d in refDistricts" :key="d.id" :value="d.id">{{ d.name }}</option>
              </select>
            </div>
          </div>

          <div class="f-group">
            <label>Localização / Coordenadas (Opcional)</label>
            <input type="text" v-model="form.location_detail" placeholder="Ex: -25.9682, 32.5732 ou descrição do local"/>
          </div>

          <div class="f-group">
            <label>Descrição Detalhada <span class="f-req">*</span></label>
            <textarea v-model="form.description"
              :class="{ 'f-err': errors.description }"
              placeholder="Descreva o que observou, pessoas envolvidas, gravidade… (mínimo 20 caracteres)"
              @input="errors.description = ''"></textarea>
            <div class="char-hint" :class="form.description.length >= 20 ? 'char-ok' : 'char-warn'"
              v-if="form.description.length > 0">
              {{ form.description.length >= 20 ? '✓ ' + form.description.length + ' caracteres' : 'Faltam ' + (20 - form.description.length) + ' caracteres' }}
            </div>
            <span class="f-err-msg" v-if="errors.description">{{ errors.description }}</span>
          </div>

          <div class="upload-zone"
            :class="{ 'drag-over': isDragging }"
            @click="fileInput.click()"
            @dragover.prevent="isDragging = true"
            @dragleave="isDragging = false"
            @drop.prevent="handleDrop">
            <div class="upload-icon-wrap">
              <svg width="20" height="20" fill="none" stroke="#2D6A4F" stroke-width="1.7" viewBox="0 0 20 20">
                <path d="M3 15v1.5A1.5 1.5 0 0 0 4.5 18h11A1.5 1.5 0 0 0 17 16.5V15" stroke-linecap="round"/>
                <path d="M10 2v10M6.5 5.5 10 2l3.5 3.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <h4>Clique para carregar ou arraste e solte</h4>
            <p>PNG, JPG, PDF até 10MB</p>
          </div>
          <input ref="fileInput" type="file" multiple accept=".png,.jpg,.jpeg,.pdf"
            style="display:none" @change="handleFileSelect"/>

          <div class="file-list" v-if="files.length">
            <div class="file-item" v-for="(f, i) in files" :key="i">
              <svg width="15" height="15" fill="none" stroke="#2D6A4F" stroke-width="1.5" viewBox="0 0 16 16">
                <rect x="2" y="1" width="10" height="14" rx="1.5"/>
                <path d="M5 5h4M5 8h4M5 11h2" stroke-linecap="round"/>
                <path d="M10 1v4h4" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="file-item-name">{{ f.name }}</span>
              <span class="file-item-size">{{ (f.size / 1024).toFixed(0) }} KB</span>
              <button class="btn-rm" @click.stop="files.splice(i, 1)">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 13 13">
                  <path d="M2 2l9 9M11 2L2 11" stroke-linecap="round"/>
                </svg>
              </button>
            </div>
          </div>

          <div class="f-actions">
            <button class="btn-registar" @click="submitForm" :disabled="loading">
              <svg v-if="loading" class="spin" width="15" height="15" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 16 16">
                <path d="M8 2a6 6 0 0 1 6 6" stroke-linecap="round"/>
              </svg>
              <svg v-else width="14" height="14" fill="none" stroke="#fff" stroke-width="1.8" viewBox="0 0 16 16">
                <rect x="2" y="1" width="10" height="14" rx="1.5"/>
                <path d="M5 5h4M5 8h4M5 11h2" stroke-linecap="round"/>
                <path d="M10 1v4h4" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              {{ loading ? 'A registar…' : 'Registar' }}
            </button>
            <button class="btn-cancelar" @click="closeDrawer">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 14 14">
                <circle cx="7" cy="7" r="5.5"/>
                <path d="M5 5l4 4M9 5L5 9" stroke-linecap="round"/>
              </svg>
              Cancelar
            </button>
          </div>

        </div>
      </div>
    </transition>

    <!-- ── TOAST ── -->
    <transition name="toast-anim">
      <div class="dash-toast" v-if="toast.show">
        <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 16 16">
          <circle cx="8" cy="8" r="6"/>
          <path d="M5.5 8l2 2 3.5-4" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        {{ toast.msg }}
      </div>
    </transition>

    <!-- Modal de relatório periódico -->
    <RelatorioPeriodicoModal
      :open="modalRelatorio"
      :projects="scopeProjects"
      :provinces="scopeProvinces"
      :categories="refCategories"
      :periodo-inicial="'mensal'"
      @close="modalRelatorio = false"
    />

  </div>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted, onActivated, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { Chart, registerables } from 'chart.js'
import { useAuthStore } from '@/stores/auth'
import { InternalService } from '@/api/services/internal.service'
import AdminProfilePanel from '@/components/AdminProfilePanel.vue'
import AdminNotificationPanel from '@/components/AdminNotificationPanel.vue'
import RelatorioPeriodicoModal from '@/components/RelatorioPeriodicoModal.vue'

Chart.register(...registerables)

const router = useRouter()
const auth   = useAuthStore()

const sidebarOpen = ref(false)

async function handleLogout() {
  await auth.logout()
  router.push('/acessoRestrito')
}

// Âmbito do gestor (províncias e projectos atribuídos)
const scopeProvinces = computed(() => auth.user?.provinces ?? [])
const scopeProjects  = computed(() => auth.user?.projects  ?? [])

const searchQ       = ref('')
const barChartRef    = ref(null)
const donutChartRef  = ref(null)
const lineChartRef   = ref(null)
const pieChartRef    = ref(null)
const ageBarChartRef = ref(null)
const channelChartRef = ref(null)
const submissions    = ref([])

// ── Filtro de KPI cards ────────────────────────────────────────
const activeFilter  = ref(null)
const filterLoading = ref(false)

const FILTER_MAP = {
  por_validar:   { status: 'por_validar'   },
  resolvido:     { status: 'resolvido'     },
  nao_resolvida: { status: 'nao_resolvida' },
  nao_validado:  { status: 'nao_validado'  },
}

const FILTER_LABELS = {
  por_validar:   'Por Validar',
  resolvido:     'Resolvidas',
  nao_resolvida: 'Não Resolvidas',
  nao_validado:  'Não Validadas',
}

// ── Estatísticas ─────────────────────────────────────────────
const statsLoading = ref(true)
const stats = reactive({
  totals:          { all: 0, por_validar: 0, por_resolver: 0, nao_validado: 0, resolvendo: 0, resolvido: 0, nao_resolvida: 0 },
  overdue:         0,
  byAlertLevel:    { normal: 0, urgent: 0, gbv: 0 },
  byProvince:      [],
  byCategory:      [],
  byMonth:         [],
  byMonthResolved: [],
  byGender:        {},
  byAgeRange:      [],
  byChannel:       [],
  byProject:       [],
})

// Valores KPI fixos - não se alteram quando um filtro está activo
const rawTotals     = reactive({ all: 0, por_validar: 0, por_resolver: 0, nao_validado: 0, resolvendo: 0, resolvido: 0, nao_resolvida: 0 })
const rawAlertLevel = reactive({ normal: 0, urgent: 0, gbv: 0 })
const rawOverdue     = ref(0)
const rawReclamacoes = ref(0)
const rawElogios     = ref(0)
const rawSugestoes   = ref(0)

// ── Filtros de dashboard (ano / província / projecto / sexo / faixa etária) ──
const dashFilter = reactive({ year: '', province_id: '', project_id: '', category_id: '', gender: '', age_range: '' })
const filterYears = computed(() => {
  const y = new Date().getFullYear()
  return Array.from({ length: y - 2019 }, (_, i) => y - i)
})
const AGE_RANGES = ['Menos de 18', '18 - 25', '26 - 35', '36 - 45', '46 - 55', '56 - 65', 'Mais de 65']
const hasActiveDashFilter = computed(() =>
  !!(dashFilter.year || dashFilter.province_id || dashFilter.project_id || dashFilter.category_id || dashFilter.gender || dashFilter.age_range)
)
function buildDashFilter() {
  const p = {}
  if (dashFilter.year)        p.year        = dashFilter.year
  if (dashFilter.province_id) p.province_id = dashFilter.province_id
  if (dashFilter.project_id)  p.project_id  = dashFilter.project_id
  if (dashFilter.category_id) p.category_id = dashFilter.category_id
  if (dashFilter.gender)      p.gender      = dashFilter.gender
  if (dashFilter.age_range)   p.age_range   = dashFilter.age_range
  return p
}
async function applyDashFilter() {
  activeFilter.value  = null
  filterLoading.value = true
  try {
    const data = await InternalService.getDashboardStats(buildDashFilter())
    const zeroTotals     = { all: 0, por_validar: 0, por_resolver: 0, nao_validado: 0, resolvendo: 0, resolvido: 0, nao_resolvida: 0 }
    const zeroAlertLevel = { normal: 0, urgent: 0, gbv: 0 }
    Object.assign(stats.totals,       zeroTotals,     data.totals         ?? {})
    Object.assign(stats.byAlertLevel, zeroAlertLevel, data.by_alert_level ?? {})
    stats.overdue         = data.overdue          ?? 0
    stats.byProvince      = data.by_province      ?? []
    stats.byCategory      = data.by_category      ?? []
    stats.byMonth         = data.by_month         ?? []
    stats.byMonthResolved = data.by_month_resolved ?? []
    stats.byGender        = data.by_gender         ?? {}
    stats.byAgeRange      = data.by_age_range      ?? []
    stats.byChannel       = data.by_channel        ?? []
    stats.byProject       = data.by_project        ?? []
    submissions.value     = mapRecent(data.recent)
    Object.assign(rawTotals,     zeroTotals,     data.totals         ?? {})
    Object.assign(rawAlertLevel, zeroAlertLevel, data.by_alert_level ?? {})
    rawOverdue.value      = data.overdue      ?? 0
    rawReclamacoes.value  = data.reclamacoes  ?? 0
    rawElogios.value      = data.elogios      ?? 0
    rawSugestoes.value    = data.sugestoes    ?? 0
    updateCharts()
  } catch (err) {
    console.error('Erro ao aplicar filtros:', err)
  } finally {
    filterLoading.value = false
  }
}
function clearDashFilter() {
  dashFilter.year = ''
  dashFilter.province_id = ''
  dashFilter.project_id  = ''
  dashFilter.category_id = ''
  dashFilter.gender = ''
  dashFilter.age_range = ''
  applyDashFilter()
}

const CHART_COLORS  = ['#52B788','#74C0FC','#F4A52A','#FC8181','#9F7AEA','#ED8936']
const GENDER_COLORS = { Masculino: '#74C0FC', Feminino: '#FC8181', 'Não Identificado': '#D1D5DB' }
const CHANNEL_COLORS = {
  'Linha Verde':           '#52B788',
  'Email':                 '#74C0FC',
  'Telefone':              '#F4A52A',
  'Reunião Comunitária':   '#9F7AEA',
  'Portal Online':         '#06B6D4',
}
const PROJECT_COLORS = ['#2D6A4F','#52B788','#74C0FC','#9F7AEA','#F4A52A','#FC8181']

let barChart     = null
let donutChart   = null
let lineChart    = null
let pieChart     = null
let ageBarChart  = null
let channelChart = null

function buildMonthAxis() {
  const months = []
  for (let i = 5; i >= 0; i--) {
    const d = new Date(); d.setMonth(d.getMonth() - i)
    months.push(`${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}`)
  }
  return months
}

function formatMonthLabel(label) {
  const [, month] = label.split('-')
  return ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'][parseInt(month) - 1]
}

function updateCharts() {
  if (barChart) {
    barChart.data.labels           = stats.byProvince.length ? stats.byProvince.map(p => p.name)  : ['Sem dados']
    barChart.data.datasets[0].data = stats.byProvince.length ? stats.byProvince.map(p => p.total) : [0]
    barChart.update()
  }
  if (donutChart) {
    donutChart.data.labels                      = stats.byCategory.length ? stats.byCategory.map(c => c.name)  : ['Sem dados']
    donutChart.data.datasets[0].data            = stats.byCategory.length ? stats.byCategory.map(c => c.total) : [1]
    donutChart.data.datasets[0].backgroundColor = stats.byCategory.length
      ? stats.byCategory.map((_, i) => CHART_COLORS[i % CHART_COLORS.length])
      : ['#E2E8F0']
    donutChart.update()
  }
  if (lineChart) {
    const ax = buildMonthAxis()
    lineChart.data.labels           = ax.map(formatMonthLabel)
    lineChart.data.datasets[0].data = ax.map(m => stats.byMonth.find(r => r.label === m)?.total ?? 0)
    lineChart.data.datasets[1].data = ax.map(m => stats.byMonthResolved.find(r => r.label === m)?.total ?? 0)
    lineChart.update()
  }
  if (pieChart) {
    const gLabels = Object.keys(stats.byGender)
    const gValues = Object.values(stats.byGender)
    pieChart.data.labels                      = gLabels.length ? gLabels : ['Sem dados']
    pieChart.data.datasets[0].data            = gValues.length ? gValues : [1]
    pieChart.data.datasets[0].backgroundColor = gLabels.length ? gLabels.map(l => GENDER_COLORS[l] ?? '#E2E8F0') : ['#E2E8F0']
    pieChart.update()
  }
  if (ageBarChart) {
    ageBarChart.data.datasets[0].data = stats.byAgeRange.map(a => a.total)
    ageBarChart.update()
  }
  if (channelChart) {
    channelChart.data.labels                      = stats.byChannel.length ? stats.byChannel.map(c => c.label) : ['Sem dados']
    channelChart.data.datasets[0].data            = stats.byChannel.length ? stats.byChannel.map(c => c.total) : [1]
    channelChart.data.datasets[0].backgroundColor = stats.byChannel.length
      ? stats.byChannel.map(c => CHANNEL_COLORS[c.label] ?? '#E2E8F0')
      : ['#E2E8F0']
    channelChart.update()
  }
}

async function refreshStats() {
  try {
    const data = await InternalService.getDashboardStats(buildDashFilter())
    const zeroTotals     = { all: 0, por_validar: 0, por_resolver: 0, nao_validado: 0, resolvendo: 0, resolvido: 0, nao_resolvida: 0 }
    const zeroAlertLevel = { normal: 0, urgent: 0, gbv: 0 }
    Object.assign(stats.totals,       zeroTotals,     data.totals         ?? {})
    Object.assign(stats.byAlertLevel, zeroAlertLevel, data.by_alert_level ?? {})
    stats.overdue         = data.overdue          ?? 0
    stats.byProvince      = data.by_province      ?? []
    stats.byCategory      = data.by_category      ?? []
    stats.byMonth         = data.by_month         ?? []
    stats.byMonthResolved = data.by_month_resolved ?? []
    stats.byGender        = data.by_gender         ?? {}
    stats.byAgeRange      = data.by_age_range      ?? []
    stats.byChannel       = data.by_channel        ?? []
    stats.byProject       = data.by_project        ?? []
    submissions.value     = mapRecent(data.recent)
    Object.assign(rawTotals,     zeroTotals,     data.totals         ?? {})
    Object.assign(rawAlertLevel, zeroAlertLevel, data.by_alert_level ?? {})
    rawOverdue.value      = data.overdue      ?? 0
    rawReclamacoes.value  = data.reclamacoes  ?? 0
    rawElogios.value      = data.elogios      ?? 0
    rawSugestoes.value    = data.sugestoes    ?? 0
    updateCharts()
  } catch (err) {
    console.error('Erro ao actualizar estatísticas:', err)
  }
}

async function selectCard(key) {
  // Card "Total" - limpa filtro (se houver) e mostra dados completos
  if (key === null) {
    if (activeFilter.value === null) return
    activeFilter.value  = null
    filterLoading.value = true
    try {
      await refreshStats()
    } finally {
      filterLoading.value = false
    }
    return
  }
  // Toggle: clicar no mesmo card filtro remove o filtro
  if (key === activeFilter.value) {
    activeFilter.value  = null
    filterLoading.value = true
    try {
      await refreshStats()
    } finally {
      filterLoading.value = false
    }
    return
  }
  activeFilter.value  = key
  filterLoading.value = true
  try {
    const data = await InternalService.getDashboardStats({ ...FILTER_MAP[key], ...buildDashFilter() })
    stats.byProvince      = data.by_province      ?? []
    stats.byCategory      = data.by_category      ?? []
    stats.byMonth         = data.by_month         ?? []
    stats.byMonthResolved = data.by_month_resolved ?? []
    stats.byGender        = data.by_gender         ?? {}
    stats.byAgeRange      = data.by_age_range      ?? []
    stats.byChannel       = data.by_channel        ?? []
    stats.byProject       = data.by_project        ?? []
    submissions.value     = mapRecent(data.recent)
    updateCharts()
  } catch (err) {
    console.error('Erro ao filtrar:', err)
  } finally {
    filterLoading.value = false
  }
}

const donutLegend = computed(() => {
  if (!stats.byCategory.length) return []
  const total = stats.byCategory.reduce((s, c) => s + c.total, 0)
  return stats.byCategory.map((cat, i) => ({
    label: cat.name,
    color: CHART_COLORS[i % CHART_COLORS.length],
    pct:   total ? Math.round(cat.total / total * 100) : 0,
  }))
})

const genderLegend = computed(() => {
  const total = Object.values(stats.byGender).reduce((s, v) => s + v, 0)
  if (!total) return []
  return Object.entries(stats.byGender).map(([label, count]) => ({
    label,
    count,
    color: GENDER_COLORS[label] ?? '#E2E8F0',
    pct:   Math.round(count / total * 100),
  }))
})

const channelTotal = computed(() => stats.byChannel.reduce((s, c) => s + c.total, 0))

const channelLegend = computed(() => {
  if (!stats.byChannel.length) return []
  const total = channelTotal.value
  return stats.byChannel.map(c => ({
    label: c.label,
    count: c.total,
    color: CHANNEL_COLORS[c.label] ?? '#E2E8F0',
    pct:   total ? Math.round(c.total / total * 100) : 0,
  }))
})

const topProject = computed(() => stats.byProject[0] ?? null)

function projectPct(total) {
  const max = Math.max(1, ...stats.byProject.map(p => p.total))
  return Math.round(total / max * 100)
}

const STATUS_MAP = {
  por_validar:  { label: 'Por Validar',  cls: 'por-validar'  },
  por_resolver: { label: 'Por Resolver', cls: 'por-resolver' },
  resolvendo:   { label: 'Resolvendo',   cls: 'resolvendo'   },
  resolvido:    { label: 'Resolvido',    cls: 'resolvido'    },
  nao_validado: { label: 'Não Validado', cls: 'nao-validado' },
}

function timeAgo(dateStr) {
  const diff = Date.now() - new Date(dateStr).getTime()
  const m = Math.floor(diff / 60000)
  const h = Math.floor(diff / 3600000)
  const d = Math.floor(diff / 86400000)
  if (d > 0) return `há ${d} dia${d > 1 ? 's' : ''}`
  if (h > 0) return `há ${h} hora${h > 1 ? 's' : ''}`
  if (m > 0) return `há ${m} minuto${m > 1 ? 's' : ''}`
  return 'agora mesmo'
}

function mapRecent(items) {
  const list = Array.isArray(items) ? items : Object.values(items ?? {})
  return list.map((o, idx) => {
    const s    = STATUS_MAP[o.status] ?? { label: o.status, cls: 'por-validar' }
    const name = o.complainant_name?.trim() || 'Anónimo'
    const init = name === 'Anónimo'
      ? 'AN'
      : name.split(' ').slice(0, 2).map(w => w[0]?.toUpperCase() ?? '').join('')
    return {
      _id:         o.id,
      name,
      code:        o.tracking_code,
      initials:    init.slice(0, 2),
      color:       CHART_COLORS[idx % CHART_COLORS.length],
      categoria:   o.category?.name ?? '-',
      catColor:    '#52B788',
      provincia:   o.province?.name ?? '-',
      status:      s.label,
      statusClass: s.cls,
      tempo:       timeAgo(o.created_at),
    }
  })
}

// ── DRAWER ───────────────────────────────────────────────────
const refCategories  = ref([])
const refTypes       = ref([])
const refDistricts   = ref([])
const loadingRef     = ref(false)
const loadingDistricts = ref(false)
const today = new Date().toISOString().split('T')[0]

async function loadRefData() {
  if (refCategories.value.length) return
  loadingRef.value = true
  try {
    const data = await InternalService.getFormData()
    refCategories.value = data.categories      ?? []
    refTypes.value      = (data.occurrence_types ?? []).filter(t => t.alert_level !== 'urgent')
  } catch (err) {
    console.error('Erro ao carregar dados do formulário:', err)
  } finally {
    loadingRef.value = false
  }
}

async function handleProvinceChange() {
  form.district_id = ''
  refDistricts.value = []
  if (!form.province_id) return
  loadingDistricts.value = true
  try {
    const data = await InternalService.getDistrictsByProvince(form.province_id)
    refDistricts.value = data.districts ?? data
  } catch (err) {
    console.error('Erro ao carregar distritos:', err)
  } finally {
    loadingDistricts.value = false
  }
}

const drawerOpen  = ref(false)
const modalRelatorio = ref(false)
const toast       = reactive({ show: false, msg: '' })
const loading     = ref(false)
const submitError = ref('')
const isDragging  = ref(false)
const files       = ref([])
const fileInput   = ref(null)

function showToast(msg) {
  toast.msg = msg; toast.show = true
  setTimeout(() => { toast.show = false }, 4000)
}

watch(drawerOpen, (isOpen) => { if (isOpen) loadRefData() })

// load categories (and other refs) eagerly so the category filter chip is populated on mount
onMounted(() => { loadRefData() })

const form = reactive({
  complainant_name: '', complainant_email: '', complainant_phone: '',
  complainant_gender: '', complainant_age: '',
  subject: '', project_id: '', category_id: '', occurrence_type_id: '',
  alert_type: '', submission_channel: '', occurrence_date: '',
  province_id: '', district_id: '', location_detail: '', description: '',
})

const errors = reactive({
  complainant_email: '', subject: '', project_id: '', category_id: '',
  occurrence_type_id: '', alert_type: '', submission_channel: '',
  province_id: '', description: '',
})

function validate() {
  let ok = true
  if (!form.subject.trim())             { errors.subject            = 'O assunto é obrigatório.';               ok = false }
  if (!form.project_id)                 { errors.project_id         = 'Seleccione o projecto.';                 ok = false }
  if (!form.category_id)                { errors.category_id        = 'Seleccione a categoria.';                ok = false }
  if (!form.occurrence_type_id)         { errors.occurrence_type_id = 'Seleccione o tipo de ocorrência.';       ok = false }
  if (!form.alert_type)                 { errors.alert_type         = 'Seleccione o nível de alerta.';          ok = false }
  if (!form.submission_channel)         { errors.submission_channel = 'Seleccione o canal de submissão.';       ok = false }
  if (!form.province_id)                { errors.province_id        = 'Seleccione a província.';                ok = false }
  if (!form.description.trim())         { errors.description        = 'A descrição é obrigatória.';             ok = false }
  else if (form.description.trim().length < 20) {
                                          errors.description        = 'A descrição deve ter pelo menos 20 caracteres.'; ok = false }
  return ok
}

async function submitForm() {
  submitError.value = ''
  if (!validate()) return

  const fd = new FormData()
  if (form.complainant_name.trim())  fd.append('complainant_name',  form.complainant_name.trim())
  if (form.complainant_email.trim()) fd.append('complainant_email', form.complainant_email.trim())
  if (form.complainant_phone.trim()) fd.append('complainant_phone', form.complainant_phone.trim())
  if (form.complainant_gender)       fd.append('complainant_gender', form.complainant_gender)
  if (form.complainant_age)          fd.append('complainant_age',    form.complainant_age)
  fd.append('subject',            form.subject.trim())
  fd.append('project_id',         form.project_id)
  fd.append('category_id',        form.category_id)
  fd.append('occurrence_type_id', form.occurrence_type_id)
  fd.append('alert_type',         form.alert_type)
  fd.append('submission_channel', form.submission_channel)
  fd.append('province_id',        form.province_id)
  fd.append('description',        form.description.trim())
  if (form.district_id)     fd.append('district_id',     form.district_id)
  if (form.occurrence_date) fd.append('occurrence_date', form.occurrence_date)
  if (form.location_detail.trim()) fd.append('location_detail', form.location_detail.trim())
  files.value.forEach(f => fd.append('attachments[]', f))

  loading.value = true
  try {
    const result = await InternalService.createOccurrence(fd)
    const code = result.tracking_code ?? ''
    closeDrawer()
    showToast(`Ocorrência registada!${code ? ' Código: ' + code : ''}`)
    refreshStats()
    setTimeout(() => router.push('/gestor/validacao'), 1800)
  } catch (err) {
    if (err.response?.status === 422) {
      const serverErrors = err.response.data.errors ?? {}
      Object.entries(serverErrors).forEach(([field, msgs]) => {
        if (field in errors) errors[field] = msgs[0]
      })
      submitError.value = 'Corrija os erros e tente novamente.'
    } else {
      submitError.value = err.response?.data?.message ?? 'Erro ao registar. Tente novamente.'
    }
  } finally {
    loading.value = false
  }
}

function closeDrawer() {
  drawerOpen.value = false
  submitError.value = ''
  Object.assign(form, {
    complainant_name: '', complainant_email: '', complainant_phone: '',
    complainant_gender: '', complainant_age: '',
    subject: '', project_id: '', category_id: '', occurrence_type_id: '',
    alert_type: '', submission_channel: '', occurrence_date: '',
    province_id: '', district_id: '', location_detail: '', description: '',
  })
  Object.assign(errors, {
    complainant_email: '', subject: '', project_id: '', category_id: '',
    occurrence_type_id: '', alert_type: '', submission_channel: '',
    province_id: '', description: '',
  })
  files.value = []
  refDistricts.value = []
}

function handleFileSelect(e) {
  addFiles(Array.from(e.target.files)); e.target.value = ''
}
function handleDrop(e) {
  isDragging.value = false; addFiles(Array.from(e.dataTransfer.files))
}
function addFiles(list) {
  list.forEach(f => { if (f.size <= 10 * 1024 * 1024) files.value.push(f) })
}

// Quando o componente é re-activado via KeepAlive, limpa filtros e refresca dados
onActivated(async () => {
  activeFilter.value  = null
  filterLoading.value = true
  try {
    await refreshStats()
  } finally {
    filterLoading.value = false
  }
})

onUnmounted(() => {
  barChart?.destroy()
  donutChart?.destroy()
  lineChart?.destroy()
  pieChart?.destroy()
  ageBarChart?.destroy()
  channelChart?.destroy()
})

onMounted(async () => {
  Chart.defaults.font.family = "'Poppins', sans-serif"
  Chart.defaults.color       = '#8A9490'

  try {
    const data = await InternalService.getDashboardStats()
    Object.assign(stats.totals,       data.totals         ?? {})
    Object.assign(stats.byAlertLevel, data.by_alert_level ?? {})
    stats.overdue         = data.overdue          ?? 0
    stats.byProvince      = data.by_province      ?? []
    stats.byCategory      = data.by_category      ?? []
    stats.byMonth         = data.by_month         ?? []
    stats.byMonthResolved = data.by_month_resolved ?? []
    stats.byGender        = data.by_gender         ?? {}
    stats.byAgeRange      = data.by_age_range      ?? []
    stats.byChannel       = data.by_channel        ?? []
    stats.byProject       = data.by_project        ?? []
    submissions.value     = mapRecent(data.recent)
    Object.assign(rawTotals,     data.totals         ?? {})
    Object.assign(rawAlertLevel, data.by_alert_level ?? {})
    rawOverdue.value      = data.overdue      ?? 0
    rawReclamacoes.value  = data.reclamacoes  ?? 0
    rawElogios.value      = data.elogios      ?? 0
    rawSugestoes.value    = data.sugestoes    ?? 0
  } catch (err) {
    console.error('Erro ao carregar estatísticas:', err)
  } finally {
    statsLoading.value = false
  }

  barChart = new Chart(barChartRef.value, {
    type: 'bar',
    data: {
      labels: stats.byProvince.length ? stats.byProvince.map(p => p.name) : ['Sem dados'],
      datasets: [{ data: stats.byProvince.length ? stats.byProvince.map(p => p.total) : [0],
        backgroundColor: '#52B788', borderRadius: 6, borderSkipped: false }]
    },
    options: { responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { display: false }, ticks: { font: { size: 11 } } },
        y: { beginAtZero: true, grid: { color: '#F0F4F2' }, ticks: { font: { size: 11 } } }
      }
    }
  })

  donutChart = new Chart(donutChartRef.value, {
    type: 'doughnut',
    data: {
      labels: stats.byCategory.length ? stats.byCategory.map(c => c.name) : ['Sem dados'],
      datasets: [{ data: stats.byCategory.length ? stats.byCategory.map(c => c.total) : [1],
        backgroundColor: stats.byCategory.length
          ? stats.byCategory.map((_, i) => CHART_COLORS[i % CHART_COLORS.length])
          : ['#E2E8F0'],
        borderWidth: 0, hoverOffset: 6 }]
    },
    options: { responsive: true, maintainAspectRatio: false, cutout: '65%',
      plugins: { legend: { display: false },
        tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.raw}` } } }
    }
  })

  const allMonths    = buildMonthAxis()
  const lineSubmit   = allMonths.map(m => stats.byMonth.find(r => r.label === m)?.total ?? 0)
  const lineResolved = allMonths.map(m => stats.byMonthResolved.find(r => r.label === m)?.total ?? 0)

  lineChart = new Chart(lineChartRef.value, {
    type: 'line',
    data: {
      labels: allMonths.map(formatMonthLabel),
      datasets: [
        { label: 'Submissões', data: lineSubmit,
          borderColor: '#52B788', backgroundColor: 'rgba(82,183,136,.08)',
          fill: true, tension: 0.45, pointBackgroundColor: '#52B788', pointRadius: 4 },
        { label: 'Resolvidas', data: lineResolved,
          borderColor: '#74C0FC', backgroundColor: 'rgba(116,192,252,.06)',
          fill: true, tension: 0.45, pointBackgroundColor: '#74C0FC', pointRadius: 4 }
      ]
    },
    options: { responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { display: false }, ticks: { font: { size: 11 } } },
        y: { beginAtZero: true, grid: { color: '#F0F4F2' }, ticks: { font: { size: 11 } } }
      }
    }
  })

  const AGE_ORDER  = ['Menos de 18', '18 - 25', '26 - 35', '36 - 45', '46 - 55', '56 - 65', 'Mais de 65']
  const AGE_COLORS = [
    '#22C55E',  /* verde vivo   — < 18      */
    '#52B788',  /* verde marca  — 18–25     */
    '#06B6D4',  /* ciano        — 26–35     */
    '#6366F1',  /* índigo       — 36–45     */
    '#A855F7',  /* violeta      — 46–55     */
    '#F59E0B',  /* âmbar        — 56–65     */
    '#F97316',  /* laranja      — > 65      */
  ]

  const gLabels0 = Object.keys(stats.byGender)
  const gValues0 = Object.values(stats.byGender)

  pieChart = new Chart(pieChartRef.value, {
    type: 'doughnut',
    data: {
      labels: gLabels0.length ? gLabels0 : ['Sem dados'],
      datasets: [{
        data: gValues0.length ? gValues0 : [1],
        backgroundColor: gLabels0.length ? gLabels0.map(l => GENDER_COLORS[l] ?? '#E2E8F0') : ['#E2E8F0'],
        borderWidth: 2,
        borderColor: '#fff',
        hoverOffset: 8,
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      cutout: '68%',
      plugins: {
        legend: { display: false },
        tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.raw}` } }
      }
    }
  })

  ageBarChart = new Chart(ageBarChartRef.value, {
    type: 'bar',
    data: {
      labels: AGE_ORDER,
      datasets: [{
        data: stats.byAgeRange.map(a => a.total),
        backgroundColor: AGE_COLORS,
        borderRadius: 5,
        borderSkipped: false,
      }]
    },
    options: {
      indexAxis: 'y',
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        y: { grid: { display: false }, ticks: { font: { size: 11 } } },
        x: { beginAtZero: true, grid: { color: '#F0F4F2' }, ticks: { font: { size: 11 }, precision: 0 } }
      }
    }
  })

  const chLabels0 = stats.byChannel.map(c => c.label)
  const chValues0 = stats.byChannel.map(c => c.total)

  channelChart = new Chart(channelChartRef.value, {
    type: 'polarArea',
    data: {
      labels: chLabels0.length ? chLabels0 : ['Sem dados'],
      datasets: [{
        data: chValues0.length ? chValues0 : [1],
        backgroundColor: chLabels0.length ? chLabels0.map(l => CHANNEL_COLORS[l] ?? '#E2E8F0') : ['#E2E8F0'],
        borderWidth: 2,
        borderColor: '#fff',
        hoverOffset: 10,
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.raw}` } }
      },
      scales: {
        r: {
          grid: { color: '#EAF2EC' },
          angleLines: { color: '#EAF2EC' },
          ticks: { display: false, backdropColor: 'transparent' }
        }
      }
    }
  })
})
</script>

<style scoped>
.app-shell {
  display: flex;
  width: 100%;
  height: 100vh;
  overflow: hidden;
  background: #EDF2EF;
}

/* ── SIDEBAR ──────────────────────────────── */
.sidebar {
  width: 220px;
  flex-shrink: 0;
  background: #fff;
  display: flex;
  flex-direction: column;
  height: 100vh;
  position: fixed;
  top: 0; left: 0;
  z-index: 50;
  border-right: 1px solid var(--border);
  box-shadow: 2px 0 16px rgba(0,0,0,0.06);
}

.sidebar-logo {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 22px 18px 20px;
  border-bottom: 1px solid var(--border);
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
  padding: 16px 10px;
  overflow-y: auto;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  border-radius: 9px;
  margin-bottom: 3px;
  font-size: 13px;
  font-weight: 500;
  color: var(--text-gray);
  cursor: pointer;
  transition: background 0.15s, color 0.15s;
  text-decoration: none;
  border-left: 3px solid transparent;
}

.nav-item:hover {
  background: var(--green-bg);
  color: var(--green-mid);
}

.nav-item.active {
  background: var(--green-bg);
  color: var(--green-mid);
  font-weight: 700;
  border-left-color: #52B788;
  padding-left: 9px;
}

.nav-item svg    { flex-shrink: 0; opacity: 0.7; }
.nav-item:hover svg { opacity: 1; }
.nav-item.active svg { opacity: 1; }

.sidebar-footer {
  padding: 14px 10px;
  border-top: 1px solid var(--border);
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
  transition: background 0.15s, color 0.15s;
}
.btn-logout:hover { background: #FFF5F5; color: #C53030; }

/* ── MAIN ────────────────────────────────── */
.main {
  margin-left: 220px;
  flex: 1;
  display: flex;
  flex-direction: column;
  height: 100vh;
  overflow: hidden;
}

/* ── TOP BAR ─────────────────────────────── */
.topbar {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 0 28px;
  height: 62px;
  background: var(--white);
  border-bottom: 1px solid var(--border);
  box-shadow: 0 2px 12px rgba(0,0,0,0.04);
  flex-shrink: 0;
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

/* ── CONTENT ─────────────────────────────── */
.content {
  flex: 1;
  overflow-y: auto;
  padding: 26px 30px 36px;
}
.content::-webkit-scrollbar { width: 5px; }
.content::-webkit-scrollbar-track { background: transparent; }
.content::-webkit-scrollbar-thumb { background: #C8D8CE; border-radius: 99px; }

/* page title */
.page-title-row {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  margin-bottom: 20px;
}
.page-title-row h1 {
  font-size: 23px;
  font-weight: 800;
  margin-bottom: 5px;
  color: #162119;
  display: flex;
  align-items: center;
  gap: 10px;
}
.page-title-row h1::before {
  content: '';
  display: inline-block;
  width: 4px;
  height: 24px;
  background: linear-gradient(180deg, #52B788 0%, #2D6A4F 100%);
  border-radius: 99px;
  flex-shrink: 0;
}
.page-title-row p  { font-size: 13px; color: var(--text-gray); line-height: 1.5; }

.title-actions { display: flex; gap: 10px; flex-shrink: 0; margin-top: 2px; }

.btn-outline-sm {
  display: flex; align-items: center; gap: 7px;
  background: var(--white, #fff); color: var(--text-dark, #1A1A1A);
  border: 1.5px solid var(--border, #DDE8E1); border-radius: 8px;
  padding: 8px 16px;
  font-family: 'Poppins', sans-serif; font-size: 12.5px; font-weight: 600;
  cursor: pointer; transition: border-color 0.2s;
}
.btn-outline-sm:hover { border-color: var(--green-mid, #2D6A4F); color: var(--green-mid, #2D6A4F); }

.btn-green-sm {
  display: flex; align-items: center; gap: 7px;
  background: #40916C; color: #fff;
  border: none; border-radius: 10px; padding: 10px 18px;
  font-family: 'Poppins', sans-serif; font-size: 12.5px; font-weight: 700;
  cursor: pointer;
  transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
  box-shadow: 0 2px 10px rgba(64,145,108,0.35);
}
.btn-green-sm:hover {
  background: #2D6A4F;
  transform: translateY(-1px);
  box-shadow: 0 4px 16px rgba(64,145,108,0.4);
}

/* ── SCOPE BANNER ────────────────────────── */
.scope-banner {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 8px 20px;
  background: linear-gradient(135deg, #EBF8F1 0%, #f0faf5 100%);
  border: 1px solid #B7E4CA;
  border-left: 4px solid #52B788;
  border-radius: 10px;
  padding: 11px 18px;
  margin-bottom: 22px;
  font-size: 12.5px;
}

.scope-section {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 6px;
  color: var(--text-gray);
}

.scope-label {
  font-weight: 700;
  color: #2D6A4F;
  font-size: 11.5px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.scope-tag {
  background: #fff;
  color: #2D6A4F;
  border: 1px solid #B7E4CA;
  border-radius: 99px;
  padding: 2px 11px;
  font-size: 11.5px;
  font-weight: 600;
}

.scope-tag.proj {
  background: #fff;
  color: #1D4ED8;
  border-color: #93C5FD;
}

.scope-sep {
  width: 1px;
  height: 18px;
  background: #B7E4CA;
}

/* ── DASH FILTER BAR ─────────────────────── */
.dash-filter-bar {
  display: flex;
  align-items: center;
  gap: 8px;
  background: var(--white);
  border: none;
  border-radius: 14px;
  padding: 11px 18px;
  margin-bottom: 20px;
  flex-wrap: wrap;
  box-shadow: 0 2px 12px rgba(0,0,0,0.06);
}

.dash-filter-title {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 11px;
  font-weight: 700;
  color: var(--text-light);
  text-transform: uppercase;
  letter-spacing: 0.7px;
  flex-shrink: 0;
}

.dash-filter-sep {
  width: 1px;
  height: 20px;
  background: var(--border);
  flex-shrink: 0;
  margin: 0 4px;
}

.dash-filter-chip {
  display: flex;
  align-items: center;
  gap: 7px;
  background: #F4F6F5;
  border: 1.5px solid var(--border);
  border-radius: 10px;
  padding: 7px 12px;
  width: 175px;
  transition: border-color 0.2s, background 0.2s;
  cursor: pointer;
}
.dash-filter-chip:focus-within { border-color: #52B788; background: #EBF8F1; }
.dash-filter-chip.chip-active  { border-color: #52B788; background: #EBF8F1; }
.dash-filter-chip svg { color: #8A9490; flex-shrink: 0; transition: color 0.2s; }
.dash-filter-chip:focus-within svg, .dash-filter-chip.chip-active svg { color: #2D6A4F; }

.dash-filter-select {
  border: none;
  outline: none;
  background: transparent;
  font-family: 'Poppins', sans-serif;
  font-size: 12.5px;
  font-weight: 500;
  color: var(--text-dark);
  cursor: pointer;
  width: 100%;
  appearance: none;
  -webkit-appearance: none;
  padding-right: 18px;
  background-image: url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%238A9490' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 2px center;
}

.dash-filter-apply-btn {
  display: flex;
  align-items: center;
  gap: 6px;
  background: #52B788;
  border: none;
  border-radius: 9px;
  padding: 7px 16px;
  font-family: 'Poppins', sans-serif;
  font-size: 12.5px;
  font-weight: 600;
  color: #fff;
  cursor: pointer;
  transition: background 0.15s, transform 0.1s;
  flex-shrink: 0;
}
.dash-filter-apply-btn:hover:not(:disabled) { background: #40a070; }
.dash-filter-apply-btn:active:not(:disabled) { transform: scale(0.97); }
.dash-filter-apply-btn:disabled { opacity: 0.55; cursor: not-allowed; }

.dash-filter-clear-btn {
  display: flex;
  align-items: center;
  gap: 5px;
  background: none;
  border: 1.5px solid #FC8181;
  border-radius: 9px;
  padding: 6px 13px;
  font-family: 'Poppins', sans-serif;
  font-size: 12px;
  font-weight: 600;
  color: #E53E3E;
  cursor: pointer;
  transition: background 0.15s;
  margin-left: 2px;
}
.dash-filter-clear-btn:hover:not(:disabled) { background: #FFF5F5; }
.dash-filter-clear-btn:disabled { opacity: 0.5; cursor: not-allowed; }

.dash-filter-spin { display: flex; align-items: center; margin-left: 4px; }

/* ── KPI CARDS ───────────────────────────── */
.kpi-grid {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 14px;
  margin-bottom: 22px;
  transition: opacity 0.2s;
}
.kpi-grid.kpi-loading {
  opacity: 0.45;
  pointer-events: none;
}

.kpi-card {
  background: var(--white);
  border: none;
  border-radius: 14px;
  padding: 20px 18px 17px;
  box-shadow: 0 2px 12px rgba(0,0,0,0.06);
  transition: box-shadow 0.2s, transform 0.2s, outline 0.15s;
  position: relative;
  overflow: hidden;
  cursor: pointer;
  user-select: none;
}
.kpi-card::after {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 3px;
  background: linear-gradient(90deg, #52B788, #B7E4CA);
  border-radius: 14px 14px 0 0;
}
.kpi-card:hover {
  box-shadow: 0 8px 28px rgba(0,0,0,0.1);
  transform: translateY(-2px);
}
.kpi-card.kpi-orange { background: linear-gradient(135deg, #c2410c 0%, #fb923c 100%); box-shadow: 0 8px 28px rgba(251,146,60,0.35); }
.kpi-card.kpi-green  { background: linear-gradient(135deg, #2D6A4F 0%, #52B788 100%); box-shadow: 0 8px 28px rgba(45,106,79,0.35); }
.kpi-card.kpi-purple { background: linear-gradient(135deg, #5B21B6 0%, #7C3AED 100%); box-shadow: 0 8px 28px rgba(91,33,182,0.35); }
.kpi-card.kpi-red    { background: linear-gradient(135deg, #991B1B 0%, #EF4444 100%); box-shadow: 0 8px 28px rgba(153,27,27,0.35); }

.kpi-card.kpi-orange::after,
.kpi-card.kpi-green::after,
.kpi-card.kpi-purple::after,
.kpi-card.kpi-red::after { display: none; }

.kpi-card.card-active {
  outline: 2px solid #52B788;
  outline-offset: 2px;
  box-shadow: 0 0 0 5px rgba(82, 183, 136, 0.15), 0 8px 28px rgba(0,0,0,0.1);
}
.kpi-card.kpi-orange.card-active,
.kpi-card.kpi-green.card-active,
.kpi-card.kpi-purple.card-active,
.kpi-card.kpi-red.card-active {
  outline: 2px solid rgba(255,255,255,0.9);
  outline-offset: 2px;
  box-shadow: 0 0 0 5px rgba(255,255,255,0.2), 0 8px 28px rgba(0,0,0,0.2);
}

.kpi-active-dot {
  width: 8px; height: 8px;
  border-radius: 50%;
  background: rgba(255,255,255,0.9);
  animation: pulse-dot 1.5s ease-in-out infinite;
}

@keyframes pulse-dot {
  0%, 100% { opacity: 1; transform: scale(1); }
  50%       { opacity: 0.5; transform: scale(0.75); }
}

/* Filter bar */
.filter-active-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: #EBF8F1;
  border: 1px solid #B7E4CA;
  border-radius: 10px;
  padding: 10px 18px;
  margin-bottom: 16px;
  font-size: 13px;
}

.filter-active-label {
  display: flex;
  align-items: center;
  gap: 8px;
  color: #2D6A4F;
}

.filter-active-label strong { font-weight: 700; }

.filter-clear-btn {
  display: flex; align-items: center; gap: 6px;
  background: #fff;
  border: 1.5px solid #B7E4CA;
  border-radius: 7px;
  padding: 5px 12px;
  font-family: 'Poppins', sans-serif;
  font-size: 12px; font-weight: 600;
  color: #2D6A4F;
  cursor: pointer;
  transition: background 0.15s, border-color 0.15s;
}
.filter-clear-btn:hover:not(:disabled) { background: #D8F3E3; border-color: #52B788; }
.filter-clear-btn:disabled { opacity: 0.6; cursor: not-allowed; }

/* Charts loading dim */
.section-dimmed {
  opacity: 0.45;
  pointer-events: none;
  transition: opacity 0.2s;
}

.kpi-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }

.kpi-icon {
  width: 38px; height: 38px; border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
}
.kpi-icon.green { background: #EBF8F1; }
.kpi-icon.white { background: rgba(255,255,255,.22); }

.kpi-label { font-size: 10px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; margin-bottom: 5px; }
.kpi-label.dark  { color: var(--text-light); }
.kpi-label.light { color: rgba(255,255,255,.75); }

.kpi-value { font-size: 30px; font-weight: 800; line-height: 1; margin-bottom: 5px; }
.kpi-value.dark  { color: #162119; }
.kpi-value.light { color: #fff; }

.kpi-sub { font-size: 11px; }
.kpi-sub.dark   { color: var(--text-light); }
.kpi-sub.light  { color: rgba(255,255,255,.75); }


/* ── CHARTS ROW ──────────────────────────── */
.charts-row {
  display: grid;
  grid-template-columns: 1fr 340px;
  gap: 16px;
  margin-bottom: 16px;
}

.chart-card {
  background: var(--white);
  border: none;
  border-radius: 14px;
  padding: 24px 22px 20px;
  box-shadow: 0 2px 12px rgba(0,0,0,0.06);
}

.chart-title { font-size: 14px; font-weight: 700; margin-bottom: 3px; color: #162119; }
.chart-sub   { font-size: 11.5px; color: var(--text-light); margin-bottom: 18px; }
.chart-wrap  { position: relative; }

.donut-legend {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 6px 14px;
  margin-top: 16px;
}

.legend-item { display: flex; align-items: center; gap: 7px; font-size: 12px; }
.legend-dot  { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
.legend-label { color: var(--text-gray); flex: 1; }
.legend-pct   { font-weight: 700; color: var(--text-dark); }

.line-legend { display: flex; gap: 16px; margin-bottom: 12px; }
.line-legend-item { display: flex; align-items: center; gap: 6px; font-size: 12px; }

/* ── BOTTOM ROW ──────────────────────────── */
.bottom-row {
  display: grid;
  grid-template-columns: 1fr 1.05fr;
  gap: 16px;
}

/* ── TABLE ───────────────────────────────── */
.table-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 4px;
}

.ver-todas {
  font-size: 12.5px; font-weight: 600; color: var(--green-mid);
  text-decoration: none; display: flex; align-items: center; gap: 4px;
  cursor: pointer; transition: gap 0.2s;
}
.ver-todas:hover { gap: 7px; }

.table-sub { font-size: 11.5px; color: var(--text-light); margin-bottom: 14px; }

.table-scroll { overflow-y: auto; max-height: 320px; }

table { width: 100%; border-collapse: collapse; }

thead th {
  font-size: 10.5px; font-weight: 700; color: var(--text-light);
  text-align: left; padding: 10px 10px;
  background: #F7FAF8;
  border-bottom: 1px solid var(--border);
  text-transform: uppercase; letter-spacing: 0.5px;
  position: sticky; top: 0; z-index: 1;
}

tbody tr { transition: background 0.12s; }
tbody tr.row-clickable { cursor: pointer; }
tbody tr.row-clickable:hover { background: #F3FAF6; }
tbody tr.row-clickable:hover .citizen-name { color: var(--green-mid); }

tbody td {
  padding: 10px; font-size: 12.5px;
  border-bottom: 1px solid #F0F4F2; vertical-align: middle;
}

.citizen-cell { display: flex; align-items: center; gap: 9px; }

.citizen-avatar {
  width: 30px; height: 30px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 11px; font-weight: 800; color: #fff; flex-shrink: 0;
}

.citizen-name { font-size: 12.5px; font-weight: 600; line-height: 1.2; }
.citizen-code { font-size: 10.5px; color: var(--text-light); }

.cat-cell { display: flex; align-items: center; gap: 6px; font-size: 12.5px; }

.td-provincia { font-size: 12.5px; }
.td-tempo     { font-size: 12px; color: var(--text-light); }

.badge-status {
  display: inline-block; font-size: 11px; font-weight: 700;
  padding: 3px 9px; border-radius: 99px;
}
.badge-status.por-validar,
.badge-status.pendente    { background: #FB923C; color: #fff;    }
.badge-status.por-resolver,
.badge-status.analise     { background: #FACC15; color: #713F12; }
.badge-status.resolvendo  { background: #3b82f6; color: #fff;    }
.badge-status.resolvido,
.badge-status.resolvida   { background: #22C55E; color: #fff;    }
.badge-status.nao-validado,
.badge-status.rejeitada   { background: #EF4444; color: #fff;    }
.badge-status.encerrada   { background: #6B7280; color: #fff;    }

.empty-row { text-align: center; color: var(--text-light); padding: 28px; font-size: 13px; }

/* ── FOOTER ──────────────────────────────── */
.dash-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 13px 30px;
  background: var(--white);
  border-top: 1px solid var(--border);
  font-size: 11.5px;
  color: var(--text-light);
  flex-shrink: 0;
}

.dash-footer a {
  color: var(--text-light);
  text-decoration: none;
  margin-left: 16px;
  transition: color 0.2s;
}
.dash-footer a:hover { color: var(--green-mid); }

/* ── DRAWER ──────────────────────────────── */
.drawer-overlay {
  position: fixed; inset: 0;
  background: rgba(0,0,0,.35);
  z-index: 100;
  backdrop-filter: blur(2px);
}

.drawer-panel {
  position: fixed;
  top: 50%; left: 50%;
  transform: translate(-50%, -50%);
  width: 680px;
  max-height: 88vh;
  background: var(--white);
  z-index: 101;
  display: flex;
  flex-direction: column;
  box-shadow: 0 20px 60px rgba(0,0,0,.18);
  border-radius: 16px;
  overflow: hidden;
}

.drawer-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  padding: 22px 26px 18px;
  border-bottom: 1px solid var(--border);
  flex-shrink: 0;
}

.drawer-title    { font-size: 17px; font-weight: 800; color: var(--text-dark); margin-bottom: 3px; }
.drawer-subtitle { font-size: 12px; color: var(--text-light); }

.drawer-close {
  width: 32px; height: 32px; border-radius: 8px;
  border: 1.5px solid var(--border);
  background: transparent;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; color: var(--text-gray);
  transition: background 0.15s, border-color 0.15s;
  flex-shrink: 0;
}
.drawer-close:hover { background: #F4F6F5; border-color: var(--green-light); color: var(--green-mid); }

.drawer-body {
  flex: 1;
  overflow-y: auto;
  padding: 22px 26px 32px;
}
.drawer-body::-webkit-scrollbar { width: 4px; }
.drawer-body::-webkit-scrollbar-thumb { background: #C8D8CE; border-radius: 99px; }

/* drawer transitions */
.fade-enter-active, .fade-leave-active { transition: opacity 0.25s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.modal-pop-enter-active, .modal-pop-leave-active {
  transition: opacity 0.22s ease, transform 0.25s cubic-bezier(.4,0,.2,1);
}
.modal-pop-enter-from, .modal-pop-leave-to {
  opacity: 0; transform: translate(-50%, -46%) scale(0.96);
}

/* ── FORM FIELDS ─────────────────────────── */
.f-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
  margin-bottom: 16px;
}

.f-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
  margin-bottom: 16px;
}

.f-row .f-group { margin-bottom: 0; }

.f-group label { font-size: 12.5px; font-weight: 600; color: var(--text-dark); }

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
  box-sizing: border-box;
  transition: border-color 0.2s, box-shadow 0.2s;
}

.f-group input::placeholder,
.f-group textarea::placeholder { color: var(--text-light); }

.f-group input:focus,
.f-group select:focus,
.f-group textarea:focus {
  border-color: var(--green-light);
  box-shadow: 0 0 0 3px rgba(82,183,136,.15);
}

.f-group select {
  cursor: pointer;
  padding-right: 36px;
  background-image: url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%238A9490' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 13px center;
}

.f-group textarea { resize: vertical; min-height: 110px; }

.f-err { border-color: #FC8181 !important; box-shadow: 0 0 0 3px rgba(252,129,129,.12) !important; }
.f-err-msg { font-size: 11.5px; color: #E53E3E; }
.f-req { color: #E53E3E; margin-left: 2px; }

.date-input-wrap { position: relative; }
.date-input-wrap input[type="date"].is-empty:not(:focus)::-webkit-datetime-edit {
  color: transparent;
}
.date-input-wrap .date-placeholder {
  position: absolute;
  left: 13px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 13px;
  color: var(--text-light);
  pointer-events: none;
}
.date-input-wrap:focus-within .date-placeholder { display: none; }

/* upload */
.upload-zone {
  border: 2px dashed var(--border);
  border-radius: 10px;
  padding: 28px 20px;
  text-align: center;
  cursor: pointer;
  background: #F7F9F8;
  transition: border-color 0.2s, background 0.2s;
  margin-bottom: 16px;
}
.upload-zone:hover, .upload-zone.drag-over {
  border-color: var(--green-light); background: #EEF7F1;
}
.upload-icon-wrap {
  width: 40px; height: 40px; border-radius: 10px;
  border: 1px solid var(--border); background: var(--white);
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 10px;
}
.upload-zone h4 { font-size: 13px; font-weight: 600; margin-bottom: 3px; color: var(--text-dark); }
.upload-zone p  { font-size: 11.5px; color: var(--text-light); }

.file-list { display: flex; flex-direction: column; gap: 8px; margin-bottom: 16px; }

.file-item {
  display: flex; align-items: center; gap: 9px;
  background: #EEF7F1; border: 1px solid #C3E6CE;
  border-radius: 8px; padding: 8px 12px;
}
.file-item-name { font-size: 12.5px; font-weight: 500; color: #2D6A4F; flex: 1; }
.file-item-size { font-size: 11px; color: var(--text-light); }

.btn-rm {
  background: none; border: none; cursor: pointer;
  color: var(--text-light); display: flex; transition: color 0.2s; padding: 0;
}
.btn-rm:hover { color: #E53E3E; }

/* form actions */
.f-actions { display: flex; align-items: center; gap: 12px; margin-top: 8px; }

.btn-registar {
  display: inline-flex; align-items: center; gap: 8px;
  background: #40916C; color: #fff; border: none;
  border-radius: 10px; padding: 12px 26px;
  font-family: 'Poppins', sans-serif; font-size: 13.5px; font-weight: 700;
  cursor: pointer;
  transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
  box-shadow: 0 3px 12px rgba(64,145,108,0.35);
}
.btn-registar:hover {
  background: #2D6A4F;
  transform: translateY(-1px);
  box-shadow: 0 6px 18px rgba(64,145,108,0.4);
}
.btn-registar:disabled { background: #A0C4B0; cursor: not-allowed; transform: none; box-shadow: none; }

.btn-cancelar {
  display: inline-flex; align-items: center; gap: 8px;
  background: transparent; color: #E53E3E;
  border: 1.5px solid #FC8181; border-radius: 9px; padding: 9px 20px;
  font-family: 'Poppins', sans-serif; font-size: 13.5px; font-weight: 600;
  cursor: pointer; transition: background 0.2s, border-color 0.2s;
}
.btn-cancelar:hover { background: #FFF5F5; border-color: #E53E3E; }

.error-banner {
  background: #FFF5F5; border: 1px solid #FEB2B2;
  border-radius: 10px; padding: 12px 16px;
  display: flex; align-items: center; gap: 10px;
  font-size: 12.5px; font-weight: 500; color: #C53030;
  margin-bottom: 18px;
}

.contact-note {
  display: flex; align-items: flex-start; gap: 7px;
  font-size: 11.5px; color: var(--text-gray);
  line-height: 1.55; padding-top: 6px;
}

.char-hint { font-size: 11.5px; margin-top: 3px; }
.char-warn { color: #C66E00; }
.char-ok   { color: #2D6A4F; }

/* ── TOAST ───────────────────────────────── */
.dash-toast {
  position: fixed; bottom: 24px; right: 24px; z-index: 600;
  background: var(--green-dark); color: #fff;
  border-radius: 12px; padding: 13px 20px;
  display: flex; align-items: center; gap: 9px;
  font-size: 13px; font-weight: 600;
  box-shadow: 0 8px 28px rgba(0,0,0,.18);
  max-width: 440px;
}

.toast-anim-enter-active, .toast-anim-leave-active { transition: opacity 0.3s ease, transform 0.3s ease; }
.toast-anim-enter-from, .toast-anim-leave-to { opacity: 0; transform: translateY(12px); }

@keyframes spin { to { transform: rotate(360deg); } }
.spin { animation: spin 0.7s linear infinite; }

/* ── DEMOGRAPHICS ROW ────────────────────── */
.demo-row {
  display: grid;
  grid-template-columns: 1fr 2fr;
  gap: 16px;
  margin-top: 16px;
  margin-bottom: 8px;
}

.pie-center-wrap {
  position: relative;
  width: 120px;
  height: 120px;
  margin: 12px auto 0;
}

.donut-center {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
  pointer-events: none;
}

.donut-total {
  font-size: 18px;
  font-weight: 800;
  color: #162119;
  line-height: 1;
}

.donut-total-label {
  font-size: 9.5px;
  color: #8A9490;
  text-transform: uppercase;
  letter-spacing: 0.6px;
  margin-top: 2px;
}

.gender-legend {
  display: flex;
  flex-direction: column;
  gap: 8px;
  margin-top: 18px;
}

.gender-legend-item {
  display: flex;
  align-items: center;
  gap: 9px;
  padding: 9px 12px;
  background: #F7FAF8;
  border-radius: 9px;
}

.pie-dot {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  flex-shrink: 0;
}

.gender-leg-label {
  flex: 1;
  font-size: 12.5px;
  color: var(--text-gray);
  font-weight: 500;
}

.gender-leg-val {
  font-size: 14px;
  font-weight: 800;
  color: var(--text-dark);
}

.gender-leg-pct {
  font-size: 11px;
  font-weight: 600;
  color: #2D6A4F;
  background: #EBF8F1;
  padding: 2px 9px;
  border-radius: 99px;
}

.pie-legend-empty {
  font-size: 12px;
  color: var(--text-light);
  text-align: center;
  padding: 16px 0;
}

/* ── CANAL & PROJECTOS ROW ───────────────── */
.demo-row-2 {
  display: grid;
  grid-template-columns: 1fr 2fr;
  gap: 16px;
  margin-top: 16px;
  margin-bottom: 8px;
}

.project-leader {
  display: flex;
  align-items: center;
  gap: 8px;
  background: linear-gradient(135deg, #EBF8F1, #F4FBF7);
  border: 1px solid #B7E4CA;
  border-radius: 9px;
  padding: 9px 14px;
  font-size: 12.5px;
  color: #2D6A4F;
}
.project-leader svg { flex-shrink: 0; }
.project-leader strong { font-weight: 800; }

.chart-card-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 10px;
}

.total-chip {
  flex-shrink: 0;
  font-size: 11.5px;
  font-weight: 700;
  color: #2D6A4F;
  background: #EBF8F1;
  border: 1px solid #B7E4CA;
  padding: 5px 12px;
  border-radius: 99px;
  white-space: nowrap;
}

.polar-wrap {
  position: relative;
  width: 150px;
  height: 150px;
  margin: 10px auto 0;
}

/* ── PROJECT RANKING LIST ────────────────── */
.rank-list {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-top: 16px;
}

.rank-item {
  display: flex;
  align-items: center;
  gap: 12px;
}

.rank-badge {
  flex-shrink: 0;
  width: 26px;
  height: 26px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  font-weight: 800;
  color: #fff;
  background: #9CA9A2;
}
.rank-badge.rank-1 { background: linear-gradient(135deg, #F5C453, #E0A724); }
.rank-badge.rank-2 { background: linear-gradient(135deg, #C9D3DC, #9AAAB8); }
.rank-badge.rank-3 { background: linear-gradient(135deg, #E3A876, #C77F44); }

.rank-info { flex: 1; min-width: 0; }

.rank-name-row {
  display: flex;
  align-items: baseline;
  justify-content: space-between;
  gap: 8px;
  margin-bottom: 5px;
}

.rank-name {
  font-size: 12.5px;
  font-weight: 600;
  color: var(--text-dark);
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.rank-count {
  flex-shrink: 0;
  font-size: 12.5px;
  font-weight: 800;
  color: #162119;
}

.rank-bar-track {
  height: 7px;
  border-radius: 99px;
  background: #F0F4F2;
  overflow: hidden;
}

.rank-bar-fill {
  height: 100%;
  border-radius: 99px;
  transition: width 0.6s ease;
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

  .charts-row { grid-template-columns: 1fr; }
  .bottom-row { grid-template-columns: 1fr; }
  .demo-row { grid-template-columns: 1fr; }
  .demo-row-2 { grid-template-columns: 1fr; }
  .drawer-panel { width: 92vw; max-width: 620px; }
}

/* ── Responsive: page content ───────────────── */
@media (max-width: 768px) {
  .kpi-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 640px) {
  .kpi-grid { grid-template-columns: 1fr; }

  .content { padding: 18px 16px 26px; }

  .topbar { padding: 0 16px; gap: 10px; }
  .search-wrap { max-width: none; }

  .page-title-row {
    flex-direction: column;
    align-items: stretch;
    gap: 12px;
  }
  .title-actions { flex-wrap: wrap; }
  .title-actions .btn-outline-sm,
  .title-actions .btn-green-sm { flex: 1; justify-content: center; }

  .dash-filter-bar { padding: 12px; }
  .dash-filter-chip { width: 100%; }
  .dash-filter-sep { display: none; }
  .dash-filter-apply-btn,
  .dash-filter-clear-btn { flex: 1; justify-content: center; }

  .donut-legend { grid-template-columns: 1fr; }

  .table-scroll { overflow-x: auto; }
  table { min-width: 560px; }

  .f-row { grid-template-columns: 1fr; }

  .drawer-panel {
    width: 100%;
    max-width: 100vw;
    max-height: 100vh;
    height: 100vh;
    top: 0; left: 0;
    transform: none;
    border-radius: 0;
  }
  .modal-pop-enter-from, .modal-pop-leave-to {
    opacity: 0; transform: translateY(12px);
  }

  .dash-footer { flex-direction: column; gap: 6px; padding: 13px 16px; text-align: center; }
}
</style>
