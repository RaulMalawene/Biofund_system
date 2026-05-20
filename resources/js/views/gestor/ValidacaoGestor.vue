<template>
  <div class="app-shell">

    <!-- ── SIDEBAR ── -->
    <aside class="sidebar">
      <router-link to="/" class="sidebar-logo">
        <img src="../../Imagem/logotipoBiofund.jpeg" alt="Biofund" class="sidebar-logo-img" />
        <div>
          <div class="sidebar-logo-text">BioFund Gestor</div>
        </div>
      </router-link>
      <nav class="sidebar-nav">
        <router-link class="nav-item" to="/gestor/dashboard">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <rect x="1" y="1" width="6" height="6" rx="1.5" />
            <rect x="9" y="1" width="6" height="6" rx="1.5" />
            <rect x="1" y="9" width="6" height="6" rx="1.5" />
            <rect x="9" y="9" width="6" height="6" rx="1.5" />
          </svg>
          Dashboard
        </router-link>
        <a class="nav-item active">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <path d="M8 1l1.5 3 3.5.5-2.5 2.5.5 3.5L8 9l-3 1.5.5-3.5L3 4.5 6.5 4z" />
          </svg>
          Validação
        </a>
        <router-link class="nav-item" to="/gestor/historico">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <rect x="2" y="1" width="10" height="14" rx="1.5" />
            <path d="M5 5h4M5 8h4M5 11h2" stroke-linecap="round" />
            <path d="M10 1v4h4" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          Histórico de Ocorrências
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

    <!-- ── MAIN ── -->
    <div class="main">

      <!-- TOPBAR -->
      <header class="topbar">
        <div class="search-wrap">
          <svg width="15" height="15" fill="none" stroke="#8A9490" stroke-width="1.8" viewBox="0 0 16 16">
            <circle cx="7" cy="7" r="5" />
            <path d="M12 12l3 3" stroke-linecap="round" />
          </svg>
          <input type="text" placeholder="Pesquisar reclamações ou utilizador" v-model="topSearch" />
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
            <h1>Validação de Ocorrências</h1>
            <p>Analise as denúncias pendentes nas províncias e projectos sob a sua responsabilidade.</p>
          </div>
          <div class="header-badges">
            <span class="badge-em-analise">{{ countStatus('in_review') }} Em Análise</span>
            <span class="badge-pendentes">{{ countStatus('pending') }} Pendentes</span>
          </div>
        </div>

        <!-- SCOPE BANNER -->
        <div class="scope-banner" v-if="scopeProvinces.length || scopeProjects.length">
          <svg width="13" height="13" fill="none" stroke="var(--green-mid)" stroke-width="1.8" viewBox="0 0 16 16">
            <circle cx="8" cy="8" r="6" />
            <path d="M8 5v3l2 2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          <span class="scope-label">Âmbito de actuação:</span>
          <span v-for="p in scopeProvinces" :key="'prov-' + p.id" class="scope-tag scope-prov">{{ p.name }}</span>
          <span class="scope-sep" v-if="scopeProvinces.length && scopeProjects.length">·</span>
          <span v-for="p in scopeProjects" :key="'proj-' + p.id" class="scope-tag scope-proj">{{ p.name }}</span>
        </div>

        <!-- FILTER CARD -->
        <div class="filter-card">
          <div class="filter-row">
            <div class="filter-group">
              <label>Província</label>
              <select v-model="f.provincia">
                <option value="">Todas as Províncias</option>
                <option v-for="p in scopeProvinces" :key="p.id" :value="p.name">{{ p.name }}</option>
              </select>
            </div>
            <div class="filter-group">
              <label>Projecto</label>
              <select v-model="f.projeto">
                <option value="">Todos os Projectos</option>
                <option v-for="p in scopeProjects" :key="p.id" :value="p.name">{{ p.name }}</option>
              </select>
            </div>
            <div class="filter-group">
              <label>Data de Submissão</label>
              <input type="date" v-model="f.data" />
            </div>
            <div class="filter-group">
              <label>Estado</label>
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
              <label>Categoria</label>
              <select v-model="f.categoria">
                <option value="">Todas as Categorias</option>
                <option v-for="c in refCategories" :key="c.id" :value="c.name">{{ c.name }}</option>
              </select>
            </div>
            <div class="filter-group">
              <label>Origem</label>
              <select v-model="f.origem">
                <option value="">Todas as Origens</option>
                <option value="internal">Funcionários Internos</option>
                <option value="external">Utilizadores Externos</option>
              </select>
            </div>
            <div></div>
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

        <!-- SPLIT LAYOUT -->
        <div class="split">

          <!-- LEFT: TABLE -->
          <div class="table-card">
            <div class="table-overflow">
              <table>
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Data</th>
                    <th>Província</th>
                    <th>Categoria</th>
                    <th>Canal</th>
                    <th>Responsável</th>
                    <th>Projecto</th>
                    <th>Estado</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-if="loading">
                    <td colspan="8" class="empty-row">A carregar ocorrências…</td>
                  </tr>
                  <template v-else>
                    <tr v-for="r in pagedRows" :key="r._id" :class="{ selected: selected?._id === r._id }"
                      @click="selectRow(r)">
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
                    <tr v-if="pagedRows.length === 0">
                      <td colspan="8" class="empty-row">Nenhuma ocorrência encontrada.</td>
                    </tr>
                  </template>
                </tbody>
              </table>
            </div>
            <div class="pagination-bar">
              <span class="pagination-info">Mostrando {{ pagedRows.length }} de {{ filteredRows.length }}
                reclamações</span>
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
                <rect x="6" y="4" width="26" height="32" rx="3" />
                <path d="M13 13h14M13 19h14M13 25h8" stroke-linecap="round" />
              </svg>
              <p>Seleccione uma reclamação para ver os detalhes e validar</p>
            </div>

            <template v-if="selected">
              <div class="detail-panel-hd">
                <div class="detail-panel-title">
                  <svg width="14" height="14" fill="none" stroke="var(--green-mid)" stroke-width="1.8"
                    viewBox="0 0 16 16">
                    <rect x="2" y="1" width="12" height="14" rx="1.5" />
                    <path d="M5 6h6M5 9h4" stroke-linecap="round" />
                    <path d="M9 1v3h4" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  Detalhes da Denúncia
                </div>
                <button class="btn-close-panel" @click="selected = null">
                  <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 12 12">
                    <path d="M2 2l8 8M10 2L2 10" stroke-linecap="round" />
                  </svg>
                </button>
              </div>

              <div class="status-row">
                <span class="badge-status" :class="statusClass(selected.status)">{{ selected.status_label }}</span>
                <div class="sub-date"><span>Submetido em</span>{{ selected.data }}</div>
              </div>

              <div class="rec-title">
                <div class="rec-id">{{ selected.id }}</div>
                <div class="rec-cat">{{ selected.categoria }}</div>
              </div>

              <div class="section-title">
                <svg width="13" height="13" fill="none" stroke="var(--green-mid)" stroke-width="1.7"
                  viewBox="0 0 14 14">
                  <rect x="1" y="2" width="12" height="10" rx="1.5" />
                  <path d="M1 8l3-3 3 3 2-2 4 4" />
                  <circle cx="10" cy="5" r="1.2" fill="var(--green-mid)" stroke="none" />
                </svg>
                Evidências
              </div>
              <div class="evidence-wrap">
                <img v-if="selected.foto" :src="selected.foto" class="evidence-img" alt="Evidência" />
                <div v-else class="evidence-placeholder">Sem evidências fotográficas</div>
                <div v-if="selected.anexos?.length" class="evidence-count-badge">
                  {{ selected.anexos.length }} anexo{{ selected.anexos.length !== 1 ? 's' : '' }}
                </div>
              </div>

              <div class="section-title">
                <svg width="13" height="13" fill="none" stroke="var(--green-mid)" stroke-width="1.7"
                  viewBox="0 0 14 14">
                  <circle cx="7" cy="7" r="5.5" />
                  <path d="M7 5v2.5M7 9.5h.01" stroke-linecap="round" stroke-width="1.8" />
                </svg>
                Descrição
              </div>
              <div class="desc-box">{{ selected.descricao }}</div>

              <button class="btn-ver-completo" @click="openFullModal">
                <div class="bvc-left">
                  <div class="bvc-icon">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 16 16">
                      <path d="M10 2h4v4M14 2l-5 5M6 14H2v-4M2 14l5-5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                  </div>
                  <div>
                    <div class="bvc-title">Ver detalhes completos</div>
                    <div class="bvc-sub">Descrição, localização e todos os anexos</div>
                  </div>
                </div>
                <div class="bvc-right">
                  <span v-if="selected.anexos?.length" class="bvc-pill">{{ selected.anexos.length }} anexo{{
                    selected.anexos.length !== 1 ? 's' : '' }}</span>
                  <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 12 12">
                    <path d="M4.5 2l4 4-4 4" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                </div>
              </button>

              <div class="info-grid">
                <div class="info-block">
                  <div class="info-label">Localização</div>
                  <div class="info-val">{{ selected.provincia }}</div>
                  <div class="info-sub">{{ selected.coords }}</div>
                </div>
                <div class="info-block">
                  <div class="info-label">Denunciante</div>
                  <div class="info-val">{{ selected.denunciante ?? 'Anónimo' }}</div>
                  <div class="info-sub">{{ selected.telefone }}</div>
                </div>
              </div>

              <div class="state-actions">
                <div class="state-flow">
                  <span class="flow-step"
                    :class="{ 'flow-active': selected.status === 'pending', 'flow-done': ['in_review', 'resolved'].includes(selected.status), 'flow-skip': selected.status === 'rejected' }">Pendente</span>
                  <svg class="flow-chevron" width="10" height="10" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 10 10">
                    <path d="M3 2l4 3-4 3" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <span class="flow-step"
                    :class="{ 'flow-active': selected.status === 'in_review', 'flow-done': selected.status === 'resolved', 'flow-skip': selected.status === 'rejected' }">Em
                    Análise</span>
                  <svg class="flow-chevron" width="10" height="10" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 10 10">
                    <path d="M3 2l4 3-4 3" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <span class="flow-step" :class="{ 'flow-active': selected.status === 'resolved' }">Resolvido</span>
                </div>

                <template v-if="selected.status === 'pending'">
                  <p class="action-hint">Valide para iniciar análise ou rejeite a ocorrência.</p>
                  <div class="dual-action-btns">
                    <button class="btn-validar" @click="changeStatus('Em Analise')" :disabled="confirming">
                      <svg v-if="confirming" class="spin" width="14" height="14" fill="none" stroke="currentColor"
                        stroke-width="2.2" viewBox="0 0 16 16">
                        <path d="M8 2a6 6 0 0 1 6 6" stroke-linecap="round" />
                      </svg>
                      <svg v-else width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2"
                        viewBox="0 0 16 16">
                        <circle cx="8" cy="8" r="6" />
                        <path d="M5.5 8l2 2 3.5-4" stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                      {{ confirming ? 'A validar…' : 'Validar' }}
                    </button>
                    <button class="btn-rejeitar-outline" @click="openCommentModal('Rejeitado')" :disabled="confirming">
                      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2"
                        viewBox="0 0 16 16">
                        <circle cx="8" cy="8" r="6" />
                        <path d="M5.5 5.5l5 5M10.5 5.5l-5 5" stroke-linecap="round" />
                      </svg>
                      Rejeitar
                    </button>
                  </div>
                </template>

                <template v-else-if="selected.status === 'in_review'">
                  <p class="action-hint">Conclua a análise e marque a ocorrência como resolvida.</p>
                  <button class="btn-confirmar" @click="openCommentModal('Resolvido')" :disabled="confirming">
                    <svg width="15" height="15" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 16 16">
                      <circle cx="8" cy="8" r="6" />
                      <path d="M5.5 8l2 2 3.5-4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Marcar como Resolvido
                  </button>
                  <button class="btn-rejeitar-secondary" @click="openCommentModal('Rejeitado')" :disabled="confirming">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 16 16">
                      <circle cx="8" cy="8" r="6" />
                      <path d="M5.5 5.5l5 5M10.5 5.5l-5 5" stroke-linecap="round" />
                    </svg>
                    Rejeitar Ocorrência
                  </button>
                </template>

                <template v-else-if="selected.status === 'resolved'">
                  <div class="state-final sf-resolvido">
                    <div class="sf-icon"><svg width="18" height="18" fill="none" stroke="currentColor"
                        stroke-width="2.2" viewBox="0 0 20 20">
                        <circle cx="10" cy="10" r="8" />
                        <path d="M6.5 10l2.5 2.5 4.5-5" stroke-linecap="round" stroke-linejoin="round" />
                      </svg></div>
                    <div>
                      <div class="sf-title">Ocorrência Resolvida</div>
                      <div class="sf-sub">Esta ocorrência foi concluída com sucesso.</div>
                    </div>
                  </div>
                  <div v-if="selected.comentario" class="sf-comment sf-comment-resolvido">{{ selected.comentario }}</div>
                </template>

                <template v-else-if="selected.status === 'rejected'">
                  <div class="state-final sf-rejeitado">
                    <div class="sf-icon"><svg width="18" height="18" fill="none" stroke="currentColor"
                        stroke-width="2.2" viewBox="0 0 20 20">
                        <circle cx="10" cy="10" r="8" />
                        <path d="M7 7l6 6M13 7l-6 6" stroke-linecap="round" />
                      </svg></div>
                    <div>
                      <div class="sf-title">Ocorrência Rejeitada</div>
                      <div class="sf-sub">Esta ocorrência foi encerrada por rejeição.</div>
                    </div>
                  </div>
                  <div v-if="selected.comentario" class="sf-comment sf-comment-rejeitado">{{ selected.comentario }}</div>
                </template>
              </div>
            </template>
          </div>
        </div>

      </main>

      <footer class="dash-footer">
        <span>© 2026 BioFund Gestor · Sistema de Gestão Ambiental de Moçambique</span>
        <div><a href="#">Suporte Técnico</a><a href="#">Privacidade</a></div>
      </footer>
    </div>

    <!-- ── MODAL DETALHES COMPLETOS ── -->
    <transition name="modal-fade">
      <div class="modal-overlay" v-if="showModal && selected" @click.self="closeFullModal">
        <div class="modal-card">

          <div class="modal-hd">
            <div class="modal-hd-left">
              <div class="modal-hd-id">{{ selected.id }}</div>
              <div class="modal-hd-cat">{{ selected.categoria }} · {{ selected.projeto }}</div>
            </div>
            <div class="modal-hd-right">
              <span class="badge-status" :class="statusClass(selected.status)">{{ selected.status_label }}</span>
              <button class="btn-close-modal" @click="closeFullModal">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 14 14">
                  <path d="M2 2l10 10M12 2L2 12" stroke-linecap="round" />
                </svg>
              </button>
            </div>
          </div>

          <div class="modal-body">

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
                <svg width="13" height="13" fill="none" stroke="var(--green-mid)" stroke-width="1.7"
                  viewBox="0 0 14 14">
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
                <div class="modal-info-sub" v-if="selected.registado_por">Registado por: {{ selected.registado_por }}</div>
              </div>
            </div>

            <div class="modal-section">
              <div class="modal-section-hd">
                <svg width="13" height="13" fill="none" stroke="var(--green-mid)" stroke-width="1.7"
                  viewBox="0 0 14 14">
                  <path
                    d="M11.5 6.5L6 12a4.243 4.243 0 0 1-6-6l6-6a2.828 2.828 0 0 1 4 4L4 10a1.414 1.414 0 0 1-2-2L8 2"
                    stroke-linecap="round" stroke-linejoin="round" />
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
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8"
                      viewBox="0 0 16 16">
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

          </div>

          <div class="modal-footer">
            <div class="modal-footer-info">
              <svg width="12" height="12" fill="none" stroke="var(--text-light)" stroke-width="1.6" viewBox="0 0 12 12">
                <rect x="1" y="1.5" width="10" height="9.5" rx="1.5" />
                <path d="M4 1v1.5M8 1v1.5M1 5h10" stroke-linecap="round" />
              </svg>
              Submetido em {{ selected.data }}
            </div>
            <button class="btn-modal-close" @click="closeFullModal">Fechar</button>
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

    <!-- MODAL COMENTÁRIO -->
    <transition name="modal-fade">
      <div class="comment-overlay" v-if="commentModal.show" @click.self="cancelComment">
        <div class="comment-card">
          <div class="comment-hd" :class="commentModal.action === 'Rejeitado' ? 'chd-rejeitar' : 'chd-resolver'">
            <div class="comment-hd-left">
              <div class="comment-hd-icon">
                <svg v-if="commentModal.action === 'Rejeitado'" width="18" height="18" fill="none" stroke="currentColor"
                  stroke-width="2.2" viewBox="0 0 20 20">
                  <circle cx="10" cy="10" r="8" />
                  <path d="M7 7l6 6M13 7l-6 6" stroke-linecap="round" />
                </svg>
                <svg v-else width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2"
                  viewBox="0 0 20 20">
                  <circle cx="10" cy="10" r="8" />
                  <path d="M6.5 10l2.5 2.5 4.5-5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
              </div>
              <div>
                <div class="comment-hd-title">{{ commentModal.action === 'Rejeitado' ? 'Rejeitar Ocorrência' : 'Marcar como Resolvido' }}</div>
                <div class="comment-hd-id">{{ selected?.id }} · {{ selected?.categoria }}</div>
              </div>
            </div>
            <button class="btn-close-comment" @click="cancelComment">
              <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 14 14">
                <path d="M2 2l10 10M12 2L2 12" stroke-linecap="round" />
              </svg>
            </button>
          </div>
          <div class="comment-body">
            <div class="comment-field-label">
              {{ commentModal.action === 'Rejeitado' ? 'Motivo da Rejeição' : 'Descrição da Resolução' }}
              <span class="comment-required">obrigatório</span>
            </div>
            <p class="comment-hint">
              {{ commentModal.action === 'Rejeitado'
                ? 'Explique o motivo pelo qual esta ocorrência está a ser rejeitada.'
                : 'Descreva como a ocorrência foi resolvida e as medidas tomadas.' }}
            </p>
            <textarea ref="commentTextareaRef" class="comment-textarea" v-model="commentModal.text"
              @input="commentModal.error = ''" :maxlength="500" rows="5"></textarea>
            <div class="comment-char" :class="{ 'comment-char-warn': commentModal.text.length > 440 }">{{
              commentModal.text.length }}/500</div>
            <p v-if="commentModal.error" class="comment-error">{{ commentModal.error }}</p>
          </div>
          <div class="comment-footer">
            <button class="btn-cancel-comment" @click="cancelComment" :disabled="confirming">Cancelar</button>
            <button class="btn-confirm-comment"
              :class="commentModal.action === 'Rejeitado' ? 'bcc-rejeitar' : 'bcc-resolver'" @click="confirmComment"
              :disabled="confirming">
              <svg v-if="confirming" class="spin" width="14" height="14" fill="none" stroke="currentColor"
                stroke-width="2.2" viewBox="0 0 16 16">
                <path d="M8 2a6 6 0 0 1 6 6" stroke-linecap="round" />
              </svg>
              {{ confirming ? 'A processar…' : commentModal.action === 'Rejeitado' ? 'Confirmar Rejeição' : 'Confirmar Resolução' }}
            </button>
          </div>
        </div>
      </div>
    </transition>

    <!-- TOAST -->
    <transition name="toast-anim">
      <div class="toast" :class="{ red: toast.red }" v-if="toast.show">
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
import { ref, reactive, computed, nextTick, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { InternalService } from '@/api/services/internal.service'
import AdminProfilePanel from '@/components/AdminProfilePanel.vue'
import AdminNotificationPanel from '@/components/AdminNotificationPanel.vue'

const router = useRouter()
const auth = useAuthStore()

// Províncias e projectos atribuídos ao gestor
const scopeProvinces = computed(() => auth.user?.provinces ?? [])
const scopeProjects = computed(() => auth.user?.projects ?? [])

// ─── UI state ────────────────────────────────────────────────
const topSearch = ref('')
const page = ref(1)
const perPage = 10
const selected = ref(null)
const confirming = ref(false)
const showModal = ref(false)
const lightboxImg = ref(null)
const commentTextareaRef = ref(null)
const commentModal = reactive({ show: false, action: '', text: '', error: '' })
const toast = reactive({ show: false, msg: '', red: false })
const loading = ref(false)

// ─── Reference data ───────────────────────────────────────────
const refCategories = ref([])
const rows = ref([])
const f = reactive({ provincia: '', projeto: '', data: '', status: '', categoria: '', origem: '' })

// ─── Status helpers ───────────────────────────────────────────
const ACTION_TO_API = {
  'Em Analise': 'in_review',
  'Resolvido': 'resolved',
  'Rejeitado': 'rejected',
}
const STATUS_LABEL = {
  pending: 'Pendente',
  in_review: 'Em Análise',
  resolved: 'Resolvida',
  rejected: 'Rejeitada',
  closed: 'Encerrada',
}

function statusClass(s) {
  const map = { pending: 'pendente', in_review: 'em-analise', resolved: 'resolvido', rejected: 'rejeitado', closed: 'fechado' }
  return map[s] ?? 'pendente'
}

function mapOccurrence(o) {
  return {
    _id: o.id,
    id: o.tracking_code,
    data: o.submitted_at,
    provincia: o.province?.name ?? '—',
    categoria: o.category?.name ?? '—',
    canal: o.submission_channel_label ?? '—',
    responsavel: o.assigned_to?.name ?? null,
    projeto: o.project?.name ?? '—',
    status: o.status,
    status_label: o.status_label,
    origem: o.origin,
    coords: o.location_detail ?? '',
    denunciante: o.complainant?.name ?? null,
    email_afectado: o.complainant?.email ?? null,
    telefone: o.complainant?.phone ?? null,
    registado_por: o.submitted_by?.name ?? null,
    descricao: o.description ?? '',
    foto: null,
    anexos: [],
    comentario: '',
  }
}

// ─── Load data ────────────────────────────────────────────────
onMounted(async () => {
  // Garante que o auth store tem as províncias e projectos do gestor
  try { await auth.fetchMe() } catch { /* ignora se falhar */ }

  try {
    const data = await InternalService.getFormData()
    refCategories.value = data.categories ?? []
  } catch (e) { console.error('Erro ao carregar form data:', e) }

  await loadOccurrences()
})

async function loadOccurrences() {
  loading.value = true
  try {
    const res = await InternalService.getOccurrences({ per_page: 200 })
    const TERMINAL = ['resolved', 'rejected', 'closed']
    const scopeProvNames = scopeProvinces.value.map(p => p.name)
    const scopeProjNames = scopeProjects.value.map(p => p.name)

    rows.value = (res.data ?? [])
      .filter(o => !TERMINAL.includes(o.status))
      .filter(o => {
        const provOk = scopeProvNames.length === 0 || scopeProvNames.includes(o.province?.name)
        const projOk = scopeProjNames.length === 0 || !o.project?.name || scopeProjNames.includes(o.project.name)
        return provOk && projOk
      })
      .map(mapOccurrence)
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
}

// ─── Select row ───────────────────────────────────────────────
async function selectRow(r) {
  selected.value = { ...r }
  showModal.value = false
  try {
    const res = await InternalService.getOccurrence(r._id)
    const full = res.data ?? res

    const rawAnexos = (full.attachments ?? []).map(a => ({
      _attId: a.id,
      tipo: a.is_image ? 'imagem' : (a.name.split('.').pop().toLowerCase()),
      nome: a.name,
      url: a.url ?? '',
      tamanho: a.size ?? '',
    }))

    const anexos = await Promise.all(rawAnexos.map(async (a) => {
      if (a.tipo === 'imagem') {
        try { a.url = await InternalService.getAttachmentBlobUrl(r._id, a._attId) } catch { }
      }
      return a
    }))

    selected.value.anexos = anexos
    selected.value.foto = anexos.find(a => a.tipo === 'imagem')?.url ?? null

    if (full.history?.length) {
      const last = [...full.history].reverse().find(h => h.comment)
      if (last) selected.value.comentario = last.comment
    }
  } catch (e) { console.error('Erro ao carregar detalhes:', e) }
}

function openFullModal() { showModal.value = true }
function closeFullModal() { showModal.value = false }

// ─── Filters ──────────────────────────────────────────────────
const imageAnexos = computed(() => selected.value?.anexos?.filter(a => a.tipo === 'imagem') ?? [])
const docAnexos = computed(() => selected.value?.anexos?.filter(a => a.tipo !== 'imagem') ?? [])

const filteredRows = computed(() => {
  let list = rows.value
  if (topSearch.value.trim()) {
    const q = topSearch.value.toLowerCase()
    list = list.filter(r => r.id.toLowerCase().includes(q) || r.descricao.toLowerCase().includes(q) || (r.responsavel && r.responsavel.toLowerCase().includes(q)))
  }
  if (f.provincia) list = list.filter(r => r.provincia === f.provincia)
  if (f.projeto) list = list.filter(r => r.projeto === f.projeto)
  if (f.data) list = list.filter(r => r.data === f.data)
  if (f.status) list = list.filter(r => r.status === f.status)
  if (f.categoria) list = list.filter(r => r.categoria === f.categoria)
  if (f.origem) list = list.filter(r => r.origem === f.origem)
  return list
})

const totalPages = computed(() => Math.max(1, Math.ceil(filteredRows.value.length / perPage)))
const pagedRows = computed(() => filteredRows.value.slice((page.value - 1) * perPage, page.value * perPage))

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
  commentModal.action = action; commentModal.text = ''; commentModal.error = ''
  commentModal.show = true
  nextTick(() => commentTextareaRef.value?.focus())
}

function cancelComment() { commentModal.show = false; commentModal.text = ''; commentModal.error = '' }

async function confirmComment() {
  if (confirming.value) return
  const trimmed = commentModal.text.trim()
  if (!trimmed) { commentModal.error = 'Este campo é obrigatório.'; return }
  if (trimmed.length < 10) { commentModal.error = 'O comentário deve ter pelo menos 10 caracteres.'; return }
  commentModal.error = ''
  const action = commentModal.action; const comment = trimmed
  commentModal.show = false
  await changeStatus(action, comment)
}

async function downloadAnexo(a) {
  try {
    const blobUrl = await InternalService.getAttachmentBlobUrl(selected.value._id, a._attId)
    const link = document.createElement('a'); link.href = blobUrl; link.download = a.nome; link.click()
    setTimeout(() => URL.revokeObjectURL(blobUrl), 60000)
  } catch { showToast('Erro ao descarregar o ficheiro.', true) }
}

async function changeStatus(newState, comment = '') {
  if (!selected.value || confirming.value) return
  confirming.value = true
  const apiStatus = ACTION_TO_API[newState]
  try {
    await InternalService.updateStatus(selected.value._id, { status: apiStatus, comment })
    const trackingCode = selected.value.id
    const isFinal = apiStatus === 'resolved' || apiStatus === 'rejected'
    if (isFinal) {
      rows.value = rows.value.filter(r => r._id !== selected.value._id)
      selected.value = null
    } else {
      const idx = rows.value.findIndex(r => r._id === selected.value._id)
      if (idx !== -1) { rows.value[idx].status = apiStatus; rows.value[idx].status_label = STATUS_LABEL[apiStatus] }
      selected.value.status = apiStatus
      selected.value.status_label = STATUS_LABEL[apiStatus]
      selected.value.comentario = comment
    }
    showToast(newState === 'Rejeitado' ? `${trackingCode} foi rejeitada.` : `${trackingCode} passou para "${STATUS_LABEL[apiStatus]}".`, newState === 'Rejeitado')
  } catch (e) {
    const errors = e?.response?.data?.errors
    showToast(errors ? Object.values(errors).flat()[0] : 'Erro ao actualizar o estado. Tente novamente.', true)
  } finally { confirming.value = false }
}

async function handleLogout() {
  try { await auth.logout() } catch { }
  router.push('/acessoRestrito')
}
</script>

<style scoped>
.app-shell {
  display: flex;
  width: 100%;
  height: 100vh;
  overflow: hidden;
  background: #EDF2EF;
}

/* SIDEBAR */
.sidebar {
  width: 220px;
  flex-shrink: 0;
  background: #fff;
  display: flex;
  flex-direction: column;
  height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
  z-index: 50;
  border-right: 1px solid var(--border);
  box-shadow: 2px 0 16px rgba(0,0,0,0.06);
}

.sidebar-logo {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 20px 18px 18px;
  border-bottom: 1px solid var(--border);
  text-decoration: none;
}

.sidebar-logo-img {
  width: 34px;
  height: 34px;
  object-fit: contain;
  border-radius: 8px;
  flex-shrink: 0;
}

.sidebar-logo-text {
  font-size: 13px;
  font-weight: 800;
  color: var(--green-dark);
  line-height: 1.2;
  letter-spacing: 0.2px;
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

.nav-item.active,
.nav-item.router-link-exact-active {
  background: var(--green-bg);
  color: var(--green-mid);
  font-weight: 700;
  border-left-color: #52B788;
  padding-left: 9px;
}

.nav-item svg {
  flex-shrink: 0;
  opacity: 0.7;
}

.nav-item:hover svg { opacity: 1; }

.nav-item.active svg,
.nav-item.router-link-exact-active svg {
  opacity: 1;
}

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

/* MAIN */
.main {
  margin-left: 220px;
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
  height: 62px;
  background: var(--white);
  box-shadow: 0 2px 12px rgba(0,0,0,0.04);
  border-bottom: 1px solid var(--border);
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

.search-wrap input::placeholder { color: var(--text-light); }

.topbar-spacer { flex: 1; }

/* CONTENT */
.content {
  flex: 1;
  overflow-y: auto;
  padding: 26px 30px 36px;
  background: #EDF2EF;
}

.content::-webkit-scrollbar { width: 5px; }
.content::-webkit-scrollbar-thumb { background: #C8D8CE; border-radius: 99px; }

/* PAGE HEADER */
.page-title-row {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  margin-bottom: 18px;
}

.page-title-row h1 {
  font-size: 22px;
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
  height: 22px;
  background: linear-gradient(180deg, #52B788 0%, #2D6A4F 100%);
  border-radius: 99px;
  flex-shrink: 0;
}

.page-title-row p {
  font-size: 13px;
  color: var(--text-gray);
  max-width: 480px;
  line-height: 1.55;
}

.header-badges {
  display: flex;
  gap: 8px;
  flex-shrink: 0;
  margin-top: 4px;
}

.badge-em-analise {
  font-size: 12px;
  font-weight: 700;
  color: #B45309;
  background: #FFFBEB;
  border: 1.5px solid #FCD34D;
  border-radius: 99px;
  padding: 4px 12px;
}

.badge-pendentes {
  font-size: 12px;
  font-weight: 700;
  color: #1D4ED8;
  background: #EFF6FF;
  border: 1.5px solid #93C5FD;
  border-radius: 99px;
  padding: 4px 12px;
}

/* SCOPE BANNER */
.scope-banner {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 7px;
  background: linear-gradient(135deg, #EBF8F1 0%, #f0faf5 100%);
  border: 1px solid #B7E4CA;
  border-left: 4px solid #52B788;
  border-radius: 10px;
  padding: 11px 18px;
  margin-bottom: 18px;
  font-size: 12.5px;
}

.scope-label {
  font-weight: 600;
  color: var(--text-dark);
  margin-right: 2px;
}

.scope-sep {
  color: var(--text-light);
  margin: 0 2px;
}

.scope-tag {
  font-size: 11.5px;
  font-weight: 600;
  border-radius: 6px;
  padding: 2px 9px;
}

.scope-prov {
  background: #EBF8F1;
  color: var(--green-dark);
  border: 1px solid #B7E4CA;
}

.scope-proj {
  background: #EFF6FF;
  color: #1D4ED8;
  border: 1px solid #93C5FD;
}

/* FILTER CARD */
.filter-card {
  background: var(--white);
  border-radius: 14px;
  padding: 18px 20px 14px;
  margin-bottom: 20px;
  box-shadow: 0 1px 3px rgba(0,0,0,.05), 0 4px 14px rgba(0,0,0,.06);
}

.filter-row, .filter-row-2 {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 12px;
  margin-bottom: 12px;
}

.filter-row-2 { margin-bottom: 0; }

.filter-group {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.filter-group label {
  font-size: 11.5px;
  font-weight: 600;
  color: var(--text-gray);
  text-transform: uppercase;
  letter-spacing: 0.4px;
}

.filter-group select,
.filter-group input[type="date"] {
  font-family: 'Poppins', sans-serif;
  font-size: 12.5px;
  color: var(--text-dark);
  border: 1.5px solid var(--border);
  border-radius: 8px;
  padding: 8px 10px;
  outline: none;
  background: var(--white);
  transition: border-color 0.2s;
}

.filter-group select:focus,
.filter-group input[type="date"]:focus {
  border-color: var(--green-light);
  box-shadow: 0 0 0 3px rgba(82, 183, 136, .12);
}

.filter-actions {
  display: flex;
  align-items: flex-end;
}

.btn-limpar {
  display: flex;
  align-items: center;
  gap: 6px;
  background: var(--white);
  border: 1.5px solid var(--border);
  border-radius: 8px;
  padding: 8px 14px;
  font-family: 'Poppins', sans-serif;
  font-size: 12.5px;
  font-weight: 600;
  color: var(--text-gray);
  cursor: pointer;
  transition: border-color 0.2s, color 0.2s;
}

.btn-limpar:hover { border-color: #FC8181; color: #C53030; }

/* SPLIT */
.split {
  display: grid;
  grid-template-columns: 1fr 380px;
  gap: 18px;
  align-items: start;
}

/* TABLE CARD */
.table-card {
  background: var(--white);
  border-radius: 14px;
  box-shadow: 0 1px 3px rgba(0,0,0,.05), 0 4px 14px rgba(0,0,0,.06);
  overflow: hidden;
}

.table-overflow { overflow-x: auto; }

table {
  width: 100%;
  border-collapse: collapse;
  font-size: 12.5px;
}

thead tr {
  background: #F4FAF7;
  border-bottom: 1.5px solid #D8EDE2;
}

th {
  padding: 11px 14px;
  text-align: left;
  font-size: 10.5px;
  font-weight: 700;
  color: #5A7A69;
  text-transform: uppercase;
  letter-spacing: 0.6px;
  white-space: nowrap;
}

td {
  padding: 11px 14px;
  border-bottom: 1px solid #F4F6F5;
  color: var(--text-dark);
  vertical-align: middle;
}

tbody tr { cursor: pointer; transition: background 0.12s; }
tbody tr:hover { background: #F3FAF6; }
tbody tr.selected { background: #E6F5EC; border-left: 3px solid #52B788; }

.id-link { font-weight: 700; color: var(--green-mid); }
.td-muted { color: var(--text-gray); }
.td-small { font-size: 12px; }
.resp-none { color: var(--text-light); }

.empty-row {
  text-align: center;
  padding: 40px;
  color: var(--text-gray);
  font-size: 13px;
}

/* PAGINATION */
.pagination-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 16px;
  border-top: 1px solid var(--border);
}

.pagination-info { font-size: 12px; color: var(--text-gray); }

.pagination-btns { display: flex; gap: 8px; }

.pg-btn {
  background: var(--white);
  border: 1.5px solid var(--border);
  border-radius: 7px;
  padding: 6px 14px;
  font-family: 'Poppins', sans-serif;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  color: var(--text-dark);
  transition: border-color 0.2s;
}

.pg-btn:hover:not(:disabled) { border-color: var(--green-light); }
.pg-btn:disabled { opacity: 0.4; cursor: not-allowed; }

/* BADGE STATUS */
.badge-status {
  font-size: 11px;
  font-weight: 700;
  border-radius: 99px;
  padding: 3px 10px;
  white-space: nowrap;
}

.badge-status.pendente { background: #EFF6FF; color: #1D4ED8; border: 1.5px solid #93C5FD; }
.badge-status.em-analise { background: #FFFBEB; color: #B45309; border: 1.5px solid #FCD34D; }
.badge-status.resolvido { background: var(--green-pale); color: var(--green-dark); border: 1.5px solid #68D391; }
.badge-status.rejeitado { background: #FFF5F5; color: #C53030; border: 1.5px solid #FEB2B2; }
.badge-status.fechado { background: #F4F6F5; color: var(--text-gray); border: 1.5px solid var(--border); }

/* DETAIL PANEL */
.detail-panel {
  background: var(--white);
  border-radius: 14px;
  box-shadow: 0 1px 3px rgba(0,0,0,.05), 0 4px 14px rgba(0,0,0,.06);
  padding: 20px;
  position: sticky;
  top: 0;
  max-height: calc(100vh - 180px);
  overflow-y: auto;
}

.detail-panel::-webkit-scrollbar { width: 4px; }
.detail-panel::-webkit-scrollbar-thumb { background: #C8D8CE; border-radius: 99px; }

.empty-detail {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 14px;
  padding: 40px 20px;
  text-align: center;
}

.empty-detail p { font-size: 13px; color: var(--text-gray); line-height: 1.6; }

.detail-panel-hd {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 14px;
}

.detail-panel-title {
  display: flex;
  align-items: center;
  gap: 7px;
  font-size: 13px;
  font-weight: 700;
  color: var(--text-dark);
}

.btn-close-panel {
  width: 28px;
  height: 28px;
  background: #F4F6F5;
  border: 1.5px solid var(--border);
  border-radius: 7px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.btn-close-panel:hover { background: #FFF5F5; border-color: #FC8181; }

.status-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 12px;
}

.sub-date {
  font-size: 11.5px;
  color: var(--text-gray);
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 1px;
}

.sub-date span { font-size: 10px; text-transform: uppercase; letter-spacing: 0.4px; color: var(--text-light); }

.rec-title { margin-bottom: 16px; }
.rec-id { font-size: 15px; font-weight: 800; color: var(--text-dark); margin-bottom: 3px; }
.rec-cat { font-size: 12px; color: var(--text-gray); }

.section-title {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 11.5px;
  font-weight: 700;
  color: var(--text-gray);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 8px;
  margin-top: 14px;
}

.evidence-wrap {
  position: relative;
  border-radius: 10px;
  overflow: hidden;
  background: #F4F6F5;
  margin-bottom: 4px;
}

.evidence-img { width: 100%; height: 160px; object-fit: cover; display: block; }

.evidence-placeholder {
  height: 100px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  color: var(--text-light);
}

.evidence-count-badge {
  position: absolute;
  bottom: 8px;
  right: 8px;
  background: rgba(0,0,0,.55);
  color: #fff;
  font-size: 10.5px;
  font-weight: 600;
  border-radius: 6px;
  padding: 2px 8px;
}

.desc-box {
  background: #F4F6F5;
  border-radius: 9px;
  padding: 12px 14px;
  font-size: 12.5px;
  color: var(--text-dark);
  line-height: 1.65;
  margin-bottom: 4px;
}

/* BTN VER COMPLETO */
.btn-ver-completo {
  width: 100%;
  background: var(--white);
  border: 1.5px solid var(--border);
  border-radius: 10px;
  padding: 10px 14px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  margin: 14px 0;
  transition: border-color 0.2s, background 0.2s;
}

.btn-ver-completo:hover { border-color: var(--green-light); background: var(--green-bg); }

.bvc-left { display: flex; align-items: center; gap: 10px; }

.bvc-icon {
  width: 32px;
  height: 32px;
  background: var(--green-bg);
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--green-mid);
  flex-shrink: 0;
}

.bvc-title { font-size: 12.5px; font-weight: 700; color: var(--text-dark); text-align: left; }
.bvc-sub { font-size: 11px; color: var(--text-gray); text-align: left; margin-top: 1px; }
.bvc-right { display: flex; align-items: center; gap: 7px; color: var(--text-gray); flex-shrink: 0; }

.bvc-pill {
  font-size: 11px;
  font-weight: 600;
  background: var(--green-bg);
  color: var(--green-mid);
  border-radius: 99px;
  padding: 2px 9px;
}

/* INFO GRID */
.info-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
  margin-bottom: 16px;
}

.info-block {
  background: #F4F6F5;
  border-radius: 9px;
  padding: 10px 12px;
}

.info-label { font-size: 10.5px; font-weight: 700; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
.info-val { font-size: 13px; font-weight: 700; color: var(--text-dark); }
.info-sub { font-size: 11px; color: var(--text-gray); margin-top: 2px; }

/* STATE ACTIONS */
.state-actions {
  border-top: 1px solid var(--border);
  padding-top: 16px;
}

.state-flow {
  display: flex;
  align-items: center;
  gap: 6px;
  margin-bottom: 14px;
}

.flow-step {
  font-size: 11px;
  font-weight: 600;
  color: var(--text-light);
  padding: 3px 8px;
  border-radius: 99px;
  background: #F4F6F5;
}

.flow-step.flow-active { color: var(--green-dark); background: var(--green-pale); border: 1.5px solid #68D391; }
.flow-step.flow-done { color: var(--green-mid); background: var(--green-bg); }
.flow-step.flow-skip { color: #C53030; background: #FFF5F5; }

.flow-chevron { color: #C8D8CE; flex-shrink: 0; }

.action-hint { font-size: 12px; color: var(--text-gray); margin-bottom: 10px; line-height: 1.5; }

.dual-action-btns { display: flex; gap: 8px; }

.btn-validar {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  background: #40916C;
  color: #fff;
  border: none;
  border-radius: 10px;
  padding: 11px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
  box-shadow: 0 2px 8px rgba(64,145,108,0.3);
}

.btn-validar:hover:not(:disabled) {
  background: #2D6A4F;
  transform: translateY(-1px);
  box-shadow: 0 4px 14px rgba(64,145,108,0.4);
}
.btn-validar:disabled { opacity: 0.55; cursor: not-allowed; box-shadow: none; }

.btn-rejeitar-outline {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  background: var(--white);
  color: #C53030;
  border: 1.5px solid #FEB2B2;
  border-radius: 10px;
  padding: 11px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.2s, border-color 0.2s, transform 0.15s;
}

.btn-rejeitar-outline:hover:not(:disabled) {
  background: #FFF5F5;
  border-color: #FC8181;
  transform: translateY(-1px);
}
.btn-rejeitar-outline:disabled { opacity: 0.55; cursor: not-allowed; }

.btn-confirmar {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 7px;
  background: #40916C;
  color: #fff;
  border: none;
  border-radius: 10px;
  padding: 12px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  margin-bottom: 8px;
  transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
  box-shadow: 0 2px 8px rgba(64,145,108,0.3);
}

.btn-confirmar:hover:not(:disabled) {
  background: #2D6A4F;
  transform: translateY(-1px);
  box-shadow: 0 4px 14px rgba(64,145,108,0.4);
}
.btn-confirmar:disabled { opacity: 0.55; cursor: not-allowed; box-shadow: none; }

.btn-rejeitar-secondary {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 7px;
  background: var(--white);
  color: #C53030;
  border: 1.5px solid #FEB2B2;
  border-radius: 10px;
  padding: 10px;
  font-family: 'Poppins', sans-serif;
  font-size: 12.5px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s, border-color 0.2s, transform 0.15s;
}

.btn-rejeitar-secondary:hover:not(:disabled) {
  background: #FFF5F5;
  border-color: #FC8181;
  transform: translateY(-1px);
}
.btn-rejeitar-secondary:disabled { opacity: 0.55; cursor: not-allowed; }

/* STATE FINAL */
.state-final {
  display: flex;
  align-items: center;
  gap: 12px;
  border-radius: 10px;
  padding: 14px;
  margin-bottom: 10px;
}

.sf-resolvido { background: var(--green-pale); color: var(--green-dark); }
.sf-rejeitado { background: #FFF5F5; color: #C53030; }

.sf-icon { flex-shrink: 0; }
.sf-title { font-size: 13px; font-weight: 700; }
.sf-sub { font-size: 11.5px; opacity: 0.75; margin-top: 2px; }

.sf-comment {
  border-radius: 8px;
  padding: 10px 13px;
  font-size: 12.5px;
  line-height: 1.6;
  font-style: italic;
}

.sf-comment-resolvido { background: #EBF8F1; color: var(--green-dark); }
.sf-comment-rejeitado { background: #FFF5F5; color: #C53030; }

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

.dash-footer a { color: var(--text-light); text-decoration: none; margin-left: 16px; }
.dash-footer a:hover { color: var(--green-mid); }

/* MODAL */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 200;
  padding: 16px;
}

.modal-card {
  background: var(--white);
  border-radius: 18px;
  width: 720px;
  max-width: 95vw;
  max-height: 90vh;
  display: flex;
  flex-direction: column;
  box-shadow: 0 20px 70px rgba(0,0,0,.22);
  overflow: hidden;
}

.modal-hd {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  padding: 22px 26px 16px;
  border-bottom: 1px solid var(--border);
  flex-shrink: 0;
}

.modal-hd-left {}
.modal-hd-id { font-size: 16px; font-weight: 800; color: var(--text-dark); margin-bottom: 3px; }
.modal-hd-cat { font-size: 12.5px; color: var(--text-gray); }

.modal-hd-right {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-shrink: 0;
}

.btn-close-modal {
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

.btn-close-modal:hover { background: #FFF5F5; border-color: #FC8181; }

.modal-body {
  flex: 1;
  overflow-y: auto;
  padding: 20px 26px;
}

.modal-body::-webkit-scrollbar { width: 5px; }
.modal-body::-webkit-scrollbar-thumb { background: #C8D8CE; border-radius: 99px; }

.modal-hero-wrap {
  position: relative;
  border-radius: 12px;
  overflow: hidden;
  margin-bottom: 16px;
  background: #F4F6F5;
}

.modal-hero { width: 100%; height: 220px; object-fit: cover; display: block; }

.modal-hero-empty {
  height: 160px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 10px;
  color: var(--text-light);
  font-size: 13px;
}

.modal-hero-meta {
  position: absolute;
  bottom: 10px;
  left: 12px;
  right: 12px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.modal-hero-date {
  font-size: 11.5px;
  color: #fff;
  background: rgba(0,0,0,.45);
  border-radius: 6px;
  padding: 3px 9px;
}

.modal-meta-strip {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 10px;
  background: #F4F6F5;
  border-radius: 10px;
  padding: 12px 16px;
  margin-bottom: 16px;
}

.modal-meta-item {}
.modal-meta-label { font-size: 10px; font-weight: 700; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px; }
.modal-meta-val { font-size: 12.5px; font-weight: 700; color: var(--text-dark); }

.modal-section { margin-bottom: 20px; }

.modal-section-hd {
  display: flex;
  align-items: center;
  gap: 7px;
  font-size: 12px;
  font-weight: 700;
  color: var(--text-gray);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 10px;
}

.section-count {
  font-size: 11px;
  font-weight: 700;
  background: var(--green-bg);
  color: var(--green-mid);
  border-radius: 99px;
  padding: 1px 7px;
  margin-left: 4px;
}

.modal-desc {
  background: #F4F6F5;
  border-radius: 10px;
  padding: 14px 16px;
  font-size: 13px;
  color: var(--text-dark);
  line-height: 1.7;
  font-style: italic;
}

.modal-two-col {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
}

.modal-info-block {
  background: #F4F6F5;
  border-radius: 10px;
  padding: 12px 14px;
}

.modal-info-label { font-size: 10px; font-weight: 700; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
.modal-info-val { font-size: 13px; font-weight: 700; color: var(--text-dark); }
.modal-info-sub { font-size: 11.5px; color: var(--text-gray); margin-top: 3px; }

.no-attachments {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 16px;
  background: #F4F6F5;
  border-radius: 10px;
  font-size: 12.5px;
  color: var(--text-gray);
}

.attachment-gallery {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 8px;
  margin-bottom: 10px;
}

.attachment-thumb {
  position: relative;
  border-radius: 8px;
  overflow: hidden;
  cursor: pointer;
  background: #F4F6F5;
}

.attachment-thumb img { width: 100%; height: 90px; object-fit: cover; display: block; }

.attachment-thumb-overlay {
  position: absolute;
  inset: 0;
  background: rgba(0,0,0,.35);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.2s;
}

.attachment-thumb:hover .attachment-thumb-overlay { opacity: 1; }

.btn-img-dl {
  position: absolute;
  top: 6px;
  right: 6px;
  width: 24px;
  height: 24px;
  background: rgba(0,0,0,.5);
  border: none;
  border-radius: 5px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  cursor: pointer;
  opacity: 0;
  transition: opacity 0.2s;
}

.attachment-thumb:hover .btn-img-dl { opacity: 1; }

.attachment-thumb-name {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: rgba(0,0,0,.55);
  color: #fff;
  font-size: 10px;
  padding: 3px 6px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.attachment-doc-list { display: flex; flex-direction: column; gap: 6px; }

.attachment-doc-row {
  display: flex;
  align-items: center;
  gap: 10px;
  background: #F4F6F5;
  border-radius: 8px;
  padding: 10px 12px;
}

.doc-icon-box {
  width: 36px;
  height: 36px;
  border-radius: 7px;
  background: var(--green-bg);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  color: var(--green-mid);
}

.doc-ext { font-size: 8px; font-weight: 800; color: var(--green-mid); letter-spacing: 0.3px; }
.doc-meta { flex: 1; min-width: 0; }
.doc-name { font-size: 12.5px; font-weight: 600; color: var(--text-dark); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.doc-size { font-size: 11px; color: var(--text-gray); margin-top: 2px; }

.btn-doc-open {
  display: flex;
  align-items: center;
  gap: 5px;
  background: var(--white);
  border: 1.5px solid var(--border);
  border-radius: 7px;
  padding: 5px 10px;
  font-family: 'Poppins', sans-serif;
  font-size: 11.5px;
  font-weight: 600;
  color: var(--text-gray);
  cursor: pointer;
  flex-shrink: 0;
  transition: border-color 0.2s;
}

.btn-doc-open:hover { border-color: var(--green-light); color: var(--green-mid); }

.modal-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 14px 26px;
  border-top: 1px solid var(--border);
  flex-shrink: 0;
}

.modal-footer-info {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  color: var(--text-gray);
}

.btn-modal-close {
  background: var(--white);
  border: 1.5px solid var(--border);
  border-radius: 9px;
  padding: 9px 22px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 600;
  color: var(--text-gray);
  cursor: pointer;
  transition: border-color 0.2s;
}

.btn-modal-close:hover { border-color: var(--text-gray); }

/* LIGHTBOX */
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

/* COMMENT MODAL */
.comment-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 300;
  padding: 16px;
}

.comment-card {
  background: var(--white);
  border-radius: 16px;
  width: 480px;
  max-width: 95vw;
  box-shadow: 0 16px 60px rgba(0,0,0,.2);
  overflow: hidden;
}

.comment-hd {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 18px 22px 16px;
}

.chd-rejeitar { background: #FFF5F5; border-bottom: 1.5px solid #FEB2B2; }
.chd-resolver { background: var(--green-pale); border-bottom: 1.5px solid #68D391; }

.comment-hd-left { display: flex; align-items: center; gap: 12px; }

.comment-hd-icon {
  width: 38px;
  height: 38px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.chd-rejeitar .comment-hd-icon { background: #FEB2B2; color: #C53030; }
.chd-resolver .comment-hd-icon { background: #68D391; color: var(--green-dark); }

.comment-hd-title { font-size: 14px; font-weight: 800; color: var(--text-dark); }
.comment-hd-id { font-size: 11.5px; color: var(--text-gray); margin-top: 2px; }

.btn-close-comment {
  width: 30px;
  height: 30px;
  background: rgba(0,0,0,.06);
  border: none;
  border-radius: 7px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.comment-body { padding: 18px 22px; }

.comment-field-label {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  font-weight: 700;
  color: var(--text-dark);
  margin-bottom: 6px;
}

.comment-required {
  font-size: 10.5px;
  font-weight: 600;
  color: #E53E3E;
  background: #FFF5F5;
  border-radius: 5px;
  padding: 1px 7px;
}

.comment-hint { font-size: 12px; color: var(--text-gray); line-height: 1.6; margin-bottom: 10px; }

.comment-textarea {
  width: 100%;
  border: 1.5px solid var(--border);
  border-radius: 9px;
  padding: 11px 13px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  color: var(--text-dark);
  resize: vertical;
  outline: none;
  transition: border-color 0.2s;
  box-sizing: border-box;
}

.comment-textarea:focus { border-color: var(--green-light); box-shadow: 0 0 0 3px rgba(82,183,136,.12); }

.comment-char {
  font-size: 11px;
  color: var(--text-light);
  text-align: right;
  margin-top: 4px;
}

.comment-char-warn { color: #C53030; }
.comment-error { font-size: 12px; color: #C53030; margin-top: 6px; }

.comment-footer {
  display: flex;
  gap: 10px;
  padding: 14px 22px;
  border-top: 1px solid var(--border);
}

.btn-cancel-comment {
  flex: 1;
  background: var(--white);
  border: 1.5px solid var(--border);
  border-radius: 9px;
  padding: 11px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 600;
  color: var(--text-gray);
  cursor: pointer;
}

.btn-confirm-comment {
  flex: 2;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 7px;
  border: none;
  border-radius: 9px;
  padding: 11px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  color: #fff;
  transition: opacity 0.2s;
}

.btn-confirm-comment:disabled { opacity: 0.6; cursor: not-allowed; }
.bcc-rejeitar { background: #C53030; }
.bcc-resolver { background: var(--green-mid); }

/* SPIN */
.spin {
  animation: spin 0.7s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
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
  box-shadow: 0 8px 28px rgba(0,0,0,.18);
}

.toast.red { background: #C53030; }

/* TRANSITIONS */
.modal-fade-enter-active, .modal-fade-leave-active { transition: opacity 0.22s ease; }
.modal-fade-enter-from, .modal-fade-leave-to { opacity: 0; }

.lightbox-fade-enter-active, .lightbox-fade-leave-active { transition: opacity 0.2s; }
.lightbox-fade-enter-from, .lightbox-fade-leave-to { opacity: 0; }

.toast-anim-enter-active, .toast-anim-leave-active { transition: opacity 0.3s, transform 0.3s; }
.toast-anim-enter-from, .toast-anim-leave-to { opacity: 0; transform: translateY(16px); }
</style>
