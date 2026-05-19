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
        <a class="nav-item active">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <rect x="1" y="1" width="6" height="6" rx="1.5"/><rect x="9" y="1" width="6" height="6" rx="1.5"/>
            <rect x="1" y="9" width="6" height="6" rx="1.5"/><rect x="9" y="9" width="6" height="6" rx="1.5"/>
          </svg>
          Dashboard
        </a>
        <a class="nav-item">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <path d="M8 1l1.5 3 3.5.5-2.5 2.5.5 3.5L8 9l-3 1.5.5-3.5L3 4.5 6.5 4z"/>
          </svg>
          Validação
        </a>
        <a class="nav-item">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <circle cx="8" cy="6" r="3"/><path d="M2 14c0-2.761 2.686-5 6-5s6 2.239 6 5" stroke-linecap="round"/>
          </svg>
          Utilizadores
        </a>
        <a class="nav-item">
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
          <input type="text" placeholder="Pesquisar reclamações ou utilizador" v-model="searchQ"/>
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
            <div class="admin-name">{{ auth.user?.name ?? 'Utilizador' }}</div>
            <div class="admin-role">{{ auth.user?.role_label ?? '' }}</div>
          </div>
          <div class="admin-avatar">{{ auth.userInitials }}</div>
        </div>
      </header>

      <!-- CONTENT -->
      <main class="content">

        <!-- Título da página -->
        <div class="page-title-row">
          <div>
            <h1>Dashboard Geral</h1>
            <p>Bem-vindo ao painel central de monitoria ambiental nacional.</p>
          </div>
          <div class="title-actions">
            <button class="btn-outline-sm">
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
              Ocorrência
            </button>
          </div>
        </div>

        <!-- KPI CARDS -->
        <div class="kpi-grid" :class="{ 'kpi-loading': statsLoading }">
          <div class="kpi-card">
            <div class="kpi-top">
              <div class="kpi-icon green">
                <svg width="18" height="18" fill="none" stroke="#2D6A4F" stroke-width="1.8" viewBox="0 0 18 18">
                  <path d="M2 16l4-8 4 4 3-5 3 4"/>
                  <circle cx="15" cy="3" r="2" fill="#52B788" stroke="none"/>
                </svg>
              </div>
              <span class="kpi-badge up">↑ 8.2%</span>
            </div>
            <div class="kpi-label dark">Total de Reclamações</div>
            <div class="kpi-value dark">{{ statsLoading ? '—' : (stats.totals.all ?? 0) }}</div>
            <div class="kpi-sub dark">{{ stats.overdue }} fora do prazo</div>
          </div>

          <div class="kpi-card highlight">
            <div class="kpi-top">
              <div class="kpi-icon white">
                <svg width="18" height="18" fill="none" stroke="rgba(255,255,255,.9)" stroke-width="1.8" viewBox="0 0 18 18">
                  <circle cx="9" cy="9" r="7"/>
                  <path d="M9 5v4l3 3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
            </div>
            <div class="kpi-label light">Pendentes</div>
            <div class="kpi-value light">{{ statsLoading ? '—' : (stats.totals.pending ?? 0) }}</div>
            <div class="kpi-sub light">Aguardando validação inicial</div>
          </div>

          <div class="kpi-card">
            <div class="kpi-top">
              <div class="kpi-icon green">
                <svg width="18" height="18" fill="none" stroke="#2D6A4F" stroke-width="1.8" viewBox="0 0 18 18">
                  <circle cx="9" cy="9" r="7"/>
                  <path d="M6 9l2.5 2.5 4-5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
            </div>
            <div class="kpi-label dark">Resolvidas</div>
            <div class="kpi-value dark">{{ statsLoading ? '—' : (stats.totals.resolved ?? 0) }}</div>
            <div class="kpi-sub dark">{{ stats.totals.in_review ?? 0 }} em análise</div>
          </div>

          <div class="kpi-card">
            <div class="kpi-top">
              <div class="kpi-icon green">
                <svg width="18" height="18" fill="none" stroke="#2D6A4F" stroke-width="1.8" viewBox="0 0 18 18">
                  <path d="M2 16c0-2.761 2.239-5 5-5h4c2.761 0 5 2.239 5 5"/>
                  <circle cx="9" cy="7" r="3"/>
                </svg>
              </div>
            </div>
            <div class="kpi-label dark">Urgentes</div>
            <div class="kpi-value dark">{{ statsLoading ? '—' : (stats.byAlertLevel.urgent ?? 0) }}</div>
            <div class="kpi-sub dark">{{ stats.byAlertLevel.gbv ?? 0 }} casos GBV</div>
          </div>

          <div class="kpi-card">
            <div class="kpi-top">
              <div class="kpi-icon green">
                <svg width="18" height="18" fill="none" stroke="#2D6A4F" stroke-width="1.8" viewBox="0 0 18 18">
                  <circle cx="6" cy="6" r="2.5"/><circle cx="12" cy="6" r="2.5"/>
                  <path d="M1 15c0-2.209 2.239-4 5-4M9 15c0-2.209 1.343-4 4-4 2.761 0 5 1.791 5 4" stroke-linecap="round"/>
                </svg>
              </div>
            </div>
            <div class="kpi-label dark">Encerradas / Rejeitadas</div>
            <div class="kpi-value dark">{{ statsLoading ? '—' : ((stats.totals.closed ?? 0) + (stats.totals.rejected ?? 0)) }}</div>
            <div class="kpi-sub dark">Processos concluídos</div>
          </div>
        </div>

        <!-- CHARTS ROW -->
        <div class="charts-row">
          <div class="chart-card">
            <div class="chart-title">Reclamações por Província</div>
            <div class="chart-sub">Volume de submissões por região geográfica</div>
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
        <div class="bottom-row">
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
              <a class="ver-todas" @click="router.push('/admin/validacao')" style="cursor:pointer">
                Ver Todas
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 13 13">
                  <path d="M2 6.5h9M7 3l3.5 3.5L7 10" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </a>
            </div>
            <div class="table-sub">Últimas 10 ocorrências registadas</div>
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
                <tr v-for="row in submissions" :key="row.code">
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
              </tbody>
            </table>
          </div>
        </div>

      </main>

      <!-- FOOTER -->
      <footer class="dash-footer">
        <span>© 2026 EcoGestor Admin · Sistema de Gestão Ambiental de Moçambique</span>
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
            <h2 class="drawer-title">Adicionar Ocorrência</h2>
            <p class="drawer-subtitle">Preencha os dados da nova ocorrência ambiental</p>
          </div>
          <button class="drawer-close" @click="closeDrawer">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 16 16">
              <path d="M3 3l10 10M13 3L3 13" stroke-linecap="round"/>
            </svg>
          </button>
        </div>

        <div class="drawer-body">

          <!-- Banner de erro -->
          <div class="error-banner" v-if="submitError">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 16 16">
              <circle cx="8" cy="8" r="6"/><path d="M8 5v3M8 11h.01" stroke-linecap="round"/>
            </svg>
            {{ submitError }}
          </div>

          <!-- Contacto do Reclamante -->
          <div class="f-row">
            <div class="f-group">
              <label>Nome do Reclamante (Opcional)</label>
              <input type="text" v-model="form.complainant_name"
                placeholder="Nome completo ou pseudónimo"/>
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
              <input type="tel" v-model="form.complainant_phone"
                placeholder="ex: +258 84 000 0000"/>
            </div>
            <div class="f-group contact-note">
              <svg width="14" height="14" fill="none" stroke="#888E8C" stroke-width="1.6" viewBox="0 0 16 16">
                <circle cx="8" cy="8" r="6"/><path d="M8 7v4M8 5h.01" stroke-linecap="round"/>
              </svg>
              Preencha pelo menos um contacto para que o reclamante possa ser notificado.
            </div>
          </div>

          <!-- Assunto -->
          <div class="f-group">
            <label>Assunto <span class="f-req">*</span></label>
            <input type="text" v-model="form.subject" maxlength="255"
              :class="{ 'f-err': errors.subject }"
              placeholder="Ex: Poluição do rio Incomáti"
              @input="errors.subject = ''"/>
            <span class="f-err-msg" v-if="errors.subject">{{ errors.subject }}</span>
          </div>

          <!-- Projecto + Categoria -->
          <div class="f-row">
            <div class="f-group">
              <label>Projecto <span class="f-req">*</span></label>
              <select v-model="form.project_id" :class="{ 'f-err': errors.project_id }" @change="errors.project_id = ''">
                <option value="" disabled>{{ loadingRef ? 'A carregar…' : 'Seleccione o projecto' }}</option>
                <option v-for="p in refProjects" :key="p.id" :value="p.id">{{ p.name }}</option>
              </select>
              <span class="f-err-msg" v-if="errors.project_id">{{ errors.project_id }}</span>
            </div>
            <div class="f-group">
              <label>Categoria <span class="f-req">*</span></label>
              <select v-model="form.category_id" :class="{ 'f-err': errors.category_id }"
                @change="handleCategoryChange">
                <option value="" disabled>Seleccione a categoria</option>
                <option v-for="c in refCategories" :key="c.id" :value="c.id">{{ c.name }}</option>
              </select>
              <span class="f-err-msg" v-if="errors.category_id">{{ errors.category_id }}</span>
            </div>
          </div>

          <!-- Tipo de Ocorrência + Nível de Alerta -->
          <div class="f-row">
            <div class="f-group">
              <label>Tipo de Ocorrência <span class="f-req">*</span></label>
              <select v-model="form.occurrence_type_id" :class="{ 'f-err': errors.occurrence_type_id }"
                @change="errors.occurrence_type_id = ''">
                <option value="" disabled>Seleccione o tipo</option>
                <option v-for="t in refTypes" :key="t.id" :value="t.id">{{ t.name }}</option>
              </select>
              <span class="f-err-msg" v-if="errors.occurrence_type_id">{{ errors.occurrence_type_id }}</span>
            </div>
            <div class="f-group">
              <label>Nível de Alerta <span class="f-req">*</span></label>
              <select v-model="form.alert_type" :class="{ 'f-err': errors.alert_type }"
                @change="errors.alert_type = ''">
                <option value="" disabled>Seleccione o nível</option>
                <option value="normal">Normal</option>
                <option value="urgent">Urgente</option>
                <option value="gbv">GBV — Violência de Género</option>
              </select>
              <span class="f-err-msg" v-if="errors.alert_type">{{ errors.alert_type }}</span>
            </div>
          </div>

          <!-- Canal de Submissão + Data -->
          <div class="f-row">
            <div class="f-group">
              <label>Canal de Submissão <span class="f-req">*</span></label>
              <select v-model="form.submission_channel" :class="{ 'f-err': errors.submission_channel }"
                @change="errors.submission_channel = ''">
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
              <input type="date" v-model="form.occurrence_date" :max="today"/>
            </div>
          </div>

          <!-- Província + Distrito -->
          <div class="f-row">
            <div class="f-group">
              <label>Província <span class="f-req">*</span></label>
              <select v-model="form.province_id" :class="{ 'f-err': errors.province_id }"
                @change="handleProvinceChange">
                <option value="" disabled>Seleccione a província</option>
                <option v-for="p in refProvinces" :key="p.id" :value="p.id">{{ p.name }}</option>
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

          <!-- Localização + Coordenadas -->
          <div class="f-group">
            <label>Localização / Coordenadas (Opcional)</label>
            <input type="text" v-model="form.location_detail"
              placeholder="Ex: -25.9682, 32.5732 ou descrição do local"/>
          </div>

          <!-- Descrição -->
          <div class="f-group">
            <label>Descrição Detalhada <span class="f-req">*</span></label>
            <textarea v-model="form.description"
              :class="{ 'f-err': errors.description }"
              placeholder="Descreva o que observou, pessoas envolvidas, gravidade e outros detalhes relevantes… (mínimo 20 caracteres)"
              @input="errors.description = ''"></textarea>
            <div class="char-hint" :class="form.description.length >= 20 ? 'char-ok' : 'char-warn'"
              v-if="form.description.length > 0">
              {{ form.description.length >= 20 ? '✓ ' + form.description.length + ' caracteres' : 'Faltam ' + (20 - form.description.length) + ' caracteres' }}
            </div>
            <span class="f-err-msg" v-if="errors.description">{{ errors.description }}</span>
          </div>

          <!-- Upload -->
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

          <!-- File list -->
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

          <!-- Actions -->
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

  </div>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { Chart, registerables } from 'chart.js'
