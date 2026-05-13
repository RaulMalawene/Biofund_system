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
        <a class="nav-item active">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <circle cx="8" cy="6" r="3"/><path d="M2 14c0-2.761 2.686-5 6-5s6 2.239 6 5" stroke-linecap="round"/>
          </svg>
          Utilizadores
        </a>
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
            <h1>Gestão de Utilizadores</h1>
            <p>Administre as contas de acesso ao sistema BioFund.</p>
          </div>
          <div class="title-actions">
            <button class="btn-green-sm" @click="openModal('gestor')">
              <svg width="13" height="13" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 16 16">
                <circle cx="8" cy="6" r="3"/><path d="M2 14c0-2.761 2.686-5 6-5s6 2.239 6 5" stroke-linecap="round"/>
                <path d="M13 4v4M11 6h4" stroke-linecap="round"/>
              </svg>
              Novo Gestor
            </button>
            <button class="btn-outline-sm" @click="openModal('func')">
              <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 14 14">
                <path d="M7 2v10M2 7h10" stroke-linecap="round"/>
              </svg>
              Novo Funcionário
            </button>
          </div>
        </div>

        <!-- KPI CARDS -->
        <div class="kpi-grid">
          <div class="kpi-card">
            <div class="kpi-top">
              <div class="kpi-icon green">
                <svg width="18" height="18" fill="none" stroke="#2D6A4F" stroke-width="1.8" viewBox="0 0 22 22">
                  <path d="M3 19c0-3.314 3.582-6 8-6s8 2.686 8 6"/><circle cx="11" cy="8" r="4"/>
                </svg>
              </div>
            </div>
            <div class="kpi-label dark">Total de Utilizadores</div>
            <div class="kpi-value dark">{{ users.length }}</div>
            <div class="kpi-sub dark">Gestores e funcionários</div>
          </div>
          <div class="kpi-card highlight">
            <div class="kpi-top">
              <div class="kpi-icon white">
                <svg width="18" height="18" fill="none" stroke="rgba(255,255,255,.9)" stroke-width="1.8" viewBox="0 0 22 22">
                  <path d="M11 3L20 8v6c0 4-4.5 7-9 9-4.5-2-9-5-9-9V8z" stroke-linejoin="round"/>
                  <path d="M8 11l2.5 2.5 4-5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
            </div>
            <div class="kpi-label light">Contas Ativas</div>
            <div class="kpi-value light">{{ users.filter(u => u.ativo).length }}</div>
            <div class="kpi-sub light">Sessões autorizadas</div>
          </div>
          <div class="kpi-card">
            <div class="kpi-top">
              <div class="kpi-icon green">
                <svg width="18" height="18" fill="none" stroke="#2D6A4F" stroke-width="1.8" viewBox="0 0 22 22">
                  <circle cx="11" cy="11" r="8"/><path d="M11 7v4l3 3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
            </div>
            <div class="kpi-label dark">Novos este Mês</div>
            <div class="kpi-value dark">+12</div>
            <div class="kpi-sub dark">Contas criadas em Maio</div>
          </div>
        </div>

        <!-- TABLE CARD -->
        <div class="table-card">
          <!-- toolbar -->
          <div class="table-toolbar">
            <div class="tabs">
              <button class="tab" :class="{ active: tab === 'gestores' }" @click="tab = 'gestores'; page = 1">Gestores</button>
              <button class="tab" :class="{ active: tab === 'funcionarios' }" @click="tab = 'funcionarios'; page = 1">Funcionários</button>
              <button class="tab" :class="{ active: tab === 'todos' }" @click="tab = 'todos'; page = 1">Todos</button>
            </div>
            <div class="toolbar-right">
              <button class="btn-filter">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 14 14">
                  <path d="M1 3h12M3 7h8M5 11h4" stroke-linecap="round"/>
                </svg>
                Filtros
              </button>
              <div class="search-inline">
                <svg width="13" height="13" fill="none" stroke="#8A9490" stroke-width="1.7" viewBox="0 0 14 14">
                  <circle cx="6" cy="6" r="4.5"/><path d="M10 10l3 3" stroke-linecap="round"/>
                </svg>
                <input type="text" placeholder="Procurar conta..." v-model="tableSearch"/>
              </div>
            </div>
          </div>

          <!-- table -->
          <table>
            <thead>
              <tr>
                <th>Utilizador</th>
                <th>Tipo</th>
                <th>Área / Localização</th>
                <th>Estado</th>
                <th colspan="2">Ações</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="u in paginatedUsers" :key="u.id">
                <td>
                  <div class="user-cell">
                    <div class="user-avatar">
                      <img v-if="u.avatar" :src="u.avatar" :alt="u.nome"/>
                      <span v-else>{{ u.nome.split(' ').map(n => n[0]).join('').slice(0, 2) }}</span>
                    </div>
                    <div>
                      <div class="user-name">{{ u.nome }}</div>
                      <div class="user-email">{{ u.email }}</div>
                    </div>
                  </div>
                </td>
                <td>
                  <span class="badge-tipo" :class="u.tipo === 'Gestor' ? 'gestor' : 'func'">{{ u.tipo }}</span>
                </td>
                <td>
                  <div class="loc-cell">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 14 14">
                      <path d="M7 1.5C4.515 1.5 2.5 3.515 2.5 6c0 3.75 4.5 7.5 4.5 7.5S11.5 9.75 11.5 6c0-2.485-2.015-4.5-4.5-4.5z" stroke-linejoin="round"/>
                      <circle cx="7" cy="6" r="1.5"/>
                    </svg>
                    {{ u.area }}
                  </div>
                </td>
                <td>
                  <div class="toggle-cell">
                    <button class="toggle" :class="u.ativo ? 'on' : 'off'" @click.stop="u.ativo = !u.ativo">
                      <span class="toggle-knob"></span>
                    </button>
                    <span class="toggle-label" :class="u.ativo ? 'on' : 'off'">{{ u.ativo ? 'Ativo' : 'Inativo' }}</span>
                  </div>
                </td>
                <td style="width:48px">
                  <div class="actions-cell">
                    <button class="action-btn del" @click.stop="confirmDelete(u)" title="Eliminar">
                      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 14 14">
                        <path d="M2 3.5h10M5.5 3.5V2.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v1M3.5 3.5l.5 8h6l.5-8" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                    </button>
                  </div>
                </td>
                <td style="width:48px">
                  <div class="actions-cell">
                    <button class="action-btn edit" @click.stop="editUser(u)" title="Editar">
                      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 14 14">
                        <path d="M9.5 2.5l2 2L4 12H2v-2z" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="paginatedUsers.length === 0">
                <td colspan="6" class="empty-row">Nenhum utilizador encontrado.</td>
              </tr>
            </tbody>
          </table>

          <!-- pagination -->
          <div class="pagination-bar">
            <span class="pagination-info">Mostrando {{ paginationInfo }} de {{ filteredUsers.length }} utilizadores</span>
            <div class="pagination-btns">
              <button class="pg-btn" :disabled="page === 1" @click="page--">Anterior</button>
              <button class="pg-btn" v-for="p in totalPages" :key="p" :class="{ active: page === p }" @click="page = p">{{ p }}</button>
              <button class="pg-btn" :disabled="page === totalPages" @click="page++">Próximo</button>
            </div>
          </div>
        </div>

        <!-- SECURITY CARD -->
        <div class="security-card">
          <div class="security-left">
            <div class="security-icon">
              <svg width="22" height="22" fill="none" stroke="#2D6A4F" stroke-width="1.8" viewBox="0 0 22 22">
                <path d="M11 2L20 6.5v6c0 5-4.5 8.5-9 10-4.5-1.5-9-5-9-10v-6z" stroke-linejoin="round"/>
                <path d="M8 11l2.5 2.5 4-5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <div>
              <div class="security-title">Configuração de Segurança</div>
              <div class="security-desc">Novas contas recebem uma palavra-passe temporária. Os utilizadores serão solicitados a alterá-la no primeiro acesso.</div>
            </div>
          </div>
          <button class="btn-outline-sm">Ver Políticas de Acesso</button>
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

    <!-- ── ADD/EDIT MODAL ── -->
    <transition name="fade">
      <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
        <div class="mf-panel" @click.stop>

          <!-- success bar -->
          <div class="mf-success" v-if="modalSaved">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 18 18">
              <circle cx="9" cy="9" r="7"/>
              <path d="M6 9l2.5 2.5 4-5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            {{ editingUser ? 'Utilizador actualizado com sucesso!' : 'Conta criada com sucesso!' }}
          </div>

          <!-- header -->
          <div class="mf-header">
            <div class="mf-title-row">
              <div class="mf-title-icon">
                <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="1.9" viewBox="0 0 20 20">
                  <circle cx="9" cy="7" r="3.5"/>
                  <path d="M2 17c0-3.314 3.134-6 7-6" stroke-linecap="round"/>
                  <path v-if="!editingUser" d="M15 12v5M12.5 14.5h5" stroke-linecap="round"/>
                  <path v-else d="M11.5 14.5l2 2 3.5-3.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
              <h2 class="mf-title">
                {{ editingUser ? 'Editar Utilizador' : (modalTipo === 'gestor' ? 'Adicionar Gestor' : 'Adicionar Funcionário') }}
              </h2>
            </div>
            <button class="btn-close" @click="closeModal">
              <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 13 13">
                <path d="M2 2l9 9M11 2L2 11" stroke-linecap="round"/>
              </svg>
            </button>
          </div>

          <!-- form body -->
          <div class="mf-body">
            <div class="mf-grid">

              <!-- LEFT COLUMN -->
              <div class="mf-col">

                <!-- Nome -->
                <div class="mf-field">
                  <label>Nome</label>
                  <div class="input-wrap" :class="{ err: mErrors.nome }">
                    <div class="input-icon">
                      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                        <circle cx="8" cy="6" r="3"/><path d="M2 14c0-2.761 2.686-5 6-5s6 2.239 6 5" stroke-linecap="round"/>
                      </svg>
                    </div>
                    <input type="text" v-model="mForm.nome" placeholder="Nome completo" @input="mErrors.nome = ''"/>
                  </div>
                  <span class="err-msg" v-if="mErrors.nome">{{ mErrors.nome }}</span>
                </div>

                <!-- Senha -->
                <div class="mf-field">
                  <label>Senha <span v-if="editingUser" class="field-hint">(deixe em branco para não alterar)</span></label>
                  <div class="input-wrap" :class="{ err: mErrors.senha }">
                    <div class="input-icon">
                      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                        <rect x="3" y="7" width="10" height="8" rx="1.5"/>
                        <path d="M5 7V5a3 3 0 0 1 6 0v2" stroke-linecap="round"/>
                        <circle cx="8" cy="11" r="1.2" fill="currentColor"/>
                      </svg>
                    </div>
                    <input :type="showSenha ? 'text' : 'password'" v-model="mForm.senha" placeholder="Palavra-passe" @input="mErrors.senha = ''"/>
                    <button class="btn-eye" type="button" @click="showSenha = !showSenha">
                      <svg v-if="!showSenha" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                        <path d="M1 8s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z"/><circle cx="8" cy="8" r="2"/>
                      </svg>
                      <svg v-else width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                        <path d="M1 8s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z"/><line x1="1" y1="1" x2="15" y2="15" stroke-linecap="round"/>
                      </svg>
                    </button>
                  </div>
                  <span class="err-msg" v-if="mErrors.senha">{{ mErrors.senha }}</span>
                </div>

                <!-- Área de Gestão — apenas Gestores -->
                <div class="mf-field" v-if="modalTipo === 'gestor'">
                  <label>Área de Gestão</label>
                  <div class="input-wrap">
                    <div class="input-icon">
                      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                        <path d="M8 1.5C5.515 1.5 3.5 3.515 3.5 6c0 3.75 4.5 9 4.5 9s4.5-5.25 4.5-9c0-2.485-2.015-4.5-4.5-4.5z"/><circle cx="8" cy="6" r="1.8"/>
                      </svg>
                    </div>
                    <select v-model="mForm.area">
                      <option value="" disabled>Seleccione a província</option>
                      <option v-for="p in provincias" :key="p">{{ p }}</option>
                    </select>
                  </div>
                </div>

                <!-- Projecto — apenas Gestores -->
                <div class="mf-field" v-if="modalTipo === 'gestor'">
                  <label>Projecto</label>
                  <div class="input-wrap">
                    <div class="input-icon">
                      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                        <path d="M2 13L6 4l4 6 3-3 3 4" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                    </div>
                    <select v-model="mForm.projeto">
                      <option value="" disabled>Seleccione o projecto</option>
                      <option>Reserva do Niassa</option>
                      <option>Parque da Gorongosa</option>
                      <option>Arquipélago de Bazaruto</option>
                      <option>Lagoa de Bilene</option>
                      <option>Parque Nacional do Limpopo</option>
                      <option>Outro</option>
                    </select>
                  </div>
                </div>

                <!-- Província — apenas Funcionários -->
                <div class="mf-field" v-if="modalTipo === 'func'">
                  <label>Província</label>
                  <div class="input-wrap">
                    <div class="input-icon">
                      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                        <path d="M8 1.5C5.515 1.5 3.5 3.515 3.5 6c0 3.75 4.5 9 4.5 9s4.5-5.25 4.5-9c0-2.485-2.015-4.5-4.5-4.5z"/><circle cx="8" cy="6" r="1.8"/>
                      </svg>
                    </div>
                    <select v-model="mForm.area">
                      <option value="" disabled>Seleccione a província</option>
                      <option v-for="p in provincias" :key="p">{{ p }}</option>
                    </select>
                  </div>
                </div>

              </div><!-- /left -->

              <!-- RIGHT COLUMN -->
              <div class="mf-col">

                <!-- Número -->
                <div class="mf-field">
                  <label>Número</label>
                  <div class="input-wrap">
                    <div class="input-icon">
                      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                        <rect x="3" y="1" width="10" height="14" rx="2"/>
                        <path d="M8 12h.01" stroke-linecap="round" stroke-width="2"/>
                      </svg>
                    </div>
                    <input type="tel" v-model="mForm.numero" placeholder="+258 84 000 0000"/>
                  </div>
                </div>

                <!-- Email -->
                <div class="mf-field">
                  <label>Email</label>
                  <div class="input-wrap" :class="{ err: mErrors.email }">
                    <div class="input-icon">
                      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                        <rect x="1" y="3" width="14" height="10" rx="1.5"/><path d="M1 5l7 5 7-5" stroke-linecap="round"/>
                      </svg>
                    </div>
                    <input type="email" v-model="mForm.email" placeholder="nome@mta.gov.mz" @input="mErrors.email = ''"/>
                  </div>
                  <span class="err-msg" v-if="mErrors.email">{{ mErrors.email }}</span>
                </div>

                <!-- Confirmar Senha -->
                <div class="mf-field">
                  <label>Confirmar Senha <span v-if="editingUser" class="field-hint">(opcional)</span></label>
                  <div class="input-wrap" :class="{ err: mErrors.confirma }">
                    <div class="input-icon">
                      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                        <rect x="3" y="7" width="10" height="8" rx="1.5"/>
                        <path d="M5 7V5a3 3 0 0 1 6 0v2" stroke-linecap="round"/>
                        <circle cx="8" cy="11" r="1.2" fill="currentColor"/>
                      </svg>
                    </div>
                    <input :type="showConfirma ? 'text' : 'password'" v-model="mForm.confirma" placeholder="Repita a palavra-passe" @input="mErrors.confirma = ''"/>
                    <button class="btn-eye" type="button" @click="showConfirma = !showConfirma">
                      <svg v-if="!showConfirma" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                        <path d="M1 8s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z"/><circle cx="8" cy="8" r="2"/>
                      </svg>
                      <svg v-else width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                        <path d="M1 8s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z"/><line x1="1" y1="1" x2="15" y2="15" stroke-linecap="round"/>
                      </svg>
                    </button>
                  </div>
                  <span class="err-msg" v-if="mErrors.confirma">{{ mErrors.confirma }}</span>
                </div>

                <!-- Checkboxes — apenas Gestores -->
                <div class="mf-field" v-if="modalTipo === 'gestor'">
                  <div class="checks-row">
                    <div class="check-group">
                      <span class="check-label-title">Alertas VBG</span>
                      <label class="check-item">
                        <input type="checkbox" v-model="mForm.alertaVbg"/>
                        <span>Sim</span>
                      </label>
                    </div>
                    <div class="check-group">
                      <span class="check-label-title">Alertas urgentes</span>
                      <label class="check-item">
                        <input type="checkbox" v-model="mForm.alertaUrgente"/>
                        <span>Sim</span>
                      </label>
                    </div>
                    <div class="check-group">
                      <span class="check-label-title">Alertas normais</span>
                      <label class="check-item">
                        <input type="checkbox" v-model="mForm.alertaNormal"/>
                        <span>Sim</span>
                      </label>
                    </div>
                  </div>
                </div>

              </div><!-- /right -->
            </div><!-- /mf-grid -->
          </div><!-- /mf-body -->

          <!-- actions -->
          <div class="mf-footer">
            <button class="btn-gravar" @click="saveUser" :disabled="mLoading">
              <svg v-if="mLoading" class="spin" width="15" height="15" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 16 16">
                <path d="M8 2a6 6 0 0 1 6 6" stroke-linecap="round"/>
              </svg>
              <svg v-else width="14" height="14" fill="none" stroke="#fff" stroke-width="1.8" viewBox="0 0 16 16">
                <path d="M2 14V9h4v5M2 2h9l3 3v9a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1z" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M10 2v4H5V2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              {{ mLoading ? 'A gravar…' : (editingUser ? 'Guardar Alterações' : 'Gravar') }}
            </button>
            <button class="btn-cancelar" @click="closeModal">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 14 14">
                <circle cx="7" cy="7" r="5.5"/><path d="M5 5l4 4M9 5L5 9" stroke-linecap="round"/>
              </svg>
              Cancelar
            </button>
          </div>

        </div>
      </div>
    </transition>

    <!-- ── DELETE CONFIRM MODAL ── -->
    <transition name="fade">
      <div v-if="showDelete" class="modal-overlay" @click.self="showDelete = false">
        <div class="modal modal-sm" @click.stop>
          <div class="del-icon-wrap">
            <svg width="24" height="24" fill="none" stroke="#E53E3E" stroke-width="2" viewBox="0 0 24 24">
              <path d="M3 6h18M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2M5 6l1 14a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-14" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <h3>Eliminar Utilizador</h3>
          <p class="del-desc">Tem a certeza que deseja eliminar <strong>{{ deleteTarget?.nome }}</strong>? Esta acção é irreversível.</p>
          <div class="modal-actions">
            <button class="btn-modal-cancel" @click="showDelete = false">Cancelar</button>
            <button class="btn-del-confirm" @click="doDelete">Eliminar</button>
          </div>
        </div>
      </div>
    </transition>

    <!-- TOAST -->
    <transition name="toast-slide">
      <div v-if="toast" class="toast">
        <svg width="16" height="16" fill="none" stroke="#fff" stroke-width="2.2" viewBox="0 0 16 16">
          <circle cx="8" cy="8" r="6"/><path d="M5.5 8l2 2 3.5-4" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        {{ toast }}
      </div>
    </transition>

  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'

