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
        <a class="nav-item active">
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
        <router-link class="nav-item" to="/admin/projectos">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <path d="M2 13L6 4l4 6 3-3 3 4" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Projectos
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
            <div class="admin-name">{{ auth.user?.name ?? 'Admin' }}</div>
            <div class="admin-role">{{ auth.user?.role ?? '' }}</div>
          </div>
          <div class="admin-avatar">{{ auth.userInitials }}</div>
        </div>
      </header>

      <!-- CONTENT -->
      <main class="content">

        <!-- Page header -->
        <div class="page-title-row">
          <div>
            <h1>Validação de Ocorrências</h1>
            <p>Analise as denúncias ambientais pendentes submetidas pela população moçambicana.</p>
          </div>
          <div class="header-badges">
            <span class="badge-em-analise">{{ countStatus('in_review') }} Em Análise</span>
            <span class="badge-pendentes">{{ countStatus('pending') }} Pendentes</span>
          </div>
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
              <select v-model="f.provincia">
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
              <select v-model="f.projeto">
                <option value="">Todos os Projectos</option>
                <option>MozRural</option><option>MozP</option>
                <option>MozBio</option><option>MozAmbiente</option>
              </select>
            </div>
            <div class="filter-group">
              <label>
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 12 12">
                  <rect x="1" y="1.5" width="10" height="9.5" rx="1.5"/><path d="M4 1v1.5M8 1v1.5M1 5h10" stroke-linecap="round"/>
                </svg>
                Data de Submissão
              </label>
              <input type="date" v-model="f.data"/>
            </div>
            <div class="filter-group">
              <label>
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 12 12">
                  <circle cx="6" cy="6" r="4.5"/><path d="M6 4v2l1.5 1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Estado
              </label>
              <select v-model="f.status">
                <option value="">Todos os Estados</option>
                <option value="pending">Pendente</option>
                <option value="in_review">Em Análise</option>
                <option value="resolved">Resolvida</option>
                <option value="rejected">Rejeitada</option>
              </select>
            </div>
          </div>
          <div class="filter-row-2">
            <div class="filter-group">
              <label>
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 12 12">
                  <path d="M1 2h10l-4 5v4l-2-1V7z" stroke-linejoin="round"/>
                </svg>
                Categoria
              </label>
              <select v-model="f.categoria">
                <option value="">Todas as Categorias</option>
                <option>Desmatamento Ilegal</option><option>Poluição Hídrica</option>
                <option>Caça Furtiva</option><option>Queimadas Descontroladas</option>
                <option>Mineração Ilegal</option><option>Pesca Ilegal</option>
                <option>Fauna</option><option>Flora</option>
              </select>
            </div>
            <div class="filter-group">
              <label>
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 12 12">
                  <circle cx="6" cy="6" r="4.5"/><path d="M4 6h4M6 4v4" stroke-linecap="round"/>
                </svg>
                Origem
              </label>
              <select v-model="f.origem">
                <option value="">Todas as Origens</option>
                <option value="gestor">Gestores</option>
                <option value="externo">Utilizadores Externos</option>
                <option value="interno">Funcionários Internos</option>
              </select>
            </div>
            <div></div>
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

        <!-- SPLIT LAYOUT -->
        <div class="split">

          <!-- LEFT: TABLE -->
          <div class="table-card">
            <table>
              <thead>
                <tr>
                  <th>ID</th><th>Data</th><th>Província</th>
                  <th>Categoria</th><th>Canal</th>
                  <th>Responsável</th><th>Projecto</th><th>Estado</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="r in pagedRows" :key="r.id"
                  :class="{ selected: selected?.id === r.id }"
                  @click="selectRow(r)"
                >
                  <td><span class="id-link">{{ r.id }}</span></td>
                  <td class="td-muted">{{ r.data }}</td>
                  <td class="td-small">{{ r.provincia }}</td>
                  <td class="td-small">{{ r.categoria }}</td>
                  <td class="td-small">{{ r.canal }}</td>
                  <td>
                    <span v-if="r.responsavel" class="td-small">{{ r.responsavel }}</span>
                    <span v-else class="resp-none">—</span>
                  </td>
                  <td class="td-muted td-small">{{ r.projeto }}</td>
                  <td>
                    <span class="badge-status" :class="statusClass(r.status)">{{ r.status_label }}</span>
                  </td>
                </tr>
                <tr v-if="loading">
                  <td colspan="8" class="empty-row">A carregar ocorrências…</td>
                </tr>
                <tr v-else-if="pagedRows.length === 0">
                  <td colspan="8" class="empty-row">Nenhuma ocorrência encontrada.</td>
                </tr>
              </tbody>
            </table>
            <div class="pagination-bar">
              <span class="pagination-info">
                Mostrando {{ pagedRows.length }} de {{ filteredRows.length }} reclamações
              </span>
              <div class="pagination-btns">
                <button class="pg-btn" :disabled="page === 1" @click="page--">Anterior</button>
                <button class="pg-btn" :disabled="page >= totalPages" @click="page++">Próxima</button>
              </div>
            </div>
          </div>

          <!-- RIGHT: DETAIL PANEL -->
          <div class="detail-panel">

            <div class="empty-detail" v-if="!selected">
              <svg width="40" height="40" fill="none" stroke="#C8D8CE" stroke-width="1.5" viewBox="0 0 40 40">
                <rect x="6" y="4" width="26" height="32" rx="3"/>
                <path d="M13 13h14M13 19h14M13 25h8" stroke-linecap="round"/>
              </svg>
              <p>Seleccione uma reclamação para ver os detalhes e validar</p>
            </div>

            <template v-if="selected">
              <div class="detail-panel-hd">
                <div class="detail-panel-title">
                  <svg width="14" height="14" fill="none" stroke="var(--green-mid)" stroke-width="1.8" viewBox="0 0 16 16">
                    <rect x="2" y="1" width="12" height="14" rx="1.5"/>
                    <path d="M5 6h6M5 9h4" stroke-linecap="round"/>
                    <path d="M9 1v3h4" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  Detalhes da Denúncia
                </div>
                <button class="btn-close-panel" @click="selected = null">
                  <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 12 12">
                    <path d="M2 2l8 8M10 2L2 10" stroke-linecap="round"/>
                  </svg>
                </button>
              </div>

              <div class="status-row">
                <span class="badge-status" :class="statusClass(selected.status)">{{ selected.status_label }}</span>
                <div class="sub-date">
                  <span>Submetido em</span>{{ selected.data }}
                </div>
              </div>

              <div class="rec-title">
                <div class="rec-id">{{ selected.id }}</div>
                <div class="rec-cat">{{ selected.categoria }}</div>
              </div>

              <!-- Evidence thumb -->
              <div class="section-title">
                <svg width="13" height="13" fill="none" stroke="var(--green-mid)" stroke-width="1.7" viewBox="0 0 14 14">
                  <rect x="1" y="2" width="12" height="10" rx="1.5"/>
                  <path d="M1 8l3-3 3 3 2-2 4 4"/>
                  <circle cx="10" cy="5" r="1.2" fill="var(--green-mid)" stroke="none"/>
                </svg>
                Evidências
              </div>
              <div class="evidence-wrap">
                <img v-if="selected.foto" :src="selected.foto" class="evidence-img" alt="Evidência"/>
                <div v-else class="evidence-placeholder">Sem evidências fotográficas</div>
                <div v-if="selected.anexos?.length" class="evidence-count-badge">
                  <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 12 12">
                    <path d="M9.5 5.5L5 10a3.536 3.536 0 0 1-5-5l5-5a2.357 2.357 0 0 1 3.333 3.333L3.5 8a1.178 1.178 0 0 1-1.667-1.667L6.5 1.5" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  {{ selected.anexos.length }} anexo{{ selected.anexos.length !== 1 ? 's' : '' }}
                </div>
              </div>

              <!-- Description preview -->
              <div class="section-title">
                <svg width="13" height="13" fill="none" stroke="var(--green-mid)" stroke-width="1.7" viewBox="0 0 14 14">
                  <circle cx="7" cy="7" r="5.5"/>
                  <path d="M7 5v2.5M7 9.5h.01" stroke-linecap="round" stroke-width="1.8"/>
                </svg>
                Descrição
              </div>
              <div class="desc-box">{{ selected.descricao }}</div>

              <!-- CTA to open modal -->
              <button class="btn-ver-completo" @click="showModal = true">
                <div class="bvc-left">
                  <div class="bvc-icon">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 16 16">
                      <path d="M10 2h4v4M14 2l-5 5M6 14H2v-4M2 14l5-5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </div>
                  <div>
                    <div class="bvc-title">Ver detalhes completos</div>
                    <div class="bvc-sub">Descrição, localização e todos os anexos</div>
                  </div>
                </div>
                <div class="bvc-right">
                  <span v-if="selected.anexos?.length" class="bvc-pill">
                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 12 12">
                      <path d="M9.5 5.5L5 10a3.536 3.536 0 0 1-5-5l5-5a2.357 2.357 0 0 1 3.333 3.333L3.5 8a1.178 1.178 0 0 1-1.667-1.667L6.5 1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    {{ selected.anexos.length }} anexo{{ selected.anexos.length !== 1 ? 's' : '' }}
                  </span>
                  <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 12 12">
                    <path d="M4.5 2l4 4-4 4" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </div>
              </button>

              <div class="info-grid">
                <div class="info-block">
                  <div class="info-label">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 12 12">
                      <path d="M6 1C3.791 1 2 2.791 2 5c0 3 4 7 4 7s4-4 4-7c0-2.209-1.791-4-4-4z"/><circle cx="6" cy="5" r="1.5"/>
                    </svg>
                    Localização
                  </div>
                  <div class="info-val">{{ selected.provincia }}</div>
                  <div class="info-sub">{{ selected.coords }}</div>
                </div>
                <div class="info-block">
                  <div class="info-label">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 12 12">
                      <circle cx="6" cy="4.5" r="2.5"/><path d="M1 11c0-2.485 2.239-4.5 5-4.5s5 2.015 5 4.5" stroke-linecap="round"/>
                    </svg>
                    Denunciante
                  </div>
                  <div class="info-val">{{ selected.denunciante }}</div>
                  <div class="info-sub">{{ selected.telefone }}</div>
                </div>
              </div>

              <div class="mini-map">
                <svg width="20" height="20" fill="none" stroke="var(--green-mid)" stroke-width="1.7" viewBox="0 0 22 22">
                  <path d="M11 2C7.686 2 5 4.686 5 8c0 5.25 6 12 6 12s6-6.75 6-12c0-3.314-2.686-6-6-6z"/>
                  <circle cx="11" cy="8" r="2.5"/>
                </svg>
                <span>Mapa de Geolocalização</span>
              </div>

              <div class="state-actions">

                <!-- Flow indicator -->
                <div class="state-flow">
                  <span class="flow-step" :class="{
                    'flow-active': selected.status === 'pending',
                    'flow-done':   selected.status === 'in_review' || selected.status === 'resolved',
                    'flow-skip':   selected.status === 'rejected'
                  }">Pendente</span>
                  <svg class="flow-chevron" width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 10 10">
                    <path d="M3 2l4 3-4 3" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  <span class="flow-step" :class="{
                    'flow-active': selected.status === 'in_review',
                    'flow-done':   selected.status === 'resolved',
                    'flow-skip':   selected.status === 'rejected'
                  }">Em Análise</span>
                  <svg class="flow-chevron" width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 10 10">
                    <path d="M3 2l4 3-4 3" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  <span class="flow-step" :class="{
                    'flow-active': selected.status === 'resolved'
                  }">Resolvido</span>
                </div>

                <!-- PENDENTE → validar ou rejeitar -->
                <template v-if="selected.status === 'pending'">
                  <p class="action-hint">Valide para iniciar análise ou rejeite a ocorrência.</p>
                  <div class="dual-action-btns">
                    <button class="btn-validar" @click="changeStatus('Em Analise')" :disabled="confirming">
                      <svg v-if="confirming" class="spin" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 16 16">
                        <path d="M8 2a6 6 0 0 1 6 6" stroke-linecap="round"/>
                      </svg>
                      <svg v-else width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 16 16">
                        <circle cx="8" cy="8" r="6"/><path d="M5.5 8l2 2 3.5-4" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                      {{ confirming ? 'A validar…' : 'Validar' }}
                    </button>
                    <button class="btn-rejeitar-outline" @click="openCommentModal('Rejeitado')" :disabled="confirming">
                      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 16 16">
                        <circle cx="8" cy="8" r="6"/><path d="M5.5 5.5l5 5M10.5 5.5l-5 5" stroke-linecap="round"/>
                      </svg>
                      Rejeitar
                    </button>
                  </div>
                </template>

                <!-- EM ANÁLISE → resolver ou rejeitar -->
                <template v-else-if="selected.status === 'in_review'">
                  <p class="action-hint">Conclua a análise e marque a ocorrência como resolvida.</p>
                  <button class="btn-confirmar" @click="openCommentModal('Resolvido')" :disabled="confirming">
                    <svg width="15" height="15" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 16 16">
                      <circle cx="8" cy="8" r="6"/><path d="M5.5 8l2 2 3.5-4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Marcar como Resolvido
                  </button>
                  <button class="btn-rejeitar-secondary" @click="openCommentModal('Rejeitado')" :disabled="confirming">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 16 16">
                      <circle cx="8" cy="8" r="6"/><path d="M5.5 5.5l5 5M10.5 5.5l-5 5" stroke-linecap="round"/>
                    </svg>
                    Rejeitar Ocorrência
                  </button>
                </template>

                <!-- ESTADO FINAL: Resolvido -->
                <template v-else-if="selected.status === 'resolved'">
                  <div class="state-final sf-resolvido">
                    <div class="sf-icon">
                      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 20 20">
                        <circle cx="10" cy="10" r="8"/><path d="M6.5 10l2.5 2.5 4.5-5" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                    </div>
                    <div>
                      <div class="sf-title">Ocorrência Resolvida</div>
                      <div class="sf-sub">Esta ocorrência foi concluída com sucesso.</div>
                    </div>
                  </div>
                  <div v-if="selected.comentario" class="sf-comment sf-comment-resolvido">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 12 12">
                      <path d="M1 2h10a1 1 0 0 1 1 1v6a1 1 0 0 1-1 1H4l-3 2V3a1 1 0 0 1 1-1z"/>
                    </svg>
                    <span>{{ selected.comentario }}</span>
                  </div>
                </template>

                <!-- ESTADO FINAL: Rejeitado -->
                <template v-else-if="selected.status === 'rejected'">
                  <div class="state-final sf-rejeitado">
                    <div class="sf-icon">
                      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 20 20">
                        <circle cx="10" cy="10" r="8"/><path d="M7 7l6 6M13 7l-6 6" stroke-linecap="round"/>
                      </svg>
                    </div>
                    <div>
                      <div class="sf-title">Ocorrência Rejeitada</div>
                      <div class="sf-sub">Esta ocorrência foi encerrada por rejeição.</div>
                    </div>
                  </div>
                  <div v-if="selected.comentario" class="sf-comment sf-comment-rejeitado">
                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 12 12">
                      <path d="M1 2h10a1 1 0 0 1 1 1v6a1 1 0 0 1-1 1H4l-3 2V3a1 1 0 0 1 1-1z"/>
                    </svg>
                    <span>{{ selected.comentario }}</span>
                  </div>
                </template>

              </div>
            </template>

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

    <!-- ── MODAL DETALHES COMPLETOS ── -->
    <transition name="modal-fade">
      <div class="modal-overlay" v-if="showModal && selected" @click.self="showModal = false">
        <div class="modal-card">

          <!-- Header -->
          <div class="modal-hd">
            <div class="modal-hd-left">
              <div class="modal-hd-id">{{ selected.id }}</div>
              <div class="modal-hd-cat">{{ selected.categoria }} · {{ selected.projeto }}</div>
            </div>
            <div class="modal-hd-right">
              <span class="badge-status" :class="statusClass(selected.status)">{{ selected.status_label }}</span>
              <button class="btn-close-modal" @click="showModal = false">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 14 14">
                  <path d="M2 2l10 10M12 2L2 12" stroke-linecap="round"/>
                </svg>
              </button>
            </div>
          </div>

          <!-- Scrollable body -->
          <div class="modal-body">

            <!-- Hero image -->
            <div class="modal-hero-wrap">
              <img v-if="selected.foto" :src="selected.foto" class="modal-hero" alt="Evidência principal"/>
              <div v-else class="modal-hero-empty">
                <svg width="28" height="28" fill="none" stroke="#C8D8CE" stroke-width="1.5" viewBox="0 0 28 28">
                  <rect x="2" y="5" width="24" height="18" rx="2.5"/>
                  <path d="M2 18l6-6 5 5 4-4 7 7"/>
                  <circle cx="20" cy="11" r="2.5" fill="#C8D8CE" stroke="none"/>
                </svg>
                <span>Sem evidência fotográfica principal</span>
              </div>
              <div class="modal-hero-meta">
                <span class="badge-status" :class="statusClass(selected.status)">{{ selected.status_label }}</span>
                <span class="modal-hero-date">{{ selected.data }}</span>
              </div>
            </div>

            <!-- Meta strip -->
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
                <span class="modal-meta-label">Responsável</span>
                <span class="modal-meta-val">{{ selected.responsavel ?? '—' }}</span>
              </div>
              <div class="modal-meta-item">
                <span class="modal-meta-label">Localização</span>
                <span class="modal-meta-val">{{ selected.provincia }}</span>
              </div>
            </div>

            <!-- Description -->
            <div class="modal-section">
              <div class="modal-section-hd">
                <svg width="13" height="13" fill="none" stroke="var(--green-mid)" stroke-width="1.7" viewBox="0 0 14 14">
                  <circle cx="7" cy="7" r="5.5"/>
                  <path d="M7 5v2.5M7 9.5h.01" stroke-linecap="round" stroke-width="1.8"/>
                </svg>
                Descrição Completa da Ocorrência
              </div>
              <div class="modal-desc">"{{ selected.descricao }}"</div>
            </div>

            <!-- Location + Reporter -->
            <div class="modal-section modal-two-col">
              <div class="modal-info-block">
                <div class="modal-info-label">
                  <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 12 12">
                    <path d="M6 1C3.791 1 2 2.791 2 5c0 3 4 7 4 7s4-4 4-7c0-2.209-1.791-4-4-4z"/><circle cx="6" cy="5" r="1.5"/>
                  </svg>
                  Localização GPS
                </div>
                <div class="modal-info-val">{{ selected.provincia }}</div>
                <div class="modal-info-sub">{{ selected.coords }}</div>
              </div>
              <div class="modal-info-block">
                <div class="modal-info-label">
                  <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 12 12">
                    <circle cx="6" cy="4.5" r="2.5"/><path d="M1 11c0-2.485 2.239-4.5 5-4.5s5 2.015 5 4.5" stroke-linecap="round"/>
                  </svg>
                  Denunciante
                </div>
                <div class="modal-info-val">{{ selected.denunciante }}</div>
                <div class="modal-info-sub">{{ selected.telefone }}</div>
              </div>
            </div>

            <!-- Attachments -->
            <div class="modal-section">
              <div class="modal-section-hd">
                <svg width="13" height="13" fill="none" stroke="var(--green-mid)" stroke-width="1.7" viewBox="0 0 14 14">
                  <path d="M11.5 6.5L6 12a4.243 4.243 0 0 1-6-6l6-6a2.828 2.828 0 0 1 4 4L4 10a1.414 1.414 0 0 1-2-2L8 2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Anexos Submetidos
                <span class="section-count">{{ selected.anexos?.length ?? 0 }}</span>
              </div>

              <!-- No attachments -->
              <div v-if="!selected.anexos?.length" class="no-attachments">
                <svg width="22" height="22" fill="none" stroke="#C8D8CE" stroke-width="1.5" viewBox="0 0 24 24">
                  <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/>
                  <path d="M13 2v7h7" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>Nenhum ficheiro foi anexado a esta ocorrência.</span>
              </div>

              <!-- Image gallery -->
              <div v-if="imageAnexos.length" class="attachment-gallery">
                <div
                  v-for="(a, i) in imageAnexos" :key="'img-'+i"
                  class="attachment-thumb"
                  @click="lightboxImg = a.url"
                >
                  <img :src="a.url" :alt="a.nome"/>
                  <div class="attachment-thumb-overlay">
                    <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 16 16">
                      <circle cx="7" cy="7" r="4.5"/><path d="M11 11l3 3" stroke-linecap="round"/>
                      <path d="M5 7h4M7 5v4" stroke-linecap="round"/>
                    </svg>
                  </div>
                  <div class="attachment-thumb-name">{{ a.nome }}</div>
                </div>
              </div>

              <!-- Doc list -->
              <div v-if="docAnexos.length" class="attachment-doc-list">
                <div v-for="(a, i) in docAnexos" :key="'doc-'+i" class="attachment-doc-row">
                  <div class="doc-icon-box" :class="'doc-' + a.tipo">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
                      <path d="M10 2H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V5L10 2z"/>
                      <path d="M10 2v3h3" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M5 7h6M5 10h4" stroke-linecap="round"/>
                    </svg>
                    <span class="doc-ext">{{ a.tipo.toUpperCase() }}</span>
                  </div>
                  <div class="doc-meta">
                    <div class="doc-name">{{ a.nome }}</div>
                    <div class="doc-size" v-if="a.tamanho">{{ a.tamanho }}</div>
                  </div>
                  <button class="btn-doc-open">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 14 14">
                      <path d="M7 2v8M3 7l4 5 4-5" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M2 12h10" stroke-linecap="round"/>
                    </svg>
                    Abrir
                  </button>
                </div>
              </div>
            </div>

            <!-- Map -->
            <div class="modal-section">
              <div class="modal-map">
                <svg width="18" height="18" fill="none" stroke="var(--green-mid)" stroke-width="1.7" viewBox="0 0 22 22">
                  <path d="M11 2C7.686 2 5 4.686 5 8c0 5.25 6 12 6 12s6-6.75 6-12c0-3.314-2.686-6-6-6z"/>
                  <circle cx="11" cy="8" r="2.5"/>
                </svg>
                <div>
                  <div class="modal-map-title">Mapa de Geolocalização</div>
                  <div class="modal-map-coords">{{ selected.coords }}</div>
                </div>
              </div>
            </div>

          </div><!-- /modal-body -->

          <!-- Footer -->
          <div class="modal-footer">
            <div class="modal-footer-info">
              <svg width="12" height="12" fill="none" stroke="var(--text-light)" stroke-width="1.6" viewBox="0 0 12 12">
                <rect x="1" y="1.5" width="10" height="9.5" rx="1.5"/><path d="M4 1v1.5M8 1v1.5M1 5h10" stroke-linecap="round"/>
              </svg>
              Submetido em {{ selected.data }}
            </div>
            <button class="btn-modal-close" @click="showModal = false">Fechar</button>
          </div>

        </div>
      </div>
    </transition>

    <!-- ── LIGHTBOX ── -->
    <transition name="lightbox-fade">
      <div class="lightbox-overlay" v-if="lightboxImg" @click="lightboxImg = null">
        <img :src="lightboxImg" class="lightbox-img" @click.stop/>
        <button class="lightbox-close" @click="lightboxImg = null">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 14 14">
            <path d="M2 2l10 10M12 2L2 12" stroke-linecap="round"/>
          </svg>
        </button>
      </div>
    </transition>

    <!-- ── MODAL COMENTÁRIO ── -->
    <transition name="modal-fade">
      <div class="comment-overlay" v-if="commentModal.show" @click.self="cancelComment">
        <div class="comment-card">

          <!-- Header -->
          <div class="comment-hd" :class="commentModal.action === 'Rejeitado' ? 'chd-rejeitar' : 'chd-resolver'">
            <div class="comment-hd-left">
              <div class="comment-hd-icon">
                <svg v-if="commentModal.action === 'Rejeitado'" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 20 20">
                  <circle cx="10" cy="10" r="8"/><path d="M7 7l6 6M13 7l-6 6" stroke-linecap="round"/>
                </svg>
                <svg v-else width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 20 20">
                  <circle cx="10" cy="10" r="8"/><path d="M6.5 10l2.5 2.5 4.5-5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
              <div>
                <div class="comment-hd-title">
                  {{ commentModal.action === 'Rejeitado' ? 'Rejeitar Ocorrência' : 'Marcar como Resolvido' }}
                </div>
                <div class="comment-hd-id">{{ selected?.id }} · {{ selected?.categoria }}</div>
              </div>
            </div>
            <button class="btn-close-comment" @click="cancelComment">
              <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 14 14">
                <path d="M2 2l10 10M12 2L2 12" stroke-linecap="round"/>
              </svg>
            </button>
          </div>

          <!-- Body -->
          <div class="comment-body">
            <div class="comment-field-label">
              {{ commentModal.action === 'Rejeitado' ? 'Motivo da Rejeição' : 'Descrição da Resolução' }}
              <span class="comment-required">obrigatório</span>
            </div>
            <p class="comment-hint">
              {{ commentModal.action === 'Rejeitado'
                ? 'Explique o motivo pelo qual esta ocorrência está a ser rejeitada. Esta informação ficará registada e será visível ao denunciante.'
                : 'Descreva como a ocorrência foi resolvida e as medidas tomadas. Este registo serve de histórico oficial.' }}
            </p>
            <textarea
              ref="commentTextareaRef"
              class="comment-textarea"
              v-model="commentModal.text"
              :maxlength="500"
              :placeholder="commentModal.action === 'Rejeitado'
                ? 'Ex: Informação insuficiente para validação. O denunciante deve resubmeter com coordenadas GPS e evidências fotográficas claras.'
                : 'Ex: Fiscalização realizada em 12/05/2024. Infractor notificado e acção correctiva iniciada. Processo encaminhado ao MITADER.'"
              rows="5"
            ></textarea>
            <div class="comment-char" :class="{ 'comment-char-warn': commentModal.text.length > 440 }">
              {{ commentModal.text.length }}/500
            </div>
          </div>

          <!-- Footer -->
          <div class="comment-footer">
            <button class="btn-cancel-comment" @click="cancelComment" :disabled="confirming">
              Cancelar
            </button>
            <button
              class="btn-confirm-comment"
              :class="commentModal.action === 'Rejeitado' ? 'bcc-rejeitar' : 'bcc-resolver'"
              @click="confirmComment"
              :disabled="!commentModal.text.trim() || confirming"
            >
              <svg v-if="confirming" class="spin" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 16 16">
                <path d="M8 2a6 6 0 0 1 6 6" stroke-linecap="round"/>
              </svg>
              <svg v-else-if="commentModal.action === 'Rejeitado'" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 16 16">
                <circle cx="8" cy="8" r="6"/><path d="M5.5 5.5l5 5M10.5 5.5l-5 5" stroke-linecap="round"/>
              </svg>
              <svg v-else width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 16 16">
                <circle cx="8" cy="8" r="6"/><path d="M5.5 8l2 2 3.5-4" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              {{ confirming
                ? 'A processar…'
                : commentModal.action === 'Rejeitado' ? 'Confirmar Rejeição' : 'Confirmar Resolução' }}
            </button>
          </div>

        </div>
      </div>
    </transition>

    <!-- TOAST -->
    <transition name="toast-anim">
      <div class="toast" :class="{ red: toast.red }" v-if="toast.show">
        <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 16 16">
          <circle cx="8" cy="8" r="6"/>
          <path d="M5.5 8l2 2 3.5-4" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        {{ toast.msg }}
      </div>
    </transition>

  </div>
