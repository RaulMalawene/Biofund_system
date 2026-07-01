<template>
  <div class="page">
    <AppNavbar />

    <!-- SEARCH HERO -->
    <section class="search-hero">
      <h1>Acompanhe a sua Ocorrência</h1>
      <p>Insira o código único recebido no momento da submissão para verificar o estado atual e as atualizações da nossa
        equipe.</p>

      <div class="search-bar">
        <div class="search-bar-icon">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <circle cx="7" cy="7" r="5" />
            <path d="M12 12l3 3" stroke-linecap="round" />
          </svg>
        </div>
        <input type="text" v-model="searchCode" placeholder="BQ-2024-0852" @keyup.enter="consultar" />
        <button class="btn-consultar" @click="consultar">Consultar</button>
      </div>

      <!-- Loading -->
      <div class="search-loading" v-if="loading">
        <span class="spinner-inline"></span> A consultar...
      </div>

      <!-- Erro de rede -->
      <div class="search-error" v-if="errorMsg">{{ errorMsg }}</div>
    </section>

    <!-- NOT FOUND -->
    <div class="error-state" v-if="notFound">
      <div class="error-icon">
        <svg width="28" height="28" fill="none" stroke="#C66E00" stroke-width="1.8" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10" />
          <path d="M12 8v4M12 16h.01" stroke-linecap="round" />
        </svg>
      </div>
      <h3>Ocorrência não encontrada</h3>
      <p>O código <strong>{{ searchCode }}</strong> não existe no nosso sistema. Verifique e tente novamente.</p>
    </div>

    <!-- RESULT -->
    <div class="result-wrapper" v-if="result">

      <div class="result-meta">
        <span class="badge-process">Processo #{{ result.codigo }}</span>
        <div class="meta-date">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 16 16">
            <rect x="1" y="2" width="14" height="13" rx="2" />
            <path d="M5 1v2M11 1v2M1 6h14" stroke-linecap="round" />
          </svg>
          Submetido em {{ result.dataSubmissao }}
        </div>
        <div class="meta-date">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 16 16">
            <rect x="1" y="1" width="14" height="14" rx="2" />
            <path d="M4 8h8M8 4v8" stroke-linecap="round" />
          </svg>
          Projecto: {{ result.projeto }}
        </div>
        <div class="meta-date" v-if="result.prazo !== 'A definir'">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 16 16">
            <circle cx="8" cy="8" r="6" />
            <path d="M8 5v3l2 2" stroke-linecap="round" />
          </svg>
          Prazo: {{ result.prazo }}
        </div>
      </div>

      <div class="result-grid">

        <!-- LEFT: DETAIL CARD -->
        <div class="detail-card">

          <div class="detail-title-row">
            <h2>{{ result.titulo }}</h2>
            <span class="badge-cat">{{ result.categoria }}</span>
          </div>
          <div class="detail-location">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
              <path d="M8 1.5C5.239 1.5 3 3.739 3 6.5c0 3.75 5 9 5 9s5-5.25 5-9c0-2.761-2.239-5-5-5z" />
              <circle cx="8" cy="6.5" r="1.8" />
            </svg>
            {{ result.localizacao }}
          </div>

          <div class="detail-section">
            <div class="section-label">
              <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                <rect x="2" y="1" width="10" height="14" rx="1.5" />
                <path d="M5 5h4M5 8h4M5 11h2" stroke-linecap="round" />
                <path d="M10 1v4h4" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
              Descrição do Incidente
            </div>
            <p class="section-body">{{ result.descricao }}</p>
          </div>

          <div class="detail-section">
            <div class="info-row">
              <div class="info-block">
                <div class="section-label" style="margin-bottom:10px">
                  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                    <circle cx="8" cy="6" r="3" />
                    <path d="M2 14c0-2.761 2.686-5 6-5s6 2.239 6 5" stroke-linecap="round" />
                  </svg>
                  Informação de Contacto
                </div>
                <label>Nome</label>
                <p>{{ result.contacto.nome }}</p>
                <template v-if="result.contacto.email">
                  <label style="margin-top:8px">Email</label>
                  <p>{{ result.contacto.email }}</p>
                </template>
                <template v-if="result.contacto.phone">
                  <label style="margin-top:8px">Telefone</label>
                  <p>{{ result.contacto.phone }}</p>
                </template>
                <template v-if="!result.contacto.email && !result.contacto.phone">
                  <label style="margin-top:8px">Contacto</label>
                  <p>Não fornecido</p>
                </template>
                <template v-if="result.contacto.sexo">
                  <label style="margin-top:8px">Sexo</label>
                  <p>{{ result.contacto.sexo === 'masculino' ? 'Masculino' : 'Feminino' }}</p>
                </template>
                <template v-if="result.contacto.idade">
                  <label style="margin-top:8px">Faixa Etária</label>
                  <p>{{ result.contacto.idade }}</p>
                </template>
              </div>
              <div class="info-block">
                <div class="section-label" style="margin-bottom:10px">
                  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                    <rect x="1" y="2" width="14" height="13" rx="2" />
                    <path d="M5 1v2M11 1v2M1 6h14" stroke-linecap="round" />
                  </svg>
                  Data da Ocorrência
                </div>
                <p>{{ result.dataOcorrencia }}</p>
              </div>
            </div>
          </div>

          <div class="detail-section" v-if="result.subcategoria || result.nivelAlerta">
            <div class="info-row">
              <div class="info-block" v-if="result.subcategoria">
                <div class="section-label" style="margin-bottom:10px">
                  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                    <rect x="1" y="1" width="14" height="14" rx="2" />
                    <path d="M1 6h14M6 6v9" stroke-linecap="round" />
                  </svg>
                  Subcategoria
                </div>
                <p>{{ result.subcategoria }}</p>
              </div>
              <div class="info-block" v-if="result.nivelAlerta">
                <div class="section-label" style="margin-bottom:10px">
                  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                    <circle cx="8" cy="6" r="3" />
                    <path d="M2 14c0-2.761 2.686-5 6-5s6 2.239 6 5" stroke-linecap="round" />
                  </svg>
                  Nível de Alerta
                </div>
                <p>{{ result.nivelAlerta }}</p>
              </div>
            </div>
          </div>

          <div class="detail-section">
            <div class="section-label">
              <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                <rect x="1" y="1" width="14" height="14" rx="2" />
                <path d="M1 6h14M6 6v9" stroke-linecap="round" />
              </svg>
              Anexos Enviados
            </div>
            <div class="attachments-grid">
              <div class="attach-thumb" v-for="(a, i) in result.anexos" :key="i">
                <img v-if="a.type === 'img' && a.src" :src="a.src" :alt="a.nome" />
                <div v-else class="attach-doc">
                  <svg width="28" height="28" fill="none" stroke="#888E8C" stroke-width="1.5" viewBox="0 0 28 28">
                    <rect x="4" y="2" width="16" height="22" rx="2" />
                    <path d="M8 8h8M8 12h8M8 16h5" stroke-linecap="round" />
                    <path d="M18 2v6h6" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <span>{{ a.ext }}</span>
                </div>
                <div class="attach-footer">
                  <div class="attach-info">
                    <span class="attach-name">{{ a.nome }}</span>
                    <span class="attach-size" v-if="a.size">{{ a.size }}</span>
                  </div>
                  <button class="btn-dl" @click="downloadAnexo(a)"
                    :title="a.isPreviewable ? 'Pré-visualizar / Descarregar' : 'Descarregar'">
                    <svg v-if="a.isPreviewable" width="15" height="15" fill="none" stroke="currentColor"
                      stroke-width="1.8" viewBox="0 0 14 14">
                      <path d="M1 7s2.5-4 6-4 6 4 6 4-2.5 4-6 4-6-4-6-4z" stroke-linecap="round" />
                      <circle cx="7" cy="7" r="2" />
                    </svg>
                    <svg v-else width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8"
                      viewBox="0 0 14 14">
                      <path d="M7 2v7M4 7l3 3 3-3" stroke-linecap="round" stroke-linejoin="round" />
                      <path d="M2 12h10" stroke-linecap="round" />
                    </svg>
                  </button>
                </div>
              </div>
              <div class="attach-end">Fim dos anexos</div>
            </div>
          </div>

        </div>

        <!-- RIGHT: SIDEBAR -->
        <div class="sidebar">

          <div class="sidebar-card">
            <div class="status-header">
              <span class="status-label">Estado Atual</span>
              <svg class="spinner" width="18" height="18" fill="none" stroke="#2D6A4F" stroke-width="2"
                viewBox="0 0 24 24">
                <path d="M12 2a10 10 0 0 1 10 10" stroke-linecap="round" />
                <path d="M12 22a10 10 0 0 1-10-10" stroke="#DDE8E1" stroke-linecap="round" />
              </svg>
            </div>
            <div class="status-value" :class="'status-' + result.statusColor">{{ result.status }}</div>
            <p class="status-desc">{{ result.statusDesc }}</p>
          </div>

          <div class="sidebar-card">
            <div class="timeline-title">
              <svg width="15" height="15" fill="none" stroke="#2D6A4F" stroke-width="1.7" viewBox="0 0 16 16">
                <circle cx="8" cy="8" r="6" />
                <path d="M8 5v3l2 2" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
              Histórico do Caso
            </div>

            <div class="timeline">
              <div class="tl-item" v-for="(ev, idx) in result.historico" :key="idx">
                <div class="tl-line">
                  <div class="tl-dot" :class="idx === 0 ? 'active' : 'past'"></div>
                  <div class="tl-connector" v-if="idx < result.historico.length - 1"></div>
                </div>
                <div class="tl-body">
                  <div class="tl-title" :class="idx === 0 ? 'active' : ''">{{ ev.titulo }}</div>
                  <div class="tl-date">{{ ev.data }}</div>
                  <div class="tl-desc">{{ ev.descricao }}</div>
                </div>
              </div>
            </div>


          </div>

          <div class="sidebar-card">
            <div class="help-header">
              <svg width="16" height="16" fill="none" stroke="#2D6A4F" stroke-width="1.7" viewBox="0 0 16 16">
                <circle cx="8" cy="8" r="6" />
                <path d="M6.5 6.5a1.5 1.5 0 0 1 3 0c0 1-1.5 1.5-1.5 2.5" stroke-linecap="round" />
                <circle cx="8" cy="12" r=".6" fill="#2D6A4F" />
              </svg>
              Precisa de ajuda?
            </div>
            <p class="help-desc">Se tiver informações adicionais ou o estado não for atualizado por mais de 5 dias
              úteis, entre
              em contacto connosco.</p>
            <button class="btn-suporte">Contactar Suporte</button>
          </div>

        </div>
      </div>
    </div>

    <AppFooter />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import AppNavbar from '@/components/AppNavbar.vue'