const topSearch    = ref('')
const tableSearch  = ref('')
const tab          = ref('gestores')
const page         = ref(1)
const perPage      = 5
const showModal    = ref(false)
const showDelete   = ref(false)
const modalTipo    = ref('gestor')
const editingUser  = ref(null)
const deleteTarget = ref(null)
const toast        = ref('')
const mLoading     = ref(false)
const modalSaved   = ref(false)
const showSenha    = ref(false)
const showConfirma = ref(false)

const provincias = [
  'Cabo Delgado', 'Gaza', 'Inhambane', 'Manica',
  'Maputo Cidade', 'Maputo Província', 'Nampula',
  'Niassa', 'Sofala', 'Tete', 'Zambézia'
]

const mForm = reactive({
  nome: '', numero: '', email: '', senha: '', confirma: '',
  tipo: '', area: '', projeto: '',
  alertaVbg: false, alertaUrgente: false, alertaNormal: false
})

const mErrors = reactive({ nome: '', email: '', senha: '', confirma: '' })

const users = ref([
  { id: 1, nome: 'João Baptista',    email: 'j.baptista@mta.gov.mz',  tipo: 'Gestor',      area: 'Maputo',        ativo: true,  avatar: 'https://randomuser.me/api/portraits/men/32.jpg' },
  { id: 2, nome: 'Carlos Mondlane',  email: 'c.mondlane@mta.gov.mz',  tipo: 'Gestor',      area: 'Sofala',        ativo: false, avatar: 'https://randomuser.me/api/portraits/men/44.jpg' },
  { id: 3, nome: 'Sérgio Chongo',    email: 's.chongo@mta.gov.mz',    tipo: 'Gestor',      area: 'Gaza',          ativo: true,  avatar: 'https://randomuser.me/api/portraits/men/55.jpg' },
  { id: 4, nome: 'Amélia Sitoe',     email: 'a.sitoe@mta.gov.mz',     tipo: 'Funcionário', area: 'Nampula',       ativo: true,  avatar: 'https://randomuser.me/api/portraits/women/33.jpg' },
  { id: 5, nome: 'Helena Macuácua',  email: 'h.macuacua@mta.gov.mz',  tipo: 'Funcionário', area: 'Inhambane',     ativo: true,  avatar: 'https://randomuser.me/api/portraits/women/45.jpg' },
  { id: 6, nome: 'Marta Tembe',      email: 'm.tembe@mta.gov.mz',     tipo: 'Funcionário', area: 'Sofala',        ativo: false, avatar: 'https://randomuser.me/api/portraits/women/56.jpg' },
  { id: 7, nome: 'Pedro Chimoio',    email: 'p.chimoio@mta.gov.mz',   tipo: 'Gestor',      area: 'Manica',        ativo: true,  avatar: 'https://randomuser.me/api/portraits/men/67.jpg' },
  { id: 8, nome: 'Fátima Nhantumbo', email: 'f.nhantumbo@mta.gov.mz', tipo: 'Funcionário', area: 'Maputo Cidade', ativo: true,  avatar: 'https://randomuser.me/api/portraits/women/67.jpg' },
])

