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
        <router-link class="nav-item" to="/admin/dashboard">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <rect x="1" y="1" width="6" height="6" rx="1.5" />
            <rect x="9" y="1" width="6" height="6" rx="1.5" />
            <rect x="1" y="9" width="6" height="6" rx="1.5" />
            <rect x="9" y="9" width="6" height="6" rx="1.5" />
          </svg>
          Dashboard
        </router-link>
        <router-link class="nav-item" to="/admin/validacao">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <path d="M8 1l1.5 3 3.5.5-2.5 2.5.5 3.5L8 9l-3 1.5.5-3.5L3 4.5 6.5 4z" />
          </svg>
          Validação
        </router-link>
        <a class="nav-item active">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <circle cx="8" cy="6" r="3" />
            <path d="M2 14c0-2.761 2.686-5 6-5s6 2.239 6 5" stroke-linecap="round" />
          </svg>
          Utilizadores
        </a>
        <router-link class="nav-item" to="/admin/historico">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <rect x="2" y="1" width="10" height="14" rx="1.5" />
            <path d="M5 5h4M5 8h4M5 11h2" stroke-linecap="round" />
            <path d="M10 1v4h4" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          Histórico de Ocorrências
        </router-link>
        <router-link class="nav-item" to="/admin/categorias">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <circle cx="5" cy="5" r="2" />
            <circle cx="11" cy="5" r="2" />
            <circle cx="5" cy="11" r="2" />
            <circle cx="11" cy="11" r="2" />
          </svg>
          Categorias
        </router-link>
        <router-link class="nav-item" to="/admin/projectos">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <path d="M2 13L6 4l4 6 3-3 3 4" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
          Projectos
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
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16"><path d="M2 4h12M2 8h12M2 12h12" stroke-linecap="round" /></svg>
        </button>
        <div class="search-wrap">
          <svg width="15" height="15" fill="none" stroke="#8A9490" stroke-width="1.8" viewBox="0 0 16 16">
            <circle cx="7" cy="7" r="5" />
            <path d="M12 12l3 3" stroke-linecap="round" />
          </svg>
          <input type="text" placeholder="Pesquisar utilizadores…" v-model="tableSearch" @keyup.enter="loadUsers(1)" />
        </div>
        <div class="topbar-spacer"></div>
        <AdminNotificationPanel />
        <AdminProfilePanel />
      </header>

      <!-- CONTENT -->
      <main class="content">

        <div class="page-title-row">
          <div>
            <h1>Gestão de Utilizadores</h1>
            <p>Administre as contas de acesso ao sistema BIOFUND.</p>
          </div>
          <div class="title-actions">
            <button class="btn-green-sm" @click="showTypeModal = true">
              <svg width="13" height="13" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 16 16">
                <circle cx="8" cy="6" r="3" />
                <path d="M2 14c0-2.761 2.686-5 6-5s6 2.239 6 5" stroke-linecap="round" />
                <path d="M13 4v4M11 6h4" stroke-linecap="round" />
              </svg>
              Novo Utilizador
            </button>
          </div>
        </div>

        <!-- KPI CARDS -->
        <div class="kpi-grid">
          <div class="kpi-card">
            <div class="kpi-top">
              <div class="kpi-icon green">
                <svg width="18" height="18" fill="none" stroke="#2D6A4F" stroke-width="1.8" viewBox="0 0 22 22">
                  <path d="M3 19c0-3.314 3.582-6 8-6s8 2.686 8 6" />
                  <circle cx="11" cy="8" r="4" />
                </svg>
              </div>
            </div>
            <div class="kpi-label dark">Total de Utilizadores</div>
            <div class="kpi-value dark">{{ meta.total ?? '-' }}</div>
            <div class="kpi-sub dark">Gestores e funcionários</div>
          </div>
          <div class="kpi-card highlight">
            <div class="kpi-top">
              <div class="kpi-icon white">
                <svg width="18" height="18" fill="none" stroke="rgba(255,255,255,.9)" stroke-width="1.8"
                  viewBox="0 0 22 22">
                  <path d="M11 3L20 8v6c0 4-4.5 7-9 9-4.5-2-9-5-9-9V8z" stroke-linejoin="round" />
                  <path d="M8 11l2.5 2.5 4-5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
              </div>
            </div>
            <div class="kpi-label light">Contas Ativas</div>
            <div class="kpi-value light">{{users.filter(u => u.is_active).length}}</div>
            <div class="kpi-sub light">Na página actual</div>
          </div>
          <div class="kpi-card">
            <div class="kpi-top">
              <div class="kpi-icon green">
                <svg width="18" height="18" fill="none" stroke="#2D6A4F" stroke-width="1.8" viewBox="0 0 22 22">
                  <circle cx="11" cy="11" r="8" />
                  <path d="M11 7v4l3 3" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
              </div>
            </div>
            <div class="kpi-label dark">Gestores</div>
            <div class="kpi-value dark">{{users.filter(u => u.role === 'gestor').length}}</div>
            <div class="kpi-sub dark">Na página actual</div>
          </div>
        </div>

        <!-- TABLE CARD -->
        <div class="table-card">
          <div class="table-toolbar">
            <div class="tabs">
              <button class="tab" :class="{ active: tab === 'todos' }" @click="changeTab('todos')">Todos</button>
              <button class="tab" :class="{ active: tab === 'gestor' }" @click="changeTab('gestor')">Gestores</button>
              <button class="tab" :class="{ active: tab === 'funcionario' }"
                @click="changeTab('funcionario')">Funcionários</button>
              <button class="tab" :class="{ active: tab === 'observador' }"
                @click="changeTab('observador')">Observadores</button>
            </div>
            <div class="toolbar-right">
              <div class="search-inline">
                <svg width="13" height="13" fill="none" stroke="#8A9490" stroke-width="1.7" viewBox="0 0 14 14">
                  <circle cx="6" cy="6" r="4.5" />
                  <path d="M10 10l3 3" stroke-linecap="round" />
                </svg>
                <input type="text" placeholder="Procurar conta…" v-model="tableSearch" @keyup.enter="loadUsers(1)" />
              </div>
            </div>
          </div>

          <!-- Skeleton -->
          <div v-if="loading" class="skeleton-wrap">
            <div class="skeleton-row" v-for="n in 5" :key="n">
              <div class="sk-bar"></div>
            </div>
          </div>

          <table v-else>
            <thead>
              <tr>
                <th>Utilizador</th>
                <th>Perfil</th>
                <th>Províncias</th>
                <th>Projectos</th>
                <th>Estado</th>
                <th colspan="2">Ações</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="u in users" :key="u.id">
                <td>
                  <div class="user-cell">
                    <div class="user-avatar">
                      <span>{{ initials(u.name) }}</span>
                    </div>
                    <div>
                      <div class="user-name">{{ u.name }}</div>
                      <div class="user-email">{{ u.email
                       }}</div>
                      <div class="user-phone" v-if="u.phone">{{ u.phone }}</div>
                    </div>
                  </div>
                </td>
                <td>
                  <span class="badge-tipo" :class="u.role">{{ u.role_label }}</span>
                </td>
                <td>
                  <!-- Gestores: múltiplas províncias em badges -->
                  <div class="provinces-cell" v-if="u.provinces?.length">
                    <span class="prov-badge" v-for="p in u.provinces" :key="p.id">{{ p.name }}</span>
                  </div>
                  <span v-else class="td-muted">-</span>
                </td>
                <td>
                  <div class="projects-cell" v-if="u.projects?.length">
                    <span class="proj-tag" v-for="p in u.projects.slice(0, 2)" :key="p.id">{{ p.code }}</span>
                    <span class="proj-more" v-if="u.projects.length > 2">+{{ u.projects.length - 2 }}</span>
                  </div>
                  <span v-else class="td-muted">-</span>
                </td>
                <td>
                  <div class="toggle-cell">
                    <button class="toggle" :class="u.is_active ? 'on' : 'off'" @click.stop="handleToggle(u)">
                      <span class="toggle-knob"></span>
                    </button>
                    <span class="toggle-label" :class="u.is_active ? 'on' : 'off'">{{ u.is_active ? 'Ativo' : 'Inativo'
                      }}</span>
                  </div>
                </td>
                <td style="width:48px">
                  <button class="action-btn del" @click.stop="confirmDelete(u)" title="Eliminar">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8"
                      viewBox="0 0 14 14">
                      <path d="M2 3.5h10M5.5 3.5V2.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5v1M3.5 3.5l.5 8h6l.5-8"
                        stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                  </button>
                </td>
                <td style="width:48px">
                  <button class="action-btn edit" @click.stop="editUser(u)" title="Editar">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8"
                      viewBox="0 0 14 14">
                      <path d="M9.5 2.5l2 2L4 12H2v-2z" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                  </button>
                </td>
              </tr>
              <tr v-if="users.length === 0">
                <td colspan="7" class="empty-row">Nenhum utilizador encontrado.</td>
              </tr>
            </tbody>
          </table>

          <div class="pagination-bar">
            <span class="pagination-info">{{ paginationInfo }}</span>
            <div class="pagination-btns">
              <button class="pg-btn" :disabled="meta.current_page <= 1 || loading"
                @click="loadUsers(meta.current_page - 1)">Anterior</button>
              <button class="pg-btn" v-for="p in visiblePages" :key="p" :class="{ active: meta.current_page === p }"
                @click="loadUsers(p)">{{ p }}</button>
              <button class="pg-btn" :disabled="meta.current_page >= meta.last_page || loading"
                @click="loadUsers(meta.current_page + 1)">Próximo</button>
            </div>
          </div>
        </div>

      </main>

      <footer class="dash-footer">
        <span>© 2026 BIOFUND Admin · Sistema de Gestão Ambiental de Moçambique</span>
        <div><a href="#">Suporte Técnico</a><a href="#">Privacidade</a></div>
      </footer>
    </div>

    <!-- ── TYPE PICKER MODAL ── -->
    <transition name="fade">
      <div v-if="showTypeModal" class="modal-overlay" @click.self="showTypeModal = false">
        <div class="type-picker-panel" @click.stop>
          <div class="mf-header">
            <div class="mf-title-row">
              <div class="mf-title-icon">
                <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="1.9" viewBox="0 0 20 20">
                  <circle cx="9" cy="7" r="3.5" />
                  <path d="M2 17c0-3.314 3.134-6 7-6" stroke-linecap="round" />
                  <path d="M15 12v5M12.5 14.5h5" stroke-linecap="round" />
                </svg>
              </div>
              <h2 class="mf-title">Novo Utilizador</h2>
            </div>
            <button type="button" class="btn-close" @click="showTypeModal = false">
              <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 13 13">
                <path d="M2 2l9 9M11 2L2 11" stroke-linecap="round" />
              </svg>
            </button>
          </div>
          <div class="type-picker-body">
            <p class="type-picker-hint">Seleccione o tipo de perfil que deseja criar:</p>

            <button type="button" class="type-option" @click="selectUserType('gestor')">
              <div class="type-option-icon green">
                <svg width="18" height="18" fill="none" stroke="#2D6A4F" stroke-width="1.8" viewBox="0 0 22 22">
                  <circle cx="11" cy="8" r="4" />
                  <path d="M3 19c0-3.314 3.582-6 8-6s8 2.686 8 6" />
                </svg>
              </div>
              <div class="type-option-text">
                <div class="type-option-title">Gestor</div>
                <div class="type-option-desc">Gere ocorrências da sua área geográfica e pode validar/rejeitar.</div>
              </div>
              <svg class="type-option-arrow" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 12 12">
                <path d="M4.5 2l4 4-4 4" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </button>

            <button type="button" class="type-option" @click="selectUserType('funcionario')">
              <div class="type-option-icon blue">
                <svg width="18" height="18" fill="none" stroke="#2B6CB0" stroke-width="1.8" viewBox="0 0 22 22">
                  <circle cx="11" cy="8" r="4" />
                  <path d="M3 19c0-3.314 3.582-6 8-6s8 2.686 8 6" />
                </svg>
              </div>
              <div class="type-option-text">
                <div class="type-option-title">Funcionário</div>
                <div class="type-option-desc">Regista ocorrências mas não pode validar/rejeitar.</div>
              </div>
              <svg class="type-option-arrow" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 12 12">
                <path d="M4.5 2l4 4-4 4" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </button>

            <button type="button" class="type-option" @click="selectUserType('observador')">
              <div class="type-option-icon purple">
                <svg width="18" height="18" fill="none" stroke="#6B46C1" stroke-width="1.8" viewBox="0 0 22 22">
                  <path d="M2 11c0-4 4-7 9-7s9 3 9 7-4 7-9 7-9-3-9-7z" />
                  <circle cx="11" cy="11" r="3" />
                </svg>
              </div>
              <div class="type-option-text">
                <div class="type-option-title">Observador</div>
                <div class="type-option-desc">Acesso só-leitura ao dashboard e ocorrências dos seus projectos.</div>
              </div>
              <svg class="type-option-arrow" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 12 12">
                <path d="M4.5 2l4 4-4 4" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </transition>

    <!-- ── ADD/EDIT MODAL ── -->
    <transition name="fade">
      <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
        <div class="mf-panel" @click.stop>

          <div class="mf-success" v-if="modalSaved">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 18 18">
              <circle cx="9" cy="9" r="7" />
              <path d="M6 9l2.5 2.5 4-5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            {{ editingUser ? 'Utilizador actualizado com sucesso!' : 'Conta criada com sucesso!' }}
          </div>

          <div class="api-error" v-if="formError">{{ formError }}</div>

          <div class="mf-header">
            <div class="mf-title-row">
              <div class="mf-title-icon">
                <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="1.9" viewBox="0 0 20 20">
                  <circle cx="9" cy="7" r="3.5" />
                  <path d="M2 17c0-3.314 3.134-6 7-6" stroke-linecap="round" />
                  <path v-if="!editingUser" d="M15 12v5M12.5 14.5h5" stroke-linecap="round" />
                  <path v-else d="M11.5 14.5l2 2 3.5-3.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
              </div>
              <h2 class="mf-title">
                {{ editingUser ? 'Editar Utilizador' : (modalTipo === 'gestor' ? 'Adicionar Gestor' : modalTipo === 'observador' ? 'Adicionar Observador' : 'Adicionar Funcionário') }}
              </h2>
            </div>
            <button type="button" class="btn-close" @click="closeModal">
              <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 13 13">
                <path d="M2 2l9 9M11 2L2 11" stroke-linecap="round" />
              </svg>
            </button>
          </div>

          <div class="mf-body">
            <div class="mf-form">

              <!-- Linha 1: Nome + Telefone -->
              <div class="mf-row">
                <div class="mf-field">
                  <label>Nome <span class="req">*</span></label>
                  <div class="input-wrap" :class="{ err: mErrors.name }">
                    <div class="input-icon">
                      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                        <circle cx="8" cy="6" r="3" />
                        <path d="M2 14c0-2.761 2.686-5 6-5s6 2.239 6 5" stroke-linecap="round" />
                      </svg>
                    </div>
                    <input type="text" v-model="mForm.name" placeholder="Nome completo" @input="mErrors.name = ''" />
                  </div>
                  <span class="err-msg" v-if="mErrors.name">{{ mErrors.name }}</span>
                </div>

                <div class="mf-field">
                  <label>Telefone</label>
                  <div class="input-wrap">
                    <div class="input-icon">
                      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                        <rect x="3" y="1" width="10" height="14" rx="2" />
                        <path d="M8 12h.01" stroke-linecap="round" stroke-width="2" />
                      </svg>
                    </div>
                    <input type="tel" v-model="mForm.phone" placeholder="+258 84 000 0000" />
                  </div>
                </div>
              </div>

              <!-- Linha 2: Email + Senha -->
              <div class="mf-row">
                <div class="mf-field">
                  <label>Email <span class="req">*</span></label>
                  <div class="input-wrap" :class="{ err: mErrors.email }">
                    <div class="input-icon">
                      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                        <rect x="1" y="3" width="14" height="10" rx="1.5" />
                        <path d="M1 5l7 5 7-5" stroke-linecap="round" />
                      </svg>
                    </div>
                    <input type="email" v-model="mForm.email" placeholder="nome@biofund.org.mz" @input="mErrors.email = ''" />
                  </div>
                  <span class="err-msg" v-if="mErrors.email">{{ mErrors.email }}</span>
                </div>

                <div class="mf-field">
                  <!-- Criação: senha gerada automaticamente -->
                  <template v-if="!editingUser">
                    <label>Palavra-passe</label>
                    <div class="pw-auto-notice">
                      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                        <circle cx="8" cy="8" r="6" />
                        <path d="M8 7v4M8 5h.01" stroke-linecap="round" />
                      </svg>
                      Gerada automaticamente e enviada por email
                    </div>
                  </template>

                  <!-- Edição: campo escondido por defeito -->
                  <template v-else>
                    <label>Palavra-passe</label>
                    <button v-if="!showPasswordChange" type="button" class="btn-change-pw" @click="showPasswordChange = true">
                      <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
                        <rect x="3" y="7" width="10" height="8" rx="1.5" />
                        <path d="M5 7V5a3 3 0 0 1 6 0v2" stroke-linecap="round" />
                      </svg>
                      Alterar senha
                    </button>
                    <template v-else>
                      <div class="input-wrap" :class="{ err: mErrors.password }">
                        <div class="input-icon">
                          <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                            <rect x="3" y="7" width="10" height="8" rx="1.5" />
                            <path d="M5 7V5a3 3 0 0 1 6 0v2" stroke-linecap="round" />
                          </svg>
                        </div>
                        <input :type="showPass ? 'text' : 'password'" v-model="mForm.password" placeholder="Nova senha (mín. 8 caracteres)" @input="mErrors.password = ''" />
                        <button class="btn-eye" type="button" @click="showPass = !showPass">
                          <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                            <path d="M1 8s2.5-5 7-5 7 5 7 5-2.5 5-7 5-7-5-7-5z" />
                            <circle cx="8" cy="8" r="2" />
                          </svg>
                        </button>
                      </div>
                      <span class="err-msg" v-if="mErrors.password">{{ mErrors.password }}</span>
                      <button type="button" class="btn-cancel-pw" @click="showPasswordChange = false; mForm.password = ''; mErrors.password = ''">Cancelar alteração</button>
                    </template>
                  </template>
                </div>
              </div>

              <!-- Linha 3: Províncias + Projectos (GESTOR) -->
              <div class="mf-row" v-if="modalTipo === 'gestor'">
                <div class="mf-field">
                  <label>Províncias <span class="req">*</span></label>
                  <div class="input-wrap" :class="{ err: mErrors.province_ids }">
                    <div class="input-icon">
                      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                        <path d="M8 1.5C5.515 1.5 3.5 3.515 3.5 6c0 3.75 4.5 9 4.5 9s4.5-5.25 4.5-9c0-2.485-2.015-4.5-4.5-4.5z" />
                        <circle cx="8" cy="6" r="1.8" />
                      </svg>
                    </div>
                    <select @change="addProvince($event.target.value); $event.target.value = ''">
                      <option value="">Seleccionar província…</option>
                      <option v-for="p in availableProvinces" :key="p.id" :value="p.id">{{ p.name }}</option>
                    </select>
                  </div>
                  <div class="selected-tags" v-if="mForm.province_ids.length">
                    <span class="tag-badge" v-for="pid in mForm.province_ids" :key="pid">
                      {{ provinceName(pid) }}
                      <button type="button" class="tag-remove" @click="removeProvince(pid)">
                        <svg width="8" height="8" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 10 10">
                          <path d="M2 2l6 6M8 2L2 8" stroke-linecap="round" />
                        </svg>
                      </button>
                    </span>
                  </div>
                  <span class="err-msg" v-if="mErrors.province_ids">{{ mErrors.province_ids }}</span>
                </div>

                <div class="mf-field">
                  <label>Projectos</label>
                  <div class="input-wrap">
                    <div class="input-icon">
                      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                        <path d="M2 13L6 4l4 6 3-3 3 4" stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                    </div>
                    <select @change="addProject($event.target.value); $event.target.value = ''">
                      <option value="">Adicionar projecto…</option>
                      <option v-for="p in availableProjects" :key="p.id" :value="p.id">{{ p.name }}</option>
                    </select>
                  </div>
                  <div class="selected-tags" v-if="mForm.project_ids.length">
                    <span class="tag-badge proj" v-for="pid in mForm.project_ids" :key="pid">
                      {{ projectName(pid) }}
                      <button type="button" class="tag-remove" @click="removeProject(pid)">
                        <svg width="8" height="8" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 10 10">
                          <path d="M2 2l6 6M8 2L2 8" stroke-linecap="round" />
                        </svg>
                      </button>
                    </span>
                  </div>
                </div>
              </div>

              <!-- Linha 3: Província + Projecto (FUNCIONÁRIO) -->
              <div class="mf-row" v-if="modalTipo === 'funcionario'">
                <div class="mf-field">
                  <label>Província <span class="req">*</span></label>
                  <div class="input-wrap" :class="{ err: mErrors.province_ids }">
                    <div class="input-icon">
                      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                        <path d="M8 1.5C5.515 1.5 3.5 3.515 3.5 6c0 3.75 4.5 9 4.5 9s4.5-5.25 4.5-9c0-2.485-2.015-4.5-4.5-4.5z" />
                        <circle cx="8" cy="6" r="1.8" />
                      </svg>
                    </div>
                    <select v-model="mForm.single_province_id" @change="mErrors.province_ids = ''">
                      <option value="" disabled>Seleccione a província</option>
                      <option v-for="p in refProvinces" :key="p.id" :value="p.id">{{ p.name }}</option>
                    </select>
                  </div>
                  <span class="err-msg" v-if="mErrors.province_ids">{{ mErrors.province_ids }}</span>
                </div>

                <div class="mf-field">
                  <label>Projecto</label>
                  <div class="input-wrap">
                    <div class="input-icon">
                      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                        <path d="M2 13L6 4l4 6 3-3 3 4" stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                    </div>
                    <select v-model="mForm.single_project_id">
                      <option value="">Nenhum (opcional)</option>
                      <option v-for="p in refProjects" :key="p.id" :value="p.id">{{ p.name }}</option>
                    </select>
                  </div>
                </div>
              </div>

              <!-- Linha 3: Províncias + Projectos (OBSERVADOR) -->
              <div class="mf-row" v-if="modalTipo === 'observador'">
                <div class="mf-field">
                  <label>Províncias <span class="req">*</span></label>
                  <div class="input-wrap" :class="{ err: mErrors.province_ids }">
                    <div class="input-icon">
                      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                        <path d="M8 1.5C5.515 1.5 3.5 3.515 3.5 6c0 3.75 4.5 9 4.5 9s4.5-5.25 4.5-9c0-2.485-2.015-4.5-4.5-4.5z" />
                        <circle cx="8" cy="6" r="1.8" />
                      </svg>
                    </div>
                    <select @change="addProvince($event.target.value); $event.target.value = ''">
                      <option value="">Seleccionar província…</option>
                      <option v-for="p in availableProvinces" :key="p.id" :value="p.id">{{ p.name }}</option>
                    </select>
                  </div>
                  <div class="selected-tags" v-if="mForm.province_ids.length">
                    <span class="tag-badge" v-for="pid in mForm.province_ids" :key="pid">
                      {{ provinceName(pid) }}
                      <button type="button" class="tag-remove" @click="removeProvince(pid)">
                        <svg width="8" height="8" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 10 10">
                          <path d="M2 2l6 6M8 2L2 8" stroke-linecap="round" />
                        </svg>
                      </button>
                    </span>
                  </div>
                  <span class="err-msg" v-if="mErrors.province_ids">{{ mErrors.province_ids }}</span>
                </div>

                <div class="mf-field">
                  <label>Projectos <span class="req">*</span></label>
                  <div class="input-wrap" :class="{ err: mErrors.project_ids }">
                    <div class="input-icon">
                      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                        <path d="M2 13L6 4l4 6 3-3 3 4" stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                    </div>
                    <select @change="addProject($event.target.value); $event.target.value = ''">
                      <option value="">Adicionar projecto…</option>
                      <option v-for="p in availableProjects" :key="p.id" :value="p.id">{{ p.name }}</option>
                    </select>
                  </div>
                  <div class="selected-tags" v-if="mForm.project_ids.length">
                    <span class="tag-badge proj" v-for="pid in mForm.project_ids" :key="pid">
                      {{ projectName(pid) }}
                      <button type="button" class="tag-remove" @click="removeProject(pid)">
                        <svg width="8" height="8" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 10 10">
                          <path d="M2 2l6 6M8 2L2 8" stroke-linecap="round" />
                        </svg>
                      </button>
                    </span>
                  </div>
                  <span class="err-msg" v-if="mErrors.project_ids">{{ mErrors.project_ids }}</span>
                </div>
              </div>

              <!-- Linha 4: Notificações + Estado (largura total) -->
              <div class="mf-row" v-if="modalTipo === 'gestor' || editingUser">
                <div class="mf-field" v-if="modalTipo === 'gestor'">
                  <label>Notificações</label>
                  <div class="checks-row">
                    <div class="check-group">
                      <span class="check-label-title">Alertas GBV</span>
                      <label class="check-item">
                        <input type="checkbox" v-model="mForm.receives_gbv_alerts" /><span>Activar</span>
                      </label>
                    </div>
                    <div class="check-group">
                      <span class="check-label-title">Alertas Urgentes</span>
                      <label class="check-item">
                        <input type="checkbox" v-model="mForm.receives_urgent_alerts" /><span>Activar</span>
                      </label>
                    </div>
                  </div>
                </div>

                <div class="mf-field" v-if="editingUser">
                  <label>Estado da Conta</label>
                  <div class="input-wrap">
                    <div class="input-icon">
                      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                        <circle cx="8" cy="8" r="6" />
                        <path d="M8 5v3l2 2" stroke-linecap="round" />
                      </svg>
                    </div>
                    <select v-model="mForm.management_scope">
                      <option value="provincial">Provincial</option>
                      <option value="national">Nacional</option>
                    </select>
                  </div>
                </div>
              </div>

            </div>
          </div>

          <div class="mf-footer">
            <button type="button" class="btn-gravar" @click="saveUser" :disabled="mLoading">
              <span v-if="mLoading" class="spin"></span>
              <svg v-else width="14" height="14" fill="none" stroke="#fff" stroke-width="1.8" viewBox="0 0 16 16">
                <path d="M2 14V9h4v5M2 2h9l3 3v9a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1z" stroke-linecap="round"
                  stroke-linejoin="round" />
                <path d="M10 2v4H5V2" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
              {{ mLoading ? 'A gravar…' : (editingUser ? 'Guardar Alterações' : 'Criar Conta') }}
            </button>
            <button type="button" class="btn-cancelar" @click="closeModal">Cancelar</button>
          </div>

        </div>
      </div>
    </transition>

    <!-- DELETE MODAL -->
    <transition name="fade">
      <div v-if="showDelete" class="modal-overlay" @click.self="showDelete = false">
        <div class="modal modal-sm" @click.stop>
          <div class="del-icon-wrap">
            <svg width="24" height="24" fill="none" stroke="#E53E3E" stroke-width="2" viewBox="0 0 24 24">
              <path d="M3 6h18M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2M5 6l1 14a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2l1-14"
                stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </div>
          <h3>Eliminar Utilizador</h3>
          <p class="del-desc">Tem a certeza que deseja eliminar <strong>{{ deleteTarget?.name }}</strong>? Esta acção é
            irreversível.</p>
          <div class="modal-actions">
            <button class="btn-modal-cancel" @click="showDelete = false">Cancelar</button>
            <button class="btn-del-confirm" @click="doDelete">Eliminar</button>
          </div>
        </div>
      </div>
    </transition>

    <!-- TOAST -->
    <transition name="toast-slide">
      <div v-if="toast.show" class="toast" :class="{ red: toast.red }">
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
import { ref, reactive, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { InternalService } from '@/api/services/internal.service'
import AdminProfilePanel from '@/components/AdminProfilePanel.vue'
import AdminNotificationPanel from '@/components/AdminNotificationPanel.vue'

const router = useRouter()
const auth = useAuthStore()

async function handleLogout() {
  await auth.logout()
  router.push('/acessoRestrito')
}

// ── Estado ────────────────────────────────────────────────────
const sidebarOpen = ref(false)
const loading = ref(false)
const mLoading = ref(false)
const showModal = ref(false)
const showTypeModal = ref(false)
const showDelete = ref(false)
const modalTipo = ref('gestor')
const editingUser = ref(null)
const deleteTarget = ref(null)
const modalSaved = ref(false)
const formError = ref('')
const showPass = ref(false)
const showPasswordChange = ref(false)
const tableSearch = ref('')
const tab = ref('todos')

const users = ref([])
const meta = reactive({ total: 0, last_page: 1, current_page: 1, per_page: 15 })

// Dados de referência para os selects
const refProvinces = ref([])
const refProjects = ref([])

const toast = reactive({ show: false, msg: '', red: false })

function showToast(msg, red = false) {
  toast.msg = msg; toast.red = red; toast.show = true
  setTimeout(() => { toast.show = false }, 3200)
}

function initials(name) {
  if (!name) return '?'
  return name.split(' ').slice(0, 2).map(n => n[0]?.toUpperCase() ?? '').join('')
}

// ── Províncias e Projectos - select com tags ──────────────────
const availableProvinces = computed(() =>
  refProvinces.value.filter(p => !mForm.province_ids.includes(p.id))
)

const availableProjects = computed(() =>
  refProjects.value.filter(p => !mForm.project_ids.includes(p.id))
)

function provinceName(id) {
  return refProvinces.value.find(p => p.id === id)?.name ?? ''
}

function projectName(id) {
  return refProjects.value.find(p => p.id === id)?.name ?? ''
}

function addProvince(id) {
  const idNum = Number(id)
  if (idNum && !mForm.province_ids.includes(idNum)) {
    mForm.province_ids.push(idNum)
    mErrors.province_ids = ''
  }
}

function removeProvince(id) {
  const idx = mForm.province_ids.indexOf(id)
  if (idx !== -1) mForm.province_ids.splice(idx, 1)
}

function addProject(id) {
  const idNum = Number(id)
  if (idNum && !mForm.project_ids.includes(idNum)) {
    mForm.project_ids.push(idNum)
  }
}

function removeProject(id) {
  const idx = mForm.project_ids.indexOf(id)
  if (idx !== -1) mForm.project_ids.splice(idx, 1)
}

// ── Paginação ─────────────────────────────────────────────────
const visiblePages = computed(() => {
  const pages = [], cur = meta.current_page, last = meta.last_page
  for (let i = Math.max(1, cur - 2); i <= Math.min(last, cur + 2); i++) pages.push(i)
  return pages
})

const paginationInfo = computed(() => {
  if (!meta.total) return '0 utilizadores'
  const start = (meta.current_page - 1) * meta.per_page + 1
  const end = Math.min(meta.current_page * meta.per_page, meta.total)
  return `${start}-${end} de ${meta.total} utilizadores`
})

// ── Init ──────────────────────────────────────────────────────
onMounted(() => {
  InternalService.getFormData()
    .then(data => {
      refProvinces.value = data.provinces ?? []
      refProjects.value  = data.projects  ?? []
    })
    .catch(err => console.error('Erro ao carregar dados de referência:', err))

  loadUsers(1)
})

// ── Carregar utilizadores ─────────────────────────────────────
async function loadUsers(page = 1) {
  loading.value = true
  try {
    const params = { page, per_page: 15 }
    if (tab.value !== 'todos') params.role = tab.value
    if (tableSearch.value.trim()) params.search = tableSearch.value.trim()

    const response = await InternalService.getUsers(params)
    users.value = response.data ?? []
    Object.assign(meta, {
      total: response.meta?.total ?? 0,
      last_page: response.meta?.last_page ?? 1,
      current_page: response.meta?.current_page ?? 1,
      per_page: response.meta?.per_page ?? 15,
    })
  } catch (err) {
    showToast('Erro ao carregar utilizadores.', true)
  } finally {
    loading.value = false
  }
}

function changeTab(newTab) {
  tab.value = newTab
  loadUsers(1)
}

// ── Toggle de estado ──────────────────────────────────────────
async function handleToggle(u) {
  try {
    const result = await InternalService.toggleUserStatus(u.id)
    u.is_active = result.is_active
    showToast(result.message)
  } catch (err) {
    showToast(err.response?.data?.message ?? 'Erro ao alterar estado.', true)
  }
}

// ── Formulário ────────────────────────────────────────────────
const mForm = reactive({
  name: '', email: '', phone: '', password: '',
  province_ids: [],   // gestores: múltiplos
  single_province_id: '',   // funcionários: um só
  project_ids: [],   // gestores: múltiplos
  single_project_id: '',   // funcionários: um só
  receives_urgent_alerts: false,
  receives_gbv_alerts: false,
  management_scope: 'provincial',
})

const mErrors = reactive({ name: '', email: '', password: '', province_ids: '', project_ids: '' })

function resetMForm() {
  Object.assign(mForm, {
    name: '', email: '', phone: '', password: '',
    province_ids: [], single_province_id: '',
    project_ids: [], single_project_id: '',
    receives_urgent_alerts: false, receives_gbv_alerts: false,
    management_scope: 'provincial',
  })
  Object.assign(mErrors, { name: '', email: '', password: '', province_ids: '', project_ids: '' })
  formError.value = ''
  modalSaved.value = false
  showPass.value = false
  showPasswordChange.value = false
}

function openModal(tipo) {
  modalTipo.value = tipo
  editingUser.value = null
  resetMForm()
  showModal.value = true
}

function selectUserType(tipo) {
  showTypeModal.value = false
  openModal(tipo)
}

function editUser(u) {
  editingUser.value = u
  modalTipo.value = u.role === 'gestor' ? 'gestor' : u.role === 'observador' ? 'observador' : 'funcionario'
  resetMForm()
  mForm.name = u.name
  mForm.email = u.email
  mForm.phone = u.phone ?? ''
  mForm.receives_urgent_alerts = u.receives_urgent_alerts
  mForm.receives_gbv_alerts = u.receives_gbv_alerts
  mForm.management_scope = u.management_scope ?? 'provincial'

  if (u.role === 'gestor' || u.role === 'observador') {
    mForm.province_ids = (u.provinces ?? []).map(p => p.id)
    mForm.project_ids = (u.projects ?? []).map(p => p.id)
  } else {
    mForm.single_province_id = u.provinces?.[0]?.id ?? u.province?.id ?? ''
    mForm.single_project_id = u.projects?.[0]?.id ?? ''
  }

  showModal.value = true
}

function closeModal() {
  showModal.value = false
  resetMForm()
}

async function saveUser() {
  formError.value = ''
  Object.assign(mErrors, { name: '', email: '', password: '', province_ids: '', project_ids: '' })

  // Validação client-side
  let ok = true
  if (!mForm.name.trim()) { mErrors.name = 'O nome é obrigatório.'; ok = false }
  if (!mForm.email.trim()) { mErrors.email = 'O email é obrigatório.'; ok = false }

  const isMultiSelect = modalTipo.value === 'gestor' || modalTipo.value === 'observador'

  // Províncias
  const provinceIds = isMultiSelect
    ? mForm.province_ids
    : (mForm.single_province_id ? [mForm.single_province_id] : [])

  if (!provinceIds.length) { mErrors.province_ids = 'Seleccione pelo menos uma província.'; ok = false }

  // Projectos
  const projectIds = isMultiSelect
    ? mForm.project_ids
    : (mForm.single_project_id ? [mForm.single_project_id] : [])

  if (modalTipo.value === 'observador' && !projectIds.length) {
    mErrors.project_ids = 'Seleccione pelo menos um projecto.'; ok = false
  }

  if (!ok) return

  const payload = {
    name: mForm.name.trim(),
    email: mForm.email.trim(),
    phone: mForm.phone.trim() || null,
    role: modalTipo.value === 'gestor' ? 'gestor' : modalTipo.value === 'observador' ? 'observador' : 'funcionario',
    province_ids: provinceIds,
    project_ids: projectIds,
    receives_urgent_alerts: mForm.receives_urgent_alerts,
    receives_gbv_alerts: mForm.receives_gbv_alerts,
  }

  if (mForm.password) payload.password = mForm.password
  if (editingUser.value) payload.management_scope = mForm.management_scope

  mLoading.value = true
  try {
    if (editingUser.value) {
      const result = await InternalService.updateUser(editingUser.value.id, payload)
      // Actualiza na lista local
      const idx = users.value.findIndex(u => u.id === editingUser.value.id)
      if (idx !== -1) users.value[idx] = { ...users.value[idx], ...result.user }
    } else {
      const result = await InternalService.createUser(payload)
      users.value.unshift(result.user)
      meta.total++
    }
    modalSaved.value = true
    setTimeout(() => { showModal.value = false; resetMForm() }, 1400)
  } catch (err) {
    if (err.response?.status === 422) {
      const errors = err.response.data.errors ?? {}
      if (errors.name) mErrors.name = errors.name[0]
      if (errors.email) mErrors.email = errors.email[0]
      if (errors.password) mErrors.password = errors.password[0]
      if (errors.province_ids) mErrors.province_ids = errors.province_ids[0]
      if (errors.project_ids) mErrors.project_ids = errors.project_ids[0]
      formError.value = 'Corrija os erros e tente novamente.'
    } else {
      formError.value = err.response?.data?.message ?? 'Erro ao guardar. Tente novamente.'
    }
  } finally {
    mLoading.value = false
  }
}

// ── Eliminar ──────────────────────────────────────────────────
function confirmDelete(u) { deleteTarget.value = u; showDelete.value = true }

async function doDelete() {
  try {
    await InternalService.deleteUser(deleteTarget.value.id)
    users.value = users.value.filter(u => u.id !== deleteTarget.value.id)
    meta.total--
    showDelete.value = false
    showToast(`${deleteTarget.value.name} eliminado.`)
  } catch (err) {
    showDelete.value = false
    showToast(err.response?.data?.message ?? 'Erro ao eliminar.', true)
  }
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

/* SIDEBAR */
.sidebar {
  width: 210px;
  flex-shrink: 0;
  background: var(--white);
  box-shadow: 2px 0 18px rgba(0, 0, 0, .07);
  display: flex;
  flex-direction: column;
  height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
  z-index: 50;
}

.sidebar-logo {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px 16px 18px;
  border-bottom: 1px solid rgba(0, 0, 0, .06);
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

.nav-item:hover {
  background: var(--green-bg);
  color: var(--green-mid);
}

.nav-item.active,
.nav-item.router-link-exact-active {
  background: var(--green-bg);
  color: var(--green-mid);
  font-weight: 700;
}

.nav-item svg {
  flex-shrink: 0;
  opacity: 0.75;
}

.nav-item.active svg,
.nav-item.router-link-exact-active svg {
  opacity: 1;
}

.sidebar-footer {
  padding: 14px 10px;
  border-top: 1px solid rgba(0, 0, 0, .06);
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
}

.btn-logout:hover {
  background: #FFF5F5;
}

/* MAIN */
.main {
  margin-left: 210px;
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
  height: 58px;
  background: var(--white);
  box-shadow: 0 1px 12px rgba(0, 0, 0, .07);
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

.search-wrap input::placeholder {
  color: var(--text-light);
}

.topbar-spacer {
  flex: 1;
}

.notif-btn {
  position: relative;
  width: 38px;
  height: 38px;
  background: #F4F6F5;
  border: 1.5px solid var(--border);
  border-radius: 9px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.notif-dot {
  position: absolute;
  top: 7px;
  right: 8px;
  width: 7px;
  height: 7px;
  border-radius: 50%;
  background: #E53E3E;
  border: 1.5px solid var(--white);
}

.admin-info {
  display: flex;
  align-items: center;
  gap: 10px;
}

.admin-name {
  font-size: 13px;
  font-weight: 700;
  color: var(--text-dark);
}

.admin-avatar {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: linear-gradient(135deg, #52B788, #1B4332);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 13px;
  font-weight: 800;
  color: #fff;
}

/* CONTENT */
.content {
  flex: 1;
  overflow-y: auto;
  padding: 24px 28px 32px;
  background: #F2F6F4;
}

.content::-webkit-scrollbar {
  width: 5px;
}

.content::-webkit-scrollbar-thumb {
  background: #C8D8CE;
  border-radius: 99px;
}

.page-title-row {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  margin-bottom: 22px;
}

.page-title-row h1 {
  font-size: 22px;
  font-weight: 800;
  margin-bottom: 4px;
}

.page-title-row p {
  font-size: 13px;
  color: var(--text-gray);
}

.title-actions {
  display: flex;
  gap: 10px;
}

.btn-green-sm {
  display: flex;
  align-items: center;
  gap: 7px; 
  background: var(--green-mid);
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 9px 18px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
}

.btn-green-sm:hover {
  background: var(--green-dark);
}

.btn-outline-sm {
  display: flex;
  align-items: center;
  gap: 7px;
  background: var(--white);
  color: var(--text-dark);
  border: 1.5px solid var(--border);
  border-radius: 8px;
  padding: 8px 16px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
}

.btn-outline-sm:hover {
  border-color: var(--green-mid);
  color: var(--green-mid);
}

/* KPI */
.kpi-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 12px;
  margin-bottom: 20px;
}

.kpi-card {
  background: var(--white);
  border-radius: 16px;
  padding: 18px 18px 16px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, .05), 0 6px 20px rgba(0, 0, 0, .07);
  transition: transform 0.2s;
}

.kpi-card:hover {
  transform: translateY(-2px);
}

.kpi-card.highlight {
  background: var(--green-mid);
  box-shadow: 0 4px 12px rgba(45, 106, 79, .35);
}

.kpi-top {
  margin-bottom: 12px;
}

.kpi-icon {
  width: 36px;
  height: 36px;
  border-radius: 9px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.kpi-icon.green {
  background: var(--green-pale);
}

.kpi-icon.white {
  background: rgba(255, 255, 255, .22);
}

.kpi-label {
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 0.8px;
  text-transform: uppercase;
  margin-bottom: 4px;
}

.kpi-label.dark {
  color: var(--text-light);
}

.kpi-label.light {
  color: rgba(255, 255, 255, .75);
}

.kpi-value {
  font-size: 28px;
  font-weight: 800;
  line-height: 1;
  margin-bottom: 5px;
}

.kpi-value.dark {
  color: var(--text-dark);
}

.kpi-value.light {
  color: #fff;
}

.kpi-sub {
  font-size: 11px;
}

.kpi-sub.dark {
  color: var(--text-light);
}

.kpi-sub.light {
  color: rgba(255, 255, 255, .75);
}

/* TABLE */
.table-card {
  background: var(--white);
  border-radius: 16px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, .05), 0 6px 20px rgba(0, 0, 0, .07);
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

.tabs {
  display: flex;
  gap: 2px;
  background: #F4F6F5;
  border-radius: 8px;
  padding: 4px;
}

.tab {
  padding: 7px 18px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 500;
  color: var(--text-gray);
  cursor: pointer;
  border: none;
  background: transparent;
  font-family: 'Poppins', sans-serif;
  transition: background 0.15s;
}

.tab.active {
  background: var(--white);
  color: var(--text-dark);
  font-weight: 700;
  box-shadow: 0 1px 4px rgba(0, 0, 0, .08);
}

.toolbar-right {
  display: flex;
  align-items: center;
  gap: 10px;
}

.search-inline {
  display: flex;
  align-items: center;
  gap: 8px;
  background: var(--white);
  border: 1.5px solid var(--border);
  border-radius: 8px;
  padding: 7px 12px;
}

.search-inline:focus-within {
  border-color: var(--green-light);
  box-shadow: 0 0 0 3px rgba(82, 183, 136, .12);
}

.search-inline input {
  border: none;
  outline: none;
  background: transparent;
  font-family: 'Poppins', sans-serif;
  font-size: 12.5px;
  color: var(--text-dark);
  width: 160px;
}

.search-inline input::placeholder {
  color: var(--text-light);
}

/* Skeleton */
.skeleton-wrap {
  padding: 14px 20px;
}

.skeleton-row {
  margin-bottom: 12px;
}

.sk-bar {
  height: 42px;
  border-radius: 9px;
  background: linear-gradient(90deg, #f0f4f2 25%, #e0eae5 50%, #f0f4f2 75%);
  background-size: 200% 100%;
  animation: shimmer 1.2s infinite;
}

@keyframes shimmer {
  0% {
    background-position: 200% 0;
  }

  100% {
    background-position: -200% 0;
  }
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 14px;
}

thead th {
  padding: 12px 20px;
  font-size: 11px;
  font-weight: 700;
  color: var(--text-light);
  text-align: left;
  border-top: 1px solid var(--border);
  border-bottom: 1px solid var(--border);
  background: #F4F6F5;
  text-transform: uppercase;
  letter-spacing: 0.4px;
}

tbody tr {
  transition: background 0.15s;
}

tbody tr:hover {
  background: #F9FBFA;
}

tbody td {
  padding: 14px 20px;
  vertical-align: middle;
  border-bottom: 1px solid var(--border);
}

tbody tr:last-child td {
  border-bottom: none;
}

.user-cell {
  display: flex;
  align-items: center;
  gap: 12px;
}

.user-avatar {
  width: 38px;
  height: 38px;
  border-radius: 50%;
  background: var(--green-bg);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 13px;
  font-weight: 800;
  color: var(--green-mid);
  flex-shrink: 0;
}

.user-name {
  font-size: 13px;
  font-weight: 600;
  color: var(--text-dark);
  margin-bottom: 2px;
}

.user-email {
  font-size: 11.5px;
  color: var(--text-light);
}

.user-phone {
  font-size: 11px;
  color: var(--text-light);
}

.badge-tipo {
  display: inline-block;
  padding: 4px 12px;
  border-radius: 99px;
  font-size: 11.5px;
  font-weight: 700;
}

.badge-tipo.gestor {
  background: var(--green-pale);
  color: var(--green-dark);
}

.badge-tipo.funcionario {
  background: #EBF4FF;
  color: #2B6CB0;
}

.badge-tipo.admin {
  background: #FAF5FF;
  color: #6B46C1;
}

.badge-tipo.observador {
  background: #FAF5FF;
  color: #6B46C1;
}

/* Províncias múltiplas */
.provinces-cell {
  display: flex;
  flex-wrap: wrap;
  gap: 4px;
  max-width: 200px;
}

.prov-badge {
  font-size: 10.5px;
  font-weight: 600;
  padding: 2px 8px;
  border-radius: 99px;
  background: var(--green-bg);
  color: var(--green-mid);
  border: 1px solid #C3E6CE;
  white-space: nowrap;
}

/* Projectos */
.projects-cell {
  display: flex;
  flex-wrap: wrap;
  gap: 4px;
}

.proj-tag {
  font-size: 10.5px;
  font-weight: 700;
  padding: 2px 7px;
  border-radius: 5px;
  background: #F4F6F5;
  color: var(--text-gray);
}

.proj-more {
  font-size: 10.5px;
  font-weight: 600;
  color: var(--text-light);
}

.td-muted {
  font-size: 12.5px;
  color: var(--text-light);
  font-style: italic;
}

/* Toggle */
.toggle-cell {
  display: flex;
  align-items: center;
  gap: 9px;
}

.toggle {
  width: 40px;
  height: 22px;
  border-radius: 99px;
  position: relative;
  cursor: pointer;
  border: none;
  padding: 0;
  transition: background 0.25s;
}

.toggle.on {
  background: var(--green-mid);
}

.toggle.off {
  background: #CBD5D1;
}

.toggle-knob {
  position: absolute;
  top: 3px;
  width: 16px;
  height: 16px;
  border-radius: 50%;
  background: #fff;
  box-shadow: 0 1px 4px rgba(0, 0, 0, .18);
  transition: left 0.25s;
}

.toggle.on .toggle-knob {
  left: 21px;
}

.toggle.off .toggle-knob {
  left: 3px;
}

.toggle-label {
  font-size: 12.5px;
  font-weight: 500;
}

.toggle-label.on {
  color: var(--green-mid);
}

.toggle-label.off {
  color: var(--text-light);
}

.action-btn {
  width: 32px;
  height: 32px;
  border-radius: 7px;
  border: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background 0.15s;
}

.action-btn.del {
  background: #FFF5F5;
  color: #E53E3E;
}

.action-btn.del:hover {
  background: #FED7D7;
}

.action-btn.edit {
  background: var(--green-bg);
  color: var(--green-mid);
}

.action-btn.edit:hover {
  background: var(--green-pale);
}

.empty-row {
  text-align: center;
  padding: 28px;
  color: var(--text-light);
  font-size: 13px;
}

/* Pagination */
.pagination-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 14px 20px;
  border-top: 1px solid var(--border);
}

.pagination-info {
  font-size: 12.5px;
  color: var(--text-light);
}

.pagination-btns {
  display: flex;
  align-items: center;
  gap: 6px;
}

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
  transition: all 0.15s;
}

.pg-btn:hover {
  border-color: var(--green-mid);
  color: var(--green-mid);
}

.pg-btn.active {
  background: var(--green-mid);
  border-color: var(--green-mid);
  color: #fff;
}

.pg-btn:disabled {
  opacity: 0.45;
  cursor: not-allowed;
}

/* Security card */
.security-card {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 20px 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
}

.security-left {
  display: flex;
  align-items: center;
  gap: 16px;
}

.security-icon {
  width: 44px;
  height: 44px;
  border-radius: 11px;
  background: var(--green-bg);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.security-title {
  font-size: 14px;
  font-weight: 700;
  margin-bottom: 4px;
}

.security-desc {
  font-size: 12.5px;
  color: var(--text-gray);
  line-height: 1.55;
}

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

.dash-footer a {
  color: var(--text-light);
  text-decoration: none;
  margin-left: 16px;
}

.dash-footer a:hover {
  color: var(--green-mid);
}

/* MODAL */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.45);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 200;
  padding: 16px;
}

.mf-panel {
  background: var(--white);
  border-radius: 16px;
  width: 780px;
  max-width: 100%;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 20px 60px rgba(0, 0, 0, .2);
  display: flex;
  flex-direction: column;
}

.mf-panel::-webkit-scrollbar {
  width: 4px;
}

.mf-panel::-webkit-scrollbar-thumb {
  background: #C8D8CE;
  border-radius: 99px;
}

/* TYPE PICKER MODAL */
.type-picker-panel {
  background: var(--white);
  border-radius: 16px;
  width: 460px;
  max-width: 100%;
  max-height: 90vh;
  overflow-y: auto;
  box-shadow: 0 20px 60px rgba(0, 0, 0, .2);
  display: flex;
  flex-direction: column;
}

.type-picker-body {
  padding: 20px 24px 26px;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.type-picker-hint {
  font-size: 13px;
  color: var(--text-gray);
  margin: 0 0 4px;
}

.type-option {
  display: flex;
  align-items: center;
  gap: 14px;
  width: 100%;
  text-align: left;
  background: var(--white);
  border: 1.5px solid var(--border);
  border-radius: 12px;
  padding: 14px 16px;
  cursor: pointer;
  transition: all 0.15s;
  font-family: 'Poppins', sans-serif;
}

.type-option:hover {
  border-color: var(--green-mid);
  background: var(--green-bg);
}

.type-option-icon {
  width: 40px;
  height: 40px;
  border-radius: 11px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.type-option-icon.green {
  background: var(--green-pale);
}

.type-option-icon.blue {
  background: #EBF4FF;
}

.type-option-icon.purple {
  background: #FAF5FF;
}

.type-option-text {
  flex: 1;
}

.type-option-title {
  font-size: 14px;
  font-weight: 700;
  color: var(--text-dark);
  margin-bottom: 2px;
}

.type-option-desc {
  font-size: 12px;
  color: var(--text-light);
  line-height: 1.4;
}

.type-option-arrow {
  color: var(--text-light);
  flex-shrink: 0;
}

.type-option:hover .type-option-arrow {
  color: var(--green-mid);
}

.mf-success {
  background: var(--green-bg);
  border: 1px solid #C3E6CE;
  border-radius: 10px;
  padding: 13px 18px;
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 13px;
  font-weight: 600;
  color: var(--green-mid);
  margin: 20px 28px 0;
  animation: fadeUp 0.3s ease;
}

@keyframes fadeUp {
  from {
    opacity: 0;
    transform: translateY(-8px);
  }

  to {
    opacity: 1;
    transform: none;
  }
}

.api-error {
  background: #FFF5F5;
  border: 1px solid #FEB2B2;
  border-radius: 10px;
  padding: 12px 18px;
  font-size: 12.5px;
  color: #C53030;
  margin: 16px 28px 0;
}

.mf-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 22px 28px 18px;
  border-bottom: 1px solid var(--border);
}

.mf-title-row {
  display: flex;
  align-items: center;
  gap: 12px;
}

.mf-title-icon {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: var(--green-mid);
  display: flex;
  align-items: center;
  justify-content: center;
}

.mf-title {
  font-size: 18px;
  font-weight: 700;
  color: var(--green-mid);
}

.btn-close {
  background: #F4F6F5;
  border: 1.5px solid var(--border);
  border-radius: 8px;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.btn-close:hover {
  background: #FFF5F5;
  border-color: #FC8181;
}

.mf-body {
  padding: 24px 28px 8px;
}

.mf-form {
  display: flex;
  flex-direction: column;
}

.mf-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0 24px;
}

.mf-field {
  display: flex;
  flex-direction: column;
  gap: 6px;
  margin-bottom: 20px;
}

.mf-field>label {
  font-size: 13px;
  font-weight: 500;
  color: var(--text-dark);
}

.field-hint {
  font-size: 11px;
  font-weight: 400;
  color: var(--text-light);
  margin-left: 5px;
}

.field-hint-bottom {
  font-size: 11.5px;
  color: var(--green-mid);
  font-weight: 600;
  margin-top: 4px;
}

.req {
  color: #E53E3E;
  margin-left: 2px;
}

.input-wrap {
  display: flex;
  align-items: stretch;
  border: 1.5px solid var(--border);
  border-radius: 9px;
  background: var(--white);
  overflow: hidden;
  transition: border-color 0.2s, box-shadow 0.2s;
  height: 44px;
  width: 100%;
  box-sizing: border-box;
}

.input-wrap:focus-within {
  border-color: var(--green-light);
  box-shadow: 0 0 0 3px rgba(82, 183, 136, .13);
}

.input-wrap.err {
  border-color: #FC8181;
  box-shadow: 0 0 0 3px rgba(252, 129, 129, .11);
}

.input-icon {
  padding: 0 11px;
  display: flex;
  align-items: center;
  color: var(--text-light);
  flex-shrink: 0;
}

.input-wrap input,
.input-wrap select {
  flex: 1;
  min-width: 0;
  border: none;
  outline: none;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  color: var(--text-dark);
  background: transparent;
  appearance: none;
  -webkit-appearance: none;
  box-sizing: border-box;
  padding: 0 12px 0 0;
  height: 100%;
}

.input-wrap input::placeholder {
  color: var(--text-light);
}

.input-wrap select {
  cursor: pointer;
  padding-right: 36px;
  background-image: url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%239AA5A2' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 12px center;
}

.btn-eye {
  background: none;
  border: none;
  cursor: pointer;
  padding: 0 11px;
  color: var(--text-light);
  display: flex;
  align-items: center;
  height: 100%;
  flex-shrink: 0;
}

.btn-change-pw {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  height: 40px;
  padding: 0 14px;
  border-radius: 8px;
  border: 1.5px dashed #C8D8CE;
  background: #F7FAF8;
  color: #2D6A4F;
  font-size: 12.5px;
  font-weight: 600;
  cursor: pointer;
  width: 100%;
  justify-content: center;
  transition: background 0.15s, border-color 0.15s;
}
.btn-change-pw:hover { background: #EDF4EF; border-color: #52B788; }

.btn-cancel-pw {
  margin-top: 5px;
  background: none;
  border: none;
  color: #8A9490;
  font-size: 11.5px;
  cursor: pointer;
  padding: 0;
  text-decoration: underline;
}
.btn-cancel-pw:hover { color: #E53E3E; }

.pw-auto-notice {
  display: flex;
  align-items: center;
  gap: 7px;
  height: 40px;
  padding: 0 13px;
  background: #EDF4EF;
  border: 1.5px dashed #8ECDA8;
  border-radius: 9px;
  font-size: 12.5px;
  color: #2D6A4F;
  font-weight: 500;
}

.err-msg {
  font-size: 11.5px;
  color: #E53E3E;
}

/* Tags de províncias / projectos seleccionados */
.selected-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
  margin-top: 8px;
}

.tag-badge {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 4px 6px 4px 10px;
  background: var(--green-bg);
  border: 1px solid #B7DFC8;
  border-radius: 99px;
  font-size: 12px;
  font-weight: 600;
  color: var(--green-dark);
  line-height: 1;
}

.tag-badge.proj {
  background: #EBF4FF;
  border-color: #90CDF4;
  color: #2B6CB0;
}

.tag-remove {
  background: none;
  border: none;
  padding: 2px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  color: inherit;
  opacity: 0.55;
  border-radius: 50%;
  transition: opacity 0.15s, background 0.15s;
  flex-shrink: 0;
}

.tag-remove:hover {
  opacity: 1;
  background: rgba(0, 0, 0, 0.1);
}

/* Checks row */
.checks-row {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
}

.check-group {
  display: flex;
  flex-direction: column;
  gap: 7px;
}

.check-label-title {
  font-size: 11.5px;
  font-weight: 600;
  color: var(--text-dark);
}

.check-item {
  display: flex;
  align-items: center;
  gap: 7px;
  cursor: pointer;
}

.check-item input[type=checkbox] {
  width: 16px;
  height: 16px;
  accent-color: var(--green-mid);
  cursor: pointer;
}

.check-item span {
  font-size: 13px;
  color: var(--text-gray);
}

.mf-footer {
  display: flex;
  gap: 12px;
  padding: 16px 28px 24px;
  border-top: 1px solid var(--border);
}

.btn-gravar {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: var(--green-mid);
  color: #fff;
  border: none;
  border-radius: 9px;
  padding: 12px 28px;
  font-family: 'Poppins', sans-serif;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-gravar:hover:not(:disabled) {
  background: var(--green-dark);
}

.btn-gravar:disabled {
  background: #A0C4B0;
  cursor: not-allowed;
}

.btn-cancelar {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: transparent;
  color: #E53E3E;
  border: 1.5px solid #FC8181;
  border-radius: 9px;
  padding: 10px 24px;
  font-family: 'Poppins', sans-serif;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
}

.btn-cancelar:hover {
  background: #FFF5F5;
}

.spin {
  display: inline-block;
  width: 14px;
  height: 14px;
  border: 2px solid rgba(255, 255, 255, .35);
  border-top-color: #fff;
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

/* Delete modal */
.modal {
  background: var(--white);
  border-radius: 16px;
  padding: 32px 32px 28px;
  width: 380px;
  max-width: 95vw;
  box-shadow: 0 16px 60px rgba(0, 0, 0, .18);
}

.del-icon-wrap {
  width: 52px;
  height: 52px;
  border-radius: 50%;
  background: #FFF5F5;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 14px;
}

.modal h3 {
  font-size: 16px;
  font-weight: 800;
  margin-bottom: 4px;
}

.del-desc {
  font-size: 13px;
  color: var(--text-gray);
  line-height: 1.6;
  margin-bottom: 22px;
}

.modal-actions {
  display: flex;
  gap: 10px;
}

.btn-modal-cancel {
  flex: 1;
  background: var(--white);
  color: var(--text-gray);
  border: 1.5px solid var(--border);
  border-radius: 9px;
  padding: 11px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
}

.btn-del-confirm {
  flex: 1;
  background: #E53E3E;
  color: #fff;
  border: none;
  border-radius: 9px;
  padding: 11px;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
}

.btn-del-confirm:hover {
  background: #C53030;
}

/* Toast */
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
  box-shadow: 0 8px 32px rgba(0, 0, 0, .18);
}

.toast.red {
  background: #C53030;
}

/* Transitions */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.toast-slide-enter-active,
.toast-slide-leave-active {
  transition: opacity 0.3s ease, transform 0.3s ease;
}

.toast-slide-enter-from,
.toast-slide-leave-to {
  opacity: 0;
  transform: translateY(16px);
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

  .mf-panel { max-width: 95vw; }
  .type-picker-panel { max-width: 95vw; }
}

@media (max-width: 768px) {
  .kpi-grid { grid-template-columns: repeat(2, 1fr); }

  .table-toolbar {
    flex-wrap: wrap;
  }
  .toolbar-right { width: 100%; }
  .search-inline { width: 100%; }
  .search-inline input { width: 100%; }
}

@media (max-width: 640px) {
  .content { padding: 16px 14px 24px; }

  .topbar { padding: 0 14px; gap: 10px; }
  .search-wrap { max-width: none; }

  .page-title-row {
    flex-direction: column;
    align-items: stretch;
    gap: 12px;
  }
  .title-actions { width: 100%; }
  .title-actions .btn-green-sm,
  .title-actions .btn-outline-sm { width: 100%; justify-content: center; }

  .kpi-grid { grid-template-columns: 1fr; }

  .table-toolbar {
    padding: 14px 14px 0;
  }
  .tabs {
    width: 100%;
    overflow-x: auto;
  }

  .table-card {
    overflow-x: auto;
  }
  table { min-width: 760px; }

  .pagination-bar {
    flex-direction: column;
    align-items: stretch;
    gap: 10px;
    padding: 14px;
  }
  .pagination-btns { justify-content: center; flex-wrap: wrap; }

  .mf-panel { width: 100%; max-width: 100%; }
  .mf-header { padding: 18px 18px 14px; }
  .mf-body { padding: 18px 18px 8px; }
  .mf-footer { padding: 14px 18px 18px; flex-direction: column; }
  .btn-gravar, .btn-cancelar { width: 100%; justify-content: center; }

  .mf-row { grid-template-columns: 1fr; gap: 0; }

  .type-picker-panel { width: 100%; max-width: 100%; }
  .type-picker-body { padding: 16px 18px 20px; }

  .modal {
    width: 100%;
    max-width: calc(100vw - 24px);
    max-height: calc(100vh - 24px);
    overflow-y: auto;
    padding: 24px 20px 20px;
  }

  .modal-actions { flex-direction: column; }

  .dash-footer {
    flex-direction: column;
    align-items: flex-start;
    gap: 6px;
    padding: 10px 14px;
  }
  .dash-footer a { margin-left: 0; margin-right: 16px; }

  .toast {
    left: 14px;
    right: 14px;
    bottom: 14px;
  }
}
</style>