import AppFooter from '@/components/AppFooter.vue'
import { PublicService } from '../../api/services/public.service'

const searchCode = ref('')
const result = ref(null)
const notFound = ref(false)
const loading = ref(false)
const errorMsg = ref('')

// Mapeia a resposta da API para a estrutura que o template espera
function mapApiResponse(data) {
  // Localização: junta província + distrito + detalhe
  const locationParts = [data.province, data.district, data.location_detail].filter(Boolean)

  // Descrição do estado actual
  const statusDescMap = {
    pending: 'A sua ocorrência foi recebida e aguarda análise pela equipa técnica.',
    in_review: 'A equipa técnica da Biofund está a analisar a ocorrência.',
    resolved: 'A ocorrência foi resolvida com sucesso.',
    rejected: 'A ocorrência foi rejeitada após análise.',
    closed: 'O processo foi encerrado.',
  }
  let statusDesc = statusDescMap[data.status] ?? ''
  if (data.is_overdue) statusDesc += ' ⚠️ Esta ocorrência está fora do prazo de resolução.'

  return {
    codigo: data.tracking_code,
    dataSubmissao: data.submitted_at,
    prazo: data.due_date ?? 'A definir',
    titulo: data.type?.name ?? data.subject ?? 'Sem assunto',
    categoria: data.category ?? '-',
    subcategoria: data.subcategory ?? null,
    nivelAlerta: data.type?.alert_label ?? null,
    projeto: data.project?.name ?? '-',
    localizacao: locationParts.join(', ') || '-',
    descricao: data.description,
    contacto: {
      nome: data.submitted_by ?? 'Anónimo',
      email: data.complainant_email ?? null,
      phone: data.complainant_phone ?? null,
      sexo: data.complainant_gender ?? null,
      idade: data.complainant_age ?? null,
    },
    dataOcorrencia: data.occurrence_date ?? 'Não especificada',
    status: data.status_label,
    statusColor: data.status_color,   // 'green' | 'blue' | 'yellow' | 'red' | 'gray'
    isOverdue: data.is_overdue,
    statusDesc,
    historico: (data.history ?? []).map(h => ({
      titulo: h.to,
      data: h.date,
      descricao: h.comment ?? '',
    })),
    anexos: (data.attachments ?? []).map(a => {
      const imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp']
      const previewExts = [...imageExts, 'pdf']
      const ext = a.name.split('.').pop().toLowerCase()
      const isImage = imageExts.includes(ext)
      return {
        _id:  a.id,
        type: isImage ? 'img' : 'doc',
        nome: a.name,
        ext:  ext.toUpperCase(),
        size: a.size,
        src:  null,   // preenchido via blob após o carregamento
        isPreviewable: previewExts.includes(ext),
      }
    }),
  }
}

