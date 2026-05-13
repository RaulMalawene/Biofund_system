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
            <div class="admin-name">Admin Central</div>
            <div class="admin-role">Ministério da Terra e Ambiente</div>
          </div>
          <div class="admin-avatar">AC</div>
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
        <div class="kpi-grid">
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
            <div class="kpi-value dark">1.284</div>
            <div class="kpi-sub dark">+12% desde o mês passado</div>
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
            <div class="kpi-value light">86</div>
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
            <div class="kpi-value dark">1.198</div>
            <div class="kpi-sub dark">Sucesso no tratamento</div>
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
            <div class="kpi-label dark">Gestores Ativos</div>
            <div class="kpi-value dark">24</div>
            <div class="kpi-sub dark">Distribuídos por província</div>
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
            <div class="kpi-label dark">Funcionários</div>
            <div class="kpi-value dark">112</div>
            <div class="kpi-sub dark">Equipa de campo operacional</div>
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
              <div class="legend-item">
                <span class="legend-dot" style="background:#52B788"></span>
                <span class="legend-label">Fauna</span>
                <span class="legend-pct">45%</span>
              </div>
              <div class="legend-item">
                <span class="legend-dot" style="background:#74C0FC"></span>
                <span class="legend-label">Flora</span>
                <span class="legend-pct">30%</span>
              </div>
              <div class="legend-item">
                <span class="legend-dot" style="background:#F4A52A"></span>
                <span class="legend-label">Poluição</span>
                <span class="legend-pct">15%</span>
              </div>
              <div class="legend-item">
                <span class="legend-dot" style="background:#FC8181"></span>
                <span class="legend-label">Pesca Ilegal</span>
                <span class="legend-pct">10%</span>
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
              <a class="ver-todas">
                Ver Todas
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 13 13">
                  <path d="M2 6.5h9M7 3l3.5 3.5L7 10" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </a>
            </div>
            <div class="table-sub">Reclamações recebidas nas últimas 48 horas</div>
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

          <div class="success-banner" v-if="submitted">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 18 18">
              <circle cx="9" cy="9" r="7"/>
              <path d="M6 9l2.5 2.5 4-5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Ocorrência registada com sucesso!
          </div>

          <!-- Nome + Email -->
          <div class="f-row">
            <div class="f-group">
              <label>Seu Nome (Opcional)</label>
              <input type="text" v-model="form.nome" placeholder="Nome completo ou pseudónimo"/>
            </div>
            <div class="f-group">
              <label>Email</label>
              <input type="email" v-model="form.email"
                :class="{ 'f-err': errors.email }"
                placeholder="email@exemplo.com"
                @input="errors.email = ''"/>
              <span class="f-err-msg" v-if="errors.email">{{ errors.email }}</span>
            </div>
          </div>

          <!-- Projecto + Telefone -->
          <div class="f-row">
            <div class="f-group">
              <label>Projecto Relacionado</label>
              <select v-model="form.projeto">
                <option value="" disabled>Seleccione o projecto</option>
                <option>Reserva do Niassa</option>
                <option>Parque da Gorongosa</option>
                <option>Arquipélago de Bazaruto</option>
                <option>Lagoa de Bilene</option>
                <option>Parque Nacional do Limpopo</option>
                <option>Outro</option>
              </select>
            </div>
            <div class="f-group">
              <label>Telefone</label>
              <input type="tel" v-model="form.telefone" placeholder="ex: +258 84…"/>
            </div>
          </div>

        
          <!-- Data + Canal -->
          <div class="f-row">
            <div class="f-group">
              <label>Data da Ocorrência</label>
              <input type="date" v-model="form.data"/>
            </div>
            <div class="f-group">
              <label>Canal</label>
              <select v-model="form.canal">
                <option value="" disabled>Seleccione o canal</option>
                <option>Web (Portal BioQueixa)</option>
                <option>Aplicação Móvel</option>
                <option>SMS</option>
                <option>Presencial</option>
                <option>Telefone</option>
                <option>Email</option>
              </select>
            </div>
          </div>

          <!-- Província + Distrito -->
          <div class="f-row">
            <div class="f-group">
              <label>Província</label>
              <select v-model="form.provincia">
                <option value="" disabled>Seleccione a província</option>
                <option v-for="p in provincias" :key="p">{{ p }}</option>
              </select>
            </div>
            <div class="f-group">
              <label>Distrito</label>
              <select v-model="form.distrito" :disabled="!form.provincia">
                <option value="" disabled>{{ form.provincia ? 'Seleccione o distrito' : 'Seleccione primeiro a província' }}</option>
                <option v-for="d in currentDistricts" :key="d">{{ d }}</option>
              </select>
            </div>
          </div>

          <!-- Comunidade + Categoria -->
          <div class="f-row">
            <div class="f-group">
              <label>Comunidade</label>
              <input type="text" v-model="form.comunidade" placeholder="Ex: Aldeia Macuacua, Bairro Central…"/>
            </div>
            <div class="f-group">
              <label>Categoria do Incidente</label>
              <select v-model="form.categoria"
                :class="{ 'f-err': errors.categoria }"
                @change="errors.categoria = ''">
                <option value="" disabled>Seleccione a categoria</option>
                <option>Fauna</option>
                <option>Flora</option>
                <option>Poluição Hídrica</option>
                <option>Poluição Atmosférica</option>
                <option>Pesca Ilegal</option>
                <option>Caça Furtiva</option>
                <option>Desflorestação</option>
                <option>Outro</option>
              </select>
              <span class="f-err-msg" v-if="errors.categoria">{{ errors.categoria }}</span>
            </div>
          </div>

          <!-- Coordenadas -->
          <div class="f-group">
            <label>Coordenadas (Opcional)</label>
            <input type="text" v-model="form.coordenadas"
              placeholder="Ex: -25.9682, 32.5732 ou descrição do local"/>
          </div>
          
      <!-- Descrição -->
          <div class="f-group">
            <label>Descrição Detalhada</label>
            <textarea v-model="form.descricao"
              :class="{ 'f-err': errors.descricao }"
              placeholder="Descreva o que observou, pessoas envolvidas, gravidade e outros detalhes relevantes…"
              @input="errors.descricao = ''"></textarea>
            <span class="f-err-msg" v-if="errors.descricao">{{ errors.descricao }}</span>
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

  </div>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted } from 'vue'