import { useAuthStore } from '@/stores/auth'
import { InternalService } from '@/api/services/internal.service'

Chart.register(...registerables)

const router = useRouter()
const auth   = useAuthStore()

async function handleLogout() {
    await auth.logout()
    router.push('/acessoRestrito')
}

const searchQ      = ref('')
const barChartRef  = ref(null)
const donutChartRef = ref(null)
const lineChartRef = ref(null)

const submissions = ref([])

// ── Estatísticas do dashboard ─────────────────────────────────
const statsLoading = ref(true)
const stats = reactive({
    totals:          { all: 0, pending: 0, in_review: 0, resolved: 0, rejected: 0, closed: 0 },
    overdue:         0,
    byAlertLevel:    { normal: 0, urgent: 0, gbv: 0 },
    byProvince:      [],
    byCategory:      [],
    byMonth:         [],
    byMonthResolved: [],
})

const CHART_COLORS = ['#52B788','#74C0FC','#F4A52A','#FC8181','#9F7AEA','#ED8936']

// Instâncias dos gráficos — guardadas para actualizar sem recriar
let barChart   = null
let donutChart = null
let lineChart  = null

// Eixo temporal: últimos 6 meses no formato 'YYYY-MM'
function buildMonthAxis() {
    const months = []
    for (let i = 5; i >= 0; i--) {
        const d = new Date(); d.setMonth(d.getMonth() - i)
        months.push(`${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}`)
    }
    return months
}