const filteredUsers = computed(() => {
  let list = users.value
  if (tab.value === 'gestores')     list = list.filter(u => u.tipo === 'Gestor')
  if (tab.value === 'funcionarios') list = list.filter(u => u.tipo === 'Funcionário')
  if (tableSearch.value.trim()) {
    const q = tableSearch.value.toLowerCase()
    list = list.filter(u =>
      u.nome.toLowerCase().includes(q) ||
      u.email.toLowerCase().includes(q) ||
      u.area.toLowerCase().includes(q)
    )
  }
  return list
})

const totalPages = computed(() => Math.max(1, Math.ceil(filteredUsers.value.length / perPage)))

const paginatedUsers = computed(() => {
  const start = (page.value - 1) * perPage
  return filteredUsers.value.slice(start, start + perPage)
})

const paginationInfo = computed(() => {
  if (!filteredUsers.value.length) return '0'
  const start = (page.value - 1) * perPage + 1
  const end   = Math.min(page.value * perPage, filteredUsers.value.length)
  return `${start}-${end}`
})

function showToast(msg) {
  toast.value = msg
  setTimeout(() => { toast.value = '' }, 3000)
}

function resetMForm() {
  Object.assign(mForm, {
    nome: '', numero: '', email: '', senha: '', confirma: '',
    tipo: '', area: '', projeto: '',
    alertaVbg: false, alertaUrgente: false, alertaNormal: false
  })
  Object.assign(mErrors, { nome: '', email: '', senha: '', confirma: '' })
  showSenha.value   = false
  showConfirma.value = false
  modalSaved.value  = false
}

