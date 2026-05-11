<template>
  <div class="page">
    <AppNavbar />

    <!-- SEARCH HERO -->
    <section class="search-hero">
      <h1>Acompanhe a sua Reclamação</h1>
      <p>Insira o código único recebido no momento da submissão para verificar o estado atual e as atualizações da nossa equipe.</p>

      <div class="search-bar">
        <div class="search-bar-icon">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 16 16">
            <circle cx="7" cy="7" r="5"/><path d="M12 12l3 3" stroke-linecap="round"/>
          </svg>
        </div>
        <input
          type="text"
          v-model="searchCode"
          placeholder="BQ-2024-0852"
          @keyup.enter="consultar"
        />
        <button class="btn-consultar" @click="consultar">Consultar</button>
      </div>

      <p class="search-hint" v-if="!result && !notFound">
        Experimente com o código de demonstração:
        <span @click="loadDemo">BQ-2024-0852</span>
      </p>
    </section>

    <!-- NOT FOUND -->
    <div class="error-state" v-if="notFound">
      <div class="error-icon">
        <svg width="28" height="28" fill="none" stroke="#C66E00" stroke-width="1.8" viewBox="0 0 24 24">
          <circle cx="12" cy="12" r="10"/>
          <path d="M12 8v4M12 16h.01" stroke-linecap="round"/>
        </svg>
      </div>
      <h3>Reclamação não encontrada</h3>
      <p>O código <strong>{{ searchCode }}</strong> não existe no nosso sistema. Verifique e tente novamente.</p>
    </div>

    <!-- RESULT -->
    <div class="result-wrapper" v-if="result">

      <div class="result-meta">
        <span class="badge-process">Processo #{{ result.codigo }}</span>
        <div class="meta-date">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 16 16">
            <rect x="1" y="2" width="14" height="13" rx="2"/><path d="M5 1v2M11 1v2M1 6h14" stroke-linecap="round"/>
          </svg>
          Submetido em {{ result.dataSubmissao }}
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
              <path d="M8 1.5C5.239 1.5 3 3.739 3 6.5c0 3.75 5 9 5 9s5-5.25 5-9c0-2.761-2.239-5-5-5z"/>
              <circle cx="8" cy="6.5" r="1.8"/>
            </svg>
            {{ result.localizacao }}
          </div>

          <div class="detail-section">
            <div class="section-label">
              <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                <rect x="2" y="1" width="10" height="14" rx="1.5"/>
                <path d="M5 5h4M5 8h4M5 11h2" stroke-linecap="round"/>
                <path d="M10 1v4h4" stroke-linecap="round" stroke-linejoin="round"/>
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
                    <circle cx="8" cy="6" r="3"/><path d="M2 14c0-2.761 2.686-5 6-5s6 2.239 6 5" stroke-linecap="round"/>
                  </svg>
                  Informação de Contacto
                </div>
                <label>Nome:</label>
                <p>{{ result.contacto.nome }}</p>
                <label style="margin-top:6px">Contacto:</label>
                <p>{{ result.contacto.telefone }}</p>
              </div>
              <div class="info-block">
                <div class="section-label" style="margin-bottom:10px">
                  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                    <rect x="1" y="2" width="14" height="13" rx="2"/><path d="M5 1v2M11 1v2M1 6h14" stroke-linecap="round"/>
                  </svg>
                  Data da Ocorrência
                </div>
                <p>{{ result.dataOcorrencia }}</p>
              </div>
            </div>
          </div>

          <div class="detail-section">
            <div class="section-label">
              <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 16 16">
                <rect x="1" y="1" width="14" height="14" rx="2"/>
                <path d="M1 6h14M6 6v9" stroke-linecap="round"/>
              </svg>
              Anexos Enviados
            </div>
            <div class="attachments-grid">
              <div class="attach-thumb" v-for="(a, i) in result.anexos" :key="i">
                <img v-if="a.type === 'img'" :src="a.src" :alt="a.nome" />
                <div v-else class="attach-doc">
                  <svg width="28" height="28" fill="none" stroke="#888E8C" stroke-width="1.5" viewBox="0 0 28 28">
                    <rect x="4" y="2" width="16" height="22" rx="2"/>
                    <path d="M8 8h8M8 12h8M8 16h5" stroke-linecap="round"/>
                    <path d="M18 2v6h6" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  <span>{{ a.ext }}</span>
                </div>
                <div class="attach-footer">
                  <span>{{ a.nome }}</span>
                  <button class="btn-dl">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 14 14">
                      <path d="M7 2v7M4 7l3 3 3-3" stroke-linecap="round" stroke-linejoin="round"/>
                      <path d="M2 12h10" stroke-linecap="round"/>
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
              <svg class="spinner" width="18" height="18" fill="none" stroke="#2D6A4F" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 2a10 10 0 0 1 10 10" stroke-linecap="round"/>
                <path d="M12 22a10 10 0 0 1-10-10" stroke="#DDE8E1" stroke-linecap="round"/>
              </svg>
            </div>
            <div class="status-value">{{ result.status }}</div>
            <p class="status-desc">{{ result.statusDesc }}</p>
          </div>

          <div class="sidebar-card">
            <div class="timeline-title">
              <svg width="15" height="15" fill="none" stroke="#2D6A4F" stroke-width="1.7" viewBox="0 0 16 16">
                <circle cx="8" cy="8" r="6"/><path d="M8 5v3l2 2" stroke-linecap="round" stroke-linejoin="round"/>
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

            <button class="tl-more">
              Ver mais atualizações
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 14 14">
                <path d="M2 7h10M8 3l4 4-4 4" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </button>
          </div>

          <div class="sidebar-card">
            <div class="help-header">
              <svg width="16" height="16" fill="none" stroke="#2D6A4F" stroke-width="1.7" viewBox="0 0 16 16">
                <circle cx="8" cy="8" r="6"/>
                <path d="M6.5 6.5a1.5 1.5 0 0 1 3 0c0 1-1.5 1.5-1.5 2.5" stroke-linecap="round"/>
                <circle cx="8" cy="12" r=".6" fill="#2D6A4F"/>
              </svg>
              Precisa de ajuda?
            </div>
            <p class="help-desc">Se tiver informações adicionais ou o estado não for atualizado por mais de 5 dias úteis, entre em contacto connosco.</p>
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