import { Chart, registerables } from 'chart.js'

Chart.register(...registerables)

const searchQ      = ref('')
const barChartRef  = ref(null)
const donutChartRef = ref(null)
const lineChartRef = ref(null)

const submissions = [
  { name: 'Amélia Mondlane', code: 'REC-2024-001', initials: 'AM', color: '#52B788', categoria: 'Fauna',        catColor: '#52B788', provincia: 'Nampula',   status: 'Pendente',  statusClass: 'pendente', tempo: 'há 2 horas' },
  { name: 'Carlos Matsinhe', code: 'REC-2024-002', initials: 'CM', color: '#4299E1', categoria: 'Poluição',     catColor: '#F4A52A', provincia: 'Maputo',    status: 'Análise',   statusClass: 'analise',  tempo: 'há 5 horas' },
  { name: 'Helena Sitoe',    code: 'REC-2024-003', initials: 'HS', color: '#9F7AEA', categoria: 'Flora',        catColor: '#74C0FC', provincia: 'Manica',    status: 'Pendente',  statusClass: 'pendente', tempo: 'há 1 dia'   },
  { name: 'João Vilanculos', code: 'REC-2024-004', initials: 'JV', color: '#ED8936', categoria: 'Pesca Ilegal', catColor: '#FC8181', provincia: 'Inhambane', status: 'Resolvida', statusClass: 'resolvida', tempo: 'há 2 dias'  },
  { name: 'Marta Tembe',     code: 'REC-2024-005', initials: 'MT', color: '#F687B3', categoria: 'Poluição',     catColor: '#F4A52A', provincia: 'Sofala',    status: 'Pendente',  statusClass: 'pendente', tempo: 'há 2 dias'  },
]

// ── DRAWER ──────────────────────────────────────────────────────────────
const drawerOpen = ref(false)
const loading    = ref(false)
const submitted  = ref(false)
const isDragging = ref(false)
const files      = ref([])
const fileInput  = ref(null)

const form = reactive({
  nome: '', email: '', projeto: '', telefone: '',
  descricao: '', data: '', canal: '',
  provincia: '', distrito: '', comunidade: '', categoria: '', coordenadas: ''
})

const districtsByProvincia = {
  'Cabo Delgado': ['Ancuabe','Balama','Chiúre','Ibo','Macomia','Mecúfi','Meluco','Mocímboa da Praia','Montepuez','Mueda','Muidumbe','Namuno','Nangade','Palma','Pemba','Quissanga'],
  'Gaza': ['Bilene','Chibuto','Chicualacuala','Chigubo','Chókwè','Chongoene','Guijá','Limpopo','Mabalane','Manjacaze','Massangena','Massingir','Xai-Xai'],
  'Inhambane': ['Funhalouro','Govuro','Homoíne','Inhambane','Inharrime','Inhassoro','Jangamo','Mabote','Massinga','Maxixe','Morrumbene','Panda','Vilankulo','Zavala'],
  'Manica': ['Báruè','Chimoio','Gondola','Guro','Machaze','Macossa','Manica','Mossurize','Sussundenga','Tambara'],
  'Maputo Cidade': ['KaMavota','KaMaxakeni','KaMpfumo','KaMubukwana','KaNyaka','KaTembe'],
  'Maputo Província': ['Boane','Magude','Manhiça','Marracuene','Matola','Matutuíne','Moamba','Namaacha'],
  'Nampula': ['Angoche','Eráti','Ilha de Moçambique','Lalaua','Liúpo','Lúrio','Malema','Meconta','Mecubúri','Memba','Mogincual','Mogovolas','Moma','Monapo','Mossuril','Muecate','Murrupula','Nacala-a-Velha','Nacala-Porto','Nacarôa','Nampula','Napo','Rapale','Ribáuè'],
  'Niassa': ['Chimbonila','Cuamba','Lago','Lichinga','Majune','Mandimba','Marrupa','Mavago','Mecanhelas','Mecula','Metarica','Muembe','Ngauma','Ngapa','Nipepe','Sanga'],
  'Sofala': ['Búzi','Caia','Chemba','Cheringoma','Chibabava','Dondo','Gorongosa','Machanga','Maríngué','Marromeu','Muanza','Nhamatanda','Beira'],
  'Tete': ['Angónia','Cahora-Bassa','Changara','Chifunde','Chiuta','Dôa','Macanga','Magoè','Marara','Moatize','Mutarara','Tete','Tsangano','Zumbo'],
  'Zambézia': ['Alto Molócuè','Chinde','Derre','Gilé','Guruè','Ile','Inhassunge','Luabo','Lugela','Maganja da Costa','Milange','Mocuba','Molumbo','Mopeia','Morrumbala','Namacurra','Namarrói','Nicoadala','Pebane','Quelimane'],
}