function openModal(tipo) {
  modalTipo.value   = tipo
  editingUser.value = null
  resetMForm()
  mForm.tipo = tipo === 'gestor' ? 'Gestor' : 'Funcionário'
  showModal.value   = true
}

function editUser(u) {
  editingUser.value = u
  modalTipo.value   = u.tipo === 'Gestor' ? 'gestor' : 'func'
  resetMForm()
  Object.assign(mForm, {
    nome: u.nome, email: u.email, tipo: u.tipo, area: u.area,
    numero: u.numero || '', projeto: u.projeto || '',
    alertaVbg: u.alertaVbg || false,
    alertaUrgente: u.alertaUrgente || false,
    alertaNormal: u.alertaNormal || false,
  })
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  resetMForm()
}

function validateForm() {
  let ok = true
  if (!mForm.nome.trim())  { mErrors.nome  = 'O nome é obrigatório.'; ok = false }
  if (!mForm.email.trim()) { mErrors.email = 'O email é obrigatório.'; ok = false }
  else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(mForm.email)) { mErrors.email = 'Formato de email inválido.'; ok = false }
  if (!editingUser.value || mForm.senha) {
    if (!editingUser.value && !mForm.senha) { mErrors.senha = 'A senha é obrigatória.'; ok = false }
    else if (mForm.senha && mForm.senha.length < 6) { mErrors.senha = 'Mínimo 6 caracteres.'; ok = false }
    if (mForm.senha && mForm.senha !== mForm.confirma) { mErrors.confirma = 'As senhas não coincidem.'; ok = false }
  }
  return ok
}

