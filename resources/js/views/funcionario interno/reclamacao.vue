<template>
  <div class="app-shell">

    <!-- ── SIDEBAR ── -->
    <aside class="sidebar" :class="{ 'sidebar-open': sidebarOpen }">
      <router-link to="/funcionario/reclamacao" class="sidebar-logo">
        <img src="../../Imagem/logotipo.png" alt="Biofund" class="sidebar-logo-img" />
      </router-link>
      <button class="sidebar-close-btn" @click="sidebarOpen = false" aria-label="Fechar menu">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 16 16"><path d="M3 3l10 10M13 3L3 13" stroke-linecap="round"/></svg>
      </button>

      <nav class="sidebar-nav" @click="sidebarOpen = false">
        <router-link class="nav-item" to="/funcionario/reclamacao">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <rect x="2" y="1" width="12" height="14" rx="1.5" />
            <path d="M5 5h6M5 8h6M5 11h4" stroke-linecap="round" />
          </svg>
          Ocorrências
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
            <h1>Gestão de Ocorrências</h1>
            <p>Ocorrências activas - por validar e em análise.</p>
          </div>
          <button class="btn-registar" @click="openRegistoModal">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 14 14">
              <path d="M7 1v12M1 7h12" stroke-linecap="round" />
            </svg>
            Registar Nova
          </button>
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
                <option value="">Todos os Activos</option>
                <option value="por_validar">Por Validar</option>
                <option value="por_resolver">Por Resolver</option>
                <option value="resolvendo">Resolvendo</option>
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
                  <path d="M6 1C3.791 1 2 2.791 2 5c0 3 4 7 4 7s4-4 4-7c0-2.209-1.791-4-4-4z" />
                  <circle cx="6" cy="5" r="1.5" />
                </svg>
                Província
              </label>
              <select v-model="filters.province_id">
                <option value="">Todas as Províncias</option>
                <option v-for="p in myProvinces" :key="p.id" :value="p.id">{{ p.name }}</option>
              </select>
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
                  <th>Origem</th>
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
                    <td colspan="10">
                      <div class="skeleton-bar"></div>
                    </td>
                  </tr>
                </template>

                <!-- Rows -->
                <template v-if="!loading">
                  <tr v-for="r in rows" :key="r.id" @click="openDetail(r)">
                    <td>
                      <span class="id-link">{{ r.tracking_code }}</span>
                      <div class="id-sub">{{ r.subject ?? '-' }}</div>
                    </td>
                    <td class="td-muted">{{ r.submitted_at }}</td>
                    <td>{{ r.province?.name ?? '-' }}</td>
                    <td class="td-small">{{ r.category?.name ?? '-' }}</td>
                    <td class="td-small">{{ r.submission_channel_label ?? '-' }}</td>
                    <td>
                      <span class="badge-origem" :class="r.origin">{{ r.origin_label }}</span>
                    </td>
                    <td>
                      <div class="resp-cell" v-if="r.assigned_to?.name">
                        <div class="resp-avatar">{{ r.assigned_to.name[0] }}</div>
                        <span class="td-small">{{ r.assigned_to.name }}</span>
                      </div>
                      <span v-else class="resp-none">Sem responsável</span>
                    </td>
                    <td class="td-muted td-small">{{ r.project?.name ?? '-' }}</td>
                    <td>
                      <span class="badge-status" :class="r.status">{{ r.status_label }}</span>
                    </td>
                    <td>
                      <button class="btn-detail" @click.stop="openDetail(r)">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8"
                          viewBox="0 0 14 14">
                          <circle cx="7" cy="7" r="5.5" />
                          <path d="M7 5v4M7 10h.01" stroke-linecap="round" />
                        </svg>
                        Detalhes
                      </button>
                    </td>
                  </tr>

                  <tr v-if="rows.length === 0">
                    <td colspan="10" class="empty-row">
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

        <!-- TIP BOX -->
        <div class="tip-box">
          <div class="tip-icon">
            <svg width="18" height="18" fill="none" stroke="#15803D" stroke-width="1.8" viewBox="0 0 18 18">
              <circle cx="9" cy="9" r="7" />
              <path d="M9 6v4M9 13h.01" stroke-linecap="round" />
            </svg>
          </div>
          <div>
            <div class="tip-title">Acompanhamento</div>
            <div class="tip-desc">As suas ocorrências são tratadas pelo gestor responsável da sua área. Pode acompanhar o estado de cada uma directamente nesta lista.</div>
          </div>
        </div>

      </main>

      <!-- FOOTER -->
      <footer class="dash-footer">
        <span>© 2026 BIOFUND · Sistema de Gestão Ambiental de Moçambique</span>
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

          <div class="detail-row" v-if="selected.complainant?.name">
            <span class="detail-key">Nome do Reclamante</span>
            <span class="detail-val">{{ selected.complainant.name }}</span>
          </div>
          <div class="detail-row" v-if="selected.complainant?.email">
            <span class="detail-key">Email do Reclamante</span>
            <span class="detail-val">{{ selected.complainant.email }}</span>
          </div>
          <div class="detail-row" v-if="selected.complainant?.phone">
            <span class="detail-key">Telefone do Reclamante</span>
            <span class="detail-val">{{ selected.complainant.phone }}</span>
          </div>
          <div class="detail-row" v-if="selected.complainant?.gender">
            <span class="detail-key">Sexo do Reclamante</span>
            <span class="detail-val">{{ selected.complainant.gender === 'masculino' ? 'Masculino' : 'Feminino' }}</span>
          </div>
          <div class="detail-row" v-if="selected.complainant?.age">
            <span class="detail-key">Faixa Etária do Reclamante</span>
            <span class="detail-val">{{ selected.complainant.age }}</span>
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
            <span class="detail-val">{{ selected.province?.name ?? '-' }}</span>
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
            <span class="detail-val">{{ selected.category?.name ?? '-' }}</span>
          </div>
          <div class="detail-row" v-if="selected.subcategory?.name">
            <span class="detail-key">Subcategoria</span>
            <span class="detail-val">{{ selected.subcategory.name }}</span>
          </div>
          <div class="detail-row" v-if="selected.type?.name">
            <span class="detail-key">Tipo de Ocorrência</span>
            <span class="detail-val">{{ selected.type.name }}</span>
          </div>
          <div class="detail-row" v-if="selected.alert_type_label">
            <span class="detail-key">Nível de Alerta</span>
            <span class="detail-val">{{ selected.alert_type_label }}</span>
          </div>
          <div class="detail-row" v-if="selected.submission_channel_label">
            <span class="detail-key">Canal de Entrada</span>
            <span class="detail-val">{{ selected.submission_channel_label }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-key">Responsável</span>
            <span class="detail-val">{{ selected.assigned_to?.name ?? 'Sem responsável' }}</span>
          </div>
          <div class="detail-row" v-if="selected.submitted_by?.name">
            <span class="detail-key">Registado por</span>
            <span class="detail-val">{{ selected.submitted_by.name }}</span>
          </div>
          <div class="detail-row">
            <span class="detail-key">Projecto</span>
            <span class="detail-val">{{ selected.project?.name ?? '-' }}</span>
          </div>
          <div class="detail-row" v-if="selected.due_date">
            <span class="detail-key">Prazo de Validação</span>
            <span class="detail-val" :class="{ 'overdue-text': selected.is_overdue }">{{ selected.due_date }}</span>
          </div>
          <div class="detail-row" v-if="selected.reviewed_at">
            <span class="detail-key">Data de Revisão</span>
            <span class="detail-val">{{ selected.reviewed_at }}</span>
          </div>
          <div class="detail-row" v-if="selected.reviewed_by">
            <span class="detail-key">Revisado por</span>
            <span class="detail-val">{{ selected.reviewed_by }}</span>
          </div>

          <div class="drawer-section">
            <div class="drawer-section-label">Descrição</div>
            <div class="drawer-desc">{{ selected.description ?? '-' }}</div>
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
                <svg class="dl-icon" width="11" height="11" fill="none" stroke="currentColor" stroke-width="2"
                  viewBox="0 0 14 14">
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
                <div class="tl-dot" :style="{ borderColor: dotColor(ev.to_color), background: dotColor(ev.to_color) + '22' }"></div>
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

    <!-- ── REGISTO MODAL ── -->
    <transition name="fade">
      <div v-if="showModal" class="modal-overlay" @click.self="closeModal"></div>
    </transition>
    <transition name="modal-pop">
      <div v-if="showModal" class="drawer-panel">
        <div class="drawer-header">
          <div>
            <div class="drawer-title">Registar Nova Ocorrência</div>
            <div class="drawer-subtitle">Preencha os dados do incidente ambiental observado.</div>
          </div>
          <button class="drawer-close" @click="closeModal">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 13 13">
              <path d="M2 2l9 9M11 2L2 11" stroke-linecap="round" />
            </svg>
          </button>
        </div>

        <div class="f-body">

          <!-- Erro global -->
          <div class="error-banner" v-if="submitError">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 16 16">
              <circle cx="8" cy="8" r="6"/><path d="M8 5v3M8 11h.01" stroke-linecap="round"/>
            </svg>
            {{ submitError }}
          </div>

          <!-- Dados da Ocorrência -->
          <div class="f-section-title">Dados da Ocorrência</div>
          <div class="f-row">
            <div class="f-group">
              <label>Projecto <span class="f-req">*</span></label>
              <select v-model="nf.project_id"
                :class="{ 'f-err': mErrors.project_id }"
                @change="mErrors.project_id = ''">
                <option value="" disabled>Seleccione o projecto</option>
                <option v-for="p in scopeProjects" :key="p.id" :value="p.id">{{ p.name }}</option>
              </select>
              <span class="f-err-msg" v-if="mErrors.project_id">{{ mErrors.project_id }}</span>
            </div>
            <div class="f-group">
              <label>Categoria <span class="f-req">*</span></label>
              <select v-model="nf.category_id"
                :class="{ 'f-err': mErrors.category_id }"
                @change="mErrors.category_id = ''">
                <option value="" disabled>Seleccione a categoria</option>
                <option v-for="c in refCategories" :key="c.id" :value="c.id">{{ c.name }}</option>
              </select>
              <span class="f-err-msg" v-if="mErrors.category_id">{{ mErrors.category_id }}</span>
            </div>
          </div>

          <div class="f-row" v-if="subcategoriasDisponiveis.length">
            <div class="f-group">
              <label>Subcategoria <span class="f-opt">(Opcional)</span></label>
              <select v-model="nf.subcategory_id">
                <option value="">Seleccione a subcategoria (opcional)</option>
                <option v-for="s in subcategoriasDisponiveis" :key="s.id" :value="s.id">{{ s.name }}</option>
              </select>
            </div>
          </div>

          <div class="f-group">
            <label>Assunto <span class="f-req">*</span></label>
            <input type="text" v-model="nf.subject" maxlength="255"
              :class="{ 'f-err': mErrors.subject }"
              placeholder="Ex: Poluição do rio Incomáti"
              @input="mErrors.subject = ''" />
            <span class="f-err-msg" v-if="mErrors.subject">{{ mErrors.subject }}</span>
          </div>

          <div class="f-row">
            <div class="f-group">
              <label>Tipo de Ocorrência <span class="f-req">*</span></label>
              <select v-model="nf.occurrence_type_id"
                :class="{ 'f-err': mErrors.occurrence_type_id }"
                @change="mErrors.occurrence_type_id = ''">
                <option value="" disabled>Seleccione o tipo</option>
                <option v-for="t in refTypes" :key="t.id" :value="t.id">{{ t.name }}</option>
              </select>
              <span class="f-err-msg" v-if="mErrors.occurrence_type_id">{{ mErrors.occurrence_type_id }}</span>
            </div>
            <div class="f-group">
              <label>Nível de Alerta <span class="f-req">*</span></label>
              <select v-model="nf.alert_type"
                :class="{ 'f-err': mErrors.alert_type }"
                @change="mErrors.alert_type = ''">
                <option value="" disabled>Seleccione o nível</option>
                <option value="normal">Normal</option>
                <option value="urgent">Urgente</option>
                <option value="gbv">GBV - Violência de Género</option>
              </select>
              <span class="f-err-msg" v-if="mErrors.alert_type">{{ mErrors.alert_type }}</span>
            </div>
          </div>

          <div class="f-row">
            <div class="f-group">
              <label>Canal de Submissão <span class="f-req">*</span></label>
              <select v-model="nf.submission_channel"
                :class="{ 'f-err': mErrors.submission_channel }"
                @change="mErrors.submission_channel = ''">
                <option value="" disabled>Seleccione</option>
                <option value="green_line">Linha Verde</option>
                <option value="email">Email</option>
                <option value="phone">Telefone</option>
                <option value="community_meeting">Reunião Comunitária</option>
              </select>
              <span class="f-err-msg" v-if="mErrors.submission_channel">{{ mErrors.submission_channel }}</span>
            </div>
            <div class="f-group">
              <label>Data da Ocorrência</label>
              <div class="date-input-wrap">
                <input type="date" v-model="nf.occurrence_date" :max="today" :class="{ 'is-empty': !nf.occurrence_date }" />
                <span class="date-placeholder" v-if="!nf.occurrence_date">dia / mês / ano</span>
              </div>
            </div>
          </div>

          <!-- Informação do Reclamante -->
          <div class="f-section-title">Informação do Reclamante</div>
          <div class="f-row">
            <div class="f-group">
              <label>Nome <span class="f-opt">(Opcional)</span></label>
              <input type="text" v-model="nf.complainant_name" placeholder="Nome completo ou pseudónimo" />
            </div>
            <div class="f-group">
              <label>Email <span class="req-hint">*pelo menos um</span></label>
              <input type="email" v-model="nf.complainant_email"
                :class="{ 'f-err': mErrors.contact }"
                placeholder="email@exemplo.com"
                @input="mErrors.contact = ''" />
            </div>
          </div>
          <div class="f-row" style="margin-top:-8px">
            <div class="f-group">
              <label>Telefone <span class="req-hint">*pelo menos um</span></label>
              <input type="tel" v-model="nf.complainant_phone"
                :class="{ 'f-err': mErrors.contact }"
                placeholder="+258 84 000 0000"
                @input="mErrors.contact = ''" />
            </div>
            <div class="f-group">
              <label>Sexo <span class="f-opt">(Opcional)</span></label>
              <select v-model="nf.complainant_gender">
                <option value="">Não identificado</option>
                <option value="masculino">Masculino</option>
                <option value="feminino">Feminino</option>
              </select>
            </div>
          </div>
          <div class="f-row">
            <div class="f-group">
              <label>Faixa Etária <span class="f-opt">(Opcional)</span></label>
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
          <div class="contact-note" v-if="mErrors.contact" style="margin-top:-10px; margin-bottom:14px; color:#C53030; font-size:11.5px; display:flex; align-items:center; gap:5px;">
            <svg width="13" height="13" fill="none" stroke="#C53030" stroke-width="1.7" viewBox="0 0 14 14">
              <circle cx="7" cy="7" r="5.5"/><path d="M7 4.5v3M7 9.5h.01" stroke-linecap="round"/>
            </svg>
            {{ mErrors.contact }}
          </div>
          <div class="contact-note" v-else style="margin-top:-10px; margin-bottom:14px; font-size:11.5px; color:var(--text-light); display:flex; align-items:center; gap:5px;">
            <svg width="13" height="13" fill="none" stroke="#888E8C" stroke-width="1.6" viewBox="0 0 14 14">
              <circle cx="7" cy="7" r="5.5"/><path d="M7 4.5v3M7 9.5h.01" stroke-linecap="round"/>
            </svg>
            Preencha pelo menos email ou telefone.
          </div>

          <!-- Localização -->
          <div class="f-section-title">Localização</div>
          <div class="f-row">
            <div class="f-group">
              <label>Província <span class="f-req">*</span></label>
              <select v-model="nf.province_id"
                :class="{ 'f-err': mErrors.province_id }"
                :disabled="myProvinces.length === 1"
                @change="onProvinceChange">
                <option value="" disabled>Seleccione</option>
                <option v-for="p in myProvinces" :key="p.id" :value="p.id">{{ p.name }}</option>
              </select>
              <span class="f-err-msg" v-if="mErrors.province_id">{{ mErrors.province_id }}</span>
            </div>
            <div class="f-group">
              <label>Distrito</label>
              <select v-model="nf.district_id"
                :disabled="!nf.province_id || loadingDistricts">
                <option value="">{{ loadingDistricts ? 'A carregar…' : (nf.province_id ? 'Seleccione (opcional)' : 'Escolha uma província') }}</option>
                <option v-for="d in refDistricts" :key="d.id" :value="d.id">{{ d.name }}</option>
              </select>
            </div>
          </div>
          <div class="f-row">
            <div class="f-group">
              <label>Comunidade <span class="f-opt">(Opcional)</span></label>
              <input type="text" v-model="nf.comunidade" placeholder="Ex: Comunidade de Mafuiane" />
            </div>
            <div class="f-group">
              <label>Posto Administrativo <span class="f-opt">(Opcional)</span></label>
              <input type="text" v-model="nf.postoAdministrativo" placeholder="Ex: Posto Administrativo de Manhiça" />
            </div>
          </div>
          <div class="f-group">
            <label>Coordenadas GPS <span class="f-opt">(Opcional)</span></label>
            <input type="text" v-model="nf.coordenadas" placeholder="Ex: -25.9682, 32.5732"
              :disabled="isVbgType" />
            <span class="char-hint char-warn" v-if="isVbgType">
              Por motivos de privacidade e segurança, as coordenadas GPS não são recolhidas para ocorrências de Violência Baseada no Género (VBG).
            </span>
          </div>

          <!-- Descrição -->
          <div class="f-group">
            <label>Descrição Detalhada <span class="f-req">*</span></label>
            <textarea v-model="nf.description"
              :class="{ 'f-err': mErrors.description }"
              placeholder="Descreva o incidente observado com o máximo de detalhe possível… (mínimo 20 caracteres)"
              rows="4"
              @input="mErrors.description = ''"></textarea>
            <div class="char-hint" :class="nf.description.length >= 20 ? 'char-ok' : 'char-warn'"
              v-if="nf.description.length > 0">
              {{ nf.description.length >= 20 ? '✓ ' + nf.description.length + ' caracteres' : 'Faltam ' + (20 - nf.description.length) + ' caracteres' }}
            </div>
            <span class="f-err-msg" v-if="mErrors.description">{{ mErrors.description }}</span>
          </div>

          <!-- Upload -->
          <div class="upload-zone"
            :class="{ 'drag-over': isDragging }"
            @click="modalFileInput.click()"
            @dragover.prevent="isDragging = true"
            @dragleave="isDragging = false"
            @drop.prevent="handleDrop">
            <div class="upload-icon-wrap">
              <svg width="22" height="22" fill="none" stroke="#2D6A4F" stroke-width="1.7" viewBox="0 0 20 20">
                <path d="M3 15v1.5A1.5 1.5 0 0 0 4.5 18h11A1.5 1.5 0 0 0 17 16.5V15" stroke-linecap="round"/>
                <path d="M10 2v10M6.5 5.5 10 2l3.5 3.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <h4>Anexar ficheiros</h4>
            <p>PNG, JPG, PDF, MP4, MP3 - máx. 10 MB por ficheiro (até 5)</p>
          </div>
          <input ref="modalFileInput" type="file" multiple accept=".png,.jpg,.jpeg,.pdf,.mp4,.mp3"
            style="display:none" @change="handleFileSelect" />

          <div class="file-list" v-if="modalFiles.length">
            <div class="file-item" v-for="(f, i) in modalFiles" :key="i">
              <svg width="14" height="14" fill="none" stroke="#2D6A4F" stroke-width="1.5" viewBox="0 0 16 16">
                <rect x="2" y="1" width="10" height="14" rx="1.5"/>
                <path d="M5 5h4M5 8h4M5 11h2" stroke-linecap="round"/>
                <path d="M10 1v4h4" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span class="file-item-name">{{ f.name }}</span>
              <span class="file-item-size">{{ (f.size / 1024).toFixed(0) }} KB</span>
              <button class="btn-rm" @click.stop="modalFiles.splice(i, 1)">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 12 12">
                  <path d="M2 2l8 8M10 2L2 10" stroke-linecap="round"/>
                </svg>
              </button>
            </div>
          </div>

          <div class="f-actions">
            <button class="btn-cancelar" @click="closeModal" :disabled="saving">Cancelar</button>
            <button class="btn-submit" @click="saveRegisto" :disabled="saving">
              <span v-if="saving" class="stat-spin" style="border-color:#fff3; border-top-color:#fff"></span>
              {{ saving ? 'A registar…' : 'Registar Ocorrência' }}
            </button>
          </div>

        </div>
      </div>
    </transition>

    <!-- TOAST -->
    <transition name="toast-in">
      <div v-if="toast.show" class="toast" :class="toast.type">
        <svg v-if="toast.type === 'success'" width="15" height="15" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 16 16">
          <circle cx="8" cy="8" r="6" />
          <path d="M5.5 8l2 2 3.5-4" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <svg v-else width="15" height="15" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 16 16">
          <circle cx="8" cy="8" r="6" />
          <path d="M8 5v3M8 11h.01" stroke-linecap="round" />
        </svg>
        {{ toast.msg }}
      </div>
    </transition>

  </div>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { InternalService } from '@/api/services/internal.service'
import AdminProfilePanel from '@/components/AdminProfilePanel.vue'
import AdminNotificationPanel from '@/components/AdminNotificationPanel.vue'

const router = useRouter()
const auth = useAuthStore()

async function handleLogout() {
  await auth.logout()
  router.push('/')
}

// ── Projectos e províncias do funcionário (âmbito) ───────────
const scopeProjects  = computed(() => auth.user?.projects  ?? [])
const myProvinces    = computed(() => auth.user?.provinces ?? [])

// ── Cores do histórico de estados (alinhadas com .badge-status) ─
const STATUS_DOT_COLORS = {
  blue:   '#FB923C', // Por Validar
  yellow: '#FACC15', // Por Resolver
  red:    '#EF4444', // Não Validado
  orange: '#3b82f6', // Resolvendo
  green:  '#22C55E', // Resolvido
  purple: '#7C3AED', // Não Resolvida
}
function dotColor(toColor) {
  return STATUS_DOT_COLORS[toColor] ?? toColor
}

// ── Estado ────────────────────────────────────────────────────
const sidebarOpen = ref(false)
const loading = ref(false)
const detailLoading = ref(false)
const saving = ref(false)
const topSearch = ref('')
const selected = ref(null)
const rows = ref([])
const showModal = ref(false)

const meta = reactive({
  total: 0,
  last_page: 1,
  current_page: 1,
  per_page: 15,
})

const toast = reactive({ show: false, msg: '', type: 'success' })

// ── Dados de referência ───────────────────────────────────────
const refCategories = ref([])
const refTypes      = ref([])
const refProvinces  = ref([])
const refDistricts  = ref([])
const loadingDistricts = ref(false)
const today = new Date().toISOString().split('T')[0]

// ── Filtros ───────────────────────────────────────────────────
const filters = reactive({
  status: '',
  category_id: '',
  province_id: '',
})

// ── Formulário de registo ─────────────────────────────────────
const nf = reactive({
  complainant_name: '',
  complainant_email: '',
  complainant_phone: '',
  complainant_gender: '',
  complainant_age: '',
  subject: '',
  project_id: '',
  category_id: '',
  subcategory_id: '',
  occurrence_type_id: '',
  alert_type: '',
  submission_channel: '',
  occurrence_date: '',
  province_id: '',
  district_id: '',
  comunidade: '',
  postoAdministrativo: '',
  coordenadas: '',
  description: '',
})

const mErrors = reactive({
  contact: '',
  subject: '',
  project_id: '',
  category_id: '',
  occurrence_type_id: '',
  alert_type: '',
  submission_channel: '',
  province_id: '',
  description: '',
})

const submitError  = ref('')
const isDragging   = ref(false)
const modalFiles   = ref([])
const modalFileInput = ref(null)

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

// ── Init ──────────────────────────────────────────────────────
onMounted(() => {
  InternalService.getFormData()
    .then(data => {
      refCategories.value = data.categories      ?? []
      refTypes.value      = (data.occurrence_types ?? []).filter(t => t.alert_level !== 'urgent')
      refProvinces.value  = data.provinces        ?? []
    })
    .catch(err => console.error('Erro ao carregar dados de referência:', err))

  loadOccurrences()
})

// ── Carregar ocorrências (só as do funcionário) ───────────────
async function loadOccurrences(page = 1) {
  loading.value = true
  try {
    const params = {
      per_page: meta.per_page,
      page,
      only_mine: true,
      active_only: 1,
    }

    if (topSearch.value.trim()) params.search      = topSearch.value.trim()
    if (filters.status)         params.status      = filters.status
    if (filters.category_id)    params.category_id = filters.category_id
    if (filters.province_id)    params.province_id = filters.province_id

    const response = await InternalService.getOccurrences(params)

    rows.value = response.data ?? []
    Object.assign(meta, {
      total:        response.meta?.total        ?? 0,
      last_page:    response.meta?.last_page    ?? 1,
      current_page: response.meta?.current_page ?? 1,
      per_page:     response.meta?.per_page     ?? 15,
    })
  } catch (err) {
    console.error('Erro ao carregar reclamações:', err)
    rows.value = []
  } finally {
    loading.value = false
  }
}

// ── Filtros ───────────────────────────────────────────────────
function aplicarFiltros() { loadOccurrences(1) }

function limparFiltros() {
  Object.assign(filters, { status: '', category_id: '', province_id: '' })
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
  const cur = meta.current_page
  const last = meta.last_page
  for (let i = Math.max(1, cur - 2); i <= Math.min(last, cur + 2); i++) pages.push(i)
  return pages
})