async function downloadAnexo(a) {
  try {
    const blobUrl = await PublicService.getAttachmentBlobUrl(result.value.codigo, a._id)
    const link = document.createElement('a')
    link.href = blobUrl
    link.download = a.nome
    link.click()
    setTimeout(() => URL.revokeObjectURL(blobUrl), 60000)
  } catch {}
}

async function consultar() {
  const code = searchCode.value.trim().toUpperCase()
  if (!code) return

  result.value = null
  notFound.value = false
  errorMsg.value = ''
  loading.value = true

  try {
    const data = await PublicService.trackOccurrence(code)
    result.value = mapApiResponse(data)

    // Carregar imagens como blob (garante exibição independente de CORS/cache)
    for (const a of result.value.anexos) {
      if (a.type !== 'img') continue
      PublicService.getAttachmentBlobUrl(data.tracking_code, a._id)
        .then(blobUrl => { a.src = blobUrl })
        .catch(() => {})
    }
  } catch (err) {
    if (err.response?.status === 404) {
      notFound.value = true
    } else {
      errorMsg.value = 'Erro ao consultar. Verifique a sua ligação e tente novamente.'
    }
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.page {
  background: var(--offwhite);
  min-height: 100vh;
}

/* SEARCH HERO */
.search-hero {
  background: #EEF7F1;
  padding: 60px 24px 56px;
  text-align: center;
}

.search-hero h1 {
  font-size: 30px;
  font-weight: 800;
  margin-bottom: 12px;
}

.search-hero p {
  font-size: 14px;
  color: var(--text-gray);
  line-height: 1.65;
  margin-bottom: 32px;
}

.search-bar {
  display: inline-flex;
  align-items: center;
  max-width: 520px;
  width: 100%;
  background: var(--white);
  border: 1.5px solid var(--border);
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 16px rgba(0, 0, 0, 0.07);
  transition: border-color 0.2s, box-shadow 0.2s;
}

.search-bar:focus-within {
  border-color: var(--green-light);
  box-shadow: 0 0 0 3px rgba(82, 183, 136, 0.18);
}

.search-bar-icon {
  padding: 0 14px;
  display: flex;
  align-items: center;
  color: var(--text-light);
}

.search-bar input {
  flex: 1;
  border: none;
  outline: none;
  font-family: 'Poppins', sans-serif;
  font-size: 14px;
  color: var(--text-dark);
  padding: 14px 0;
  background: transparent;
}

.search-bar input::placeholder {
  color: var(--text-light);
}

.btn-consultar {
  background: var(--green-mid);
  color: #fff;
  border: none;
  padding: 0 28px;
  font-family: 'Poppins', sans-serif;
  font-size: 14px;
  font-weight: 700;
  cursor: pointer;
  height: 100%;
  min-height: 52px;
  transition: background 0.2s;
}

.btn-consultar:hover {
  background: var(--green-dark);
}

.search-hint {
  margin-top: 12px;
  font-size: 12px;
  color: var(--text-light);
}

.search-hint span {
  cursor: pointer;
  color: var(--green-mid);
  font-weight: 600;
  text-decoration: underline;
}

/* ERROR STATE */
.error-state {
  max-width: 960px;
  margin: 48px auto;
  padding: 0 24px;
  text-align: center;
  animation: fadeUp 0.4s ease;
}

.error-icon {
  width: 64px;
  height: 64px;
  background: #FFF3CD;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 18px;
}

.error-state h3 {
  font-size: 17px;
  font-weight: 700;
  margin-bottom: 8px;
}

.error-state p {
  font-size: 13.5px;
  color: var(--text-gray);
}

/* RESULT LAYOUT */
.result-wrapper {
  max-width: 960px;
  margin: 40px auto 80px;
  padding: 0 24px;
  animation: fadeUp 0.45s ease;
}

@keyframes fadeUp {
  from {
    opacity: 0;
    transform: translateY(16px);
  }

  to {
    opacity: 1;
    transform: none;
  }
}

.result-meta {
  display: flex;
  align-items: center;
  gap: 16px;
  margin-bottom: 22px;
  flex-wrap: wrap;
}

.badge-process {
  background: var(--green-pale);
  color: var(--green-dark);
  font-size: 11.5px;
  font-weight: 700;
  padding: 4px 12px;
  border-radius: 99px;
}

.meta-date {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 12.5px;
  color: var(--text-gray);
}

.result-grid {
  display: grid;
  grid-template-columns: 1fr 300px;
  gap: 20px;
  align-items: start;
}

/* DETAIL CARD */
.detail-card {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 14px;
  padding: 28px 32px;
}

.detail-title-row {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 16px;
  margin-bottom: 6px;
}

.detail-card h2 {
  font-size: 20px;
  font-weight: 800;
  line-height: 1.25;
}

.badge-cat {
  display: inline-block;
  background: #FFF3E0;
  color: #C66E00;
  font-size: 11px;
  font-weight: 700;
  padding: 4px 10px;
  border-radius: 6px;
  white-space: nowrap;
  flex-shrink: 0;
}

.detail-location {
  display: flex;
  align-items: center;
  gap: 5px;
  font-size: 12.5px;
  color: var(--text-gray);
  margin-bottom: 24px;
}

.detail-section {
  margin-bottom: 24px;
  padding-bottom: 24px;
  border-bottom: 1px solid var(--border);
}

.detail-section:last-child {
  margin-bottom: 0;
  padding-bottom: 0;
  border-bottom: none;
}

.section-label {
  display: flex;
  align-items: center;
  gap: 7px;
  font-size: 13px;
  font-weight: 700;
  margin-bottom: 10px;
  color: var(--text-dark);
}

.section-body {
  font-size: 13px;
  color: var(--text-gray);
  line-height: 1.75;
}

.info-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}

.info-block label {
  font-size: 12px;
  color: var(--text-light);
  display: block;
  margin-bottom: 4px;
}

.info-block p {
  font-size: 13px;
  color: var(--text-dark);
  font-weight: 500;
}

/* ATTACHMENTS */
.attachments-grid {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
  margin-top: 12px;
}

.attach-thumb {
  width: 155px;
  border: 1px solid var(--border);
  border-radius: 10px;
  overflow: hidden;
  position: relative;
  cursor: pointer;
  transition: box-shadow 0.2s;
}

.attach-thumb:hover {
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
}

.attach-thumb img {
  width: 100%;
  height: 100px;
  object-fit: cover;
  display: block;
}

.attach-doc {
  height: 100px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 6px;
  background: #F7F9F8;
}

.attach-doc span {
  font-size: 11px;
  color: var(--text-gray);
}

.attach-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 7px 10px;
  border-top: 1px solid var(--border);
}