async function saveUser() {
  modalSaved.value = false
  if (!validateForm()) return
  mLoading.value = true
  await new Promise(r => setTimeout(r, 900))
  mLoading.value = false
  if (editingUser.value) {
    Object.assign(editingUser.value, {
      nome: mForm.nome, email: mForm.email, tipo: mForm.tipo, area: mForm.area,
      numero: mForm.numero, projeto: mForm.projeto,
      alertaVbg: mForm.alertaVbg, alertaUrgente: mForm.alertaUrgente, alertaNormal: mForm.alertaNormal
    })
  } else {
    users.value.push({
      id: Date.now(), nome: mForm.nome, email: mForm.email,
      tipo: mForm.tipo, area: mForm.area, numero: mForm.numero,
      projeto: mForm.projeto, ativo: true, avatar: '',
      alertaVbg: mForm.alertaVbg, alertaUrgente: mForm.alertaUrgente, alertaNormal: mForm.alertaNormal
    })
  }
  modalSaved.value = true
  setTimeout(() => { showModal.value = false; resetMForm() }, 1400)
}

function confirmDelete(u) { deleteTarget.value = u; showDelete.value = true }

function doDelete() {
  users.value = users.value.filter(u => u.id !== deleteTarget.value.id)
  showDelete.value = false
  showToast('Utilizador eliminado.')
}
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
  width: 32px; height: 32px;
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