// Actualiza os dados dos gráficos sem recriar as instâncias
function updateCharts() {
    if (barChart) {
        barChart.data.labels           = stats.byProvince.length ? stats.byProvince.map(p => p.name)  : ['Sem dados']
        barChart.data.datasets[0].data = stats.byProvince.length ? stats.byProvince.map(p => p.total) : [0]
        barChart.update('none')
    }
    if (donutChart) {
        donutChart.data.labels                      = stats.byCategory.length ? stats.byCategory.map(c => c.name)  : ['Sem dados']
        donutChart.data.datasets[0].data            = stats.byCategory.length ? stats.byCategory.map(c => c.total) : [1]
        donutChart.data.datasets[0].backgroundColor = stats.byCategory.length
            ? stats.byCategory.map((_, i) => CHART_COLORS[i % CHART_COLORS.length])
            : ['#E2E8F0']
        donutChart.update('none')
    }
    if (lineChart) {
        const ax = buildMonthAxis()
        lineChart.data.labels           = ax.map(formatMonthLabel)
        lineChart.data.datasets[0].data = ax.map(m => stats.byMonth.find(r => r.label === m)?.total ?? 0)
        lineChart.data.datasets[1].data = ax.map(m => stats.byMonthResolved.find(r => r.label === m)?.total ?? 0)
        lineChart.update('none')
    }
}

