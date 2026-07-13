<template>
  <div class="home">
    <AppNavbar variant="fixed" />

    <!-- HERO -->
    <section class="hero">
      <div class="hero-bg"></div>
      <div class="hero-overlay"></div>
      <div class="hero-content">
        <div class="hero-badge">MDR · Mecanismo de Diálogo e Reclamação</div>
        <h1>
          Registe a sua
          <span>ocorrência</span>
        </h1>
        <p>Fortalecer a transparência e a participação das partes interessadas nas iniciativas de Conservação da Biodiversidade em Moçambique.</p>
        <div class="hero-buttons">
          <button class="btn-primary" @click="$router.push('/submeterReclamacao')">
            Submeter Ocorrência
            <svg width="14" height="14" fill="none" viewBox="0 0 16 16"><path d="M3 8h10M9 4l4 4-4 4" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
          <button class="btn-outline-white" @click="$router.push('/visualizarReclamacao')">Consultar Ocorrência</button>
        </div>
      </div>
    </section>

    <!-- FEATURE CARDS -->
    <div class="features-wrap reveal">
      <div class="features-grid">
        <div class="feature-card">
          <div class="feature-icon">
            <svg width="36" height="36" fill="none" viewBox="0 0 36 36">
              <path d="M18 5 L33 30 H3 Z" stroke="#2D6A4F" stroke-width="2" stroke-linejoin="round"/>
              <line x1="18" y1="14" x2="18" y2="22" stroke="#2D6A4F" stroke-width="2.5" stroke-linecap="round"/>
              <circle cx="18" cy="26" r="1.5" fill="#2D6A4F"/>
            </svg>
          </div>
          <h3>Registar Ocorrência</h3>
          <p>Submeta a sua reclamação, sugestão, preocupação, ou elogio através do formulário disponível na plataforma</p>
          <router-link to="/submeterReclamacao" class="feature-link">
            Começar Submissão
            <svg width="14" height="14" fill="none" viewBox="0 0 16 16"><path d="M3 8h10M9 4l4 4-4 4" stroke="#2D6A4F" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </router-link>
        </div>

        <div class="feature-card feature-card--tinted">
          <div class="feature-icon">
            <svg width="36" height="36" fill="none" viewBox="0 0 36 36">
              <circle cx="16" cy="16" r="9" stroke="#2D6A4F" stroke-width="2"/>
              <line x1="23" y1="23" x2="31" y2="31" stroke="#2D6A4F" stroke-width="2.5" stroke-linecap="round"/>
            </svg>
          </div>
          <h3>Acompanhar Estado</h3>
          <p>Consulte o progresso da sua Ocorrência utilizando o código do registo</p>
          <router-link to="/visualizarReclamacao" class="feature-link">
            Consultar Agora
            <svg width="14" height="14" fill="none" viewBox="0 0 16 16"><path d="M3 8h10M9 4l4 4-4 4" stroke="#2D6A4F" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </router-link>
        </div>

        <div class="feature-card">
          <div class="feature-icon">
            <svg width="36" height="36" fill="none" viewBox="0 0 36 36">
              <path d="M18 4 L30 9 V18 C30 24 24 29 18 32 C12 29 6 24 6 18 V9 Z" stroke="#2D6A4F" stroke-width="2" stroke-linejoin="round"/>
              <path d="M13 18 L17 22 L23 14" stroke="#2D6A4F" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <h3>Resolução</h3>
          <p>Receba a resposta e a resolução da sua ocorrência após a análise e tratamento da mesma.</p>
          <a href="#" class="feature-link">
            Ver Impacto
            <svg width="14" height="14" fill="none" viewBox="0 0 16 16"><path d="M3 8h10M9 4l4 4-4 4" stroke="#2D6A4F" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </a>
        </div>
      </div>
    </div>

    <!-- WHY SECTION -->
    <section class="why-section reveal">
      <div class="why-left">
        <h2>Por que registar uma ocorrência?</h2>
        <div class="why-underline"></div>
        <p>O registo de ocorrências permite que as suas preocupações, reclamações, sugestões, consultas ou elogios sejam analisados e tratados de forma adequada e atempada.</p>

        <div class="benefits-grid">
          <div class="benefit-item" v-for="b in benefits" :key="b.title">
            <div class="benefit-check">
              <svg fill="none" viewBox="0 0 12 12"><path d="M2 6 L5 9 L10 3" stroke="#2D6A4F" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <div>
              <h4>{{ b.title }}</h4>
              <p>{{ b.desc }}</p>
            </div>
          </div>
        </div>

        <button class="btn-green" @click="$router.push('/submeterReclamacao')">Registar Ocorrência</button>
      </div>

      <div class="why-right image-carousel">
        <img
          v-for="(img, i) in porqueDenunciarImages"
          :key="img.src"
          :src="img.src"
          :alt="img.alt"
          :class="{ 'is-active': i === activePorqueDenunciarImage }"
        />
        <div class="carousel-dots">
          <span
            v-for="(img, i) in porqueDenunciarImages"
            :key="i"
            class="carousel-dot"
            :class="{ 'is-active': i === activePorqueDenunciarImage }"
            @click="activePorqueDenunciarImage = i"
          ></span>
        </div>
      </div>
    </section>

    <!-- COMMUNITIES SECTION -->
    <section class="why-section community-section reveal">
      <div class="why-right image-carousel">
        <img
          v-for="(img, i) in communityImages"
          :key="img.src"
          :src="img.src"
          :alt="img.alt"
          :class="{ 'is-active': i === activeCommunityImage }"
        />
        <div class="carousel-dots">
          <span
            v-for="(img, i) in communityImages"
            :key="i"
            class="carousel-dot"
            :class="{ 'is-active': i === activeCommunityImage }"
            @click="activeCommunityImage = i"
          ></span>
        </div>
        
      </div>

      <div class="why-left community-text">
        <h2>Engajamento Comunitário</h2>
        <div class="why-underline"></div>
        <p>A conservação da biodiversidade só é sustentável quando as comunidades que vivem dela fazem parte da solução. A Biofund financia a conservação, fortalece comunidades e promove o uso sustentável dos ecossistemas em Moçambique.</p>

        <a
          href="https://www.biofund.org.mz/o-que-fazemos/projectos-financiados-e-patrocinados/"
          target="_blank"
          rel="noopener noreferrer"
          class="community-readmore"
        >
          Ler mais
          <svg width="14" height="14" fill="none" viewBox="0 0 16 16"><path d="M3 8h10M9 4l4 4-4 4" stroke="#2D6A4F" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
      </div>
    </section>

    <!-- DOCUMENTOS E POLÍTICAS -->
    <section class="documents-section reveal">
      <div class="documents-intro">
        <h2>Documentos e Políticas</h2>
        <div class="documents-underline"></div>
        <p>Consulte os documentos oficiais que orientam a actuação do BIOFUND e o funcionamento do Mecanismo de Diálogo e Reclamação (MDR).</p>
      </div>

      <div class="documents-grid">
        <a
          v-for="doc in documents"
          :key="doc.title"
          :href="doc.link"
          target="_blank"
          rel="noopener noreferrer"
          class="document-card"
        >
          <div class="document-card-img">
            <img :src="doc.img" :alt="doc.title" loading="lazy" />
          </div>
          <div class="document-card-body">
            <h3>{{ doc.title }}</h3>
            <p>{{ doc.desc }}</p>
            <span class="document-card-link">
              Ver documento
              <svg width="14" height="14" fill="none" viewBox="0 0 16 16"><path d="M3 8h10M9 4l4 4-4 4" stroke="#2D6A4F" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
          </div>
        </a>
      </div>
    </section>

    <AppFooter />
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue'
import AppNavbar from '@/components/AppNavbar.vue'
import AppFooter from '@/components/AppFooter.vue'