/* ── TOPBAR ──────────────────────────────── */
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
  border: none; outline: none; background: transparent;
  font-family: 'Poppins', sans-serif; font-size: 13px; color: var(--text-dark); width: 100%;
}
.search-wrap input::placeholder { color: var(--text-light); }

.topbar-spacer { flex: 1; }

.notif-btn {
  position: relative;
  width: 38px; height: 38px;
  background: #F4F6F5;
  border: 1.5px solid var(--border);
  border-radius: 9px;
  display: flex; align-items: center; justify-content: center;
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
  display: flex; align-items: center; justify-content: center;
  font-size: 13px; font-weight: 800; color: #fff;
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

/* page title */
.page-title-row {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  margin-bottom: 22px;
}
.page-title-row h1 { font-size: 22px; font-weight: 800; margin-bottom: 4px; }
.page-title-row p  { font-size: 13px; color: var(--text-gray); }

.title-actions { display: flex; gap: 10px; flex-shrink: 0; }

.btn-green-sm {
  display: flex; align-items: center; gap: 7px;
  background: var(--green-mid); color: #fff;
  border: none; border-radius: 8px; padding: 9px 18px;
  font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 700;
  cursor: pointer; transition: background 0.2s;
}
.btn-green-sm:hover { background: var(--green-dark); }

.btn-outline-sm {
  display: flex; align-items: center; gap: 7px;
  background: var(--white); color: var(--text-dark);
  border: 1.5px solid var(--border); border-radius: 8px; padding: 8px 16px;
  font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600;
  cursor: pointer; transition: border-color 0.2s, color 0.2s;
}
.btn-outline-sm:hover { border-color: var(--green-mid); color: var(--green-mid); }

/* ── KPI CARDS ───────────────────────────── */
.kpi-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
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

/* ── TABLE CARD ──────────────────────────── */
.table-card {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 12px;
  overflow: hidden;
  margin-bottom: 16px;
}

.table-toolbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px 20px 0;
  gap: 12px;
}