const searchCode = ref('')
const result     = ref(null)
const notFound   = ref(false)

const mockDB = {
  'BQ-2024-0852': {
    codigo: 'BQ-2024-0852',
    dataSubmissao: '12 de Outubro, 2024',
    titulo: 'Descarte Ilegal de Resíduos Químicos',
    categoria: 'Poluição Hídrica',
    localizacao: 'Distrito de Marracuene, Província de Maputo',
    descricao: 'Foi observado o descarte de tambores de cor azul contendo substâncias oleosas perto da margem do Rio Incomáti. A água apresenta uma mancha escura e há um odor forte de combustível na zona. O incidente ocorreu aproximadamente a 500 metros da ponte principal.',
    contacto: { nome: 'Amélia Sitoe', telefone: '+258 84 *** **45' },
    dataOcorrencia: '11 de Outubro de 2024, por volta das 09:30',
    status: 'Em Análise',
    statusDesc: 'A equipe técnica da Biofund está a validar os dados no local.',
    historico: [
      { titulo: 'Equipe Deslocada',      data: '15 Out, 2024 · 10:20', descricao: 'Inspetores ambientais foram enviados para a zona de Marracuene.' },
      { titulo: 'Em Análise Preliminar', data: '13 Out, 2024 · 14:45', descricao: 'Reclamação aceite e triagem realizada pelo departamento de fiscalização.' },
      { titulo: 'Submetido',             data: '12 Out, 2024 · 16:30', descricao: 'A sua reclamação foi registada com sucesso no sistema BioQueixa.' }
    ],
    anexos: [
      { type: 'img', nome: 'evidencia_rio_1.jpg',   src: 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&q=70' },
      { type: 'img', nome: 'tambores_margem.jpg',   src: 'https://images.unsplash.com/photo-1611532736597-de2d4265fba3?w=400&q=70' },
      { type: 'doc', nome: 'localizacao_mapa.pdf',  ext: 'PDF' }
    ]
  },
  'BQ-2024-0731': {
    codigo: 'BQ-2024-0731',
    dataSubmissao: '3 de Setembro, 2024',
    titulo: 'Caça Furtiva na Reserva do Niassa',
    categoria: 'Caça Ilegal',
    localizacao: 'Reserva Nacional do Niassa, Província de Niassa',
    descricao: 'Foram observadas armadilhas de arame instaladas ao longo de um percurso de 2 km dentro da área protegida. Evidências de pelo menos dois animais abatidos ilegalmente foram encontradas no local.',
    contacto: { nome: 'Anónimo', telefone: 'Não fornecido' },
    dataOcorrencia: '1 de Setembro de 2024, de manhã',
    status: 'Resolvido',
    statusDesc: 'O caso foi encerrado com sucesso. As autoridades retiraram as armadilhas e identificaram os suspeitos.',
    historico: [
      { titulo: 'Caso Encerrado',    data: '20 Set, 2024 · 09:00', descricao: 'Suspeitos identificados e armadilhas removidas. Processo entregue ao Ministério Público.' },
      { titulo: 'Operação Realizada',data: '10 Set, 2024 · 07:30', descricao: 'Patrulha especial deslocada à zona. Armadilhas documentadas e confiscadas.' },
      { titulo: 'Submetido',         data: '3 Set, 2024 · 19:15',  descricao: 'Reclamação registada com sucesso no sistema BioQueixa.' }
    ],
    anexos: [
      { type: 'img', nome: 'armadilha_1.jpg',     src: 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=400&q=70' },
      { type: 'doc', nome: 'relatorio_campo.pdf', ext: 'PDF' }
    ]
  }
}

function consultar() {
  const code = searchCode.value.trim().toUpperCase()
  result.value   = null
  notFound.value = false
  if (!code) return
  const found = mockDB[code]
  if (found) { result.value = found }
  else       { notFound.value = true }
}

function loadDemo() {
  searchCode.value = 'BQ-2024-0852'
  consultar()
}
</script>

<style scoped>
.page { background: var(--offwhite); min-height: 100vh; }

/* SEARCH HERO */
.search-hero {
  background: #EEF7F1;
  padding: 60px 24px 56px;
  text-align: center;
}

.search-hero h1 { font-size: 30px; font-weight: 800; margin-bottom: 12px; }

.search-hero p {
  font-size: 14px;
  color: var(--text-gray);
  line-height: 1.65;
  margin-bottom: 32px;
}

.search-bar {
  display: inline-flex;
  align-items: center;
  max-width: 520px; width: 100%;
  background: var(--white);
  border: 1.5px solid var(--border);
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 16px rgba(0,0,0,0.07);
  transition: border-color 0.2s, box-shadow 0.2s;
}

.search-bar:focus-within {
  border-color: var(--green-light);
  box-shadow: 0 0 0 3px rgba(82,183,136,0.18);
}

.search-bar-icon {
  padding: 0 14px;
  display: flex; align-items: center;
  color: var(--text-light);
}

.search-bar input {
  flex: 1; border: none; outline: none;
  font-family: 'Poppins', sans-serif;
  font-size: 14px; color: var(--text-dark);
  padding: 14px 0;
  background: transparent;
}

.search-bar input::placeholder { color: var(--text-light); }

.btn-consultar {
  background: var(--green-mid); color: #fff; border: none;
  padding: 0 28px;
  font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 700;
  cursor: pointer; height: 100%; min-height: 52px;
  transition: background 0.2s;
}

.btn-consultar:hover { background: var(--green-dark); }

.search-hint { margin-top: 12px; font-size: 12px; color: var(--text-light); }

.search-hint span {
  cursor: pointer; color: var(--green-mid); font-weight: 600;
  text-decoration: underline;
}

/* ERROR STATE */
.error-state {
  max-width: 960px; margin: 48px auto; padding: 0 24px;
  text-align: center;
  animation: fadeUp 0.4s ease;
}

.error-icon {
  width: 64px; height: 64px;
  background: #FFF3CD;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 18px;
}

.error-state h3 { font-size: 17px; font-weight: 700; margin-bottom: 8px; }
.error-state p  { font-size: 13.5px; color: var(--text-gray); }

/* RESULT LAYOUT */
.result-wrapper {
  max-width: 960px; margin: 40px auto 80px; padding: 0 24px;
  animation: fadeUp 0.45s ease;
}

@keyframes fadeUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: none; } }