import imgComunidade1 from '../../Imagem/comunidade-costeira.jpg'
import imgComunidade2 from '../../Imagem/Picture7.jpg'
import imgComunidade3 from '../../Imagem/Picture8.jpg'

import imgPorqueDenunciar1 from '../../Imagem/Porque denunciar/imagem 1.png'
import imgPorqueDenunciar2 from '../../Imagem/Porque denunciar/imagem 2.png'
import imgPorqueDenunciar3 from '../../Imagem/Porque denunciar/imagem3.png'
import imgPorqueDenunciar4 from '../../Imagem/Porque denunciar/Picture7.jpg'

import imgCodigoConduta from '../../Imagem/MDR-img/codigo de conduta.png'
import imgCodigoEtica from '../../Imagem/MDR-img/Codigo de Etica.png'
import imgManualMDR from '../../Imagem/MDR-img/MDR.png'

const benefits = [
  { title: 'Transparência', desc: 'Garantia de registo, acompanhamento e tratamento das ocorrências, permitindo ao utilizador acompanhar o estado do processo.' },
  { title: 'Acessibilidade',  desc: 'Disponibilização de canais simples e inclusivos para submissão de reclamações, sugestões, preocupações, consultas ou elogios.' },
  { title: 'Confidencialidade e Anonimato',     desc: 'Protecção da identidade e dos dados pessoais dos utilizadores, com a possibilidade de submissão anónima quando aplicável.' },
  { title: 'Resposta e Resolução',     desc: 'Compromisso com a análise, tratamento e resposta atempada das ocorrências, promovendo soluções adequadas e melhoria contínua.' },
]