</template>

<script setup>
import { ref, reactive, computed, nextTick, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { InternalService } from '@/api/services/internal.service'

const router = useRouter()
const auth   = useAuthStore()

// ─── UI state ────────────────────────────────────────────────
const topSearch          = ref('')
const page               = ref(1)
const perPage            = 10
const selected           = ref(null)
const confirming         = ref(false)
const showModal          = ref(false)
const lightboxImg        = ref(null)
const commentTextareaRef = ref(null)
const commentModal = reactive({ show: false, action: '', text: '' })
const toast        = reactive({ show: false, msg: '', red: false })
const loading      = ref(false)
const loadError    = ref('')

const rows = ref([])
const f    = reactive({ provincia: '', projeto: '', data: '', status: '', categoria: '', origem: '' })

// ─── Status helpers ───────────────────────────────────────────
const ACTION_TO_API = {
  'Em Analise': 'in_review',
  'Resolvido':  'resolved',
  'Rejeitado':  'rejected',
}
const STATUS_LABEL = {
  pending:   'Pendente',
  in_review: 'Em Análise',
  resolved:  'Resolvida',
  rejected:  'Rejeitada',
  closed:    'Encerrada',
}

function statusClass(s) {
  const map = {
    pending:   'pendente',
    in_review: 'em-analise',
    resolved:  'resolvido',
    rejected:  'rejeitado',
    closed:    'fechado',
  }
  return map[s] ?? 'pendente'
}

// ─── Map API → row ────────────────────────────────────────────
function mapOccurrence(o) {
  return {
    _id:              o.id,
    id:               o.tracking_code,
    data:             o.submitted_at,
    provincia:        o.province?.name  ?? '—',
    categoria:        o.category?.name  ?? '—',
    canal:            o.submission_channel_label ?? '—',
    responsavel:      o.assigned_to?.name ?? null,
    projeto:          o.project?.name   ?? '—',
    status:           o.status,
    status_label:     o.status_label,
    origem:           o.origin,
    coords:           o.location_detail ?? '',
    denunciante:      o.complainant?.name  ?? 'Anónimo',
    telefone:         o.complainant?.phone ?? '',
    descricao:        o.description ?? '',
    assunto:          o.subject     ?? '',
    foto:             null,
    anexos:           [],
    comentario:       '',
    attachments_count: o.attachments_count ?? 0,
  }
}

// ─── Load occurrences from API ────────────────────────────────
async function loadOccurrences() {
  loading.value   = true
  loadError.value = ''
  try {
    const res  = await InternalService.getOccurrences({ per_page: 100 })
    rows.value = (res.data ?? []).map(mapOccurrence)
  } catch (e) {
    loadError.value = 'Erro ao carregar ocorrências.'
    console.error(e)
  } finally {
    loading.value = false
  }
}

onMounted(loadOccurrences)

// ─── Select row + carregar detalhes completos ─────────────────
async function selectRow(r) {
  selected.value  = { ...r }
  showModal.value = false
  try {
    const res  = await InternalService.getOccurrence(r._id)
    const full = res.data ?? res
    selected.value.anexos = (full.attachments ?? []).map(a => ({
      tipo:    a.is_image ? 'imagem' : (a.name.split('.').pop().toLowerCase()),
      nome:    a.name,
      url:     a.url ?? '',
      tamanho: a.size ?? '',
    }))
    selected.value.foto = selected.value.anexos.find(a => a.tipo === 'imagem')?.url ?? null
    if (full.history?.length) {
      const last = [...full.history].reverse().find(h => h.comment)
      if (last) selected.value.comentario = last.comment
    }
  } catch (e) {
    console.error('Erro ao carregar detalhes:', e)
  }
}

// ─── Filters ──────────────────────────────────────────────────
const provincias = computed(() =>
  [...new Set(rows.value.map(r => r.provincia).filter(p => p !== '—'))].sort()
)

// ─── Computed helpers ─────────────────────────────────────────
const imageAnexos = computed(() => selected.value?.anexos?.filter(a => a.tipo === 'imagem') ?? [])
const docAnexos   = computed(() => selected.value?.anexos?.filter(a => a.tipo !== 'imagem') ?? [])

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
  if (f.provincia) list = list.filter(r => r.provincia === f.provincia)
  if (f.projeto)   list = list.filter(r => r.projeto   === f.projeto)
  if (f.data)      list = list.filter(r => r.data      === f.data)
  if (f.status)    list = list.filter(r => r.status    === f.status)
  if (f.categoria) list = list.filter(r => r.categoria === f.categoria)
  if (f.origem)    list = list.filter(r => r.origem    === f.origem)
  return list
})