const currentDistricts = computed(() => districtsByProvincia[form.provincia] ?? [])

watch(() => form.provincia, () => { form.distrito = '' })

const errors = reactive({ email: '', descricao: '', categoria: '' })

const provincias = [
  'Cabo Delgado', 'Gaza', 'Inhambane', 'Manica',
  'Maputo Cidade', 'Maputo Província', 'Nampula',
  'Niassa', 'Sofala', 'Tete', 'Zambézia'
]

function validate() {
  let ok = true
  if (form.email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
    errors.email = 'Formato de e-mail inválido.'; ok = false
  }
  if (!form.descricao.trim()) {
    errors.descricao = 'A descrição é obrigatória.'; ok = false
  }
  if (!form.categoria) {
    errors.categoria = 'Seleccione uma categoria.'; ok = false
  }
  return ok
}

async function submitForm() {
  submitted.value = false
  if (!validate()) return
  loading.value = true
  await new Promise(r => setTimeout(r, 1300))
  loading.value = false
  submitted.value = true
  resetDrawerForm(false)
}

function resetDrawerForm(clearSuccess = true) {
  if (clearSuccess) submitted.value = false
  Object.assign(form, {
    nome: '', email: '', projeto: '', telefone: '',
    descricao: '', data: '', canal: '', provincia: '', distrito: '', comunidade: '', categoria: '', coordenadas: ''
  })
  Object.assign(errors, { email: '', descricao: '', categoria: '' })
  files.value = []
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

onMounted(() => {
  Chart.defaults.font.family = "'Poppins', sans-serif"
  Chart.defaults.color       = '#8A9490'

  new Chart(barChartRef.value, {
    type: 'bar',
    data: {
      labels: ['Maputo', 'Gaza', 'Inhambane', 'Sofala', 'Manica', 'Tete', 'Zambézia'],
      datasets: [{
        data: [420, 185, 210, 340, 165, 140, 280],
        backgroundColor: '#52B788',
        borderRadius: 6,
        borderSkipped: false,
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { display: false }, ticks: { font: { size: 11 } } },
        y: { beginAtZero: true, grid: { color: '#F0F4F2' }, ticks: { font: { size: 11 }, stepSize: 150 } }
      }
    }
  })

  new Chart(donutChartRef.value, {
    type: 'doughnut',
    data: {
      labels: ['Fauna', 'Flora', 'Poluição', 'Pesca Ilegal'],
      datasets: [{
        data: [45, 30, 15, 10],
        backgroundColor: ['#52B788', '#74C0FC', '#F4A52A', '#FC8181'],
        borderWidth: 0,
        hoverOffset: 6,
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      cutout: '65%',
      plugins: {
        legend: { display: false },
        tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.raw}%` } }
      }
    }
  })

  new Chart(lineChartRef.value, {
    type: 'line',
    data: {
      labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
      datasets: [
        {
          label: 'Submissões',
          data: [30, 42, 58, 65, 80, 95],
          borderColor: '#52B788', backgroundColor: 'rgba(82,183,136,.08)',
          fill: true, tension: 0.45,
          pointBackgroundColor: '#52B788', pointRadius: 4,
        },
        {
          label: 'Resolvidas',
          data: [20, 28, 40, 50, 62, 78],
          borderColor: '#74C0FC', backgroundColor: 'rgba(116,192,252,.06)',
          fill: true, tension: 0.45,
          pointBackgroundColor: '#74C0FC', pointRadius: 4,
        }
      ]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: { grid: { display: false }, ticks: { font: { size: 11 } } },
        y: { beginAtZero: true, grid: { color: '#F0F4F2' }, ticks: { font: { size: 11 }, stepSize: 25 } }
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
  display: flex; align-items: center; gap: 10px;
  font-size: 13px; font-weight: 600; color: #2D6A4F;
  margin-bottom: 18px;
  animation: fadeInBanner 0.3s ease;
}

@keyframes fadeInBanner {
  from { opacity: 0; transform: translateY(-8px); }
  to   { opacity: 1; transform: none; }
}

@keyframes spin { to { transform: rotate(360deg); } }
.spin { animation: spin 0.7s linear infinite; }
</style>
