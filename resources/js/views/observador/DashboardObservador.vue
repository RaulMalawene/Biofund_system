<template>
  <div class="app-shell">

    <!-- ── SIDEBAR ── -->
    <aside class="sidebar">
      <router-link to="/" class="sidebar-logo">
        <img src="../../Imagem/logotipo.png" alt="Biofund" class="sidebar-logo-img"/>
      </router-link>

      <nav class="sidebar-nav">
        <a class="nav-item active">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <rect x="1" y="1" width="6" height="6" rx="1.5"/><rect x="9" y="1" width="6" height="6" rx="1.5"/>
            <rect x="1" y="9" width="6" height="6" rx="1.5"/><rect x="9" y="9" width="6" height="6" rx="1.5"/>
          </svg>
          Dashboard
        </a>
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

    <!-- ── MAIN ── -->
    <div class="main">

      <!-- TOP BAR -->
      <header class="topbar">
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
            <h1>Dashboard do Observador</h1>
            <p>Visão geral, em modo de consulta, das ocorrências nas províncias e projectos atribuídos.</p>
          </div>
        </div>

        <!-- Âmbito do observador -->
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
            </div>
            <div class="table-sub">Últimas 10 ocorrências nas suas províncias e projectos</div>
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
                    @click="selectRow(row)"
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
        <span>© 2026 BIOFUND Observador · Sistema de Gestão Ambiental de Moçambique</span>
        <div>
          <a href="#">Suporte Técnico</a>
          <a href="#">Privacidade</a>
        </div>
      </footer>
    </div>

    <!-- ── MODAL DETALHES (só leitura) ── -->
    <transition name="modal-fade">
      <div class="modal-overlay" v-if="showModal" @click.self="closeModal">
        <div class="modal-card">

          <div class="modal-hd">
            <div class="modal-hd-left">
              <div class="modal-hd-id">{{ selected ? selected.id : 'Detalhes da Ocorrência' }}</div>
              <div class="modal-hd-cat" v-if="selected">{{ selected.categoria }} · {{ selected.projeto }}</div>
            </div>
            <div class="modal-hd-right">
              <span v-if="selected" class="badge-status" :class="statusClass(selected.status)">{{ selected.status_label }}</span>
              <button class="btn-close-modal" @click="closeModal">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 14 14">
                  <path d="M2 2l10 10M12 2L2 12" stroke-linecap="round" />
                </svg>
              </button>
            </div>
          </div>

          <div class="modal-body">

            <div v-if="modalLoading" class="modal-loading">
              <svg class="spin" width="22" height="22" fill="none" stroke="#52B788" stroke-width="2.2" viewBox="0 0 16 16">
                <path d="M8 2a6 6 0 0 1 6 6" stroke-linecap="round"/>
              </svg>
              A carregar detalhes…
            </div>

            <template v-else-if="selected">
              <div class="modal-hero-wrap">
                <img v-if="selected.foto" :src="selected.foto" class="modal-hero" alt="Evidência principal" />
                <div v-else class="modal-hero-empty">
                  <svg width="28" height="28" fill="none" stroke="#C8D8CE" stroke-width="1.5" viewBox="0 0 28 28">
                    <rect x="2" y="5" width="24" height="18" rx="2.5" />
                    <path d="M2 18l6-6 5 5 4-4 7 7" />
                    <circle cx="20" cy="11" r="2.5" fill="#C8D8CE" stroke="none" />
                  </svg>
                  <span>Sem evidência fotográfica principal</span>
                </div>
                <div class="modal-hero-meta">
                  <span class="badge-status" :class="statusClass(selected.status)">{{ selected.status_label }}</span>
                  <span class="modal-hero-date">{{ selected.data }}</span>
                </div>
              </div>

              <div class="modal-meta-strip">
                <div class="modal-meta-item">
                  <span class="modal-meta-label">Canal</span>
                  <span class="modal-meta-val">{{ selected.canal }}</span>
                </div>
                <div class="modal-meta-item">
                  <span class="modal-meta-label">Projecto</span>
                  <span class="modal-meta-val">{{ selected.projeto }}</span>
                </div>
                <div class="modal-meta-item">
                  <span class="modal-meta-label">Categoria</span>
                  <span class="modal-meta-val">{{ selected.categoria }}</span>
                </div>
                <div class="modal-meta-item">
                  <span class="modal-meta-label">Localização</span>
                  <span class="modal-meta-val">{{ selected.provincia }}</span>
                </div>
              </div>

              <div class="modal-section">
                <div class="modal-section-hd">
                  <svg width="13" height="13" fill="none" stroke="var(--green-mid)" stroke-width="1.7" viewBox="0 0 14 14">
                    <circle cx="7" cy="7" r="5.5" />
                    <path d="M7 5v2.5M7 9.5h.01" stroke-linecap="round" stroke-width="1.8" />
                  </svg>
                  Descrição Completa da Ocorrência
                </div>
                <div class="modal-desc">"{{ selected.descricao }}"</div>
              </div>

              <div class="modal-section modal-two-col">
                <div class="modal-info-block">
                  <div class="modal-info-label">Localização GPS</div>
                  <div class="modal-info-val">{{ selected.provincia }}</div>
                  <div class="modal-info-sub">{{ selected.coords }}</div>
                </div>
                <div class="modal-info-block">
                  <div class="modal-info-label">Pessoa Afectada</div>
                  <div class="modal-info-val">{{ selected.denunciante ?? 'Anónimo' }}</div>
                  <div class="modal-info-sub" v-if="selected.email_afectado">{{ selected.email_afectado }}</div>
                  <div class="modal-info-sub" v-if="selected.telefone">{{ selected.telefone }}</div>
                  <div class="modal-info-sub" v-if="selected.sexo">{{ selected.sexo === 'masculino' ? 'Masculino' : 'Feminino' }}</div>
                  <div class="modal-info-sub" v-if="selected.idade">{{ selected.idade }}</div>
                  <div class="modal-info-sub" v-if="selected.registado_por">Registado por: {{ selected.registado_por }}</div>
                </div>
              </div>

              <div class="modal-section modal-two-col"
                v-if="selected.subcategoria || selected.tipo_ocorrencia || selected.nivel_alerta || selected.distrito || selected.responsavel || selected.origem || selected.prazo || selected.revisado_por">
                <div class="modal-info-block">
                  <div class="modal-info-label">Classificação Adicional</div>
                  <div class="modal-info-val">{{ selected.subcategoria ?? 'Sem subcategoria' }}</div>
                  <div class="modal-info-sub" v-if="selected.tipo_ocorrencia">Tipo: {{ selected.tipo_ocorrencia }}</div>
                  <div class="modal-info-sub" v-if="selected.nivel_alerta">Nível de Alerta: {{ selected.nivel_alerta }}</div>
                  <div class="modal-info-sub" v-if="selected.distrito">Distrito: {{ selected.distrito }}</div>
                  <div class="modal-info-sub" v-if="selected.origem">Origem: {{ selected.origem }}</div>
                  <div class="modal-info-sub" v-if="selected.responsavel">Responsável: {{ selected.responsavel }}</div>
                </div>
                <div class="modal-info-block">
                  <div class="modal-info-label">Prazo &amp; Revisão</div>
                  <div class="modal-info-val" :class="{ 'overdue-text': selected.atrasada }">{{ selected.prazo ?? 'Sem prazo definido' }}</div>
                  <div class="modal-info-sub" v-if="selected.data_revisao">Revisado em: {{ selected.data_revisao }}</div>
                  <div class="modal-info-sub" v-if="selected.revisado_por">Revisado por: {{ selected.revisado_por }}</div>
                </div>
              </div>

              <div class="modal-section">
                <div class="modal-section-hd">
                  <svg width="13" height="13" fill="none" stroke="var(--green-mid)" stroke-width="1.7" viewBox="0 0 14 14">
                    <path d="M11.5 6.5L6 12a4.243 4.243 0 0 1-6-6l6-6a2.828 2.828 0 0 1 4 4L4 10a1.414 1.414 0 0 1-2-2L8 2" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  Anexos Submetidos
                  <span class="section-count">{{ selected.anexos?.length ?? 0 }}</span>
                </div>

                <div v-if="!selected.anexos?.length" class="no-attachments">
                  <svg width="22" height="22" fill="none" stroke="#C8D8CE" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z" />
                    <path d="M13 2v7h7" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <span>Nenhum ficheiro foi anexado a esta ocorrência.</span>
                </div>

                <div v-if="imageAnexos.length" class="attachment-gallery">
                  <div v-for="(a, i) in imageAnexos" :key="'img-' + i" class="attachment-thumb"
                    @click="lightboxImg = a.url">
                    <img :src="a.url" :alt="a.nome" />
                    <div class="attachment-thumb-overlay">
                      <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 16 16">
                        <circle cx="7" cy="7" r="4.5" />
                        <path d="M11 11l3 3" stroke-linecap="round" />
                      </svg>
                    </div>
                    <button class="btn-img-dl" @click.stop="downloadAnexo(a)" title="Descarregar">
                      <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 14 14">
                        <path d="M7 2v8M3 7l4 5 4-5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M2 12h10" stroke-linecap="round" />
                      </svg>
                    </button>
                    <div class="attachment-thumb-name">{{ a.nome }}</div>
                  </div>
                </div>

                <div v-if="docAnexos.length" class="attachment-doc-list">
                  <div v-for="(a, i) in docAnexos" :key="'doc-' + i" class="attachment-doc-row">
                    <div class="doc-icon-box" :class="'doc-' + a.tipo">
                      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
                        <path d="M10 2H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V5L10 2z" />
                        <path d="M10 2v3h3" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M5 7h6M5 10h4" stroke-linecap="round" />
                      </svg>
                      <span class="doc-ext">{{ a.tipo.toUpperCase() }}</span>
                    </div>
                    <div class="doc-meta">
                      <div class="doc-name">{{ a.nome }}</div>
                      <div class="doc-size" v-if="a.tamanho">{{ a.tamanho }}</div>
                    </div>
                    <button class="btn-doc-open" @click="downloadAnexo(a)">
                      <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 14 14">
                        <path d="M7 2v8M3 7l4 5 4-5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M2 12h10" stroke-linecap="round" />
                      </svg>
                      Descarregar
                    </button>
                  </div>
                </div>
              </div>
            </template>
          </div>

          <div class="modal-footer">
            <div class="modal-footer-info">
              <svg width="12" height="12" fill="none" stroke="var(--text-light)" stroke-width="1.6" viewBox="0 0 12 12">
                <rect x="1" y="1.5" width="10" height="9.5" rx="1.5" />
                <path d="M4 1v1.5M8 1v1.5M1 5h10" stroke-linecap="round" />
              </svg>
              {{ selected ? `Submetido em ${selected.data}` : '' }}
            </div>
            <button class="btn-modal-close" @click="closeModal">Fechar</button>
          </div>

        </div>
      </div>
    </transition>

    <!-- LIGHTBOX -->
    <transition name="lightbox-fade">
      <div class="lightbox-overlay" v-if="lightboxImg" @click="lightboxImg = null">
        <img :src="lightboxImg" class="lightbox-img" @click.stop />
        <button class="lightbox-close" @click="lightboxImg = null">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 14 14">
            <path d="M2 2l10 10M12 2L2 12" stroke-linecap="round" />
          </svg>
        </button>
      </div>
    </transition>

  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, onActivated, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { Chart, registerables } from 'chart.js'