.result-meta {
  display: flex; align-items: center; gap: 16px;
  margin-bottom: 22px; flex-wrap: wrap;
}

.badge-process {
  background: var(--green-pale); color: var(--green-dark);
  font-size: 11.5px; font-weight: 700;
  padding: 4px 12px; border-radius: 99px;
}

.meta-date {
  display: flex; align-items: center; gap: 6px;
  font-size: 12.5px; color: var(--text-gray);
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
  display: flex; align-items: flex-start;
  justify-content: space-between; gap: 16px; margin-bottom: 6px;
}

.detail-card h2 { font-size: 20px; font-weight: 800; line-height: 1.25; }

.badge-cat {
  display: inline-block;
  background: #FFF3E0; color: #C66E00;
  font-size: 11px; font-weight: 700;
  padding: 4px 10px; border-radius: 6px;
  white-space: nowrap; flex-shrink: 0;
}

.detail-location {
  display: flex; align-items: center; gap: 5px;
  font-size: 12.5px; color: var(--text-gray); margin-bottom: 24px;
}

.detail-section {
  margin-bottom: 24px; padding-bottom: 24px;
  border-bottom: 1px solid var(--border);
}

.detail-section:last-child { margin-bottom: 0; padding-bottom: 0; border-bottom: none; }

.section-label {
  display: flex; align-items: center; gap: 7px;
  font-size: 13px; font-weight: 700; margin-bottom: 10px; color: var(--text-dark);
}