const community = [
  { title: 'MozRural', desc: 'Programa de Economia Rural Sustentável que apoia infra-estruturas, formação técnica e novas fontes de rendimento para famílias que vivem junto às áreas de conservação.' },
  { title: 'PLCM', desc: 'Programa de Liderança para a Conservação de Moçambique: estágios pré-profissionais que já capacitaram mais de 100 jovens para o Sistema Nacional de Áreas de Conservação.' },
  { title: 'Áreas de Conservação Comunitária', desc: 'Rede de áreas protegidas geridas directamente pelas comunidades locais, com participação na gestão dos recursos naturais e partilha de benefícios.' },
  { title: 'MozNorte', desc: 'Projecto de resiliência rural que reforça a adaptação às alterações climáticas e a subsistência sustentável das comunidades do Norte de Moçambique.' },
]

const communityProjects = [
  'PROMOVE Biodiversidade',
  'Fundo de Apoio aos Fiscais',
  'Contrabalanços de Biodiversidade',
  'Cartão BIO',
]

const communityImages = [
  { src: imgComunidade1, alt: 'Comunidades costeiras envolvidas na conservação em Moçambique' },
  { src: imgComunidade2, alt: 'Sessão de diálogo com comunidades sobre conservação da biodiversidade' },
  { src: imgComunidade3, alt: 'Equipa do Biofund em sessão de trabalho com comunidades locais' },
]

const activeCommunityImage = ref(0)
let communityTimer = null

const porqueDenunciarImages = [
  { src: imgPorqueDenunciar1, alt: 'Sessão de diálogo com comunidades sobre o registo de ocorrências' },
  { src: imgPorqueDenunciar2, alt: 'Equipa do Biofund em acção de protecção da vida selvagem' },
  { src: imgPorqueDenunciar3, alt: 'Elefante numa área de conservação em Moçambique' },
  { src: imgPorqueDenunciar4, alt: 'Equipa do Biofund em sessão de trabalho com comunidades locais' },
]

const activePorqueDenunciarImage = ref(0)
let porqueDenunciarTimer = null

const documents = [
  {
    title: 'Manual do MDR',
    desc: 'Manual de Implementação do Mecanismo de Diálogo e Reclamação, com os procedimentos de registo e tratamento de ocorrências.',
    img: imgManualMDR,
    link: 'https://www.biofund.org.mz/wp-content/uploads/2025/12/Manual-do-Mecanismo-de-Dialogo-e-Reclamacao.pdf',
  },
  {
    title: 'Código de Ética',
    desc: 'Princípios e valores que orientam a conduta institucional do Biofund, dos seus colaboradores e parceiros.',
    img: imgCodigoEtica,
    link: 'https://www.biofund.org.mz/wp-content/uploads/2024/01/Codigo-de-Etica_BIOFUND.pdf',
  },
  {
    title: 'Código de Conduta',
    desc: 'Prevenção da Violência Baseada no Género, Exploração e Abuso Sexual e Assédio Sexual (VBG/EAS/AS).',
    img: imgCodigoConduta,
    link: 'https://www.biofund.org.mz/wp-content/uploads/2025/12/Codigo-de-Conduta-contra-VBG-EAS-AS.pdf',
  },
]