import { useAuthStore } from '@/stores/auth'
import { InternalService } from '@/api/services/internal.service'
import AdminProfilePanel from '@/components/AdminProfilePanel.vue'
import AdminNotificationPanel from '@/components/AdminNotificationPanel.vue'

Chart.register(...registerables)

const router = useRouter()
const auth   = useAuthStore()

async function handleLogout() {
  await auth.logout()
  router.push('/acessoRestrito')
}

// Âmbito do observador (províncias e projectos atribuídos)
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

// ── Categorias (para o filtro de dashboard) ────────────────────
const refCategories = ref([])

// ── Filtros de dashboard (ano / província / projecto) ──────────
const dashFilter = reactive({ year: '', province_id: '', project_id: '', category_id: '' })
const filterYears = computed(() => {
  const y = new Date().getFullYear()
  return Array.from({ length: y - 2019 }, (_, i) => y - i)
})
const hasActiveDashFilter = computed(() =>
  !!(dashFilter.year || dashFilter.province_id || dashFilter.project_id || dashFilter.category_id)
)
function buildDashFilter() {
  const p = {}
  if (dashFilter.year)        p.year        = dashFilter.year
  if (dashFilter.province_id) p.province_id = dashFilter.province_id
  if (dashFilter.project_id)  p.project_id  = dashFilter.project_id
  if (dashFilter.category_id) p.category_id = dashFilter.category_id
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
  por_validar:   { label: 'Por Validar',   cls: 'por-validar'   },
  por_resolver:  { label: 'Por Resolver',  cls: 'por-resolver'  },
  resolvendo:    { label: 'Resolvendo',    cls: 'resolvendo'    },
  resolvido:     { label: 'Resolvido',     cls: 'resolvido'     },
  nao_validado:  { label: 'Não Validado',  cls: 'nao-validado'  },
  nao_resolvida: { label: 'Não Resolvida', cls: 'nao-resolvida' },
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

// ── MODAL DE DETALHES (só leitura) ──────────────────────────────
const selected    = ref(null)
const showModal   = ref(false)
const modalLoading = ref(false)
const lightboxImg = ref(null)

function statusClass(s) {
  return STATUS_MAP[s]?.cls ?? 'por-validar'
}

function mapOccurrence(o) {
  return {
    _id: o.id,
    id: o.tracking_code,
    data: o.submitted_at,
    provincia: o.province?.name ?? '-',
    categoria: o.category?.name ?? '-',
    canal: o.submission_channel_label ?? '-',
    projeto: o.project?.name ?? '-',
    status: o.status,
    status_label: o.status_label,
    origem: o.origin_label ?? null,
    coords: o.location_detail ?? '',
    denunciante: o.complainant?.name ?? null,
    email_afectado: o.complainant?.email ?? null,
    telefone: o.complainant?.phone ?? null,
    sexo: o.complainant?.gender ?? null,
    idade: o.complainant?.age ?? null,
    responsavel: o.assigned_to?.name ?? null,
    registado_por: o.submitted_by?.name ?? null,
    subcategoria: o.subcategory?.name ?? null,
    tipo_ocorrencia: o.type?.name ?? null,
    nivel_alerta: o.alert_type_label ?? null,
    distrito: o.district?.name ?? null,
    prazo: o.due_date ?? null,
    atrasada: o.is_overdue ?? false,
    data_revisao: o.reviewed_at ?? null,
    revisado_por: o.reviewed_by ?? null,
    descricao: o.description ?? '',
    foto: null,
    anexos: [],
  }
}

async function selectRow(row) {
  if (!row._id) return
  showModal.value   = true
  modalLoading.value = true
  selected.value    = null
  try {
    const res  = await InternalService.getOccurrence(row._id)
    const full = res.data ?? res
    const mapped = mapOccurrence(full)

    const rawAnexos = (full.attachments ?? []).map(a => ({
      _attId: a.id,
      tipo: a.is_image ? 'imagem' : (a.name.split('.').pop().toLowerCase()),
      nome: a.name,
      url: a.url ?? '',
      tamanho: a.size ?? '',
    }))

    const anexos = await Promise.all(rawAnexos.map(async (a) => {
      if (a.tipo === 'imagem') {
        try { a.url = await InternalService.getAttachmentBlobUrl(full.id, a._attId) } catch { }
      }
      return a
    }))

    mapped.anexos = anexos
    mapped.foto   = anexos.find(a => a.tipo === 'imagem')?.url ?? null

    selected.value = mapped
  } catch (err) {
    console.error('Erro ao carregar detalhes:', err)
    showModal.value = false
  } finally {
    modalLoading.value = false
  }
}

function closeModal() {
  showModal.value = false
  selected.value  = null
}

async function downloadAnexo(a) {
  try {
    const blobUrl = await InternalService.getAttachmentBlobUrl(selected.value._id, a._attId)
    const link = document.createElement('a'); link.href = blobUrl; link.download = a.nome; link.click()
    setTimeout(() => URL.revokeObjectURL(blobUrl), 60000)
  } catch (err) {
    console.error('Erro ao descarregar o ficheiro:', err)
  }
}

const imageAnexos = computed(() => selected.value?.anexos?.filter(a => a.tipo === 'imagem') ?? [])
const docAnexos   = computed(() => selected.value?.anexos?.filter(a => a.tipo !== 'imagem') ?? [])

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

  InternalService.getFormData()
    .then(data => { refCategories.value = data.categories ?? [] })
    .catch(err => console.error('Erro ao carregar categorias:', err))

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
.badge-status.nao-resolvida { background: #7C3AED; color: #fff;  }
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

/* drawer/modal transitions */
.fade-enter-active, .fade-leave-active { transition: opacity 0.25s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

@keyframes spin { to { transform: rotate(360deg); } }
.spin { animation: spin 0.7s linear infinite; }

/* ── MODAL DETALHES ──────────────────────── */
.modal-overlay {
  position: fixed;
  inset: 0;
  z-index: 200;
  background: rgba(8, 24, 16, .6);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 24px;
}

.modal-card {
  background: var(--white);
  border-radius: 20px;
  width: 100%;
  max-width: 680px;
  max-height: 90vh;
  display: flex;
  flex-direction: column;
  box-shadow: 0 32px 80px rgba(0, 0, 0, .28);
  overflow: hidden;
}

.modal-hd {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  padding: 18px 22px 16px;
  border-bottom: 1px solid var(--border);
  flex-shrink: 0;
}

.modal-hd-left {
  display: flex;
  flex-direction: column;
  gap: 3px;
}

.modal-hd-id {
  font-size: 16px;
  font-weight: 800;
  color: var(--text-dark);
}

.modal-hd-cat {
  font-size: 12px;
  color: var(--text-gray);
}

.modal-hd-right {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-shrink: 0;
}

.btn-close-modal {
  width: 32px;
  height: 32px;
  background: #F4F6F5;
  border: 1.5px solid var(--border);
  border-radius: 9px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  color: var(--text-gray);
}

.btn-close-modal:hover {
  background: #FFF5F5;
  border-color: #FC8181;
  color: #E53E3E;
}

.modal-body {
  overflow-y: auto;
  flex: 1;
  min-height: 0;
}

.modal-body::-webkit-scrollbar {
  width: 4px;
}

.modal-body::-webkit-scrollbar-thumb {
  background: #C8D8CE;
  border-radius: 99px;
}

.modal-loading {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 60px 20px;
  color: var(--text-light);
  font-size: 13px;
}

.modal-hero-wrap {
  position: relative;
}

.modal-hero {
  width: 100%;
  height: 230px;
  object-fit: cover;
  display: block;
}

.modal-hero-empty {
  width: 100%;
  height: 100px;
  background: #F4F6F5;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 8px;
  color: var(--text-light);
  font-size: 12.5px;
}

.modal-hero-meta {
  position: absolute;
  bottom: 12px;
  left: 14px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.modal-hero-date {
  background: rgba(0, 0, 0, .5);
  color: #fff;
  border-radius: 99px;
  padding: 3px 11px;
  font-size: 11px;
  font-weight: 600;
  backdrop-filter: blur(4px);
}

.modal-meta-strip {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  border-bottom: 1px solid var(--border);
}

.modal-meta-item {
  padding: 12px 16px;
  border-right: 1px solid var(--border);
  display: flex;
  flex-direction: column;
  gap: 3px;
}

.modal-meta-item:last-child {
  border-right: none;
}

.modal-meta-label {
  font-size: 10px;
  font-weight: 700;
  color: var(--text-light);
  text-transform: uppercase;
  letter-spacing: 0.4px;
}

.modal-meta-val {
  font-size: 13px;
  font-weight: 600;
  color: var(--text-dark);
}

.modal-section {
  padding: 16px 22px;
  border-bottom: 1px solid var(--border);
}

.modal-section:last-child {
  border-bottom: none;
}

.modal-section-hd {
  display: flex;
  align-items: center;
  gap: 7px;
  font-size: 12.5px;
  font-weight: 700;
  color: var(--text-dark);
  margin-bottom: 12px;
}

.section-count {
  margin-left: 4px;
  background: var(--green-pale);
  color: var(--green-mid);
  border: 1.5px solid var(--green-light);
  border-radius: 99px;
  padding: 1px 8px;
  font-size: 11px;
  font-weight: 700;
}

.modal-desc {
  background: #F4F6F5;
  border-radius: 10px;
  padding: 14px 16px;
  font-size: 13.5px;
  color: var(--text-gray);
  line-height: 1.8;
  font-style: italic;
  border-left: 3px solid var(--green-light);
}

.modal-two-col {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0;
}

.modal-info-block {
  padding: 0 16px 0 0;
}

.modal-info-block+.modal-info-block {
  padding: 0 0 0 16px;
  border-left: 1px solid var(--border);
}

.modal-info-label {
  font-size: 10px;
  font-weight: 700;
  color: var(--text-light);
  text-transform: uppercase;
  letter-spacing: 0.4px;
  margin-bottom: 5px;
}

.modal-info-val {
  font-size: 13px;
  font-weight: 600;
  color: var(--text-dark);
  margin-bottom: 2px;
}

.modal-info-sub {
  font-size: 11px;
  color: var(--text-gray);
}

.overdue-text {
  color: #C05621;
  font-weight: 600;
}

.no-attachments {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 14px 16px;
  background: #F9FBFA;
  border: 1.5px dashed var(--border);
  border-radius: 10px;
  color: var(--text-light);
  font-size: 12.5px;
}

.attachment-gallery {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 10px;
  margin-bottom: 12px;
}

.attachment-thumb {
  position: relative;
  border-radius: 10px;
  overflow: hidden;
  cursor: pointer;
  background: #F4F6F5;
  border: 1.5px solid var(--border);
  transition: transform 0.18s, box-shadow 0.18s;
}

.attachment-thumb:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 18px rgba(0, 0, 0, .14);
}

.attachment-thumb img {
  width: 100%;
  height: 90px;
  object-fit: cover;
  display: block;
}

.attachment-thumb-overlay {
  position: absolute;
  inset: 0;
  background: rgba(0, 0, 0, .38);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.18s;
}

.attachment-thumb:hover .attachment-thumb-overlay {
  opacity: 1;
}

.attachment-thumb-name {
  padding: 5px 7px;
  font-size: 10px;
  font-weight: 600;
  color: var(--text-gray);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  background: #F4F6F5;
}

.btn-img-dl {
  position: absolute;
  bottom: 26px;
  right: 6px;
  width: 24px;
  height: 24px;
  background: rgba(0, 0, 0, .55);
  border: none;
  border-radius: 5px;
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  opacity: 0;
  transition: opacity 0.18s;
  z-index: 2;
}

.attachment-thumb:hover .btn-img-dl {
  opacity: 1;
}

.attachment-doc-list {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.attachment-doc-row {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 14px;
  background: #F9FBFA;
  border: 1.5px solid var(--border);
  border-radius: 10px;
  transition: border-color 0.2s;
}

.attachment-doc-row:hover {
  border-color: var(--green-light);
}

.doc-icon-box {
  width: 40px;
  height: 40px;
  border-radius: 9px;
  flex-shrink: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 2px;
  background: #FAF5FF;
  border: 1.5px solid #B794F4;
  color: #6B46C1;
}

.doc-icon-box.doc-pdf {
  background: #FFF5F5;
  border: 1.5px solid #FC8181;
  color: #E53E3E;
}

.doc-icon-box.doc-doc {
  background: #EBF8FF;
  border: 1.5px solid #63B3ED;
  color: #2B6CB0;
}

.doc-icon-box.doc-xlsx {
  background: #F0FFF4;
  border: 1.5px solid #68D391;
  color: var(--green-mid);
}

.doc-ext {
  font-size: 8.5px;
  font-weight: 800;
  letter-spacing: 0.3px;
}

.doc-meta {
  flex: 1;
  min-width: 0;
}

.doc-name {
  font-size: 12.5px;
  font-weight: 600;
  color: var(--text-dark);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.doc-size {
  font-size: 11px;
  color: var(--text-light);
  margin-top: 2px;
}

.btn-doc-open {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  height: 30px;
  padding: 0 12px;
  flex-shrink: 0;
  background: var(--white);
  color: var(--text-gray);
  border: 1.5px solid var(--border);
  border-radius: 7px;
  font-family: 'Poppins', sans-serif;
  font-size: 11.5px;
  font-weight: 600;
  cursor: pointer;
  transition: border-color 0.2s, color 0.2s;
}

.btn-doc-open:hover {
  border-color: var(--green-mid);
  color: var(--green-mid);
}

.modal-footer {
  padding: 14px 22px;
  border-top: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-shrink: 0;
  background: #FAFBFA;
}

.modal-footer-info {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  color: var(--text-light);
}

.btn-modal-close {
  height: 38px;
  padding: 0 24px;
  background: var(--white);
  color: var(--text-gray);
  border: 1.5px solid var(--border);
  border-radius: 9px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
}

.btn-modal-close:hover {
  border-color: var(--text-gray);
  color: var(--text-dark);
}

.modal-fade-enter-active, .modal-fade-leave-active { transition: opacity 0.22s ease; }
.modal-fade-enter-from, .modal-fade-leave-to { opacity: 0; }

/* ── LIGHTBOX ────────────────────────────── */
.lightbox-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.88);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 500;
}

.lightbox-img {
  max-width: 90vw;
  max-height: 88vh;
  border-radius: 10px;
  object-fit: contain;
}

.lightbox-close {
  position: fixed;
  top: 20px;
  right: 24px;
  width: 38px;
  height: 38px;
  background: rgba(255,255,255,.12);
  border: 1.5px solid rgba(255,255,255,.2);
  border-radius: 9px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  cursor: pointer;
}

.lightbox-fade-enter-active, .lightbox-fade-leave-active { transition: opacity 0.2s; }
.lightbox-fade-enter-from, .lightbox-fade-leave-to { opacity: 0; }

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
</style>