.section-body { font-size: 13px; color: var(--text-gray); line-height: 1.75; }

.info-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

.info-block label { font-size: 12px; color: var(--text-light); display: block; margin-bottom: 4px; }
.info-block p { font-size: 13px; color: var(--text-dark); font-weight: 500; }

/* ATTACHMENTS */
.attachments-grid { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 12px; }

.attach-thumb {
  width: 155px;
  border: 1px solid var(--border);
  border-radius: 10px;
  overflow: hidden;
  position: relative; cursor: pointer;
  transition: box-shadow 0.2s;
}

.attach-thumb:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.12); }

.attach-thumb img { width: 100%; height: 100px; object-fit: cover; display: block; }

.attach-doc {
  height: 100px;
  display: flex; flex-direction: column;
  align-items: center; justify-content: center; gap: 6px;
  background: #F7F9F8;
}

.attach-doc span { font-size: 11px; color: var(--text-gray); }

.attach-footer {
  display: flex; align-items: center; justify-content: space-between;
  padding: 7px 10px; border-top: 1px solid var(--border);
}

.attach-footer span { font-size: 11px; color: var(--text-gray); }

.btn-dl {
  background: none; border: none; cursor: pointer;
  color: var(--green-mid); display: flex; transition: color 0.2s;
}

.btn-dl:hover { color: var(--green-dark); }

.attach-end {
  width: 155px; height: 100px;
  display: flex; align-items: center; justify-content: center;
  font-size: 12px; color: var(--text-light);
  background: #F7F9F8;
  border: 1px dashed var(--border);
  border-radius: 10px;
}

/* SIDEBAR */
.sidebar { display: flex; flex-direction: column; gap: 16px; }

.sidebar-card {
  background: var(--white);
  border: 1px solid var(--border);
  border-radius: 14px;
  padding: 22px;
}

.status-header {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 10px;
}

.status-label {
  font-size: 11px; font-weight: 700;
  letter-spacing: 1px; text-transform: uppercase; color: var(--text-gray);
}

@keyframes spin { to { transform: rotate(360deg); } }
.spinner { animation: spin 1.6s linear infinite; }

.status-value { font-size: 24px; font-weight: 800; color: var(--text-dark); margin-bottom: 6px; }
.status-desc  { font-size: 12.5px; color: var(--text-gray); line-height: 1.55; }

/* TIMELINE */
.timeline-title {
  display: flex; align-items: center; gap: 7px;
  font-size: 13px; font-weight: 700; margin-bottom: 18px;
}

.timeline { display: flex; flex-direction: column; }

.tl-item { display: flex; gap: 12px; }

.tl-line {
  display: flex; flex-direction: column; align-items: center;
  width: 18px; flex-shrink: 0;
}

.tl-dot {
  width: 12px; height: 12px;
  border-radius: 50%; flex-shrink: 0; margin-top: 3px;
}

.tl-dot.active { background: var(--green-mid); }
.tl-dot.past   { background: #C6D8CC; }

.tl-connector {
  width: 2px; background: #E4EDE6; flex: 1;
  margin: 4px 0; min-height: 28px;
}

.tl-body { padding-bottom: 20px; flex: 1; }

.tl-title { font-size: 13px; font-weight: 700; color: var(--text-dark); margin-bottom: 2px; }
.tl-title.active { color: var(--green-mid); }
.tl-date { font-size: 11px; color: var(--text-light); margin-bottom: 4px; }
.tl-desc { font-size: 12px; color: var(--text-gray); line-height: 1.55; }

.tl-more {
  display: flex; align-items: center; gap: 4px;
  background: none; border: none; cursor: pointer;
  color: var(--green-mid);
  font-family: 'Poppins', sans-serif; font-size: 12.5px; font-weight: 600;
  margin-top: 6px; transition: gap 0.2s;
}

.tl-more:hover { gap: 8px; }

/* HELP CARD */
.help-header {
  display: flex; align-items: center; gap: 8px;
  font-size: 13px; font-weight: 700; margin-bottom: 10px;
}

.help-desc { font-size: 12.5px; color: var(--text-gray); line-height: 1.6; margin-bottom: 14px; }

.btn-suporte {
  width: 100%; background: var(--white); color: var(--text-dark);
  border: 1.5px solid var(--border); border-radius: 8px;
  padding: 10px 0;
  font-family: 'Poppins', sans-serif; font-size: 13px; font-weight: 600;
  cursor: pointer; transition: border-color 0.2s, color 0.2s;
}

.btn-suporte:hover { border-color: var(--green-mid); color: var(--green-mid); }
</style>