onMounted(() => {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.classList.add('visible')
        observer.unobserve(e.target)
      }
    })
  }, { threshold: 0.12 })

  document.querySelectorAll('.reveal').forEach(el => observer.observe(el))

  communityTimer = setInterval(() => {
    activeCommunityImage.value = (activeCommunityImage.value + 1) % communityImages.length
  }, 4500)

  porqueDenunciarTimer = setInterval(() => {
    activePorqueDenunciarImage.value = (activePorqueDenunciarImage.value + 1) % porqueDenunciarImages.length
  }, 4500)
})

onUnmounted(() => {
  clearInterval(communityTimer)
  clearInterval(porqueDenunciarTimer)
})
</script>

<style scoped>
.home { background: var(--white); overflow-x: hidden; }

/* ── HERO ──────────────────────────────────────────────────── */
.hero {
  position: relative;
  height: 620px;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  overflow: hidden;
}

.hero-bg {
  position: absolute;
  inset: 0;
  background-image: url('../../Imagem/imagem\ background.jpg');
  background-size: cover;
  background-position: center 40%;
  filter: brightness(0.6);
}

.hero-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to bottom, rgba(10,25,15,0.35) 0%, rgba(10,25,15,0.55) 100%);
}

.hero-content {
  position: relative;
  z-index: 2;
  max-width: 680px;
  padding: 0 24px;
  animation: fadeUp 0.9s ease both;
}

@keyframes fadeUp {
  from { opacity: 0; transform: translateY(28px); }
  to   { opacity: 1; transform: translateY(0); }
}

.hero-badge {
  display: inline-block;
  background: rgba(255,255,255,0.15);
  border: 1px solid rgba(255,255,255,0.35);
  backdrop-filter: blur(8px);
  color: #fff;
  font-size: 11.5px;
  font-weight: 500;
  letter-spacing: 0.5px;
  padding: 5px 16px;
  border-radius: 99px;
  margin-bottom: 22px;
}

.hero h1 {
  font-size: clamp(38px, 6vw, 58px);
  font-weight: 800;
  line-height: 1.1;
  color: #fff;
  margin-bottom: 18px;
}

.hero h1 span { color: var(--amber); display: block; }

.hero p {
  color: rgba(255,255,255,0.88);
  font-size: 15px;
  line-height: 1.7;
  margin-bottom: 34px;
  max-width: 540px;
  margin-left: auto;
  margin-right: auto;
  text-align: justify;
  hyphens: auto;
}

.hero-buttons {
  display: flex;
  gap: 12px;
  justify-content: center;
  flex-wrap: wrap;
}

.btn-primary {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: var(--green-mid);
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 13px 26px;
  font-family: 'Poppins', sans-serif;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s, transform 0.15s;
}

.btn-primary:hover { background: var(--green-dark); transform: translateY(-1px); }

.btn-outline-white {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: transparent;
  color: #fff;
  border: 2px solid rgba(255,255,255,0.7);
  border-radius: 8px;
  padding: 11px 26px;
  font-family: 'Poppins', sans-serif;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s, border-color 0.2s;
}

.btn-outline-white:hover {
  background: rgba(255,255,255,0.12);
  border-color: #fff;
}

/* ── FEATURE CARDS ─────────────────────────────────────────── */
.features-wrap {
  position: relative;
  z-index: 10;
  margin-top: -72px;
  padding: 0 64px;
  max-width: 1100px;
  margin-left: auto;
  margin-right: auto;
}

.features-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
  border-radius: 14px;
  overflow: visible;
}

.feature-card {
  background: var(--white);
  padding: 32px 28px 28px;
  border-radius: 12px;
  border: 1px solid var(--card-border);
  box-shadow: 0 4px 20px rgba(0,0,0,0.07);
  transition: background 0.2s, transform 0.2s;
}