const totalPages = computed(() => Math.max(1, Math.ceil(filteredRows.value.length / perPage)))
const pagedRows  = computed(() => {
  const start = (page.value - 1) * perPage
  return filteredRows.value.slice(start, start + perPage)
})

function countStatus(s) { return rows.value.filter(r => r.status === s).length }

function limpar() {
  Object.assign(f, { provincia: '', projeto: '', data: '', status: '', categoria: '', origem: '' })
  page.value = 1
}

function showToast(msg, red = false) {
  toast.msg = msg; toast.red = red; toast.show = true
  setTimeout(() => { toast.show = false }, 3200)
}

function openCommentModal(action) {
  if (!selected.value || confirming.value) return
  commentModal.action = action
  commentModal.text   = ''
  commentModal.show   = true
  nextTick(() => commentTextareaRef.value?.focus())
}

function cancelComment() {
  commentModal.show = false
  commentModal.text = ''
}

async function confirmComment() {
  if (!commentModal.text.trim() || confirming.value) return
  const action  = commentModal.action
  const comment = commentModal.text.trim()
  commentModal.show = false
  await changeStatus(action, comment)
}

async function changeStatus(newState, comment = '') {
  if (!selected.value || confirming.value) return
  confirming.value = true
  const apiStatus = ACTION_TO_API[newState]
  try {
    await InternalService.updateStatus(selected.value._id, { status: apiStatus, comment })
    const idx = rows.value.findIndex(r => r._id === selected.value._id)
    if (idx !== -1) {
      rows.value[idx].status       = apiStatus
      rows.value[idx].status_label = STATUS_LABEL[apiStatus]
    }
    selected.value.status       = apiStatus
    selected.value.status_label = STATUS_LABEL[apiStatus]
    selected.value.comentario   = comment
    showToast(
      newState === 'Rejeitado'
        ? `${selected.value.id} foi rejeitada.`
        : `${selected.value.id} passou para "${STATUS_LABEL[apiStatus]}".`,
      newState === 'Rejeitado'
    )
  } catch (e) {
    console.error('Erro ao atualizar estado:', e)
    showToast('Erro ao actualizar o estado. Tente novamente.', true)
  } finally {
    confirming.value = false
  }
}