const paginationInfo = computed(() => {
  if (!meta.total) return '0'
  const start = (meta.current_page - 1) * meta.per_page + 1
  const end = Math.min(meta.current_page * meta.per_page, meta.total)
  return `${start}-${end}`
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

// ── Modal de registo ──────────────────────────────────────────
function openRegistoModal() {
  // Pré-selecciona a província se o funcionário só tiver uma
  const singleProvince = myProvinces.value.length === 1 ? myProvinces.value[0].id : ''
  Object.assign(nf, {
    complainant_name: '', complainant_email: '', complainant_phone: '',
    complainant_gender: '', complainant_age: '',
    subject: '', project_id: '', category_id: '', subcategory_id: '', occurrence_type_id: '',
    alert_type: '', submission_channel: '', occurrence_date: '',
    province_id: singleProvince, district_id: '',
    comunidade: '', postoAdministrativo: '', coordenadas: '',
    description: '',
  })
  Object.assign(mErrors, {
    contact: '', subject: '', project_id: '', category_id: '',
    occurrence_type_id: '', alert_type: '', submission_channel: '',
    province_id: '', description: '',
  })
  submitError.value = ''
  modalFiles.value  = []
  refDistricts.value = []
  // Se já foi pré-seleccionada uma província, carrega os distritos
  if (singleProvince) onProvinceChange()
  showModal.value = true
}

function closeModal() {
  showModal.value = false
}

async function onProvinceChange() {
  nf.district_id = ''
  refDistricts.value = []
  mErrors.province_id = ''
  if (!nf.province_id) return
  loadingDistricts.value = true
  try {
    const data = await InternalService.getDistrictsByProvince(nf.province_id)
    refDistricts.value = data.districts ?? data
  } catch {}
  finally { loadingDistricts.value = false }
}

// ── Upload ────────────────────────────────────────────────────
function handleFileSelect(e) {
  addFiles(Array.from(e.target.files))
  e.target.value = ''
}
function handleDrop(e) {
  isDragging.value = false
  addFiles(Array.from(e.dataTransfer.files))
}
function addFiles(list) {
  list.forEach(f => {
    if (modalFiles.value.length >= 5) return
    if (f.size <= 10 * 1024 * 1024) modalFiles.value.push(f)
  })
}

// ── Validação ─────────────────────────────────────────────────
function validate() {
  let ok = true
  if (!nf.complainant_email.trim() && !nf.complainant_phone.trim()) {
    mErrors.contact = 'Preencha pelo menos email ou número de telefone.'
    ok = false
  }
  if (!nf.subject.trim())           { mErrors.subject            = 'O assunto é obrigatório.';               ok = false }
  if (!nf.project_id)               { mErrors.project_id         = 'Seleccione o projecto.';                 ok = false }
  if (!nf.category_id)              { mErrors.category_id        = 'Seleccione a categoria.';                ok = false }
  if (!nf.occurrence_type_id)       { mErrors.occurrence_type_id = 'Seleccione o tipo de ocorrência.';       ok = false }
  if (!nf.alert_type)               { mErrors.alert_type         = 'Seleccione o nível de alerta.';          ok = false }
  if (!nf.submission_channel)       { mErrors.submission_channel = 'Seleccione o canal de submissão.';       ok = false }
  if (!nf.province_id)              { mErrors.province_id        = 'Seleccione a província.';                ok = false }
  if (!nf.description.trim())       { mErrors.description        = 'A descrição é obrigatória.';             ok = false }
  else if (nf.description.trim().length < 20) {
                                      mErrors.description        = 'A descrição deve ter pelo menos 20 caracteres.'; ok = false }
  return ok
}

// ── Submissão ─────────────────────────────────────────────────
async function saveRegisto() {
  submitError.value = ''
  Object.assign(mErrors, {
    contact: '', subject: '', project_id: '', category_id: '',
    occurrence_type_id: '', alert_type: '', submission_channel: '',
    province_id: '', description: '',
  })

  if (!validate()) {
    submitError.value = 'Corrija os erros assinalados e tente novamente.'
    return
  }

  saving.value = true
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
    modalFiles.value.forEach(f => fd.append('attachments[]', f))

    const res = await InternalService.createOccurrence(fd)
    closeModal()
    showToast(`Ocorrência ${res.tracking_code ?? ''} registada com sucesso!`, 'success')
    await loadOccurrences(1)
  } catch (err) {
    if (err.response?.status === 422) {
      const serverErrors = err.response.data.errors ?? {}
      Object.entries(serverErrors).forEach(([field, msgs]) => {
        if (field in mErrors) mErrors[field] = msgs[0]
      })
      submitError.value = 'Corrija os erros assinalados e tente novamente.'
    } else {
      submitError.value = err.response?.data?.message ?? 'Erro ao registar. Tente novamente.'
    }
  } finally {
    saving.value = false
  }
}

// ── Toast ─────────────────────────────────────────────────────
function showToast(msg, type = 'success') {
  Object.assign(toast, { show: true, msg, type })
  setTimeout(() => { toast.show = false }, 4000)
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

.nav-item svg { flex-shrink: 0; opacity: 0.7; }
.nav-item:hover svg,
.nav-item.active svg,
.nav-item.router-link-exact-active svg { opacity: 1; }

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

.btn-logout:hover {
  background: #FFF5F5;
  color: #C53030;
}

/* ── MAIN ───────────────────────────────── */
.main {
  margin-left: 220px;
  flex: 1;
  display: flex;
  flex-direction: column;
  height: 100vh;
  overflow: hidden;
}

/* ── TOPBAR ─────────────────────────────── */
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
  max-width: 380px;
  transition: border-color 0.2s;
}

.search-wrap:focus-within { border-color: var(--green-mid); }

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

/* ── CONTENT ────────────────────────────── */
.content {
  flex: 1;
  overflow-y: auto;
  padding: 26px 30px 36px;
  background: #fff;
}

.content::-webkit-scrollbar { width: 5px; }
.content::-webkit-scrollbar-thumb { background: #C8D8CE; border-radius: 99px; }

/* PAGE TITLE */
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

.page-title-row p { font-size: 13px; color: var(--text-gray); }

.btn-registar {
  display: flex;
  align-items: center;
  gap: 7px;
  background: #40916C;
  color: #fff;
  border: none;
  border-radius: 10px;
  padding: 10px 18px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
  flex-shrink: 0;
  box-shadow: 0 2px 10px rgba(64,145,108,0.3);
}

.btn-registar:hover {
  background: #2D6A4F;
  transform: translateY(-1px);
  box-shadow: 0 4px 16px rgba(64,145,108,0.4);
}

/* ── FILTER CARD ────────────────────────── */
.filter-card {
  background: var(--white);
  border-radius: 16px;
  padding: 18px 20px 20px;
  margin-bottom: 18px;
  box-shadow: 0 1px 3px rgba(0,0,0,.05), 0 6px 20px rgba(0,0,0,.07);
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

.filter-group select {
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  color: var(--text-dark);
  background: var(--white);
  border: 1.5px solid var(--border);
  border-radius: 8px;
  padding: 9px 30px 9px 12px;
  outline: none;
  width: 100%;
  appearance: none;
  -webkit-appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%238A9490' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 10px center;
  transition: border-color 0.2s;
}

.filter-group select:focus {
  border-color: var(--green-mid);
  box-shadow: 0 0 0 3px rgba(82,183,136,.12);
}

.filter-actions {
  display: flex;
  gap: 8px;
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
  transition: border-color 0.2s, color 0.2s;
}

.btn-limpar:hover { border-color: var(--text-gray); color: var(--text-dark); }

.btn-filtrar {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  height: 39px;
  background: #40916C;
  color: #fff;
  border: none;
  border-radius: 10px;
  padding: 0 22px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  white-space: nowrap;
  transition: background 0.2s, transform 0.15s;
  box-shadow: 0 2px 8px rgba(64,145,108,0.3);
}

.btn-filtrar:hover:not(:disabled) { background: #2D6A4F; transform: translateY(-1px); }
.btn-filtrar:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

/* ── STAT BAR ───────────────────────────── */
.stat-bar {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 14px;
}

.stat-label {
  font-size: 12px;
  font-weight: 600;
  color: var(--text-light);
  display: flex;
  align-items: center;
  gap: 6px;
}

/* ── SKELETON ───────────────────────────── */
.skeleton-row td { padding: 14px 16px; }

.skeleton-bar {
  height: 14px;
  border-radius: 6px;
  background: linear-gradient(90deg, #f0f4f2 25%, #e0eae5 50%, #f0f4f2 75%);
  background-size: 200% 100%;
  animation: shimmer 1.2s infinite;
}

@keyframes shimmer {
  0%   { background-position: 200% 0; }
  100% { background-position: -200% 0; }
}

.stat-spin {
  display: inline-block;
  width: 12px;
  height: 12px;
  border: 2px solid #C8D8CE;
  border-top-color: var(--green-mid);
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
  flex-shrink: 0;
}

@keyframes spin { to { transform: rotate(360deg); } }

/* ── TABLE ──────────────────────────────── */
.table-card {
  background: var(--white);
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 1px 3px rgba(0,0,0,.05), 0 6px 20px rgba(0,0,0,.07);
  margin-bottom: 18px;
}

.table-overflow {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

table {
  width: 100%;
  border-collapse: collapse;
  min-width: 900px;
}

thead th {
  padding: 11px 16px;
  font-size: 10.5px;
  font-weight: 700;
  color: #5A7A69;
  text-align: left;
  background: #F4FAF7;
  border-bottom: 1.5px solid #D8EDE2;
  text-transform: uppercase;
  letter-spacing: 0.6px;
  white-space: nowrap;
}

tbody tr { transition: background 0.12s; cursor: pointer; }
tbody tr:hover { background: #F3FAF6; }

tbody td {
  padding: 13px 16px;
  font-size: 13px;
  border-bottom: 1px solid var(--border);
  vertical-align: middle;
}

tbody tr:last-child td { border-bottom: none; }

.td-muted { color: var(--text-gray); font-size: 12.5px; }
.td-small { font-size: 12.5px; }

.id-link {
  color: var(--green-mid);
  font-weight: 700;
  font-size: 12.5px;
  display: block;
}

.id-sub {
  font-size: 11px;
  color: var(--text-light);
  margin-top: 2px;
  max-width: 160px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.resp-cell { display: flex; align-items: center; gap: 8px; }

.resp-avatar {
  width: 26px;
  height: 26px;
  border-radius: 50%;
  flex-shrink: 0;
  background: var(--green-bg);
  color: var(--green-mid);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 11px;
  font-weight: 800;
}

.resp-none { color: var(--text-light); font-size: 12.5px; font-style: italic; }

/* status badges */
.badge-status {
  display: inline-block;
  padding: 3px 10px;
  border-radius: 99px;
  font-size: 11.5px;
  font-weight: 700;
  border-width: 1.5px;
  border-style: solid;
}

.badge-status.por_validar, .badge-status.pendente, .badge-status.pending {
  color: #fff; border-color: #EA580C; background: #FB923C;
}
.badge-status.por_resolver, .badge-status.em_analise, .badge-status.in_review {
  color: #713F12; border-color: #CA8A04; background: #FACC15;
}
.badge-status.resolvendo {
  color: #fff; border-color: #2563EB; background: #3b82f6;
}
.badge-status.resolvido, .badge-status.resolved {
  color: #fff; border-color: #16A34A; background: #22C55E;
}
.badge-status.rejected, .badge-status.nao_validado {
  color: #fff; border-color: #DC2626; background: #EF4444;
}
.badge-status.nao_resolvida {
  color: #fff; border-color: #6D28D9; background: #7C3AED;
}

.badge-origem {
  display: inline-block;
  padding: 3px 9px;
  border-radius: 99px;
  font-size: 11px;
  font-weight: 700;
  border: 1.5px solid;
}
.badge-origem.external { color: #C05621; border-color: #F6AD55; background: #FFFAF0; }
.badge-origem.internal { color: #2B6CB0; border-color: #90CDF4; background: #EBF8FF; }

.btn-detail {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  background: var(--green-bg);
  color: var(--green-mid);
  border: none;
  border-radius: 7px;
  padding: 6px 12px;
  font-family: 'Poppins', sans-serif;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.15s;
}

.btn-detail:hover { background: var(--green-pale); }

/* pagination */
.pagination-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 14px 20px;
  border-top: 1px solid var(--border);
}

.pagination-info { font-size: 12.5px; color: var(--text-light); }

.pagination-btns { display: flex; align-items: center; gap: 6px; }

.pg-btn {
  height: 32px;
  min-width: 32px;
  border-radius: 7px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-family: 'Poppins', sans-serif;
  font-size: 12.5px;
  font-weight: 600;
  cursor: pointer;
  border: 1.5px solid var(--border);
  background: var(--white);
  color: var(--text-gray);
  padding: 0 10px;
  transition: border-color 0.15s, background 0.15s, color 0.15s;
}

.pg-btn:hover { border-color: var(--green-mid); color: var(--green-mid); }
.pg-btn.active { background: var(--green-mid); border-color: var(--green-mid); color: #fff; }
.pg-btn:disabled { opacity: 0.45; cursor: not-allowed; }

.empty-row { text-align: center; padding: 36px; color: var(--text-light); font-size: 13px; }

/* ── TIP BOX ─────────────────────────────── */
.tip-box {
  display: flex;
  align-items: flex-start;
  gap: 14px;
  background: var(--green-bg);
  border: 1.5px solid #A7F3D0;
  border-radius: 12px;
  padding: 16px 20px;
}

.tip-icon {
  width: 38px;
  height: 38px;
  border-radius: 9px;
  background: #BBF7D0;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.tip-title { font-size: 13px; font-weight: 700; color: var(--green-mid); margin-bottom: 5px; }
.tip-desc  { font-size: 12.5px; color: #166534; line-height: 1.65; }

/* ── FOOTER ─────────────────────────────── */
.dash-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 28px;
  background: var(--white);
  box-shadow: 0 -1px 10px rgba(0,0,0,.06);
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

/* ── DETAIL DRAWER ──────────────────────── */
.drawer-overlay {
  position: fixed;
  inset: 0;
  background: rgba(15,28,22,0.4);
  z-index: 200;
}

.drawer {
  position: fixed;
  top: 0; right: 0; bottom: 0;
  width: 480px;
  max-width: 95vw;
  background: var(--white);
  z-index: 201;
  display: flex;
  flex-direction: column;
  box-shadow: -6px 0 32px rgba(0,0,0,.14);
  overflow: hidden;
}

.drawer-hd {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px 24px 18px;
  border-bottom: 1px solid var(--border);
  flex-shrink: 0;
}

.drawer-hd h3 { font-size: 15px; font-weight: 800; margin-bottom: 2px; }
.drawer-hd p  { font-size: 12px; color: var(--text-light); }

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
  transition: background 0.2s;
  flex-shrink: 0;
}

.btn-close:hover { background: #FFF5F5; border-color: #FC8181; }

.drawer-loading {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 28px 24px;
  font-size: 13px;
  color: var(--text-gray);
}

.drawer-body {
  flex: 1;
  overflow-y: auto;
  padding: 22px 24px;
}

.drawer-body::-webkit-scrollbar { width: 4px; }
.drawer-body::-webkit-scrollbar-thumb { background: #C8D8CE; border-radius: 99px; }

.drawer-status-row { display: flex; align-items: center; margin-bottom: 18px; }

.detail-row {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  padding: 10px 0;
  border-bottom: 1px solid #F0F4F2;
}

.detail-key { font-size: 12px; font-weight: 600; color: var(--text-light); min-width: 140px; }
.detail-val { font-size: 13px; color: var(--text-dark); text-align: right; flex: 1; }

.overdue-text { color: #C05621; font-weight: 600; }

.drawer-section { margin-top: 20px; }

.drawer-section-label {
  font-size: 11.5px;
  font-weight: 700;
  color: var(--text-light);
  text-transform: uppercase;
  letter-spacing: 0.6px;
  margin-bottom: 10px;
}

.drawer-desc {
  background: #F4F6F5;
  border-radius: 9px;
  padding: 14px;
  font-size: 13px;
  color: var(--text-gray);
  line-height: 1.7;
}

.attach-list { display: flex; flex-direction: column; gap: 6px; }

.attach-item {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 12px;
  background: var(--green-bg);
  border: 1px solid #C3E6CE;
  border-radius: 8px;
  color: var(--green-dark);
  font-family: 'Poppins', sans-serif;
  font-size: 12.5px;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.2s;
  width: 100%;
  text-align: left;
}

.attach-item:hover { background: var(--green-pale); }
.attach-size { margin-left: auto; font-size: 11px; color: var(--text-light); }
.dl-icon { flex-shrink: 0; opacity: 0.5; }

/* Timeline */
.timeline { display: flex; flex-direction: column; }

.timeline-item {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  padding-bottom: 16px;
  position: relative;
}

.timeline-item:not(:last-child)::before {
  content: '';
  position: absolute;
  left: 5px; top: 14px; bottom: 0;
  width: 1.5px;
  background: var(--border);
}

.tl-dot {
  width: 12px; height: 12px;
  border-radius: 50%;
  flex-shrink: 0;
  margin-top: 2px;
  border: 2px solid;
}

.tl-content { flex: 1; }
.tl-title   { font-size: 13px; font-weight: 600; color: var(--text-dark); }
.tl-comment { font-size: 12.5px; color: var(--text-gray); margin-top: 3px; line-height: 1.5; }
.tl-date    { font-size: 11.5px; color: var(--text-light); margin-top: 3px; }
.tl-empty   { font-size: 13px; color: var(--text-light); font-style: italic; }

/* ── MODAL ──────────────────────────────── */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(15,28,22,.4);
  z-index: 200;
}

.drawer-panel {
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

.drawer-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  padding: 22px 26px 18px;
  border-bottom: 1px solid var(--border);
  flex-shrink: 0;
}

.drawer-title    { font-size: 17px; font-weight: 800; color: var(--text-dark); margin-bottom: 3px; }
.drawer-subtitle { font-size: 12px; color: var(--text-gray); }

.drawer-close {
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
.drawer-close:hover { border-color: #E53E3E; color: #E53E3E; }

.f-body {
  flex: 1;
  overflow-y: auto;
  padding: 22px 26px 32px;
  display: flex;
  flex-direction: column;
}
.f-body::-webkit-scrollbar { width: 4px; }
.f-body::-webkit-scrollbar-thumb { background: #C8D8CE; border-radius: 99px; }

.f-section-title {
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

.f-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }

.f-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px; }
.f-row .f-group { margin-bottom: 0; }

.f-group label {
  font-size: 12.5px;
  font-weight: 600;
  color: var(--text-dark);
  display: flex;
  align-items: center;
  gap: 4px;
}

.f-req { color: #E53E3E; }
.f-opt { font-size: 11px; font-weight: 500; color: var(--text-light); }

.req-hint {
  font-size: 10.5px;
  font-weight: 500;
  color: #C66E00;
  margin-left: 4px;
}

.f-group input,
.f-group select,
.f-group textarea {
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

.f-group select {
  background-image: url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%238A9490' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 12px center;
  padding-right: 32px;
}

.f-group input:focus,
.f-group select:focus,
.f-group textarea:focus {
  border-color: var(--green-mid);
  box-shadow: 0 0 0 3px rgba(82,183,136,.12);
}

.f-group select:disabled,
.f-group input:disabled { opacity: 0.55; cursor: not-allowed; }
.f-group textarea { resize: vertical; min-height: 90px; height: auto; background-image: none; }

.f-err { border-color: #FC8181 !important; box-shadow: 0 0 0 3px rgba(252,129,129,.1) !important; }
.f-err-msg { font-size: 11.5px; color: #E53E3E; }

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

.error-banner {
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

.contact-note {
  display: flex;
  align-items: flex-start;
  gap: 6px;
  font-size: 11.5px;
  color: var(--text-gray);
  line-height: 1.5;
}

.char-hint { font-size: 11.5px; margin-top: 3px; }
.char-warn { color: #C66E00; }
.char-ok   { color: #2D6A4F; }

.upload-zone {
  border: 2px dashed var(--border);
  border-radius: 10px;
  padding: 28px 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 8px;
  cursor: pointer;
  background: #F7F9F8;
  transition: border-color 0.2s, background 0.2s;
  text-align: center;
  margin-bottom: 12px;
}
.upload-zone:hover,
.upload-zone.drag-over {
  border-color: var(--green-light);
  background: #EEF7F1;
}
.upload-icon-wrap {
  width: 40px; height: 40px;
  display: flex; align-items: center; justify-content: center;
  background: #DCF0E6;
  border-radius: 10px;
}
.upload-zone h4 { font-size: 13px; font-weight: 700; color: var(--text-dark); margin: 0; }
.upload-zone p  { font-size: 12px; color: var(--text-gray); margin: 0; }

.file-list { display: flex; flex-direction: column; gap: 8px; margin-bottom: 16px; }

.file-item {
  display: flex;
  align-items: center;
  gap: 8px;
  background: #EEF7F1;
  border: 1px solid #C3E6CE;
  border-radius: 8px;
  padding: 8px 12px;
}

.file-item-name { font-size: 12.5px; font-weight: 500; color: #2D6A4F; flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.file-item-size { font-size: 11px; color: var(--text-light); flex-shrink: 0; }

.btn-rm {
  background: none;
  border: none;
  cursor: pointer;
  color: var(--text-light);
  display: flex;
  flex-shrink: 0;
  transition: color 0.15s;
  padding: 0;
}
.btn-rm:hover { color: #E53E3E; }

.f-actions {
  display: flex;
  gap: 12px;
  margin-top: 8px;
  padding-top: 20px;
  border-top: 1px solid var(--border);
}

.btn-submit {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  background: #40916C;
  color: #fff;
  border: none;
  border-radius: 10px;
  padding: 12px;
  font-family: 'Poppins', sans-serif;
  font-size: 13.5px;
  font-weight: 700;
  cursor: pointer;
  box-shadow: 0 4px 14px rgba(64,145,108,.3);
  transition: background 0.2s;
}
.btn-submit:hover:not(:disabled) { background: #2D6A4F; }
.btn-submit:disabled { opacity: 0.6; cursor: not-allowed; }

.btn-cancelar {
  flex: 1;
  background: transparent;
  color: #E53E3E;
  border: 1.5px solid #FC8181;
  border-radius: 10px;
  padding: 12px;
  font-family: 'Poppins', sans-serif;
  font-size: 13.5px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s, border-color 0.2s;
}
.btn-cancelar:hover:not(:disabled) { background: #FFF5F5; border-color: #E53E3E; }
.btn-cancelar:disabled { opacity: 0.5; cursor: not-allowed; }

.modal-pop-enter-active { transition: opacity 0.22s ease, transform 0.22s cubic-bezier(.16,1,.3,1); }
.modal-pop-leave-active { transition: opacity 0.18s ease, transform 0.18s ease; }
.modal-pop-enter-from  { opacity: 0; transform: translate(-50%, -46%) scale(0.96); }
.modal-pop-leave-to    { opacity: 0; transform: translate(-50%, -46%) scale(0.96); }

/* ── TOAST ──────────────────────────────── */
.toast {
  position: fixed;
  bottom: 24px;
  right: 24px;
  z-index: 500;
  border-radius: 12px;
  padding: 13px 18px;
  display: flex;
  align-items: center;
  gap: 9px;
  font-size: 13px;
  font-weight: 600;
  box-shadow: 0 8px 28px rgba(0,0,0,.16);
  color: #fff;
}

.toast.success { background: #2D6A4F; }
.toast.error   { background: #C53030; }

/* ── TRANSITIONS ────────────────────────── */
.fade-enter-active, .fade-leave-active { transition: opacity 0.25s ease; }
.fade-enter-from,  .fade-leave-to      { opacity: 0; }

.slide-right-enter-active, .slide-right-leave-active {
  transition: transform 0.3s cubic-bezier(.16,1,.3,1);
}
.slide-right-enter-from, .slide-right-leave-to { transform: translateX(100%); }

.toast-in-enter-active, .toast-in-leave-active { transition: opacity 0.3s, transform 0.3s; }
.toast-in-enter-from, .toast-in-leave-to { opacity: 0; transform: translateY(12px); }

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

  .drawer,
  .drawer-panel {
    max-width: 95vw;
  }
}

@media (max-width: 640px) {
  .filter-row {
    grid-template-columns: 1fr;
  }

  .drawer,
  .drawer-panel {
    width: 100%;
  }

  .f-row {
    grid-template-columns: 1fr;
  }

  .content {
    padding: 18px 16px 26px;
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

  .btn-registar {
    width: 100%;
    justify-content: center;
  }

  .drawer-hd,
  .drawer-header {
    padding: 16px 18px 14px;
  }

  .drawer-body,
  .f-body {
    padding: 16px 18px 24px;
  }

  .detail-row {
    flex-direction: column;
    align-items: flex-start;
    gap: 4px;
  }

  .detail-val {
    text-align: left;
  }
}
</style>