.attach-footer span {
  font-size: 11px;
  color: var(--text-gray);
}

.btn-dl {
  background: none;
  border: none;
  padding: 0;
  cursor: pointer;
  color: var(--green-mid);
  display: flex;
  align-items: center;
  transition: color 0.2s;
  font-family: inherit;
}

.btn-dl:hover {
  color: var(--green-dark);
}

.attach-end {
  width: 155px;
  height: 100px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  color: var(--text-light);
  background: #F7F9F8;
  border: 1px dashed var(--border);
  border-radius: 10px;
}

/* SIDEBAR */
.sidebar {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.sidebar-card {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 14px;
  padding: 22px;
}

.status-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 10px;
}

.status-label {
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 1px;
  text-transform: uppercase;
  color: var(--text-gray);
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.spinner {
  animation: spin 1.6s linear infinite;
}

.status-value {
  font-size: 24px;
  font-weight: 800;
  color: var(--text-dark);
  margin-bottom: 6px;
}

.status-desc {
  font-size: 12.5px;
  color: var(--text-gray);
  line-height: 1.55;
}

/* TIMELINE */
.timeline-title {
  display: flex;
  align-items: center;
  gap: 7px;
  font-size: 13px;
  font-weight: 700;
  margin-bottom: 18px;
}

.timeline {
  display: flex;
  flex-direction: column;
}

.tl-item {
  display: flex;
  gap: 12px;
}

.tl-line {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 18px;
  flex-shrink: 0;
}

.tl-dot {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  flex-shrink: 0;
  margin-top: 3px;
}

.tl-dot.active {
  background: var(--green-mid);
}

.tl-dot.past {
  background: #C6D8CC;
}

.tl-connector {
  width: 2px;
  background: #E4EDE6;
  flex: 1;
  margin: 4px 0;
  min-height: 28px;
}

.tl-body {
  padding-bottom: 20px;
  flex: 1;
}

.tl-title {
  font-size: 13px;
  font-weight: 700;
  color: var(--text-dark);
  margin-bottom: 2px;
}

.tl-title.active {
  color: var(--green-mid);
}

.tl-date {
  font-size: 11px;
  color: var(--text-light);
  margin-bottom: 4px;
}

.tl-desc {
  font-size: 12px;
  color: var(--text-gray);
  line-height: 1.55;
}

.tl-more {
  display: flex;
  align-items: center;
  gap: 4px;
  background: none;
  border: none;
  cursor: pointer;
  color: var(--green-mid);
  font-family: 'Poppins', sans-serif;
  font-size: 12.5px;
  font-weight: 600;
  margin-top: 6px;
  transition: gap 0.2s;
}

.tl-more:hover {
  gap: 8px;
}

/* HELP CARD */
.help-header {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  font-weight: 700;
  margin-bottom: 10px;
}

.help-desc {
  font-size: 12.5px;
  color: var(--text-gray);
  line-height: 1.6;
  margin-bottom: 14px;
}

.btn-suporte {
  width: 100%;
  background: var(--white);
  color: var(--text-dark);
  border: 1.5px solid var(--border);
  border-radius: 8px;
  padding: 10px 0;
  font-family: 'Poppins', sans-serif;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: border-color 0.2s, color 0.2s;
}

.btn-suporte:hover {
  border-color: var(--green-mid);
  color: var(--green-mid);
}

/* LOADING & ERROR */
.search-loading {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  margin-top: 16px;
  font-size: 13.5px;
  color: var(--text-gray);
}

.spinner-inline {
  display: inline-block;
  width: 16px;
  height: 16px;
  border: 2px solid #C3E6CE;
  border-top-color: var(--green-mid);
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.search-error {
  margin-top: 14px;
  font-size: 13px;
  color: #C53030;
  background: #FFF5F5;
  border: 1px solid #FEB2B2;
  border-radius: 8px;
  padding: 10px 16px;
  display: inline-block;
}

/* STATUS CORES DINÂMICAS */
.status-green {
  color: var(--green-mid);
}

.status-blue {
  color: #EA580C;
}

.status-yellow {
  color: #C66E00;
}

.status-red {
  color: #C53030;
}

.status-orange {
  color: #3b82f6;
}

.status-gray {
  color: var(--text-gray);
}

/* ── RESPONSIVE ─────────────────────────────────────────────── */
@media (max-width: 900px) {
  .result-grid { grid-template-columns: 1fr; }
}

@media (max-width: 768px) {
  .search-hero { padding: 44px 20px 40px; }
  .search-hero h1 { font-size: 24px; }

  .search-bar { flex-wrap: wrap; height: auto; }
  .search-bar input { padding: 12px 0; min-width: 0; }
  .btn-consultar { width: 100%; padding: 12px 0; min-height: 44px; }

  .result-wrapper { margin: 28px auto 56px; padding: 0 16px; }

  .detail-card { padding: 20px 18px; }
  .detail-title-row { flex-direction: column; align-items: flex-start; gap: 8px; }

  .info-row { grid-template-columns: 1fr; gap: 12px; }

  .attach-thumb, .attach-end { width: calc(50% - 6px); }
}

@media (max-width: 420px) {
  .attach-thumb, .attach-end { width: 100%; }
}
</style>