async function handleLogout() {
  try { await auth.logout() } catch {}
  router.push('/acessoRestrito')
}
</script>

<style scoped>
.app-shell { display: flex; width: 100%; height: 100vh; overflow: hidden; background: #fff; }

/* ── SIDEBAR ─────────────────────────────── */
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
.nav-item:hover  { background: var(--green-bg); color: var(--green-mid); }
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

/* ── MAIN ───────────────────────────────── */
.main { margin-left: 210px; flex: 1; display: flex; flex-direction: column; height: 100vh; overflow: hidden; }

/* ── TOPBAR ─────────────────────────────── */
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

/* ── CONTENT ────────────────────────────── */
.content { flex: 1; overflow-y: auto; padding: 24px 28px 32px; background: #F2F6F4; }
.content::-webkit-scrollbar { width: 5px; }
.content::-webkit-scrollbar-thumb { background: #C8D8CE; border-radius: 99px; }

.page-title-row {
  display: flex; align-items: flex-start;
  justify-content: space-between; margin-bottom: 20px;
}
.page-title-row h1 { font-size: 22px; font-weight: 800; margin-bottom: 4px; }
.page-title-row p  { font-size: 13px; color: var(--text-gray); }
.header-badges { display: flex; gap: 8px; flex-shrink: 0; margin-top: 4px; }
.badge-em-analise {
  border: 1.5px solid #F6AD55; color: #C05621; background: #FFFAF0;
  border-radius: 9px; font-size: 12px; font-weight: 700; padding: 5px 13px;
}
.badge-pendentes {
  border: 1.5px solid #68D391; color: var(--green-mid); background: var(--green-pale);
  border-radius: 9px; font-size: 12px; font-weight: 700; padding: 5px 13px;
}

/* ── FILTER CARD ────────────────────────── */
.filter-card {
  background: var(--white); border-radius: 16px;
  padding: 18px 20px 20px; margin-bottom: 18px;
  box-shadow: 0 1px 3px rgba(0,0,0,.05), 0 6px 20px rgba(0,0,0,.07);
}
.filter-row {
  display: grid; grid-template-columns: 1fr 1fr 1fr 1fr;
  gap: 12px; align-items: end; margin-bottom: 12px;
}
.filter-row-2 {
  display: grid; grid-template-columns: 1fr 1fr 1fr 1fr;
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
.filter-actions { display: flex; align-items: flex-end; }
.btn-limpar {
  display: inline-flex; align-items: center; gap: 6px; height: 39px;
  background: var(--white); color: var(--text-gray);
  border: 1.5px solid var(--border); border-radius: 8px;
  padding: 0 16px; font-family: 'Poppins', sans-serif;
  font-size: 12.5px; font-weight: 600; cursor: pointer;
  transition: border-color 0.2s, color 0.2s;
}
.btn-limpar:hover { border-color: var(--text-gray); color: var(--text-dark); }

/* ── SPLIT ──────────────────────────────── */
.split { display: grid; grid-template-columns: 1fr 290px; gap: 16px; align-items: start; }

/* ── TABLE CARD ─────────────────────────── */
.table-card {
  background: var(--white); border-radius: 16px; overflow: hidden;
  box-shadow: 0 1px 3px rgba(0,0,0,.05), 0 6px 20px rgba(0,0,0,.07);
}
table { width: 100%; border-collapse: collapse; }
thead th {
  padding: 11px 14px; font-size: 11px; font-weight: 700;
  color: var(--text-light); text-align: left;
  background: #F4F6F5; border-bottom: 1px solid var(--border);
  text-transform: uppercase; letter-spacing: 0.5px; white-space: nowrap;
}
tbody tr { transition: background 0.15s; cursor: pointer; }
tbody tr:hover    { background: #F9FBFA; }
tbody tr.selected { background: var(--green-bg); }
tbody td {
  padding: 11px 14px; font-size: 13px;
  border-bottom: 1px solid var(--border); vertical-align: middle;
}
tbody tr:last-child td { border-bottom: none; }
.td-muted  { color: var(--text-gray); font-size: 12.5px; }
.td-small  { font-size: 12.5px; }
.id-link   { color: var(--green-mid); font-weight: 700; font-size: 12.5px; }
.resp-none { color: var(--text-light); font-size: 12.5px; }

.badge-status {
  display: inline-block; padding: 3px 10px; border-radius: 99px;
  font-size: 11.5px; font-weight: 700; border: 1.5px solid;
}
.badge-status.pendente   { color: #744210; border-color: #F6D860; background: #FEFCBF; }
.badge-status.em-analise { color: #C05621; border-color: #F6AD55; background: #FFFAF0; }
.badge-status.resolvido  { color: var(--green-mid); border-color: #68D391; background: var(--green-pale); }
.badge-status.rejeitado  { color: #E53E3E; border-color: #FC8181; background: #FFF5F5; }

.pagination-bar {
  display: flex; align-items: center; justify-content: space-between;
  padding: 13px 16px; border-top: 1px solid var(--border);
}
.pagination-info { font-size: 12.5px; color: var(--text-light); }
.pagination-btns { display: flex; gap: 6px; }
.pg-btn {
  height: 32px; min-width: 80px; border-radius: 7px;
  display: inline-flex; align-items: center; justify-content: center;
  font-family: 'Poppins', sans-serif; font-size: 12.5px; font-weight: 600;
  cursor: pointer; border: 1.5px solid var(--border);
  background: var(--white); color: var(--text-gray); padding: 0 14px;
  transition: border-color 0.15s, color 0.15s;
}
.pg-btn:hover:not(:disabled) { border-color: var(--green-mid); color: var(--green-mid); }
.pg-btn:disabled { opacity: 0.45; cursor: not-allowed; }
.empty-row { text-align: center; padding: 36px; color: var(--text-light); font-size: 13px; }

/* ── DETAIL PANEL ───────────────────────── */
.detail-panel {
  background: var(--white); border-radius: 16px; overflow: hidden;
  box-shadow: 0 1px 3px rgba(0,0,0,.05), 0 6px 20px rgba(0,0,0,.07);
  position: sticky; top: 0;
}
.empty-detail {
  display: flex; flex-direction: column; align-items: center;
  justify-content: center; padding: 48px 20px;
  text-align: center; color: var(--text-light);
}
.empty-detail p { font-size: 12.5px; margin-top: 10px; line-height: 1.55; }

.detail-panel-hd {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 16px 12px; border-bottom: 1px solid var(--border);
}
.detail-panel-title { display: flex; align-items: center; gap: 7px; font-size: 13px; font-weight: 700; }
.btn-close-panel {
  width: 28px; height: 28px; background: #F4F6F5;
  border: 1.5px solid var(--border); border-radius: 7px;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; transition: background 0.2s; color: var(--text-gray);
}
.btn-close-panel:hover { background: #FFF5F5; border-color: #FC8181; color: #E53E3E; }

.status-row {
  display: flex; align-items: center; justify-content: space-between;
  padding: 10px 16px; border-bottom: 1px solid var(--border);
}
.sub-date { font-size: 11px; color: var(--text-light); text-align: right; }
.sub-date span { display: block; font-size: 10px; margin-bottom: 1px; }

.rec-title { padding: 10px 16px 12px; border-bottom: 1px solid var(--border); }
.rec-id  { font-size: 16px; font-weight: 800; }
.rec-cat { font-size: 12px; color: var(--text-gray); margin-top: 2px; }

.section-title {
  display: flex; align-items: center; gap: 6px;
  font-size: 12px; font-weight: 700; padding: 12px 16px 8px;
}

/* Evidence wrap with attachment badge */
.evidence-wrap { position: relative; }
.evidence-img { width: 100%; height: 120px; object-fit: cover; display: block; }
.evidence-placeholder {
  width: 100%; height: 72px; background: #F4F6F5;
  display: flex; align-items: center; justify-content: center;
  color: var(--text-light); font-size: 12px;
}
.evidence-count-badge {
  position: absolute; bottom: 8px; right: 8px;
  background: rgba(0,0,0,.55); color: #fff;
  border-radius: 99px; padding: 3px 9px;
  font-size: 11px; font-weight: 600;
  display: flex; align-items: center; gap: 4px;
  backdrop-filter: blur(4px);
}

/* Description box (clipped) */
.desc-box {
  margin: 0 16px 0; background: #F4F6F5;
  border-radius: 8px; padding: 10px 12px;
  font-size: 12px; color: var(--text-gray);
  line-height: 1.65; font-style: italic;
  border-left: 3px solid var(--green-light);
  display: -webkit-box; -webkit-animation: 3; -webkit-box-orient: vertical;
  overflow: hidden;
}

/* ── BTN VER COMPLETO ───────────────────── */
.btn-ver-completo {
  display: flex; align-items: center; justify-content: space-between;
  width: calc(100% - 32px); margin: 10px 16px 0;
  padding: 11px 14px;
  background: linear-gradient(135deg, var(--green-pale) 0%, #EAF4EE 100%);
  border: 1.5px solid var(--green-light);
  border-radius: 11px; cursor: pointer;
  transition: all 0.2s; font-family: 'Poppins', sans-serif;
}
.btn-ver-completo:hover {
  background: var(--green-bg); border-color: var(--green-mid);
  transform: translateY(-1px);
  box-shadow: 0 4px 14px rgba(82,183,136,.18);
}
.bvc-left { display: flex; align-items: center; gap: 10px; }
.bvc-icon {
  width: 30px; height: 30px; border-radius: 8px;
  background: var(--white); border: 1.5px solid var(--green-light);
  display: flex; align-items: center; justify-content: center;
  color: var(--green-mid); flex-shrink: 0;
  box-shadow: 0 1px 4px rgba(82,183,136,.15);
}
.bvc-title { font-size: 12.5px; font-weight: 700; color: var(--green-dark); line-height: 1.3; text-align: left; }
.bvc-sub   { font-size: 10.5px; color: var(--green-mid); opacity: 0.8; text-align: left; }
.bvc-right { display: flex; align-items: center; gap: 6px; color: var(--green-mid); }
.bvc-pill {
  display: inline-flex; align-items: center; gap: 4px;
  background: var(--white); border: 1.5px solid var(--green-light);
  color: var(--green-mid); border-radius: 99px;
  padding: 2px 9px; font-size: 11px; font-weight: 700;
}

.info-grid { display: grid; grid-template-columns: 1fr 1fr; border-top: 1px solid var(--border); margin-top: 12px; }
.info-block { padding: 10px 16px; border-right: 1px solid var(--border); }
.info-block:last-child { border-right: none; }
.info-label {
  display: flex; align-items: center; gap: 5px;
  font-size: 10px; font-weight: 700; color: var(--text-light);
  text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 4px;
}
.info-val { font-size: 12px; font-weight: 600; color: var(--text-dark); margin-bottom: 2px; }
.info-sub { font-size: 10.5px; color: var(--text-gray); }

.mini-map {
  margin: 10px 16px;
  background: var(--green-bg); border: 1px solid var(--border);
  border-radius: 9px; height: 68px;
  display: flex; flex-direction: column; align-items: center;
  justify-content: center; gap: 5px; cursor: pointer; transition: background 0.2s;
}
.mini-map:hover { background: var(--green-pale); }
.mini-map span { font-size: 11.5px; font-weight: 600; color: var(--green-mid); }

.state-actions { padding: 10px 16px 16px; }

/* Flow indicator */
.state-flow {
  display: flex; align-items: center; gap: 5px;
  margin-bottom: 12px; padding: 8px 12px;
  background: #F9FBFA; border-radius: 9px; border: 1px solid var(--border);
}
.flow-step {
  font-size: 10.5px; font-weight: 700; color: var(--text-light);
  padding: 2px 8px; border-radius: 99px;
  transition: all 0.2s;
}
.flow-step.flow-active {
  background: var(--green-pale); color: var(--green-mid);
  border: 1.5px solid var(--green-light);
}
.flow-step.flow-done {
  background: transparent; color: var(--green-mid); opacity: 0.6;
  text-decoration: line-through;
}
.flow-step.flow-skip { opacity: 0.35; text-decoration: line-through; }
.flow-chevron { color: var(--border); flex-shrink: 0; }

/* Action hint */
.action-hint {
  font-size: 11.5px; color: var(--text-light); margin-bottom: 10px;
  line-height: 1.5; padding: 0 2px;
}

/* Pendente: two side-by-side buttons */
.dual-action-btns { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.btn-validar {
  display: flex; align-items: center; justify-content: center; gap: 7px;
  padding: 10px; border: none; border-radius: 9px;
  background: var(--green-mid); color: #fff;
  font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 700;
  cursor: pointer; transition: background 0.2s;
}
.btn-validar:hover:not(:disabled) { background: var(--green-dark); }
.btn-validar:disabled { background: #A0C4B0; cursor: not-allowed; }
.btn-rejeitar-outline {
  display: flex; align-items: center; justify-content: center; gap: 7px;
  padding: 10px; border-radius: 9px;
  background: var(--white); color: #E53E3E;
  border: 1.5px solid #FC8181;
  font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 700;
  cursor: pointer; transition: background 0.2s, border-color 0.2s;
}
.btn-rejeitar-outline:hover:not(:disabled) { background: #FFF5F5; border-color: #E53E3E; }
.btn-rejeitar-outline:disabled { opacity: 0.45; cursor: not-allowed; }

/* Em Análise: primary + secondary */
.btn-confirmar {
  width: 100%; background: var(--green-mid); color: #fff; border: none;
  border-radius: 9px; padding: 11px; font-family: 'Poppins', sans-serif;
  font-size: 13.5px; font-weight: 700; cursor: pointer;
  display: flex; align-items: center; justify-content: center; gap: 8px;
  transition: background 0.2s; margin-bottom: 8px;
}
.btn-confirmar:hover:not(:disabled) { background: var(--green-dark); }
.btn-confirmar:disabled { background: #A0C4B0; cursor: not-allowed; }
.btn-rejeitar-secondary {
  width: 100%; display: flex; align-items: center; justify-content: center; gap: 7px;
  padding: 9px; border-radius: 9px;
  background: var(--white); color: #E53E3E;
  border: 1.5px solid #FC8181;
  font-family: 'Poppins', sans-serif; font-size: 12.5px; font-weight: 600;
  cursor: pointer; transition: background 0.2s;
}
.btn-rejeitar-secondary:hover:not(:disabled) { background: #FFF5F5; }
.btn-rejeitar-secondary:disabled { opacity: 0.45; cursor: not-allowed; }

/* Final state cards */
.state-final {
  display: flex; align-items: center; gap: 12px;
  padding: 13px 14px; border-radius: 10px;
  border: 1.5px solid;
}
.sf-resolvido { background: var(--green-pale); border-color: #68D391; color: var(--green-mid); }
.sf-rejeitado { background: #FFF5F5; border-color: #FC8181; color: #E53E3E; }
.sf-icon {
  width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
  background: rgba(255,255,255,.7);
}
.sf-title { font-size: 12.5px; font-weight: 700; margin-bottom: 2px; }
.sf-sub   { font-size: 11px; opacity: 0.75; line-height: 1.4; }

/* ── FOOTER ─────────────────────────────── */
.dash-footer {
  display: flex; align-items: center; justify-content: space-between;
  padding: 12px 28px; background: var(--white);
  box-shadow: 0 -1px 10px rgba(0,0,0,.06);
  font-size: 11.5px; color: var(--text-light); flex-shrink: 0;
}
.dash-footer a { color: var(--text-light); text-decoration: none; margin-left: 16px; transition: color 0.2s; }
.dash-footer a:hover { color: var(--green-mid); }

/* ── TOAST ──────────────────────────────── */
.toast {
  position: fixed; bottom: 24px; right: 24px; z-index: 600;
  background: var(--green-dark); color: #fff;
  border-radius: 12px; padding: 12px 18px;
  display: flex; align-items: center; gap: 9px;
  font-size: 13px; font-weight: 600;
  box-shadow: 0 8px 28px rgba(0,0,0,.18);
}
.toast.red { background: #C53030; }
.toast-anim-enter-active, .toast-anim-leave-active { transition: opacity 0.25s, transform 0.25s; }
.toast-anim-enter-from, .toast-anim-leave-to { opacity: 0; transform: translateY(10px); }

@keyframes spin { to { transform: rotate(360deg); } }
.spin { animation: spin 0.7s linear infinite; }

/* ── MODAL ──────────────────────────────── */
.modal-overlay {
  position: fixed; inset: 0; z-index: 200;
  background: rgba(8, 24, 16, 0.6);
  display: flex; align-items: center; justify-content: center;
  padding: 24px;
}
.modal-card {
  background: var(--white); border-radius: 20px;
  width: 100%; max-width: 680px; max-height: 90vh;
  display: flex; flex-direction: column;
  box-shadow: 0 32px 80px rgba(0,0,0,.28), 0 4px 16px rgba(0,0,0,.1);
  overflow: hidden;
}

/* Modal header */
.modal-hd {
  display: flex; align-items: flex-start; justify-content: space-between;
  padding: 18px 22px 16px; border-bottom: 1px solid var(--border);
  flex-shrink: 0; background: var(--white);
}
.modal-hd-left { display: flex; flex-direction: column; gap: 3px; }
.modal-hd-id  { font-size: 16px; font-weight: 800; color: var(--text-dark); }
.modal-hd-cat { font-size: 12px; color: var(--text-gray); }
.modal-hd-right { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
.btn-close-modal {
  width: 32px; height: 32px; background: #F4F6F5;
  border: 1.5px solid var(--border); border-radius: 9px;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; transition: background 0.2s; color: var(--text-gray);
}
.btn-close-modal:hover { background: #FFF5F5; border-color: #FC8181; color: #E53E3E; }

/* Modal scrollable body */
.modal-body { overflow-y: auto; flex: 1; }
.modal-body::-webkit-scrollbar { width: 4px; }
.modal-body::-webkit-scrollbar-thumb { background: #C8D8CE; border-radius: 99px; }

/* Hero */
.modal-hero-wrap { position: relative; }
.modal-hero {
  width: 100%; height: 230px; object-fit: cover; display: block;
}
.modal-hero-empty {
  width: 100%; height: 100px; background: #F4F6F5;
  display: flex; flex-direction: column; align-items: center;
  justify-content: center; gap: 8px;
  color: var(--text-light); font-size: 12.5px;
}
.modal-hero-meta {
  position: absolute; bottom: 12px; left: 14px;
  display: flex; align-items: center; gap: 8px;
}
.modal-hero-date {
  background: rgba(0,0,0,.5); color: #fff;
  border-radius: 99px; padding: 3px 11px;
  font-size: 11px; font-weight: 600; backdrop-filter: blur(4px);
}

/* Meta strip */
.modal-meta-strip {
  display: grid; grid-template-columns: repeat(4, 1fr);
  border-bottom: 1px solid var(--border);
}
.modal-meta-item {
  padding: 12px 16px; border-right: 1px solid var(--border);
  display: flex; flex-direction: column; gap: 3px;
}
.modal-meta-item:last-child { border-right: none; }
.modal-meta-label { font-size: 10px; font-weight: 700; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.4px; }
.modal-meta-val   { font-size: 13px; font-weight: 600; color: var(--text-dark); }

/* Modal section blocks */
.modal-section { padding: 16px 22px; border-bottom: 1px solid var(--border); }
.modal-section:last-child { border-bottom: none; }
.modal-section-hd {
  display: flex; align-items: center; gap: 7px;
  font-size: 12.5px; font-weight: 700; color: var(--text-dark);
  margin-bottom: 12px;
}
.section-count {
  margin-left: 4px; background: var(--green-pale);
  color: var(--green-mid); border: 1.5px solid var(--green-light);
  border-radius: 99px; padding: 1px 8px;
  font-size: 11px; font-weight: 700;
}
.modal-desc {
  background: #F4F6F5; border-radius: 10px; padding: 14px 16px;
  font-size: 13.5px; color: var(--text-gray);
  line-height: 1.8; font-style: italic;
  border-left: 3px solid var(--green-light);
}

/* 2-col info */
.modal-two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 0; }
.modal-info-block { padding: 0 16px 0 0; }
.modal-info-block + .modal-info-block { padding: 0 0 0 16px; border-left: 1px solid var(--border); }
.modal-info-label {
  display: flex; align-items: center; gap: 5px;
  font-size: 10px; font-weight: 700; color: var(--text-light);
  text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 5px;
}
.modal-info-val { font-size: 13px; font-weight: 600; color: var(--text-dark); margin-bottom: 2px; }
.modal-info-sub { font-size: 11px; color: var(--text-gray); }

/* No attachments */
.no-attachments {
  display: flex; align-items: center; gap: 10px;
  padding: 14px 16px; background: #F9FBFA;
  border: 1.5px dashed var(--border); border-radius: 10px;
  color: var(--text-light); font-size: 12.5px;
}

/* Image gallery */
.attachment-gallery {
  display: grid; grid-template-columns: repeat(3, 1fr);
  gap: 10px; margin-bottom: 12px;
}
.attachment-thumb {
  position: relative; border-radius: 10px; overflow: hidden;
  cursor: pointer; background: #F4F6F5;
  border: 1.5px solid var(--border);
  transition: transform 0.18s, box-shadow 0.18s;
}
.attachment-thumb:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,0,0,.14); }
.attachment-thumb img {
  width: 100%; height: 90px; object-fit: cover; display: block;
}
.attachment-thumb-overlay {
  position: absolute; inset: 0; background: rgba(0,0,0,.38);
  display: flex; align-items: center; justify-content: center;
  opacity: 0; transition: opacity 0.18s;
}
.attachment-thumb:hover .attachment-thumb-overlay { opacity: 1; }
.attachment-thumb-name {
  padding: 5px 7px; font-size: 10px; font-weight: 600;
  color: var(--text-gray); white-space: nowrap;
  overflow: hidden; text-overflow: ellipsis;
  background: #F4F6F5;
}

/* Document list */
.attachment-doc-list { display: flex; flex-direction: column; gap: 8px; }
.attachment-doc-row {
  display: flex; align-items: center; gap: 12px;
  padding: 10px 14px; background: #F9FBFA;
  border: 1.5px solid var(--border); border-radius: 10px;
  transition: border-color 0.2s;
}
.attachment-doc-row:hover { border-color: var(--green-light); }
.doc-icon-box {
  width: 40px; height: 40px; border-radius: 9px; flex-shrink: 0;
  display: flex; flex-direction: column; align-items: center;
  justify-content: center; gap: 2px;
}
.doc-icon-box.doc-pdf  { background: #FFF5F5; border: 1.5px solid #FC8181; color: #E53E3E; }
.doc-icon-box.doc-doc  { background: #EBF8FF; border: 1.5px solid #63B3ED; color: #2B6CB0; }
.doc-icon-box.doc-xlsx { background: #F0FFF4; border: 1.5px solid #68D391; color: var(--green-mid); }
.doc-icon-box          { background: #FAF5FF; border: 1.5px solid #B794F4; color: #6B46C1; }
.doc-icon-box.doc-pdf  { background: #FFF5F5; border: 1.5px solid #FC8181; color: #E53E3E; }
.doc-ext { font-size: 8.5px; font-weight: 800; letter-spacing: 0.3px; }
.doc-meta { flex: 1; min-width: 0; }
.doc-name { font-size: 12.5px; font-weight: 600; color: var(--text-dark); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.doc-size { font-size: 11px; color: var(--text-light); margin-top: 2px; }
.btn-doc-open {
  display: inline-flex; align-items: center; gap: 5px;
  height: 30px; padding: 0 12px; flex-shrink: 0;
  background: var(--white); color: var(--text-gray);
  border: 1.5px solid var(--border); border-radius: 7px;
  font-family: 'Poppins', sans-serif; font-size: 11.5px; font-weight: 600;
  cursor: pointer; transition: border-color 0.2s, color 0.2s;
}
.btn-doc-open:hover { border-color: var(--green-mid); color: var(--green-mid); }

/* Map */
.modal-map {
  background: var(--green-bg); border: 1px solid var(--border);
  border-radius: 12px; height: 80px;
  display: flex; align-items: center; justify-content: center; gap: 12px;
  cursor: pointer; transition: background 0.2s;
}
.modal-map:hover { background: var(--green-pale); }
.modal-map-title  { font-size: 13px; font-weight: 700; color: var(--green-mid); }
.modal-map-coords { font-size: 11px; color: var(--text-gray); margin-top: 2px; }

/* Modal footer */
.modal-footer {
  padding: 14px 22px; border-top: 1px solid var(--border);
  display: flex; align-items: center; justify-content: space-between;
  flex-shrink: 0; background: #FAFBFA;
}
.modal-footer-info {
  display: flex; align-items: center; gap: 6px;
  font-size: 12px; color: var(--text-light);
}
.btn-modal-close {
  height: 38px; padding: 0 24px;
  background: var(--white); color: var(--text-gray);
  border: 1.5px solid var(--border); border-radius: 9px;
  font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600;
  cursor: pointer; transition: border-color 0.2s, color 0.2s;
}
.btn-modal-close:hover { border-color: var(--text-gray); color: var(--text-dark); }

/* Modal transition */
.modal-fade-enter-active, .modal-fade-leave-active { transition: opacity 0.22s; }
.modal-fade-enter-from, .modal-fade-leave-to { opacity: 0; }
.modal-fade-enter-active .modal-card { transition: transform 0.22s; }
.modal-fade-enter-from  .modal-card  { transform: translateY(18px) scale(0.97); }

/* ── LIGHTBOX ───────────────────────────── */
.lightbox-overlay {
  position: fixed; inset: 0; z-index: 400;
  background: rgba(0,0,0,.88);
  display: flex; align-items: center; justify-content: center;
  padding: 32px; cursor: zoom-out;
}
.lightbox-img {
  max-width: 100%; max-height: 100%;
  border-radius: 12px;
  box-shadow: 0 24px 64px rgba(0,0,0,.5);
  cursor: default;
}
.lightbox-close {
  position: absolute; top: 18px; right: 18px;
  width: 38px; height: 38px; border-radius: 50%;
  background: rgba(255,255,255,.15); border: 1.5px solid rgba(255,255,255,.3);
  display: flex; align-items: center; justify-content: center;
  color: #fff; cursor: pointer; transition: background 0.2s;
}
.lightbox-close:hover { background: rgba(255,255,255,.28); }
.lightbox-fade-enter-active, .lightbox-fade-leave-active { transition: opacity 0.18s; }
.lightbox-fade-enter-from, .lightbox-fade-leave-to { opacity: 0; }

/* ── COMMENT MODAL ──────────────────────── */
.comment-overlay {
  position: fixed; inset: 0; z-index: 300;
  background: rgba(8, 24, 16, 0.55);
  display: flex; align-items: center; justify-content: center;
  padding: 24px;
}
.comment-card {
  background: var(--white); border-radius: 18px;
  width: 100%; max-width: 520px;
  display: flex; flex-direction: column;
  box-shadow: 0 28px 72px rgba(0,0,0,.26), 0 4px 16px rgba(0,0,0,.1);
  overflow: hidden;
}

/* Comment header */
.comment-hd {
  display: flex; align-items: center; justify-content: space-between;
  padding: 18px 20px 16px; flex-shrink: 0;
}
.chd-rejeitar { background: #FFF5F5; border-bottom: 1.5px solid #FEB2B2; }
.chd-resolver { background: var(--green-pale); border-bottom: 1.5px solid #9AE6B4; }
.comment-hd-left { display: flex; align-items: center; gap: 12px; }
.comment-hd-icon {
  width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;
  display: flex; align-items: center; justify-content: center;
}
.chd-rejeitar .comment-hd-icon { background: #FED7D7; color: #E53E3E; }
.chd-resolver .comment-hd-icon { background: #C6F6D5; color: var(--green-mid); }
.comment-hd-title { font-size: 15px; font-weight: 800; line-height: 1.2; }
.chd-rejeitar .comment-hd-title { color: #C53030; }
.chd-resolver .comment-hd-title { color: var(--green-dark); }
.comment-hd-id { font-size: 11.5px; color: var(--text-gray); margin-top: 2px; }
.btn-close-comment {
  width: 30px; height: 30px; flex-shrink: 0;
  border-radius: 8px; border: 1.5px solid transparent;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer; transition: background 0.2s; color: var(--text-gray);
  background: rgba(0,0,0,.06);
}
.btn-close-comment:hover { background: rgba(0,0,0,.12); }

/* Comment body */
.comment-body { padding: 20px; }
.comment-field-label {
  display: flex; align-items: center; gap: 8px;
  font-size: 13px; font-weight: 700; color: var(--text-dark);
  margin-bottom: 6px;
}
.comment-required {
  font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px;
  padding: 2px 7px; border-radius: 99px;
  background: #FEFCBF; color: #744210; border: 1px solid #F6D860;
}
.comment-hint {
  font-size: 12px; color: var(--text-light); line-height: 1.6;
  margin-bottom: 12px;
}
.comment-textarea {
  width: 100%; box-sizing: border-box;
  font-family: 'Poppins', sans-serif; font-size: 13px; color: var(--text-dark);
  background: #F9FBFA; border: 1.5px solid var(--border);
  border-radius: 10px; padding: 12px 14px;
  resize: vertical; min-height: 110px;
  outline: none; line-height: 1.65;
  transition: border-color 0.2s, box-shadow 0.2s;
}
.comment-textarea:focus {
  border-color: var(--green-light);
  box-shadow: 0 0 0 3px rgba(82,183,136,.12);
  background: var(--white);
}
.comment-textarea::placeholder { color: var(--text-light); }
.comment-char {
  text-align: right; font-size: 11px; color: var(--text-light);
  margin-top: 5px; transition: color 0.2s;
}
.comment-char-warn { color: #C05621; font-weight: 600; }

/* Comment footer */
.comment-footer {
  display: flex; align-items: center; justify-content: flex-end; gap: 10px;
  padding: 14px 20px; border-top: 1px solid var(--border);
  background: #FAFBFA; flex-shrink: 0;
}
.btn-cancel-comment {
  height: 38px; padding: 0 20px;
  background: var(--white); color: var(--text-gray);
  border: 1.5px solid var(--border); border-radius: 9px;
  font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600;
  cursor: pointer; transition: border-color 0.2s, color 0.2s;
}
.btn-cancel-comment:hover:not(:disabled) { border-color: var(--text-gray); color: var(--text-dark); }
.btn-cancel-comment:disabled { opacity: 0.5; cursor: not-allowed; }
.btn-confirm-comment {
  height: 38px; padding: 0 20px; border: none; border-radius: 9px;
  font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 700;
  cursor: pointer; display: flex; align-items: center; gap: 7px;
  transition: background 0.2s, opacity 0.2s;
}
.btn-confirm-comment:disabled { opacity: 0.5; cursor: not-allowed; }
.bcc-rejeitar { background: #E53E3E; color: #fff; }
.bcc-rejeitar:hover:not(:disabled) { background: #C53030; }
.bcc-resolver { background: var(--green-mid); color: #fff; }
.bcc-resolver:hover:not(:disabled) { background: var(--green-dark); }

/* Comment in final state card */
.sf-comment {
  display: flex; align-items: flex-start; gap: 7px;
  margin-top: 8px; padding: 9px 12px;
  border-radius: 8px; font-size: 11.5px; line-height: 1.6;
}
.sf-comment svg { flex-shrink: 0; margin-top: 2px; }
.sf-comment-resolvido { background: var(--green-bg); color: var(--green-mid); border: 1px solid #9AE6B4; }
.sf-comment-rejeitado { background: #FFF5F5; color: #C53030; border: 1px solid #FEB2B2; }
</style>
