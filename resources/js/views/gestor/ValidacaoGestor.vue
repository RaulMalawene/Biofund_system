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
          <input type="text" placeholder="Pesquisar ocorrências ou utilizador" v-model="topSearchRaw" />
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
          <div class="header-right">
            <button class="btn-registar" @click="openRegistoModal">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 14 14">
                <path d="M7 1v12M1 7h12" stroke-linecap="round" />
              </svg>
              Registar Nova
            </button>
            <div class="header-badges">
              <span class="badge-em-analise">{{ countStatus('por_resolver') }} Por Resolver</span>
              <span class="badge-pendentes">{{ countStatus('por_validar') }} Por Validar</span>
            </div>
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
                <option value="por_validar">Por Validar</option>
                <option value="por_resolver">Por Resolver</option>
                <option value="nao_validado">Não Validado</option>
                <option value="resolvendo">Resolvendo</option>
                <option value="resolvido">Resolvido</option>
                <option value="nao_resolvida">Não Resolvida</option>
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
                        <span v-else class="resp-none">-</span>
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
                ocorrências</span>
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
              <p>Seleccione uma ocorrência para ver os detalhes e validar</p>
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

              <div class="panel-body">
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
                  <div class="info-sub" v-if="selected.telefone">{{ selected.telefone }}</div>
                  <div class="info-sub" v-if="selected.sexo">{{ selected.sexo === 'masculino' ? 'Masculino' : 'Feminino' }}</div>
                  <div class="info-sub" v-if="selected.idade">{{ selected.idade }}</div>
                </div>
              </div>

              <div class="state-actions">
                <div class="state-flow">
                  <span class="flow-step"
                    :class="{ 'flow-active': selected.status === 'por_validar', 'flow-done': ['por_resolver','resolvendo','resolvido'].includes(selected.status), 'flow-skip': ['nao_validado','nao_resolvida'].includes(selected.status) }">Por Validar</span>
                  <svg class="flow-chevron" width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 10 10">
                    <path d="M3 2l4 3-4 3" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <span class="flow-step"
                    :class="{ 'flow-active': selected.status === 'por_resolver', 'flow-done': ['resolvendo','resolvido'].includes(selected.status), 'flow-skip': ['nao_validado','nao_resolvida'].includes(selected.status) }">Por Resolver</span>
                  <svg class="flow-chevron" width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 10 10">
                    <path d="M3 2l4 3-4 3" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <span class="flow-step"
                    :class="{ 'flow-active': selected.status === 'resolvendo', 'flow-done': selected.status === 'resolvido', 'flow-skip': selected.status === 'nao_resolvida' }">Resolvendo</span>
                  <svg class="flow-chevron" width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 10 10">
                    <path d="M3 2l4 3-4 3" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <span class="flow-step" :class="{ 'flow-active': selected.status === 'resolvido', 'flow-skip': selected.status === 'nao_resolvida' }">Resolvido</span>
                </div>

                <template v-if="selected.status === 'por_validar'">
                  <p class="action-hint">Valide a ocorrência para que seja tratada, ou recuse caso não seja válida.</p>
                  <div class="dual-action-btns">
                    <button class="btn-validar" @click="changeStatus('PorResolver')" :disabled="confirming">
                      <svg v-if="confirming" class="spin" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 16 16">
                        <path d="M8 2a6 6 0 0 1 6 6" stroke-linecap="round" />
                      </svg>
                      <svg v-else width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 16 16">
                        <circle cx="8" cy="8" r="6" />
                        <path d="M5.5 8l2 2 3.5-4" stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                      {{ confirming ? 'A validar…' : 'Validar' }}
                    </button>
                    <button class="btn-rejeitar-outline" @click="openCommentModal('NaoValidado')" :disabled="confirming">
                      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 16 16">
                        <circle cx="8" cy="8" r="6" />
                        <path d="M5.5 5.5l5 5M10.5 5.5l-5 5" stroke-linecap="round" />
                      </svg>
                      Não Validar
                    </button>
                  </div>
                </template>

                <template v-else-if="selected.status === 'por_resolver'">
                  <p class="action-hint">Ocorrência validada. Inicie a resolução quando estiver a ser tratada.</p>
                  <button class="btn-confirmar" @click="changeStatus('Resolvendo')" :disabled="confirming">
                    <svg v-if="confirming" class="spin" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 16 16">
                      <path d="M8 2a6 6 0 0 1 6 6" stroke-linecap="round" />
                    </svg>
                    <svg v-else width="15" height="15" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 16 16">
                      <circle cx="8" cy="8" r="6" />
                      <path d="M8 5v3l2 2" stroke-linecap="round" />
                    </svg>
                    {{ confirming ? 'A processar…' : 'Iniciar Resolução' }}
                  </button>
                </template>

                <template v-else-if="selected.status === 'resolvendo'">
                  <p class="action-hint">A ocorrência está a ser resolvida. Marque como resolvida quando concluída.</p>
                  <button class="btn-confirmar" @click="openCommentModal('Resolvido')" :disabled="confirming">
                    <svg width="15" height="15" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 16 16">
                      <circle cx="8" cy="8" r="6" />
                      <path d="M5.5 8l2 2 3.5-4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Marcar como Resolvido
                  </button>
                </template>

                <template v-else-if="selected.status === 'resolvido'">
                  <div class="state-final sf-resolvido">
                    <div class="sf-icon"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 20 20">
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

                <template v-else-if="selected.status === 'nao_validado'">
                  <div class="state-final sf-rejeitado">
                    <div class="sf-icon"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 20 20">
                        <circle cx="10" cy="10" r="8" />
                        <path d="M7 7l6 6M13 7l-6 6" stroke-linecap="round" />
                      </svg></div>
                    <div>
                      <div class="sf-title">Ocorrência Não Validada</div>
                      <div class="sf-sub">Esta ocorrência foi recusada.</div>
                    </div>
                  </div>
                  <div v-if="selected.comentario" class="sf-comment sf-comment-rejeitado">{{ selected.comentario }}</div>
                </template>

                <template v-else-if="selected.status === 'nao_resolvida'">
                  <div class="state-final sf-nao-resolvida">
                    <div class="sf-icon"><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 20 20">
                        <circle cx="10" cy="10" r="8" />
                        <path d="M10 6v4M10 13.5v.5" stroke-linecap="round" />
                      </svg></div>
                    <div>
                      <div class="sf-title">Não Resolvida</div>
                      <div class="sf-sub">Marcada automaticamente por falta de actividade durante 5 dias.</div>
                    </div>
                  </div>
                </template>

                <!-- Adicionar comentário - disponível em todos os estados -->
                <button class="btn-add-comment" @click="openCommentModal('Comentario')" :disabled="confirming">
                  <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 14 14">
                    <path d="M12 2H2a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h2l2 2 2-2h4a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1z" />
                    <path d="M4 6h6M4 8.5h4" stroke-linecap="round" />
                  </svg>
                  Adicionar Comentário
                </button>
              </div>
              </div><!-- /panel-body -->
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
              <!-- Edição de Projecto/Categoria apenas no estado 'Por Resolver' -->
              <button v-if="selected.status === 'por_resolver'" class="btn-edit-class" @click="toggleEditMode" :class="{ active: editMode }">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 14 14">
                  <path d="M9.5 2.5l2 2L4 12H2v-2L9.5 2.5z" stroke-linejoin="round" />
                </svg>
                {{ editMode ? 'Cancelar' : 'Editar Classificação' }}
              </button>
              <button class="btn-close-modal" @click="closeFullModal">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 14 14">
                  <path d="M2 2l10 10M12 2L2 12" stroke-linecap="round" />
                </svg>
              </button>
            </div>
          </div>

          <!-- ── EDIT CLASSIFICATION BAR (só quando editMode=true) ── -->
          <transition name="edit-slide">
            <div class="edit-class-bar" v-if="editMode">
              <div class="edit-class-title">
                <svg width="14" height="14" fill="none" stroke="var(--green-mid)" stroke-width="1.8"
                  viewBox="0 0 16 16">
                  <path d="M9.5 2.5l2 2L4 12H2v-2L9.5 2.5z" stroke-linejoin="round" />
                </svg>
                Reclassificar Ocorrência
              </div>
              <div class="edit-class-fields">
                <div class="edit-field">
                  <label>Projecto</label>
                  <select v-model="editForm.project_id" :disabled="editSaving">
                    <option value="">Sem projecto</option>
                    <option v-for="p in scopeProjects" :key="p.id" :value="p.id">{{ p.name }}</option>
                  </select>
                </div>
                <div class="edit-field">
                  <label>Categoria</label>
                  <select v-model="editForm.category_id" :disabled="editSaving">
                    <option value="">Sem categoria</option>
                    <option v-for="c in refCategories" :key="c.id" :value="c.id">{{ c.name }}</option>
                  </select>
                </div>
                <div class="edit-field" v-if="editSubcategoriasDisponiveis.length">
                  <label>Subcategoria</label>
                  <select v-model="editForm.subcategory_id" :disabled="editSaving">
                    <option value="">Sem subcategoria</option>
                    <option v-for="s in editSubcategoriasDisponiveis" :key="s.id" :value="s.id">{{ s.name }}</option>
                  </select>
                </div>
                <div class="edit-class-actions">
                  <button class="btn-edit-cancel" @click="toggleEditMode" :disabled="editSaving">Cancelar</button>
                  <button class="btn-edit-save" @click="saveClassification" :disabled="editSaving">
                    <span v-if="editSaving" class="spin-sm"></span>
                    <svg v-else width="13" height="13" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 14 14">
                      <path d="M2 7l4 4 6-6" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    {{ editSaving ? 'A guardar…' : 'Guardar' }}
                  </button>
                </div>
              </div>
            </div>
          </transition>

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
                <span class="modal-meta-val" :class="{ 'edited-field': editMode }">{{ selected.projeto }}</span>
              </div>
              <div class="modal-meta-item">
                <span class="modal-meta-label">Categoria</span>
                <span class="modal-meta-val" :class="{ 'edited-field': editMode }">{{ selected.categoria }}</span>
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
                <div class="modal-info-sub" v-if="selected.sexo">{{ selected.sexo === 'masculino' ? 'Masculino' : 'Feminino' }}</div>
                <div class="modal-info-sub" v-if="selected.idade">{{ selected.idade }}</div>
                <div class="modal-info-sub" v-if="selected.registado_por">Registado por: {{ selected.registado_por }}</div>
              </div>
            </div>

            <div class="modal-section modal-two-col"
              v-if="selected.subcategoria || selected.tipo_ocorrencia || selected.nivel_alerta || selected.distrito || selected.prazo || selected.revisado_por">
              <div class="modal-info-block">
                <div class="modal-info-label">Classificação Adicional</div>
                <div class="modal-info-val">{{ selected.subcategoria ?? 'Sem subcategoria' }}</div>
                <div class="modal-info-sub" v-if="selected.tipo_ocorrencia">Tipo: {{ selected.tipo_ocorrencia }}</div>
                <div class="modal-info-sub" v-if="selected.nivel_alerta">Nível de Alerta: {{ selected.nivel_alerta }}</div>
                <div class="modal-info-sub" v-if="selected.distrito">Distrito: {{ selected.distrito }}</div>
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
          <div class="comment-hd" :class="commentModal.action === 'NaoValidado' ? 'chd-rejeitar' : commentModal.action === 'Comentario' ? 'chd-comentario' : 'chd-resolver'">
            <div class="comment-hd-left">
              <div class="comment-hd-icon">
                <svg v-if="commentModal.action === 'NaoValidado'" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 20 20">
                  <circle cx="10" cy="10" r="8" />
                  <path d="M7 7l6 6M13 7l-6 6" stroke-linecap="round" />
                </svg>
                <svg v-else-if="commentModal.action === 'Comentario'" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 20 20">
                  <path d="M18 2H2a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h3l3 3 3-3h7a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1z" />
                  <path d="M6 8h8M6 11h5" stroke-linecap="round" />
                </svg>
                <svg v-else width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 20 20">
                  <circle cx="10" cy="10" r="8" />
                  <path d="M6.5 10l2.5 2.5 4.5-5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
              </div>
              <div>
                <div class="comment-hd-title">{{ commentModal.action === 'NaoValidado' ? 'Não Validar Ocorrência' : commentModal.action === 'Comentario' ? 'Adicionar Comentário' : 'Marcar como Resolvido' }}</div>
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
              {{ commentModal.action === 'NaoValidado' ? 'Motivo da Não Validação' : commentModal.action === 'Comentario' ? 'Comentário' : 'Descrição da Resolução' }}
              <span class="comment-required">obrigatório</span>
            </div>
            <p class="comment-hint">
              {{ commentModal.action === 'NaoValidado'
                ? 'Explique o motivo pelo qual esta ocorrência não foi validada.'
                : commentModal.action === 'Comentario'
                  ? 'Escreva um comentário sobre esta ocorrência.'
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
              :class="commentModal.action === 'NaoValidado' ? 'bcc-rejeitar' : commentModal.action === 'Comentario' ? 'bcc-comentario' : 'bcc-resolver'"
              @click="confirmComment" :disabled="confirming">
              <svg v-if="confirming" class="spin" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 16 16">
                <path d="M8 2a6 6 0 0 1 6 6" stroke-linecap="round" />
              </svg>
              {{ confirming ? 'A processar…' : commentModal.action === 'NaoValidado' ? 'Confirmar Não Validação' : commentModal.action === 'Comentario' ? 'Guardar Comentário' : 'Confirmar Resolução' }}
            </button>
          </div>
        </div>
      </div>
    </transition>

    <!-- ── MODAL REGISTO ── -->
    <transition name="modal-fade">
      <div class="modal-overlay" v-if="showRegistoModal" @click.self="closeRegistoModal"></div>
    </transition>
    <transition name="modal-pop">
      <div v-if="showRegistoModal" class="registo-panel">
        <div class="registo-header">
          <div>
            <div class="registo-title">Registar Nova Ocorrência</div>
            <div class="registo-subtitle">Preencha os dados do incidente ambiental observado.</div>
          </div>
          <button class="registo-close" @click="closeRegistoModal">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 13 13">
              <path d="M2 2l9 9M11 2L2 11" stroke-linecap="round" />
            </svg>
          </button>
        </div>

        <div class="registo-body">

          <div class="r-error-banner" v-if="rSubmitError">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 16 16">
              <circle cx="8" cy="8" r="6"/><path d="M8 5v3M8 11h.01" stroke-linecap="round"/>
            </svg>
            {{ rSubmitError }}
          </div>

          <!-- Dados da Ocorrência -->
          <div class="r-section-title">Dados da Ocorrência</div>
          <div class="r-row">
            <div class="r-group">
              <label>Projecto <span class="r-req">*</span></label>
              <select v-model="nf.project_id" :class="{ 'r-err': rErrors.project_id }" @change="rErrors.project_id = ''">
                <option value="" disabled>Seleccione o projecto</option>
                <option v-for="p in scopeProjects" :key="p.id" :value="p.id">{{ p.name }}</option>
              </select>
              <span class="r-err-msg" v-if="rErrors.project_id">{{ rErrors.project_id }}</span>
            </div>
            <div class="r-group">
              <label>Categoria <span class="r-req">*</span></label>
              <select v-model="nf.category_id" :class="{ 'r-err': rErrors.category_id }" @change="rErrors.category_id = ''">
                <option value="" disabled>Seleccione a categoria</option>
                <option v-for="c in refCategories" :key="c.id" :value="c.id">{{ c.name }}</option>
              </select>
              <span class="r-err-msg" v-if="rErrors.category_id">{{ rErrors.category_id }}</span>
            </div>
          </div>

          <div class="r-row" v-if="subcategoriasDisponiveis.length">
            <div class="r-group">
              <label>Subcategoria <span class="r-opt">(Opcional)</span></label>
              <select v-model="nf.subcategory_id">
                <option value="">Seleccione a subcategoria (opcional)</option>
                <option v-for="s in subcategoriasDisponiveis" :key="s.id" :value="s.id">{{ s.name }}</option>
              </select>
            </div>
          </div>

          <div class="r-group">
            <label>Assunto <span class="r-req">*</span></label>
            <input type="text" v-model="nf.subject" maxlength="255"
              :class="{ 'r-err': rErrors.subject }"
              placeholder="Ex: Poluição do rio Incomáti"
              @input="rErrors.subject = ''" />
            <span class="r-err-msg" v-if="rErrors.subject">{{ rErrors.subject }}</span>
          </div>

          <div class="r-row">
            <div class="r-group">
              <label>Tipo de Ocorrência <span class="r-req">*</span></label>
              <select v-model="nf.occurrence_type_id" :class="{ 'r-err': rErrors.occurrence_type_id }" @change="rErrors.occurrence_type_id = ''">
                <option value="" disabled>Seleccione o tipo</option>
                <option v-for="t in refTypes" :key="t.id" :value="t.id">{{ t.name }}</option>
              </select>
              <span class="r-err-msg" v-if="rErrors.occurrence_type_id">{{ rErrors.occurrence_type_id }}</span>
            </div>
            <div class="r-group">
              <label>Nível de Alerta <span class="r-req">*</span></label>
              <select v-model="nf.alert_type" :class="{ 'r-err': rErrors.alert_type }" @change="rErrors.alert_type = ''">
                <option value="" disabled>Seleccione o nível</option>
                <option value="normal">Normal</option>
                <option value="urgent">Urgente</option>
                <option value="gbv">GBV - Violência de Género</option>
              </select>
              <span class="r-err-msg" v-if="rErrors.alert_type">{{ rErrors.alert_type }}</span>
            </div>
          </div>

          <div class="r-row">
            <div class="r-group">
              <label>Canal de Submissão <span class="r-req">*</span></label>
              <select v-model="nf.submission_channel" :class="{ 'r-err': rErrors.submission_channel }" @change="rErrors.submission_channel = ''">
                <option value="" disabled>Seleccione</option>
                <option value="green_line">Linha Verde</option>
                <option value="email">Email</option>
                <option value="phone">Telefone</option>
                <option value="community_meeting">Reunião Comunitária</option>
              </select>
              <span class="r-err-msg" v-if="rErrors.submission_channel">{{ rErrors.submission_channel }}</span>
            </div>
            <div class="r-group">
              <label>Data da Ocorrência</label>
              <div class="date-input-wrap">
                <input type="date" v-model="nf.occurrence_date" :max="today" :class="{ 'is-empty': !nf.occurrence_date }" />
                <span class="date-placeholder" v-if="!nf.occurrence_date">dia / mês / ano</span>
              </div>
            </div>
          </div>

          <!-- Informação do Reclamante -->
          <div class="r-section-title">Informação do Reclamante</div>
          <div class="r-row">
            <div class="r-group">
              <label>Nome <span class="r-opt">(Opcional)</span></label>
              <input type="text" v-model="nf.complainant_name" placeholder="Nome completo ou pseudónimo" />
            </div>
            <div class="r-group">
              <label>Sexo <span class="r-opt">(Opcional)</span></label>
              <select v-model="nf.complainant_gender">
                <option value="">Não identificado</option>
                <option value="masculino">Masculino</option>
                <option value="feminino">Feminino</option>
              </select>
            </div>
          </div>
          <div class="r-row">
            <div class="r-group">
              <label>Faixa Etária <span class="r-opt">(Opcional)</span></label>
              <select v-model="nf.complainant_age">
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
          <div class="r-row">
            <div class="r-group">
              <label>Email <span class="r-req-hint">*pelo menos um</span></label>
              <input type="email" v-model="nf.complainant_email"
                :class="{ 'r-err': rErrors.contact }"
                placeholder="email@exemplo.com"
                @input="rErrors.contact = ''" />
            </div>
            <div class="r-group">
              <label>Telefone <span class="r-req-hint">*pelo menos um</span></label>
              <input type="tel" v-model="nf.complainant_phone"
                :class="{ 'r-err': rErrors.contact }"
                placeholder="+258 84 000 0000"
                @input="rErrors.contact = ''" />
            </div>
          </div>
          <div v-if="rErrors.contact" class="r-contact-hint r-hint-error">{{ rErrors.contact }}</div>
          <div v-else class="r-contact-hint">Preencha pelo menos email ou telefone.</div>

          <!-- Localização -->
          <div class="r-section-title">Localização</div>
          <div class="r-row">
            <div class="r-group">
              <label>Província <span class="r-req">*</span></label>
              <select v-model="nf.province_id"
                :class="{ 'r-err': rErrors.province_id }"
                :disabled="scopeProvinces.length === 1"
                @change="onProvinceChange">
                <option value="" disabled>Seleccione</option>
                <option v-for="p in scopeProvinces" :key="p.id" :value="p.id">{{ p.name }}</option>
              </select>
              <span class="r-err-msg" v-if="rErrors.province_id">{{ rErrors.province_id }}</span>
            </div>
            <div class="r-group">
              <label>Distrito</label>
              <select v-model="nf.district_id" :disabled="!nf.province_id || rLoadingDistricts">
                <option value="">{{ rLoadingDistricts ? 'A carregar…' : (nf.province_id ? 'Seleccione (opcional)' : 'Escolha uma província') }}</option>
                <option v-for="d in rDistricts" :key="d.id" :value="d.id">{{ d.name }}</option>
              </select>
            </div>
          </div>
          <div class="r-row">
            <div class="r-group">
              <label>Comunidade <span class="r-opt">(Opcional)</span></label>
              <input type="text" v-model="nf.comunidade" placeholder="Ex: Comunidade de Mafuiane" />
            </div>
            <div class="r-group">
              <label>Posto Administrativo <span class="r-opt">(Opcional)</span></label>
              <input type="text" v-model="nf.postoAdministrativo" placeholder="Ex: Posto Administrativo de Manhiça" />
            </div>
          </div>
          <div class="r-group">
            <label>Coordenadas GPS <span class="r-opt">(Opcional)</span></label>
            <input type="text" v-model="nf.coordenadas" placeholder="Ex: -25.9682, 32.5732"
              :disabled="isVbgType" />
            <span class="r-char-hint r-char-warn" v-if="isVbgType">
              Por motivos de privacidade e segurança, as coordenadas GPS não são recolhidas para ocorrências de Violência Baseada no Género (VBG).
            </span>
          </div>

          <!-- Descrição -->
          <div class="r-group">
            <label>Descrição Detalhada <span class="r-req">*</span></label>
            <textarea v-model="nf.description"
              :class="{ 'r-err': rErrors.description }"
              placeholder="Descreva o incidente observado com o máximo de detalhe possível… (mínimo 20 caracteres)"
              rows="4"
              @input="rErrors.description = ''"></textarea>
            <div class="r-char-hint" :class="nf.description.length >= 20 ? 'r-char-ok' : 'r-char-warn'"
              v-if="nf.description.length > 0">
              {{ nf.description.length >= 20 ? '✓ ' + nf.description.length + ' caracteres' : 'Faltam ' + (20 - nf.description.length) + ' caracteres' }}
            </div>
            <span class="r-err-msg" v-if="rErrors.description">{{ rErrors.description }}</span>
          </div>

          <!-- Upload -->
          <div class="r-upload-zone"
            :class="{ 'drag-over': rIsDragging }"
            @click="rFileInput.click()"
            @dragover.prevent="rIsDragging = true"
            @dragleave="rIsDragging = false"
            @drop.prevent="handleRDrop">
            <div class="r-upload-icon">
              <svg width="22" height="22" fill="none" stroke="#2D6A4F" stroke-width="1.7" viewBox="0 0 20 20">
                <path d="M3 15v1.5A1.5 1.5 0 0 0 4.5 18h11A1.5 1.5 0 0 0 17 16.5V15" stroke-linecap="round"/>
                <path d="M10 2v10M6.5 5.5 10 2l3.5 3.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <h4>Anexar ficheiros</h4>
            <p>PNG, JPG, PDF, MP4, MP3 - máx. 10 MB por ficheiro (até 5)</p>
          </div>
          <input ref="rFileInput" type="file" multiple accept=".png,.jpg,.jpeg,.pdf,.mp4,.mp3"
            style="display:none" @change="handleRFileSelect" />

          <div class="r-file-list" v-if="rFiles.length">
            <div class="r-file-item" v-for="(f, i) in rFiles" :key="i">
              <svg width="14" height="14" fill="none" stroke="#2D6A4F" stroke-width="1.5" viewBox="0 0 16 16">
                <rect x="2" y="1" width="10" height="14" rx="1.5"/>
                <path d="M5 5h4M5 8h4M5 11h2" stroke-linecap="round"/>
                <path d="M10 1v4h4" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="r-file-name">{{ f.name }}</span>
              <span class="r-file-size">{{ (f.size / 1024).toFixed(0) }} KB</span>
              <button class="r-btn-rm" @click.stop="rFiles.splice(i, 1)">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 12 12">
                  <path d="M2 2l8 8M10 2L2 10" stroke-linecap="round"/>
                </svg>
              </button>
            </div>
          </div>

          <div class="r-actions">
            <button class="r-btn-cancelar" @click="closeRegistoModal" :disabled="rSaving">Cancelar</button>
            <button class="r-btn-submit" @click="saveRegisto" :disabled="rSaving">
              <span v-if="rSaving" class="r-spin"></span>
              {{ rSaving ? 'A registar…' : 'Registar Ocorrência' }}
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
import { ref, reactive, computed, watch, nextTick, onMounted, onActivated } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { InternalService } from '@/api/services/internal.service'
import AdminProfilePanel from '@/components/AdminProfilePanel.vue'
import AdminNotificationPanel from '@/components/AdminNotificationPanel.vue'

const router = useRouter()
const route  = useRoute()
const auth   = useAuthStore()

const sidebarOpen = ref(false)

// Províncias e projectos atribuídos ao gestor
const scopeProvinces = computed(() => auth.user?.provinces ?? [])
const scopeProjects = computed(() => auth.user?.projects ?? [])

// ─── UI state ────────────────────────────────────────────────
const topSearchRaw = ref('')
const topSearch    = ref('')
let _stDebounce    = null
watch(topSearchRaw, v => {
  clearTimeout(_stDebounce)
  _stDebounce = setTimeout(() => { topSearch.value = v }, 250)
})
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

// ─── Edit classification state ───────────────────────────────
const editMode   = ref(false)
const editSaving = ref(false)
const editForm   = reactive({ project_id: '', category_id: '', subcategory_id: '' })

// Subcategorias da categoria seleccionada na edição de classificação (opcional)
const editSubcategoriasDisponiveis = computed(() => {
  const c = refCategories.value.find(c => c.id === editForm.category_id)
  return c?.subcategories ?? []
})

watch(() => editForm.category_id, () => {
  if (!editSubcategoriasDisponiveis.value.some(s => s.id === editForm.subcategory_id)) {
    editForm.subcategory_id = ''
  }
})

// ─── Reference data ───────────────────────────────────────────
const refCategories = ref([])
const rows = ref([])
const f = reactive({ provincia: '', projeto: '', data: '', status: '', categoria: '', origem: '' })

// ─── Status helpers ───────────────────────────────────────────
const ACTION_TO_API = {
  'PorResolver': 'por_resolver',
  'NaoValidado': 'nao_validado',
  'Resolvendo':  'resolvendo',
  'Resolvido':   'resolvido',
}
const STATUS_LABEL = {
  por_validar:   'Por Validar',
  por_resolver:  'Por Resolver',
  nao_validado:  'Não Validado',
  resolvendo:    'Resolvendo',
  resolvido:     'Resolvido',
  nao_resolvida: 'Não Resolvida',
}

function statusClass(s) {
  const map = {
    por_validar:   'por-validar',
    por_resolver:  'por-resolver',
    nao_validado:  'nao-validado',
    resolvendo:    'resolvendo',
    resolvido:     'resolvido',
    nao_resolvida: 'nao-resolvida',
  }
  return map[s] ?? 'por-validar'
}

function mapOccurrence(o) {
  return {
    _id: o.id,
    id: o.tracking_code,
    data: o.submitted_at,
    provincia: o.province?.name ?? '-',
    categoria: o.category?.name ?? '-',
    canal: o.submission_channel_label ?? '-',
    responsavel: o.assigned_to?.name ?? null,
    projeto: o.project?.name ?? '-',
    status: o.status,
    status_label: o.status_label,
    origem: o.origin,
    coords: o.location_detail ?? '',
    denunciante: o.complainant?.name ?? null,
    email_afectado: o.complainant?.email ?? null,
    telefone: o.complainant?.phone ?? null,
    sexo: o.complainant?.gender ?? null,
    idade: o.complainant?.age ?? null,
    registado_por: o.submitted_by?.name ?? null,
    descricao: o.description ?? '',
    foto: null,
    anexos: [],
    comentario: '',
    _project_id: o.project?.id ?? '',
    _category_id: o.category?.id ?? '',
  }
}

// ─── Auto-select vindo do dashboard ───────────────────────────
async function tryAutoSelect() {
  const id = parseInt(route.query.select)
  if (!id) return
  router.replace({ query: {} })
  const row = rows.value.find(r => r._id === id)
  if (row) await selectRow(row)
}

// ─── Load data ────────────────────────────────────────────────
onMounted(async () => {
  InternalService.getFormData()
    .then(data => {
      refCategories.value = data.categories      ?? []
      refTypes.value      = (data.occurrence_types ?? []).filter(t => t.alert_level !== 'urgent')
    })
    .catch(e => console.error('Erro ao carregar form data:', e))

  await loadOccurrences()
  tryAutoSelect()
})

// Quando o componente é reactivado via KeepAlive (ex: vindo do dashboard)
onActivated(() => {
  tryAutoSelect()
})

async function loadOccurrences() {
  loading.value = true
  try {
    const res = await InternalService.getOccurrences({ per_page: 200 })
    const TERMINAL = ['resolvido', 'nao_validado', 'nao_resolvida']
    rows.value = (res.data ?? []).map(mapOccurrence).filter(r => !TERMINAL.includes(r.status))
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
  editMode.value = false
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

    selected.value.subcategoria    = full.subcategory?.name ?? null
    selected.value._subcategory_id = full.subcategory?.id ?? ''
    selected.value.tipo_ocorrencia = full.type?.name ?? null
    selected.value.nivel_alerta    = full.alert_type_label ?? null
    selected.value.distrito        = full.district?.name ?? null
    selected.value.prazo           = full.due_date ?? null
    selected.value.atrasada        = full.is_overdue ?? false
    selected.value.data_revisao    = full.reviewed_at ?? null
    selected.value.revisado_por    = full.reviewed_by ?? null

    if (full.history?.length) {
      const last = [...full.history].reverse().find(h => h.comment)
      if (last) selected.value.comentario = last.comment
    }
  } catch (e) { console.error('Erro ao carregar detalhes:', e) }
}

function openFullModal() { editMode.value = false; showModal.value = true }
function closeFullModal() { showModal.value = false; editMode.value = false }

// ─── Edit classification ──────────────────────────────────────
function toggleEditMode() {
  editMode.value = !editMode.value
  if (editMode.value && selected.value) {
    editForm.project_id  = selected.value._project_id  ?? ''
    editForm.category_id = selected.value._category_id ?? ''
    editForm.subcategory_id = selected.value._subcategory_id ?? ''
  }
}

async function saveClassification() {
  if (!selected.value || editSaving.value) return
  editSaving.value = true
  try {
    const payload = {}
    if (editForm.project_id)  payload.project_id  = editForm.project_id
    if (editForm.category_id) payload.category_id = editForm.category_id
    payload.subcategory_id = editForm.subcategory_id || null
    const result = await InternalService.updateClassification(selected.value._id, payload)
    if (result.project) {
      selected.value.projeto     = result.project.name
      selected.value._project_id = result.project.id
    }
    if (result.category) {
      selected.value.categoria     = result.category.name
      selected.value._category_id  = result.category.id
    }
    selected.value.subcategoria    = result.subcategory?.name ?? null
    selected.value._subcategory_id = result.subcategory?.id ?? ''
    const idx = rows.value.findIndex(r => r._id === selected.value._id)
    if (idx !== -1) {
      if (result.project)  rows.value[idx].projeto   = result.project.name
      if (result.category) rows.value[idx].categoria = result.category.name
    }
    editMode.value = false
    showToast('Classificação actualizada com sucesso.')
  } catch (e) {
    showToast(e.response?.data?.message ?? 'Erro ao guardar. Tente novamente.', true)
  } finally {
    editSaving.value = false
  }
}

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
  if (f.data) {
    const [y, m, d] = f.data.split('-')
    const prefix = `${d}/${m}/${y}`
    list = list.filter(r => r.data.startsWith(prefix))
  }
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
  if (action === 'Comentario') {
    await addStandaloneComment(comment)
  } else {
    await changeStatus(action, comment)
  }
}

async function downloadAnexo(a) {
  try {
    const blobUrl = await InternalService.getAttachmentBlobUrl(selected.value._id, a._attId)
    const link = document.createElement('a'); link.href = blobUrl; link.download = a.nome; link.click()
    setTimeout(() => URL.revokeObjectURL(blobUrl), 60000)
  } catch { showToast('Erro ao descarregar o ficheiro.', true) }
}

async function addStandaloneComment(comment) {
  if (!selected.value || confirming.value) return
  confirming.value = true
  try {
    await InternalService.addComment(selected.value._id, { comment })
    showToast('Comentário adicionado com sucesso.')
  } catch (e) {
    const errors = e?.response?.data?.errors
    showToast(errors ? Object.values(errors).flat()[0] : 'Erro ao adicionar comentário. Tente novamente.', true)
  } finally { confirming.value = false }
}

async function changeStatus(newState, comment = '') {
  if (!selected.value || confirming.value) return
  confirming.value = true
  const apiStatus = ACTION_TO_API[newState]
  try {
    const payload = { status: apiStatus }
    if (comment && comment.trim()) payload.comment = comment.trim()
    const result = await InternalService.updateStatus(selected.value._id, payload)
    const trackingCode = selected.value.id
    const isFinal = ['nao_validado', 'resolvido', 'nao_resolvida'].includes(apiStatus)
    if (isFinal) {
      rows.value = rows.value.filter(r => r._id !== selected.value._id)
      selected.value = null
      editMode.value = false
    } else {
      const idx = rows.value.findIndex(r => r._id === selected.value._id)
      if (idx !== -1) {
        rows.value[idx].status        = apiStatus
        rows.value[idx].status_label  = STATUS_LABEL[apiStatus]
        if (result.assigned_to) rows.value[idx].responsavel = result.assigned_to.name
      }
      selected.value.status       = apiStatus
      selected.value.status_label = STATUS_LABEL[apiStatus]
      selected.value.comentario   = comment
      if (result.assigned_to) selected.value.responsavel = result.assigned_to.name
      if (editMode.value) editMode.value = false
    }
    showToast(newState === 'NaoValidado' ? `${trackingCode} foi não validada.` : `${trackingCode} passou para "${STATUS_LABEL[apiStatus]}".`, newState === 'NaoValidado')
  } catch (e) {
    const errors = e?.response?.data?.errors
    showToast(errors ? Object.values(errors).flat()[0] : 'Erro ao actualizar o estado. Tente novamente.', true)
  } finally { confirming.value = false }
}

// ─── Registo Modal ────────────────────────────────────────────
const today             = new Date().toISOString().split('T')[0]
const refTypes          = ref([])
const rDistricts        = ref([])
const rLoadingDistricts = ref(false)
const showRegistoModal  = ref(false)
const rSaving           = ref(false)
const rSubmitError      = ref('')
const rIsDragging       = ref(false)
const rFiles            = ref([])
const rFileInput        = ref(null)

const nf = reactive({
  complainant_name: '', complainant_gender: '', complainant_age: '', complainant_email: '', complainant_phone: '',
  subject: '', project_id: '', category_id: '', subcategory_id: '', occurrence_type_id: '',
  alert_type: '', submission_channel: '', occurrence_date: '',
  province_id: '', district_id: '', comunidade: '', postoAdministrativo: '', coordenadas: '',
  description: '',
})

const rErrors = reactive({
  contact: '', subject: '', project_id: '', category_id: '',
  occurrence_type_id: '', alert_type: '', submission_channel: '',
  province_id: '', description: '',
})

// Tipo VBG seleccionado - restringe a recolha de coordenadas GPS
// por motivos de privacidade e segurança do reclamante.
const isVbgType = computed(() => {
  const t = refTypes.value.find(t => t.id === nf.occurrence_type_id)
  return t?.alert_level === 'gbv' || nf.alert_type === 'gbv'
})

watch(isVbgType, (vbg) => {
  if (vbg) nf.coordenadas = ''
})

// Subcategorias da categoria seleccionada (opcional)
const subcategoriasDisponiveis = computed(() => {
  const c = refCategories.value.find(c => c.id === nf.category_id)
  return c?.subcategories ?? []
})

watch(() => nf.category_id, () => {
  nf.subcategory_id = ''
})

function openRegistoModal() {
  const singleProvince = scopeProvinces.value.length === 1 ? scopeProvinces.value[0].id : ''
  Object.assign(nf, {
    complainant_name: '', complainant_gender: '', complainant_age: '', complainant_email: '', complainant_phone: '',
    subject: '', project_id: '', category_id: '', subcategory_id: '', occurrence_type_id: '',
    alert_type: '', submission_channel: '', occurrence_date: '',
    province_id: singleProvince, district_id: '',
    comunidade: '', postoAdministrativo: '', coordenadas: '',
    description: '',
  })
  Object.assign(rErrors, {
    contact: '', subject: '', project_id: '', category_id: '',
    occurrence_type_id: '', alert_type: '', submission_channel: '',
    province_id: '', description: '',
  })
  rSubmitError.value = ''
  rFiles.value = []
  rDistricts.value = []
  if (singleProvince) onProvinceChange()
  showRegistoModal.value = true
}

function closeRegistoModal() { showRegistoModal.value = false }

async function onProvinceChange() {
  nf.district_id = ''
  rDistricts.value = []
  rErrors.province_id = ''
  if (!nf.province_id) return
  rLoadingDistricts.value = true
  try {
    const data = await InternalService.getDistrictsByProvince(nf.province_id)
    rDistricts.value = data.districts ?? data
  } catch {}
  finally { rLoadingDistricts.value = false }
}

function handleRFileSelect(e) { addRFiles(Array.from(e.target.files)); e.target.value = '' }
function handleRDrop(e) { rIsDragging.value = false; addRFiles(Array.from(e.dataTransfer.files)) }
function addRFiles(list) {
  list.forEach(f => {
    if (rFiles.value.length >= 5) return
    if (f.size <= 10 * 1024 * 1024) rFiles.value.push(f)
  })
}

function validateRegisto() {
  let ok = true
  if (!nf.complainant_email.trim() && !nf.complainant_phone.trim()) {
    rErrors.contact = 'Preencha pelo menos email ou número de telefone.'
    ok = false
  }
  if (!nf.subject.trim())           { rErrors.subject            = 'O assunto é obrigatório.';               ok = false }
  if (!nf.project_id)               { rErrors.project_id         = 'Seleccione o projecto.';                 ok = false }
  if (!nf.category_id)              { rErrors.category_id        = 'Seleccione a categoria.';                ok = false }
  if (!nf.occurrence_type_id)       { rErrors.occurrence_type_id = 'Seleccione o tipo de ocorrência.';       ok = false }
  if (!nf.alert_type)               { rErrors.alert_type         = 'Seleccione o nível de alerta.';          ok = false }
  if (!nf.submission_channel)       { rErrors.submission_channel = 'Seleccione o canal de submissão.';       ok = false }
  if (!nf.province_id)              { rErrors.province_id        = 'Seleccione a província.';                ok = false }
  if (!nf.description.trim())       { rErrors.description        = 'A descrição é obrigatória.';             ok = false }
  else if (nf.description.trim().length < 20) {
                                      rErrors.description        = 'A descrição deve ter pelo menos 20 caracteres.'; ok = false }
  return ok
}

async function saveRegisto() {
  rSubmitError.value = ''
  Object.assign(rErrors, {
    contact: '', subject: '', project_id: '', category_id: '',
    occurrence_type_id: '', alert_type: '', submission_channel: '',
    province_id: '', description: '',
  })

  if (!validateRegisto()) {
    rSubmitError.value = 'Corrija os erros assinalados e tente novamente.'
    return
  }

  rSaving.value = true
  try {
    const fd = new FormData()
    if (nf.complainant_name.trim())  fd.append('complainant_name',   nf.complainant_name.trim())
    if (nf.complainant_gender)       fd.append('complainant_gender', nf.complainant_gender)
    if (nf.complainant_age)          fd.append('complainant_age',    nf.complainant_age)
    if (nf.complainant_email.trim()) fd.append('complainant_email',  nf.complainant_email.trim())
    if (nf.complainant_phone.trim()) fd.append('complainant_phone',  nf.complainant_phone.trim())
    fd.append('subject',            nf.subject.trim())
    fd.append('project_id',         nf.project_id)
    fd.append('category_id',        nf.category_id)
    if (nf.subcategory_id) fd.append('subcategory_id', nf.subcategory_id)
    fd.append('occurrence_type_id', nf.occurrence_type_id)
    fd.append('alert_type',         nf.alert_type)
    fd.append('submission_channel', nf.submission_channel)
    fd.append('province_id',        nf.province_id)
    fd.append('description',        nf.description.trim())
    if (nf.district_id)     fd.append('district_id',     nf.district_id)
    if (nf.occurrence_date) fd.append('occurrence_date', nf.occurrence_date)
    const locationParts = []
    if (nf.comunidade.trim())          locationParts.push(nf.comunidade.trim())
    if (nf.postoAdministrativo.trim()) locationParts.push(nf.postoAdministrativo.trim())
    if (nf.coordenadas.trim())         locationParts.push(nf.coordenadas.trim())
    if (locationParts.length) fd.append('location_detail', locationParts.join(' - '))
    rFiles.value.forEach(f => fd.append('attachments[]', f))

    const res = await InternalService.createOccurrence(fd)
    closeRegistoModal()
    showToast(`Ocorrência ${res.tracking_code ?? ''} registada com sucesso!`)
    await loadOccurrences()
  } catch (err) {
    if (err.response?.status === 422) {
      const serverErrors = err.response.data.errors ?? {}
      Object.entries(serverErrors).forEach(([field, msgs]) => {
        if (field in rErrors) rErrors[field] = msgs[0]
      })
      rSubmitError.value = 'Corrija os erros assinalados e tente novamente.'
    } else {
      rSubmitError.value = err.response?.data?.message ?? 'Erro ao registar. Tente novamente.'
    }
  } finally {
    rSaving.value = false
  }
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
  color: #713F12;
  background: #FACC15;
  border: 1.5px solid #CA8A04;
  border-radius: 99px;
  padding: 4px 12px;
}

.badge-pendentes {
  font-size: 12px;
  font-weight: 700;
  color: #fff;
  background: #FB923C;
  border: 1.5px solid #EA580C;
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

.badge-status.por-validar   { background: #FB923C; color: #fff;    border: 1.5px solid #EA580C; }
.badge-status.por-resolver  { background: #FACC15; color: #713F12; border: 1.5px solid #CA8A04; }
.badge-status.nao-validado  { background: #EF4444; color: #fff;    border: 1.5px solid #DC2626; }
.badge-status.resolvendo    { background: #3b82f6; color: #fff;    border: 1.5px solid #2563EB; }
.badge-status.resolvido     { background: #22C55E; color: #fff;    border: 1.5px solid #16A34A; }
.badge-status.nao-resolvida { background: #7C3AED; color: #fff;    border: 1.5px solid #6D28D9; }

/* DETAIL PANEL */
.detail-panel {
  background: var(--white);
  border-radius: 14px;
  box-shadow: 0 1px 3px rgba(0,0,0,.05), 0 4px 14px rgba(0,0,0,.06);
  overflow: hidden;
  position: sticky;
  top: 0;
  max-height: calc(100vh - 180px);
  display: flex;
  flex-direction: column;
}

.panel-body {
  flex: 1;
  overflow-y: auto;
}
.panel-body::-webkit-scrollbar { width: 4px; }
.panel-body::-webkit-scrollbar-thumb { background: #C8D8CE; border-radius: 99px; }

.empty-detail {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 14px;
  padding: 48px 20px;
  text-align: center;
  color: var(--text-light);
}

.empty-detail p { font-size: 12.5px; margin-top: 10px; line-height: 1.55; }

.detail-panel-hd {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 14px 16px 12px;
  border-bottom: 1px solid var(--border);
  flex-shrink: 0;
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
  color: var(--text-gray);
}

.btn-close-panel:hover { background: #FFF5F5; border-color: #FC8181; color: #E53E3E; }

.status-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px 16px;
  border-bottom: 1px solid var(--border);
}

.sub-date {
  font-size: 11px;
  color: var(--text-light);
  text-align: right;
}

.sub-date span { display: block; font-size: 10px; margin-bottom: 1px; }

.rec-title { padding: 10px 16px 12px; border-bottom: 1px solid var(--border); }
.rec-id { font-size: 16px; font-weight: 800; }
.rec-cat { font-size: 12px; color: var(--text-gray); margin-top: 2px; }

.section-title {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  font-weight: 700;
  padding: 12px 16px 8px;
}

.evidence-wrap { position: relative; }

.evidence-img { width: 100%; height: 120px; object-fit: cover; display: block; }

.evidence-placeholder {
  width: 100%;
  height: 72px;
  background: #F4F6F5;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--text-light);
  font-size: 12px;
}

.evidence-count-badge {
  position: absolute;
  bottom: 8px;
  right: 8px;
  background: rgba(0,0,0,.55);
  color: #fff;
  border-radius: 99px;
  padding: 3px 9px;
  font-size: 11px;
  font-weight: 600;
  backdrop-filter: blur(4px);
}


.desc-box {
  margin: 0 16px;
  background: #F4F6F5;
  border-radius: 8px;
  padding: 10px 12px;
  font-size: 12px;
  color: var(--text-gray);
  line-height: 1.65;
  font-style: italic;
  border-left: 3px solid var(--green-light);
  display: -webkit-box;
  -webkit-line-clamp: 3;
  line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}


/* BTN VER COMPLETO */
.btn-ver-completo {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: calc(100% - 32px);
  margin: 10px 16px 0;
  padding: 11px 14px;
  background: linear-gradient(135deg, var(--green-pale) 0%, #EAF4EE 100%);
  border: 1.5px solid var(--green-light);
  border-radius: 11px;
  cursor: pointer;
  transition: all 0.2s;
  font-family: 'Poppins', sans-serif;
}

.btn-ver-completo:hover {
  background: var(--green-bg);
  border-color: var(--green-mid);
  transform: translateY(-1px);
  box-shadow: 0 4px 14px rgba(82, 183, 136, .18);
}

.bvc-left { display: flex; align-items: center; gap: 10px; }

.bvc-icon {
  width: 30px;
  height: 30px;
  border-radius: 8px;
  background: var(--white);
  border: 1.5px solid var(--green-light);
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--green-mid);
  flex-shrink: 0;
}

.bvc-title { font-size: 12.5px; font-weight: 700; color: var(--green-dark); line-height: 1.3; text-align: left; }
.bvc-sub { font-size: 10.5px; color: var(--green-mid); opacity: 0.8; text-align: left; }
.bvc-right { display: flex; align-items: center; gap: 6px; color: var(--green-mid); }

.bvc-pill {
  display: inline-flex;
  align-items: center;
  background: var(--white);
  border: 1.5px solid var(--green-light);
  color: var(--green-mid);
  border-radius: 99px;
  padding: 2px 9px;
  font-size: 11px;
  font-weight: 700;
}

/* INFO GRID */
.info-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  border-top: 1px solid var(--border);
  margin-top: 12px;
}

.info-block {
  padding: 10px 16px;
  border-right: 1px solid var(--border);
}

.info-block:last-child { border-right: none; }

.info-label { font-size: 10px; font-weight: 700; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 4px; }
.info-val { font-size: 12px; font-weight: 600; color: var(--text-dark); margin-bottom: 2px; }
.info-sub { font-size: 10.5px; color: var(--text-gray); }

/* STATE ACTIONS */
.state-actions { padding: 10px 16px 16px; }

.state-flow {
  display: flex;
  align-items: center;
  gap: 5px;
  margin-bottom: 12px;
  padding: 8px 12px;
  background: #F9FBFA;
  border-radius: 9px;
  border: 1px solid var(--border);
}

.flow-step {
  font-size: 10.5px;
  font-weight: 700;
  color: var(--text-light);
  padding: 2px 8px;
  border-radius: 99px;
  transition: all 0.2s;
}

.flow-step.flow-active { background: var(--green-pale); color: var(--green-mid); border: 1.5px solid var(--green-light); }
.flow-step.flow-done { color: var(--green-mid); opacity: 0.6; text-decoration: line-through; }
.flow-step.flow-skip { opacity: 0.35; text-decoration: line-through; }

.flow-chevron { color: var(--border); flex-shrink: 0; }

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

.btn-add-comment {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  background: var(--white);
  border: 1.5px dashed var(--border);
  border-radius: 10px;
  padding: 9px;
  font-family: 'Poppins', sans-serif;
  font-size: 12.5px;
  font-weight: 600;
  color: var(--text-gray);
  cursor: pointer;
  margin-top: 10px;
  transition: border-color 0.2s, color 0.2s;
}
.btn-add-comment:hover:not(:disabled) { border-color: var(--green-light); color: var(--green-mid); }
.btn-add-comment:disabled { opacity: 0.45; cursor: not-allowed; }

/* STATE FINAL */
.state-final {
  display: flex;
  align-items: center;
  gap: 12px;
  border-radius: 10px;
  padding: 14px;
  margin-bottom: 10px;
}

.sf-resolvido     { background: var(--green-pale); color: var(--green-dark); }
.sf-rejeitado     { background: #FFF5F5; color: #C53030; }
.sf-nao-resolvida { background: #F5F3FF; color: #5B21B6; }

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

/* ── MODAL ──────────────────────────────── */
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

/* Modal header */
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

/* Modal body */
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
  transition: color 0.2s;
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

.chd-rejeitar  { background: #FFF5F5; border-bottom: 1.5px solid #FEB2B2; }
.chd-resolver  { background: var(--green-pale); border-bottom: 1.5px solid #68D391; }
.chd-comentario { background: #F0F9FF; border-bottom: 1.5px solid #7DD3FC; }

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

.chd-rejeitar .comment-hd-icon  { background: #FEB2B2; color: #C53030; }
.chd-resolver .comment-hd-icon  { background: #68D391; color: var(--green-dark); }
.chd-comentario .comment-hd-icon { background: #7DD3FC; color: #0369A1; }

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
.bcc-rejeitar   { background: #C53030; }
.bcc-resolver   { background: var(--green-mid); }
.bcc-comentario { background: #0284C7; }

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

/* ── EDIT CLASSIFICATION BUTTON ─────────── */
.btn-edit-class {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  height: 32px;
  padding: 0 14px;
  background: var(--white);
  color: var(--text-gray);
  border: 1.5px solid var(--border);
  border-radius: 8px;
  font-family: 'Poppins', sans-serif;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  white-space: nowrap;
}

.btn-edit-class:hover {
  border-color: var(--green-mid);
  color: var(--green-mid);
}

.btn-edit-class.active {
  background: var(--green-bg);
  border-color: var(--green-mid);
  color: var(--green-mid);
}

/* ── EDIT CLASSIFICATION BAR ────────────── */
.edit-class-bar {
  background: var(--green-bg);
  border-bottom: 2px solid var(--green-light);
  padding: 14px 22px;
  flex-shrink: 0;
}

.edit-class-title {
  display: flex;
  align-items: center;
  gap: 7px;
  font-size: 12.5px;
  font-weight: 700;
  color: var(--green-dark);
  margin-bottom: 12px;
}

.edit-class-fields {
  display: flex;
  align-items: flex-end;
  gap: 12px;
}

.edit-field {
  display: flex;
  flex-direction: column;
  gap: 5px;
  flex: 1;
}

.edit-field label {
  font-size: 11.5px;
  font-weight: 600;
  color: var(--green-dark);
}

.edit-field select {
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  color: var(--text-dark);
  background: var(--white);
  border: 1.5px solid var(--green-light);
  border-radius: 8px;
  padding: 8px 10px;
  outline: none;
  transition: border-color 0.2s, box-shadow 0.2s;
}

.edit-field select:focus {
  border-color: var(--green-mid);
  box-shadow: 0 0 0 3px rgba(82, 183, 136, .18);
}

.edit-field select:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.edit-class-actions {
  display: flex;
  gap: 8px;
  flex-shrink: 0;
}

.btn-edit-cancel {
  height: 40px;
  padding: 0 16px;
  background: var(--white);
  border: 1.5px solid var(--border);
  border-radius: 8px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 600;
  color: var(--text-gray);
  cursor: pointer;
  transition: border-color 0.2s;
}

.btn-edit-cancel:hover:not(:disabled) {
  border-color: var(--text-gray);
}

.btn-edit-cancel:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.btn-edit-save {
  height: 40px;
  padding: 0 20px;
  background: var(--green-mid);
  color: #fff;
  border: none;
  border-radius: 8px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 7px;
  transition: background 0.2s;
}

.btn-edit-save:hover:not(:disabled) {
  background: var(--green-dark);
}

.btn-edit-save:disabled {
  background: #A0C4B0;
  cursor: not-allowed;
}

.edited-field {
  color: var(--green-mid);
  font-weight: 700;
}

/* Edit bar transition */
.edit-slide-enter-active,
.edit-slide-leave-active {
  transition: all 0.25s ease;
}

.edit-slide-enter-from,
.edit-slide-leave-to {
  opacity: 0;
  transform: translateY(-8px);
  max-height: 0;
}

.edit-slide-enter-to,
.edit-slide-leave-from {
  opacity: 1;
  transform: none;
  max-height: 120px;
}

/* Spin small */
.spin-sm {
  display: inline-block;
  width: 13px;
  height: 13px;
  border: 2px solid rgba(255, 255, 255, .35);
  border-top-color: #fff;
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
}

/* ── BOTÃO REGISTAR ─────────────────────────────── */
.header-right {
  display: flex;
  align-items: center;
  gap: 12px;
  flex-shrink: 0;
}

.btn-registar {
  display: flex;
  align-items: center;
  gap: 7px;
  background: #40916C;
  color: #fff;
  border: none;
  border-radius: 10px;
  padding: 9px 16px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
  box-shadow: 0 2px 10px rgba(64,145,108,0.3);
}
.btn-registar:hover { background: #2D6A4F; transform: translateY(-1px); box-shadow: 0 4px 16px rgba(64,145,108,0.4); }

/* ── MODAL REGISTO ──────────────────────────────── */
.registo-panel {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 680px;
  max-width: 96vw;
  max-height: 90vh;
  background: var(--white);
  border-radius: 16px;
  box-shadow: 0 24px 64px rgba(0,0,0,.18);
  z-index: 301;
  display: flex;
  flex-direction: column;
}

.registo-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  padding: 22px 26px 18px;
  border-bottom: 1px solid var(--border);
  flex-shrink: 0;
}
.registo-title    { font-size: 17px; font-weight: 800; color: var(--text-dark); margin-bottom: 3px; }
.registo-subtitle { font-size: 12px; color: var(--text-gray); }

.registo-close {
  width: 32px; height: 32px;
  display: flex; align-items: center; justify-content: center;
  border: 1.5px solid var(--border);
  border-radius: 8px;
  background: none;
  cursor: pointer;
  color: var(--text-gray);
  transition: border-color 0.2s, color 0.2s;
  flex-shrink: 0;
}
.registo-close:hover { border-color: #E53E3E; color: #E53E3E; }

.registo-body {
  flex: 1;
  overflow-y: auto;
  padding: 22px 26px 32px;
  display: flex;
  flex-direction: column;
}
.registo-body::-webkit-scrollbar { width: 4px; }
.registo-body::-webkit-scrollbar-thumb { background: #C8D8CE; border-radius: 99px; }

.r-error-banner {
  background: #FFF5F5;
  border: 1px solid #FEB2B2;
  border-radius: 9px;
  padding: 11px 14px;
  display: flex;
  align-items: center;
  gap: 9px;
  font-size: 12.5px;
  font-weight: 500;
  color: #C53030;
  margin-bottom: 16px;
}

.r-section-title {
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.8px;
  color: var(--text-light);
  padding-bottom: 6px;
  border-bottom: 1px solid var(--border);
  margin-bottom: 14px;
  margin-top: 6px;
}

.r-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
.r-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px; }
.r-row .r-group { margin-bottom: 0; }

.r-group label {
  font-size: 12.5px;
  font-weight: 600;
  color: var(--text-dark);
  display: flex;
  align-items: center;
  gap: 4px;
}
.r-req { color: #E53E3E; }
.r-opt { font-size: 11px; font-weight: 500; color: var(--text-light); }
.r-req-hint { font-size: 10.5px; font-weight: 500; color: #C66E00; margin-left: 4px; }

.r-group input,
.r-group select,
.r-group textarea {
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  color: var(--text-dark);
  border: 1.5px solid var(--border);
  border-radius: 8px;
  padding: 10px 13px;
  height: 42px;
  outline: none;
  width: 100%;
  appearance: none;
  -webkit-appearance: none;
  transition: border-color 0.2s, box-shadow 0.2s;
  background: var(--white);
}
.r-group select {
  background-image: url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%238A9490' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 12px center;
  padding-right: 32px;
}
.r-group input:focus, .r-group select:focus, .r-group textarea:focus {
  border-color: var(--green-mid);
  box-shadow: 0 0 0 3px rgba(82,183,136,.12);
}
.r-group select:disabled, .r-group input:disabled { opacity: 0.55; cursor: not-allowed; }
.r-group textarea { resize: vertical; min-height: 90px; height: auto; background-image: none; }

.r-err { border-color: #FC8181 !important; box-shadow: 0 0 0 3px rgba(252,129,129,.1) !important; }
.r-err-msg { font-size: 11.5px; color: #E53E3E; }

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

.r-contact-hint { font-size: 11.5px; color: var(--text-light); margin-top: -10px; margin-bottom: 14px; }
.r-hint-error   { color: #C53030; }

.r-char-hint { font-size: 11.5px; margin-top: 3px; }
.r-char-warn { color: #C66E00; }
.r-char-ok   { color: #2D6A4F; }

.r-upload-zone {
  border: 2px dashed var(--border);
  border-radius: 10px;
  padding: 28px 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  background: #F7F9F8;
  transition: border-color 0.2s, background 0.2s;
  text-align: center;
  margin-bottom: 12px;
}
.r-upload-zone:hover, .r-upload-zone.drag-over { border-color: var(--green-light); background: #EEF7F1; }
.r-upload-icon { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: #DCF0E6; border-radius: 10px; }
.r-upload-zone h4 { font-size: 13px; font-weight: 700; color: var(--text-dark); margin: 0; }
.r-upload-zone p  { font-size: 12px; color: var(--text-gray); margin: 0; }

.r-file-list { display: flex; flex-direction: column; gap: 8px; margin-bottom: 16px; }
.r-file-item { display: flex; align-items: center; gap: 8px; background: #EEF7F1; border: 1px solid #C3E6CE; border-radius: 8px; padding: 8px 12px; }
.r-file-name { font-size: 12.5px; font-weight: 500; color: #2D6A4F; flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.r-file-size { font-size: 11px; color: var(--text-light); flex-shrink: 0; }
.r-btn-rm { background: none; border: none; cursor: pointer; color: var(--text-light); display: flex; flex-shrink: 0; transition: color 0.15s; padding: 0; }
.r-btn-rm:hover { color: #E53E3E; }

.r-actions { display: flex; gap: 12px; margin-top: 8px; padding-top: 20px; border-top: 1px solid var(--border); }
.r-btn-submit {
  flex: 1;
  display: flex; align-items: center; justify-content: center; gap: 8px;
  background: #40916C; color: #fff; border: none; border-radius: 10px; padding: 12px;
  font-family: 'Poppins', sans-serif; font-size: 13.5px; font-weight: 700;
  cursor: pointer; box-shadow: 0 4px 14px rgba(64,145,108,.3); transition: background 0.2s;
}
.r-btn-submit:hover:not(:disabled) { background: #2D6A4F; }
.r-btn-submit:disabled { opacity: 0.6; cursor: not-allowed; }
.r-btn-cancelar {
  flex: 1;
  background: transparent; color: #E53E3E; border: 1.5px solid #FC8181; border-radius: 10px; padding: 12px;
  font-family: 'Poppins', sans-serif; font-size: 13.5px; font-weight: 600;
  cursor: pointer; transition: background 0.2s, border-color 0.2s;
}
.r-btn-cancelar:hover:not(:disabled) { background: #FFF5F5; border-color: #E53E3E; }
.r-btn-cancelar:disabled { opacity: 0.5; cursor: not-allowed; }
.r-spin { display: inline-block; width: 14px; height: 14px; border: 2px solid rgba(255,255,255,.35); border-top-color: #fff; border-radius: 50%; animation: spin 0.7s linear infinite; }

/* Transição do modal de registo */
.modal-pop-enter-active { transition: opacity 0.22s ease, transform 0.22s cubic-bezier(.16,1,.3,1); }
.modal-pop-leave-active { transition: opacity 0.18s ease, transform 0.18s ease; }
.modal-pop-enter-from  { opacity: 0; transform: translate(-50%, -46%) scale(0.96); }
.modal-pop-leave-to    { opacity: 0; transform: translate(-50%, -46%) scale(0.96); }

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

  .filter-row, .filter-row-2 {
    grid-template-columns: 1fr 1fr;
  }

  .split {
    grid-template-columns: 1fr;
  }

  .detail-panel {
    position: static;
    max-height: none;
  }

  .modal-meta-strip {
    grid-template-columns: repeat(2, 1fr);
  }

  .modal-two-col,
  .info-grid {
    grid-template-columns: 1fr;
  }

  .registo-panel {
    max-width: 95vw;
  }
}

@media (max-width: 640px) {
  .filter-row, .filter-row-2 {
    grid-template-columns: 1fr;
  }

  .modal-card {
    width: 100vw;
    height: 100vh;
    max-width: 100vw;
    max-height: 100vh;
    border-radius: 0;
  }

  .modal-body { overflow-y: auto; }

  .modal-meta-strip {
    grid-template-columns: repeat(2, 1fr);
  }

  .attachment-gallery {
    grid-template-columns: repeat(2, 1fr);
  }

  .registo-panel {
    width: 100%;
  }

  .r-row {
    grid-template-columns: 1fr;
  }

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
  }
}
</style>