// Recarrega stats da API e actualiza tudo reactivamente
async function refreshStats() {
    try {
        const data = await InternalService.getDashboardStats()
        Object.assign(stats.totals,       data.totals         ?? {})
        Object.assign(stats.byAlertLevel, data.by_alert_level ?? {})
        stats.overdue         = data.overdue          ?? 0
        stats.byProvince      = data.by_province      ?? []
        stats.byCategory      = data.by_category      ?? []
        stats.byMonth         = data.by_month         ?? []
        stats.byMonthResolved = data.by_month_resolved ?? []
        submissions.value     = mapRecent(data.recent)
        updateCharts()
    } catch (err) {
        console.error('Erro ao actualizar estatísticas:', err)
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

const STATUS_MAP = {
    pending:   { label: 'Pendente',   cls: 'pendente'  },
    in_review: { label: 'Em Análise', cls: 'analise'   },
    resolved:  { label: 'Resolvida',  cls: 'resolvida' },
    rejected:  { label: 'Rejeitada',  cls: 'rejeitada' },
    closed:    { label: 'Encerrada',  cls: 'encerrada' },
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

function formatMonthLabel(label) {
    const [, month] = label.split('-')
    return ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'][parseInt(month) - 1]
}

function mapRecent(items) {
    return (items ?? []).map((o, idx) => {
        const s    = STATUS_MAP[o.status] ?? { label: o.status, cls: 'pendente' }

        // Cidadão: usa o nome do reclamante; se anónimo mostra "Anónimo"
        const name = o.complainant_name?.trim() || 'Anónimo'

        // Iniciais: a partir do nome do reclamante
        const init = name === 'Anónimo'
            ? 'AN'
            : name.split(' ').slice(0, 2).map(w => w[0]?.toUpperCase() ?? '').join('')

        return {
            name,
            code:        o.tracking_code,
            initials:    init.slice(0, 2),
            color:       CHART_COLORS[idx % CHART_COLORS.length],
            // Categoria: vem da relação category (não occurrenceType)
            categoria:   o.category?.name ?? '—',
            catColor:    '#52B788',
            provincia:   o.province?.name ?? '—',
            status:      s.label,
            statusClass: s.cls,
            tempo:       timeAgo(o.created_at),
        }
    })
}

// ── DRAWER ──────────────────────────────────────────────────────────────
// ── Referências de dados para os selects ──────────────────────
const refProjects    = ref([])
const refCategories  = ref([])
const refTypes       = ref([])
const refProvinces   = ref([])
const refDistricts   = ref([])
const loadingRef     = ref(false)
const loadingDistricts = ref(false)
const today = new Date().toISOString().split('T')[0]

// Carrega os dados de referência ao abrir o drawer
async function loadRefData() {
    if (refProjects.value.length) return  // já carregados
    loadingRef.value = true
    try {
        const data = await InternalService.getFormData()
        refProjects.value   = data.projects        ?? []
        refCategories.value = data.categories      ?? []
        refTypes.value      = data.occurrence_types ?? []
        refProvinces.value  = data.provinces       ?? []
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

function handleCategoryChange() {
    errors.category_id = ''
}

// ── Estado do drawer ───────────────────────────────────────────
const drawerOpen       = ref(false)
const toast            = reactive({ show: false, msg: '' })

function showToast(msg) {
    toast.msg = msg; toast.show = true
    setTimeout(() => { toast.show = false }, 4000)
}

// Carrega os dados de referência quando o drawer abre pela primeira vez
watch(drawerOpen, (isOpen) => {
    if (isOpen) loadRefData()
})
const loading          = ref(false)
const submitted        = ref(false)
const submitError      = ref('')
const lastTrackingCode = ref('')
const isDragging       = ref(false)
const files            = ref([])
const fileInput        = ref(null)

const form = reactive({
    complainant_name:   '',
    complainant_email:  '',
    complainant_phone:  '',
    subject:            '',
    project_id:         '',
    category_id:        '',
    occurrence_type_id: '',
    alert_type:         '',
    submission_channel: '',
    occurrence_date:    '',
    province_id:        '',
    district_id:        '',
    location_detail:    '',
    description:        '',
})

const errors = reactive({
    complainant_email:  '',
    subject:            '',
    project_id:         '',
    category_id:        '',
    occurrence_type_id: '',
    alert_type:         '',
    submission_channel: '',
    province_id:        '',
    description:        '',
})

function validate() {
    let ok = true
    if (!form.subject.trim())            { errors.subject            = 'O assunto é obrigatório.';               ok = false }
    if (!form.project_id)                { errors.project_id         = 'Seleccione o projecto.';                 ok = false }
    if (!form.category_id)               { errors.category_id        = 'Seleccione a categoria.';                ok = false }
    if (!form.occurrence_type_id)        { errors.occurrence_type_id = 'Seleccione o tipo de ocorrência.';       ok = false }
    if (!form.alert_type)                { errors.alert_type         = 'Seleccione o nível de alerta.';          ok = false }
    if (!form.submission_channel)        { errors.submission_channel = 'Seleccione o canal de submissão.';       ok = false }
    if (!form.province_id)               { errors.province_id        = 'Seleccione a província.';                ok = false }
    if (!form.description.trim())        { errors.description        = 'A descrição é obrigatória.';             ok = false }
    else if (form.description.trim().length < 20) {
                                           errors.description        = 'A descrição deve ter pelo menos 20 caracteres.'; ok = false }
    return ok
}

async function submitForm() {
    submitted.value  = false
    submitError.value = ''
    if (!validate()) return

    const fd = new FormData()
    if (form.complainant_name.trim())  fd.append('complainant_name',  form.complainant_name.trim())
    if (form.complainant_email.trim()) fd.append('complainant_email', form.complainant_email.trim())
    if (form.complainant_phone.trim()) fd.append('complainant_phone', form.complainant_phone.trim())
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
        showToast(`Ocorrência registada com sucesso!${code ? ' Código: ' + code : ''}`)
        refreshStats()
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

function resetDrawerForm(clearSuccess = true) {
    if (clearSuccess) { submitted.value = false; lastTrackingCode.value = '' }
    submitError.value = ''
    Object.assign(form, {
        complainant_name: '', complainant_email: '', complainant_phone: '',
        subject: '', project_id: '', category_id: '',
        occurrence_type_id: '', alert_type: '', submission_channel: '',
        occurrence_date: '', province_id: '', district_id: '',
        location_detail: '', description: '',
    })
    Object.assign(errors, {
        complainant_email: '', subject: '', project_id: '', category_id: '',
        occurrence_type_id: '', alert_type: '', submission_channel: '',
        province_id: '', description: '',
    })
    files.value = []
    refDistricts.value = []
}

function closeDrawer() {
  drawerOpen.value = false
  resetDrawerForm()
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

onMounted(async () => {
  Chart.defaults.font.family = "'Poppins', sans-serif"
  Chart.defaults.color       = '#8A9490'

  try {
    const data = await InternalService.getDashboardStats()
    Object.assign(stats.totals,      data.totals         ?? {})
    Object.assign(stats.byAlertLevel, data.by_alert_level ?? {})
    stats.overdue         = data.overdue          ?? 0
    stats.byProvince      = data.by_province      ?? []
    stats.byCategory      = data.by_category      ?? []
    stats.byMonth         = data.by_month         ?? []
    stats.byMonthResolved = data.by_month_resolved ?? []
    submissions.value     = mapRecent(data.recent)
  } catch (err) {
    console.error('Erro ao carregar estatísticas:', err)
  } finally {
    statsLoading.value = false
  }

  // ── Bar chart: por província ────────────────────────────────
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

  // ── Donut chart: por categoria ──────────────────────────────
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

  // ── Line chart: submissões e resolvidas por mês ─────────────
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
})
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
  width: 210px;
  flex-shrink: 0;
  background: var(--white);
  border-right: 1px solid var(--border);
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
  border-bottom: 1px solid var(--border);
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

/* ── TOP BAR ─────────────────────────────── */
.topbar {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 0 28px;
  height: 58px;
  background: var(--white);
  border-bottom: 1px solid var(--border);
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

.notif-btn {
  position: relative;
  width: 38px; height: 38px;
  background: #F4F6F5;
  border: 1.5px solid var(--border);
  border-radius: 9px;
  display: flex;
  align-items: center;
  justify-content: center;
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
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 13px;
  font-weight: 800;
  color: #fff;
  flex-shrink: 0;
}

/* ── CONTENT ─────────────────────────────── */
.content {
  flex: 1;
  overflow-y: auto;
  padding: 24px 28px 32px;
}
.content::-webkit-scrollbar { width: 5px; }
.content::-webkit-scrollbar-track { background: transparent; }
.content::-webkit-scrollbar-thumb { background: #C8D8CE; border-radius: 99px; }

/* page title row */
.page-title-row {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  margin-bottom: 22px;
}
.page-title-row h1 { font-size: 22px; font-weight: 800; margin-bottom: 4px; }
.page-title-row p  { font-size: 13px; color: var(--text-gray); }

.title-actions { display: flex; gap: 10px; flex-shrink: 0; }

.btn-outline-sm {
  display: flex; align-items: center; gap: 7px;
  background: var(--white); color: var(--text-dark);
  border: 1.5px solid var(--border); border-radius: 8px;
  padding: 8px 16px;
  font-family: 'Poppins', sans-serif; font-size: 12.5px; font-weight: 600;
  cursor: pointer; transition: border-color 0.2s;
}
.btn-outline-sm:hover { border-color: var(--green-mid); color: var(--green-mid); }

.btn-green-sm {
  display: flex; align-items: center; gap: 7px;
  background: var(--green-mid); color: #fff;
  border: none; border-radius: 8px; padding: 8px 16px;
  font-family: 'Poppins', sans-serif; font-size: 12.5px; font-weight: 700;
  cursor: pointer; transition: background 0.2s;
}
.btn-green-sm:hover { background: var(--green-dark); }

/* ── KPI CARDS ───────────────────────────── */
.kpi-grid {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 12px;
  margin-bottom: 20px;
}

.kpi-card {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 18px 18px 16px;
  transition: box-shadow 0.2s;
}
.kpi-card:hover { box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07); }
.kpi-card.highlight { background: var(--green-mid); border-color: var(--green-mid); }

.kpi-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }

.kpi-icon {
  width: 36px; height: 36px;
  border-radius: 9px;
  display: flex; align-items: center; justify-content: center;
}
.kpi-icon.green { background: var(--green-pale); }
.kpi-icon.white { background: rgba(255, 255, 255, 0.22); }

.kpi-badge {
  font-size: 10.5px; font-weight: 700;
  padding: 3px 8px; border-radius: 99px;
  display: flex; align-items: center; gap: 3px;
}
.kpi-badge.up { background: #E6F7EE; color: var(--green-mid); }

.kpi-label {
  font-size: 10px; font-weight: 700;
  letter-spacing: 0.8px; text-transform: uppercase; margin-bottom: 4px;
}
.kpi-label.dark  { color: var(--text-light); }
.kpi-label.light { color: rgba(255, 255, 255, 0.75); }

.kpi-value { font-size: 28px; font-weight: 800; line-height: 1; margin-bottom: 5px; }
.kpi-value.dark  { color: var(--text-dark); }
.kpi-value.light { color: #fff; }

.kpi-sub { font-size: 11px; }
.kpi-sub.dark  { color: var(--text-light); }
.kpi-sub.light { color: rgba(255, 255, 255, 0.75); }

/* ── CHARTS ROW ──────────────────────────── */
.charts-row {
  display: grid;
  grid-template-columns: 1fr 340px;
  gap: 16px;
  margin-bottom: 16px;
}

.chart-card {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 22px 22px 18px;
}

.chart-title { font-size: 14px; font-weight: 700; margin-bottom: 3px; }
.chart-sub   { font-size: 11.5px; color: var(--text-light); margin-bottom: 18px; }
.chart-wrap  { position: relative; }

/* donut legend */
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

/* line chart legend */
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

table { width: 100%; border-collapse: collapse; }

thead th {
  font-size: 11px; font-weight: 700; color: var(--text-light);
  text-align: left; padding: 8px 10px;
  border-bottom: 1px solid var(--border);
  text-transform: uppercase; letter-spacing: 0.5px;
}

tbody tr { transition: background 0.15s; cursor: pointer; }
tbody tr:hover { background: #F4F6F5; }

tbody td {
  padding: 10px 10px; font-size: 12.5px;
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
.badge-status.pendente  { background: #FFF8E6; color: #B7791F; }
.badge-status.analise   { background: #EBF4FF; color: #2B6CB0; }
.badge-status.resolvida { background: var(--green-pale); color: var(--green-dark); }

/* ── FOOTER ──────────────────────────────── */
.dash-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 28px;
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
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.35);
  z-index: 100;
  backdrop-filter: blur(2px);
}

.drawer-panel {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 680px;
  max-height: 88vh;
  background: var(--white);
  z-index: 101;
  display: flex;
  flex-direction: column;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.18);
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

.drawer-title {
  font-size: 17px;
  font-weight: 800;
  color: var(--text-dark);
  margin-bottom: 3px;
}

.drawer-subtitle {
  font-size: 12px;
  color: var(--text-light);
}

.drawer-close {
  width: 32px; height: 32px;
  border-radius: 8px;
  border: 1.5px solid var(--border);
  background: transparent;
  display: flex; align-items: center; justify-content: center;
  cursor: pointer;
  color: var(--text-gray);
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
.drawer-body::-webkit-scrollbar-track { background: transparent; }
.drawer-body::-webkit-scrollbar-thumb { background: #C8D8CE; border-radius: 99px; }

/* drawer transitions */
.fade-enter-active, .fade-leave-active { transition: opacity 0.25s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.modal-pop-enter-active, .modal-pop-leave-active {
  transition: opacity 0.22s ease, transform 0.25s cubic-bezier(.4, 0, .2, 1);
}
.modal-pop-enter-from, .modal-pop-leave-to {
  opacity: 0;
  transform: translate(-50%, -46%) scale(0.96);
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

.f-group label {
  font-size: 12.5px;
  font-weight: 600;
  color: var(--text-dark);
}

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
  transition: border-color 0.2s, box-shadow 0.2s;
}

.f-group input::placeholder,
.f-group textarea::placeholder { color: var(--text-light); }

.f-group input:focus,
.f-group select:focus,
.f-group textarea:focus {
  border-color: var(--green-light);
  box-shadow: 0 0 0 3px rgba(82, 183, 136, .15);
}

.f-group select {
  cursor: pointer;
  padding-right: 36px;
  background-image: url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%238A9490' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 13px center;
}

.f-group textarea {
  resize: vertical;
  min-height: 110px;
}

.f-err {
  border-color: #FC8181 !important;
  box-shadow: 0 0 0 3px rgba(252, 129, 129, .12) !important;
}

.f-err-msg {
  font-size: 11.5px;
  color: #E53E3E;
}

/* ── UPLOAD ──────────────────────────────── */
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
  border-color: var(--green-light);
  background: #EEF7F1;
}

.upload-icon-wrap {
  width: 40px; height: 40px;
  border-radius: 10px;
  border: 1px solid var(--border);
  background: var(--white);
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 10px;
}

.upload-zone h4 { font-size: 13px; font-weight: 600; margin-bottom: 3px; color: var(--text-dark); }
.upload-zone p  { font-size: 11.5px; color: var(--text-light); }

.file-list { display: flex; flex-direction: column; gap: 8px; margin-bottom: 16px; }

.file-item {
  display: flex; align-items: center; gap: 9px;
  background: #EEF7F1;
  border: 1px solid #C3E6CE;
  border-radius: 8px;
  padding: 8px 12px;
}

.file-item-name { font-size: 12.5px; font-weight: 500; color: #2D6A4F; flex: 1; }
.file-item-size { font-size: 11px; color: var(--text-light); }

.btn-rm {
  background: none; border: none; cursor: pointer;
  color: var(--text-light); display: flex;
  transition: color 0.2s; padding: 0;
}
.btn-rm:hover { color: #E53E3E; }

/* ── FORM ACTIONS ────────────────────────── */
.f-actions {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-top: 8px;
}

.btn-registar {
  display: inline-flex; align-items: center; gap: 8px;
  background: #2D6A4F; color: #fff; border: none;
  border-radius: 9px; padding: 11px 24px;
  font-family: 'Poppins', sans-serif; font-size: 13.5px; font-weight: 700;
  cursor: pointer; transition: background 0.2s, transform 0.15s;
}
.btn-registar:hover { background: #1B4332; transform: translateY(-1px); }
.btn-registar:disabled { background: #A0C4B0; cursor: not-allowed; transform: none; }

.btn-cancelar {
  display: inline-flex; align-items: center; gap: 8px;
  background: transparent; color: #E53E3E;
  border: 1.5px solid #FC8181; border-radius: 9px;
  padding: 9px 20px;
  font-family: 'Poppins', sans-serif; font-size: 13.5px; font-weight: 600;
  cursor: pointer; transition: background 0.2s, border-color 0.2s;
}
.btn-cancelar:hover { background: #FFF5F5; border-color: #E53E3E; }

/* success banner */
.success-banner {
  background: #EEF7F1; border: 1px solid #C3E6CE;
  border-radius: 10px; padding: 13px 16px;
  display: flex; align-items: flex-start; gap: 10px;
  font-size: 13px; font-weight: 600; color: #2D6A4F;
  margin-bottom: 18px;
  animation: fadeInBanner 0.3s ease;
}

.tracking-inline {
  display: block; font-size: 12px; font-weight: 400;
  color: #2D6A4F; margin-top: 3px; letter-spacing: 0.5px;
}

.tracking-inline strong { font-weight: 800; letter-spacing: 1px; }

.error-banner {
  background: #FFF5F5; border: 1px solid #FEB2B2;
  border-radius: 10px; padding: 12px 16px;
  display: flex; align-items: center; gap: 10px;
  font-size: 12.5px; font-weight: 500; color: #C53030;
  margin-bottom: 18px;
}

.f-req { color: #E53E3E; margin-left: 2px; }

.contact-note {
  display: flex; align-items: flex-start; gap: 7px;
  font-size: 11.5px; color: var(--text-gray);
  line-height: 1.55; padding-top: 6px;
  justify-content: flex-start;
}

.char-hint { font-size: 11.5px; margin-top: 3px; }
.char-warn { color: #C66E00; }
.char-ok   { color: #2D6A4F; }

.submit-overlay {
  position: fixed; inset: 0;
  background: rgba(255, 255, 255, 0.75);
  backdrop-filter: blur(3px);
  z-index: 200;
  display: flex; align-items: center; justify-content: center;
}

.submit-loader {
  display: flex; flex-direction: column;
  align-items: center; gap: 16px;
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 16px;
  padding: 36px 48px;
  box-shadow: 0 8px 40px rgba(0,0,0,0.12);
}

.submit-loader p {
  font-size: 14px; font-weight: 600; color: var(--green-dark);
}

@keyframes spin-lg { to { transform: rotate(360deg); } }
.spin-lg { animation: spin-lg 0.9s linear infinite; }

@keyframes fadeInBanner {
  from { opacity: 0; transform: translateY(-8px); }
  to   { opacity: 1; transform: none; }
}

@keyframes spin { to { transform: rotate(360deg); } }
.spin { animation: spin 0.7s linear infinite; }
/* ── DASHBOARD TOAST ────────────────────────────────────────── */
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

</style>