.feature-card:hover { transform: translateY(-3px); background: var(--offwhite); }
.feature-card--tinted { background: #F0FAF4; }

.feature-icon {
  width: 44px;
  height: 44px;
  margin-bottom: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.feature-card h3 {
  font-size: 15.5px;
  font-weight: 700;
  margin-bottom: 10px;
  color: var(--text-dark);
}

.feature-card p {
  font-size: 13px;
  color: var(--text-gray);
  line-height: 1.65;
  margin-bottom: 20px;
  text-align: justify;
  hyphens: auto;
}

.feature-link {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  color: var(--green-mid);
  font-size: 13px;
  font-weight: 600;
  text-decoration: none;
  transition: gap 0.2s;
}

.feature-link:hover { gap: 10px; }

/* ── WHY SECTION ───────────────────────────────────────────── */
.why-section {
  padding: 100px 64px;
  max-width: 1100px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 72px;
  align-items: center;
}

.community-section {
  margin-top: -64px;
  padding-top: 36px;
}

.why-section h2 {
  font-size: 36px;
  font-weight: 800;
  margin-bottom: 10px;
  color: var(--text-dark);
}

.why-underline {
  width: 44px;
  height: 4px;
  background: var(--amber);
  border-radius: 2px;
  margin-bottom: 22px;
}

.why-left > p {
  font-size: 14px;
  color: var(--text-gray);
  line-height: 1.75;
  margin-bottom: 32px;
  max-width: 420px;
}

.benefits-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
  margin-bottom: 36px;
}

.benefit-item { display: flex; gap: 10px; align-items: flex-start; }

.benefit-check {
  width: 22px; height: 22px;
  border-radius: 50%;
  background: var(--green-pale);
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
  margin-top: 1px;
}

.benefit-check svg { width: 12px; height: 12px; }

.benefit-item h4 {
  font-size: 13.5px;
  font-weight: 700;
  margin-bottom: 4px;
  color: var(--text-dark);
}

.benefit-item p {
  font-size: 12px;
  color: var(--text-gray);
  line-height: 1.55;
}

.btn-green {
  background: var(--green-mid);
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 13px 26px;
  font-family: 'Poppins', sans-serif;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-green:hover { background: var(--green-dark); }

.why-right {
  position: relative;
  border-radius: 16px;
  overflow: hidden;
  height: 420px;
}

.why-right img { width: 100%; height: 100%; object-fit: cover; }

.why-stat {
  position: absolute;
  bottom: 24px; left: 24px;
  background: #fff;
  border-radius: 12px;
  padding: 14px 18px;
  box-shadow: 0 4px 24px rgba(0,0,0,0.14);
  display: flex;
  align-items: center;
  gap: 10px;
}

.why-stat-icon {
  width: 36px; height: 36px;
  background: var(--green-pale);
  border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
}

.why-stat-num { font-size: 17px; font-weight: 800; color: var(--green-mid); }

.why-stat-label {
  font-size: 11px;
  color: var(--text-gray);
  max-width: 140px;
  line-height: 1.4;
}

/* ── IMAGE CAROUSEL ────────────────────────────────────────── */
.image-carousel img {
  position: absolute;
  inset: 0;
  opacity: 0;
  transition: opacity 1.2s ease;
}

.image-carousel img.is-active {
  opacity: 1;
}

.carousel-dots {
  position: absolute;
  top: 18px;
  right: 18px;
  z-index: 2;
  display: flex;
  gap: 8px;
}

.carousel-dot {
  width: 9px;
  height: 9px;
  border-radius: 50%;
  background: rgba(255,255,255,0.5);
  border: 1px solid rgba(255,255,255,0.6);
  cursor: pointer;
  transition: background 0.2s, transform 0.2s;
}

.carousel-dot.is-active {
  background: #fff;
  transform: scale(1.25);
}

/* ── COMMUNITY TEXT & READ MORE ────────────────────────────── */
.community-text > p {
  font-size: 15px;
  line-height: 1.85;
  color: var(--text-gray);
  max-width: 440px;
  margin-bottom: 36px;
}

.community-readmore {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  color: var(--green-mid);
  font-size: 14px;
  font-weight: 700;
  text-decoration: none;
  border-bottom: 2px solid var(--green-mid);
  padding-bottom: 2px;
  transition: gap 0.2s, color 0.2s, border-color 0.2s;
}

.community-readmore:hover {
  gap: 13px;
  color: var(--green-dark);
  border-color: var(--green-dark);
}

/* ── DOCUMENTOS E POLÍTICAS ─────────────────────────────────── */
.documents-section {
  position: relative;
  padding: 90px 64px 110px;
  max-width: 1100px;
  margin: 0 auto;
}

.documents-intro {
  text-align: center;
  max-width: 620px;
  margin: 0 auto 48px;
}

.documents-intro h2 {
  font-size: 36px;
  font-weight: 800;
  margin-bottom: 10px;
  color: var(--text-dark);
}

.documents-underline {
  width: 44px;
  height: 4px;
  background: var(--amber);
  border-radius: 2px;
  margin: 0 auto 22px;
}

.documents-intro p {
  font-size: 14px;
  color: var(--text-gray);
  line-height: 1.75;
  text-align: justify;
  hyphens: auto;
}

.documents-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
}

.document-card {
  display: flex;
  flex-direction: column;
  background: var(--white);
  border: 1px solid var(--card-border);
  border-radius: 14px;
  overflow: hidden;
  box-shadow: 0 4px 20px rgba(0,0,0,0.07);
  text-decoration: none;
  transition: transform 0.2s, box-shadow 0.2s, background 0.2s;
}

.document-card:hover {
  transform: translateY(-4px);
  background: var(--offwhite);
  box-shadow: 0 10px 28px rgba(27,67,50,0.14);
}

.document-card-img {
  aspect-ratio: 4 / 3;
  overflow: hidden;
  background: var(--offwhite);
}

.document-card-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: top;
  transition: transform 0.5s ease;
}