.tabs { display: flex; gap: 2px; background: #F4F6F5; border-radius: 8px; padding: 4px; }

.tab {
  padding: 7px 18px;
  border-radius: 6px;
  font-size: 13px; font-weight: 500;
  color: var(--text-gray);
  cursor: pointer; transition: background 0.15s, color 0.15s;
  border: none; background: transparent;
  font-family: 'Poppins', sans-serif;
}
.tab.active { background: var(--white); color: var(--text-dark); font-weight: 700; box-shadow: 0 1px 4px rgba(0,0,0,.08); }

.toolbar-right { display: flex; align-items: center; gap: 10px; }

.btn-filter {
  display: inline-flex; align-items: center; gap: 6px;
  background: var(--white); color: var(--text-gray);
  border: 1.5px solid var(--border); border-radius: 8px;
  padding: 7px 14px;
  font-family: 'Poppins', sans-serif; font-size: 12.5px; font-weight: 600;
  cursor: pointer; transition: border-color 0.2s;
}
.btn-filter:hover { border-color: var(--green-mid); color: var(--green-mid); }

.search-inline {
  display: flex; align-items: center; gap: 8px;
  background: var(--white); border: 1.5px solid var(--border);
  border-radius: 8px; padding: 7px 12px;
  transition: border-color 0.2s, box-shadow 0.2s;
}
.search-inline:focus-within { border-color: var(--green-light); box-shadow: 0 0 0 3px rgba(82,183,136,.12); }
.search-inline input {
  border: none; outline: none; background: transparent;
  font-family: 'Poppins', sans-serif; font-size: 12.5px; color: var(--text-dark); width: 160px;
}
.search-inline input::placeholder { color: var(--text-light); }

/* table */
table { width: 100%; border-collapse: collapse; margin-top: 14px; }

thead th {
  padding: 12px 20px;
  font-size: 11px; font-weight: 700;
  color: var(--text-light); text-align: left;
  border-top: 1px solid var(--border);
  border-bottom: 1px solid var(--border);
  background: #F4F6F5;
  text-transform: uppercase; letter-spacing: 0.4px;
}

tbody tr { transition: background 0.15s; cursor: pointer; }
tbody tr:hover { background: #F9FBFA; }
tbody td { padding: 14px 20px; vertical-align: middle; border-bottom: 1px solid var(--border); }
tbody tr:last-child td { border-bottom: none; }

.user-cell { display: flex; align-items: center; gap: 12px; }
.user-avatar {
  width: 38px; height: 38px; border-radius: 50%; overflow: hidden;
  flex-shrink: 0; background: var(--green-bg);
  display: flex; align-items: center; justify-content: center;
  font-size: 13px; font-weight: 800; color: var(--green-mid);
}
.user-avatar img { width: 100%; height: 100%; object-fit: cover; }
.user-name  { font-size: 13px; font-weight: 600; color: var(--text-dark); margin-bottom: 2px; }
.user-email { font-size: 11.5px; color: var(--text-light); }

.badge-tipo {
  display: inline-block; padding: 4px 12px; border-radius: 99px;
  font-size: 11.5px; font-weight: 700;
}
.badge-tipo.gestor { background: var(--green-pale); color: var(--green-dark); }
.badge-tipo.func   { background: #EBF4FF; color: #2B6CB0; }

.loc-cell { display: flex; align-items: center; gap: 5px; font-size: 13px; color: var(--text-gray); }

.toggle-cell { display: flex; align-items: center; gap: 9px; font-size: 13px; }
.toggle {
  width: 40px; height: 22px; border-radius: 99px;
  position: relative; cursor: pointer; border: none; padding: 0;
  transition: background 0.25s; flex-shrink: 0;
}
.toggle.on  { background: var(--green-mid); }
.toggle.off { background: #CBD5D1; }
.toggle-knob {
  position: absolute; top: 3px; width: 16px; height: 16px;
  border-radius: 50%; background: #fff;
  box-shadow: 0 1px 4px rgba(0,0,0,.18);
  transition: left 0.25s;
}
.toggle.on  .toggle-knob { left: 21px; }
.toggle.off .toggle-knob { left: 3px; }
.toggle-label { font-size: 12.5px; font-weight: 500; }
.toggle-label.on  { color: var(--green-mid); }
.toggle-label.off { color: var(--text-light); }

.action-btn {
  width: 32px; height: 32px; border-radius: 7px; border: none;
  display: inline-flex; align-items: center; justify-content: center;
  cursor: pointer; transition: background 0.15s;
}
.action-btn.del  { background: #FFF5F5; color: #E53E3E; }
.action-btn.del:hover  { background: #FED7D7; }
.action-btn.edit { background: var(--green-bg); color: var(--green-mid); }
.action-btn.edit:hover { background: var(--green-pale); }
.actions-cell { display: flex; align-items: center; gap: 6px; }

.empty-row { text-align: center; padding: 28px; color: var(--text-light); font-size: 13px; }

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

/* ── SECURITY CARD ───────────────────────── */
.security-card {
  background: var(--white); border: 1px solid var(--border); border-radius: 12px;
  padding: 20px 24px; display: flex; align-items: center; justify-content: space-between; gap: 20px;
}
.security-left { display: flex; align-items: center; gap: 16px; }
.security-icon {
  width: 44px; height: 44px; border-radius: 11px; background: var(--green-bg);
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.security-title { font-size: 14px; font-weight: 700; margin-bottom: 4px; }
.security-desc  { font-size: 12.5px; color: var(--text-gray); line-height: 1.55; max-width: 600px; }

/* ── FOOTER ──────────────────────────────── */
.dash-footer {
  display: flex; align-items: center; justify-content: space-between;
  padding: 12px 28px;
  background: var(--white); border-top: 1px solid var(--border);
  font-size: 11.5px; color: var(--text-light); flex-shrink: 0;
}
.dash-footer a {
  color: var(--text-light); text-decoration: none; margin-left: 16px; transition: color 0.2s;
}
.dash-footer a:hover { color: var(--green-mid); }

/* ── MODAL OVERLAY (shared) ──────────────── */
.modal-overlay {
  position: fixed; inset: 0; background: rgba(0, 0, 0, 0.45);
  display: flex; align-items: center; justify-content: center; z-index: 200;
  padding: 16px;
}

/* ── ADD/EDIT MODAL FORM ─────────────────── */
.mf-panel {
  background: var(--white); border-radius: 16px;
  width: 760px; max-width: 100%;
  max-height: 90vh; overflow-y: auto;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
  display: flex; flex-direction: column;
}
.mf-panel::-webkit-scrollbar { width: 4px; }
.mf-panel::-webkit-scrollbar-thumb { background: #C8D8CE; border-radius: 99px; }

.mf-success {
  background: var(--green-bg); border: 1px solid #C3E6CE; border-radius: 10px;
  padding: 13px 18px; display: flex; align-items: center; gap: 10px;
  font-size: 13px; font-weight: 600; color: var(--green-mid);
  margin: 20px 28px 0;
  animation: fadeInBanner 0.3s ease;
}
@keyframes fadeInBanner { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: none; } }

.mf-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 22px 28px 18px; border-bottom: 1px solid var(--border); flex-shrink: 0;
}
.mf-title-row { display: flex; align-items: center; gap: 12px; }
.mf-title-icon {
  width: 40px; height: 40px; border-radius: 50%;
  background: var(--green-mid);
  display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.mf-title { font-size: 18px; font-weight: 700; color: var(--green-mid); }

.btn-close {
  background: #F4F6F5; border: 1.5px solid var(--border); border-radius: 8px;
  width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;
  cursor: pointer; transition: background 0.2s; flex-shrink: 0;
}
.btn-close:hover { background: #FFF5F5; border-color: #FC8181; }

.mf-body { padding: 24px 28px 8px; }

.mf-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0 48px;
}

.mf-col { display: flex; flex-direction: column; }

.mf-field { display: flex; flex-direction: column; gap: 6px; margin-bottom: 18px; }
.mf-field > label { font-size: 13px; font-weight: 500; color: var(--text-dark); }

.field-hint { font-size: 11px; font-weight: 400; color: var(--text-light); margin-left: 5px; }

/* input-wrap (icon + input) */
.input-wrap {
  display: flex; align-items: center;
  border: 1.5px solid var(--border); border-radius: 9px;
  background: var(--white); overflow: hidden;
  transition: border-color 0.2s, box-shadow 0.2s;
}
.input-wrap:focus-within {
  border-color: var(--green-light);
  box-shadow: 0 0 0 3px rgba(82, 183, 136, 0.13);
}
.input-wrap.err { border-color: #FC8181; box-shadow: 0 0 0 3px rgba(252, 129, 129, 0.11); }

.input-icon {
  padding: 0 11px; display: flex; align-items: center;
  color: var(--text-light); flex-shrink: 0;
}

.input-wrap input,
.input-wrap select {
  flex: 1; border: none; outline: none;
  font-family: 'Poppins', sans-serif; font-size: 13px; color: var(--text-dark);
  padding: 11px 12px 11px 0; background: transparent;
  appearance: none; -webkit-appearance: none;
}
.input-wrap input::placeholder { color: var(--text-light); }
.input-wrap select {
  cursor: pointer; padding-right: 36px;
  background-image: url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%239AA5A2' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
  background-repeat: no-repeat; background-position: right 12px center;
}

.btn-eye {
  background: none; border: none; cursor: pointer;
  padding: 0 11px; color: var(--text-light); display: flex;
  transition: color 0.2s;
}
.btn-eye:hover { color: var(--green-mid); }

.err-msg { font-size: 11.5px; color: #E53E3E; }

/* checkboxes */
.checks-row { display: flex; gap: 20px; align-items: flex-start; flex-wrap: wrap; }
.check-group { display: flex; flex-direction: column; gap: 7px; }
.check-label-title { font-size: 11.5px; font-weight: 600; color: var(--text-dark); line-height: 1.35; }
.check-item { display: flex; align-items: center; gap: 7px; cursor: pointer; }
.check-item input[type=checkbox] {
  width: 16px; height: 16px; border-radius: 4px;
  accent-color: var(--green-mid); cursor: pointer; flex-shrink: 0;
}
.check-item span { font-size: 13px; color: var(--text-gray); }

.mf-footer {
  display: flex; gap: 12px;
  padding: 16px 28px 24px; border-top: 1px solid var(--border);
  flex-shrink: 0;
}

/* action buttons */
.btn-gravar {
  display: inline-flex; align-items: center; gap: 8px;
  background: var(--green-mid); color: #fff; border: none; border-radius: 9px;
  padding: 12px 28px;
  font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 600;
  cursor: pointer; transition: background 0.2s, transform 0.15s;
}
.btn-gravar:hover { background: var(--green-dark); transform: translateY(-1px); }
.btn-gravar:disabled { background: #A0C4B0; cursor: not-allowed; transform: none; }

.btn-cancelar {
  display: inline-flex; align-items: center; gap: 8px;
  background: transparent; color: #E53E3E;
  border: 1.5px solid #FC8181; border-radius: 9px;
  padding: 10px 24px;
  font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 600;
  cursor: pointer; transition: background 0.2s, border-color 0.2s;
}
.btn-cancelar:hover { background: #FFF5F5; border-color: #E53E3E; }

@keyframes spin { to { transform: rotate(360deg); } }
.spin { animation: spin 0.7s linear infinite; }

/* ── DELETE MODAL ────────────────────────── */
.modal {
  background: var(--white); border-radius: 16px; padding: 32px 32px 28px;
  width: 380px; max-width: 95vw;
  box-shadow: 0 16px 60px rgba(0, 0, 0, 0.18);
}

.modal-actions { display: flex; gap: 10px; margin-top: 22px; }

.btn-modal-cancel {
  flex: 1; background: var(--white); color: var(--text-gray);
  border: 1.5px solid var(--border); border-radius: 9px;
  padding: 11px; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600;
  cursor: pointer; transition: border-color 0.2s;
}
.btn-modal-cancel:hover { border-color: var(--text-gray); }

.del-icon-wrap {
  width: 52px; height: 52px; border-radius: 50%; background: #FFF5F5;
  display: flex; align-items: center; justify-content: center; margin-bottom: 14px;
}
.modal h3 { font-size: 16px; font-weight: 800; margin-bottom: 4px; }
.del-desc { font-size: 13px; color: var(--text-gray); line-height: 1.6; margin-bottom: 22px; }

.btn-del-confirm {
  flex: 1; background: #E53E3E; color: #fff; border: none; border-radius: 9px;
  padding: 11px; font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 700;
  cursor: pointer; transition: background 0.2s;
}
.btn-del-confirm:hover { background: #C53030; }

/* ── TOAST ───────────────────────────────── */
.toast {
  position: fixed; bottom: 24px; right: 24px; z-index: 500;
  background: var(--green-dark); color: #fff; border-radius: 12px;
  padding: 13px 18px; display: flex; align-items: center; gap: 9px;
  font-size: 13px; font-weight: 600; box-shadow: 0 8px 32px rgba(0,0,0,.18);
}

/* transitions */
.fade-enter-active, .fade-leave-active { transition: opacity 0.2s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

.toast-slide-enter-active, .toast-slide-leave-active { transition: opacity 0.3s ease, transform 0.3s ease; }
.toast-slide-enter-from, .toast-slide-leave-to { opacity: 0; transform: translateY(16px); }
</style>