.document-card:hover .document-card-img img {
  transform: scale(1.05);
}

.document-card-body {
  padding: 22px 24px 26px;
}

.document-card-body h3 {
  font-size: 16px;
  font-weight: 700;
  margin-bottom: 8px;
  color: var(--text-dark);
}

.document-card-body p {
  font-size: 13px;
  color: var(--text-gray);
  line-height: 1.6;
  margin-bottom: 16px;
  text-align: justify;
  hyphens: auto;
}

.document-card-link {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  color: var(--green-mid);
  font-size: 13px;
  font-weight: 600;
  transition: gap 0.2s;
}

.document-card:hover .document-card-link { gap: 10px; }

@media (max-width: 820px) {
  .documents-section { padding: 60px 24px 80px; }
  .documents-grid { grid-template-columns: 1fr; }
}

/* ── SCROLL REVEAL ─────────────────────────────────────────── */
.reveal {
  opacity: 0;
  transform: translateY(30px);
  transition: opacity 0.7s ease, transform 0.7s ease;
}

.reveal.visible {
  opacity: 1;
  transform: translateY(0);
}

/* ── RESPONSIVE ─────────────────────────────────────────────── */
@media (max-width: 1024px) {
  .features-wrap { padding: 0 32px; }
  .why-section { padding: 80px 32px; gap: 48px; }
}

@media (max-width: 900px) {
  .why-section {
    grid-template-columns: 1fr;
  }

  .why-right { height: 320px; }

  .community-section { margin-top: 0; padding-top: 60px; }
  .community-section .why-right { order: -1; }
}

@media (max-width: 768px) {
  .hero { height: auto; min-height: 520px; padding: 96px 0 64px; }
  .hero-content { padding: 0 20px; }

  .features-wrap { margin-top: -48px; padding: 0 20px; }
  .features-grid { grid-template-columns: 1fr; gap: 14px; }

  .why-section { padding: 56px 20px; gap: 32px; }
  .why-section h2, .documents-intro h2 { font-size: 28px; }

  .benefits-grid { grid-template-columns: 1fr; gap: 16px; }

  .documents-section { padding: 56px 20px 64px; }
}

@media (max-width: 480px) {
  .hero-buttons { flex-direction: column; width: 100%; }
  .hero-buttons button { width: 100%; justify-content: center; }

  .why-right { height: 240px; }
}
</style>
