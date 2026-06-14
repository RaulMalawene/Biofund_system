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

        <button class="btn-green" @click="$router.push('/submeterReclamacao')">Participar no MDR</button>
      </div>

      <div class="why-right">
        <img src="../../Imagem/LATERALIMAGEM.jpg" alt="Guarda florestal em Moçambique" />
        <div class="why-stat">
          <div class="why-stat-icon">
            <svg width="18" height="18" fill="none" viewBox="0 0 18 18">
              <path d="M9 2 L11.5 7 L17 7.5 L13 11.5 L14.5 17 L9 14 L3.5 17 L5 11.5 L1 7.5 L6.5 7 Z" fill="#2D6A4F"/>
            </svg>
          </div>
          <div>
            <div class="why-stat-num">+1.2k</div>
            <div class="why-stat-label">Casos resolvidos</div>
          </div>
        </div>
      </div>
    </section>

    <!-- COMMUNITIES SECTION -->
    <section class="why-section community-section reveal">
      <div class="why-right community-carousel">
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
        <div class="why-stat">
          <div class="why-stat-icon">
            <svg width="18" height="18" fill="none" viewBox="0 0 18 18">
              <circle cx="6.5" cy="5.5" r="2.2" stroke="#2D6A4F" stroke-width="1.4"/>
              <path d="M2 14c0-2.3 2-3.8 4.5-3.8s4.5 1.5 4.5 3.8" stroke="#2D6A4F" stroke-width="1.4" stroke-linecap="round"/>
              <circle cx="12.5" cy="6" r="1.8" stroke="#2D6A4F" stroke-width="1.4"/>
              <path d="M11 10.5c1.8.3 3.3 1.6 3.5 3.5" stroke="#2D6A4F" stroke-width="1.4" stroke-linecap="round"/>
            </svg>
          </div>
          <div>
            <div class="why-stat-num">5 Áreas</div>
            <div class="why-stat-label">de conservação comunitária geridas pelas populações locais</div>
          </div>
        </div>
      </div>

      <div class="why-left">
        <h2>Ao Lado das Comunidades</h2>
        <div class="why-underline"></div>
        <p>A conservação da biodiversidade só é sustentável quando as comunidades que vivem dela fazem parte da solução. O Biofund financia a conservação, fortalece comunidades e promove o uso sustentável dos ecossistemas em Moçambique.</p>

        <div class="benefits-grid">
          <div class="benefit-item" v-for="c in community" :key="c.title">
            <div class="benefit-check">
              <svg fill="none" viewBox="0 0 12 12"><path d="M2 6 L5 9 L10 3" stroke="#2D6A4F" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <div>
              <h4>{{ c.title }}</h4>
              <p>{{ c.desc }}</p>
            </div>
          </div>
        </div>

        <div class="community-projects">
          <span class="community-projects-label">Também fazem parte dos nossos projectos</span>
          <div class="project-pills">
            <span class="project-pill" v-for="proj in communityProjects" :key="proj">
              <span class="project-pill-dot"></span>{{ proj }}
            </span>
          </div>
        </div>

       <!--- <button class="btn-green" @click="$router.push('/submeterReclamacao')">Submeter Ocorrência</button> -->
      </div>
    </section>

    <!-- PATRIMÓNIO / HERITAGE MOSAIC -->
    <section class="patrimonio-section reveal">
      <div class="patrimonio-grid">
        <!-- Intro card: title + description live inside the whale-shark photo -->
        <article class="patrimonio-card patrimonio-card--intro">
          <img :src="patrimonioIntro.img" :alt="patrimonioIntro.alt" />
          <div class="patrimonio-shade patrimonio-shade--intro"></div>
          <div class="patrimonio-intro-content">

            <h2>Património a Proteger</h2>
            <div class="patrimonio-underline"></div>
            <p>Conheça alguns dos ecossistemas e espécies emblemáticas que o Biofund ajuda a salvaguardar, com o apoio da vigilância participativa das comunidades.</p>
            <div class="patrimonio-stat">
            </div>
          </div>
        </article>

        <!-- Species cards: name + location live inside each photo -->
        <article
          v-for="p in patrimonio"
          :key="p.name"
          class="patrimonio-card"
          :class="`patrimonio-card--${p.size}`"
        >
          <img :src="p.img" :alt="`${p.name} — ${p.location}`" loading="lazy" />
          <div class="patrimonio-shade"></div>
          <div class="patrimonio-caption">
            
            <div>
              <div class="patrimonio-caption-title">{{ p.name }}</div>
              <div class="patrimonio-caption-loc">{{ p.location }}</div>
            </div>
          </div>
        </article>
      </div>
    </section>

    <AppFooter />
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue'
import AppNavbar from '@/components/AppNavbar.vue'
import AppFooter from '@/components/AppFooter.vue'

import imgGirafasMaputo  from '../../Imagem/Back/Diogo-Marecos-Duarte-BIOFUND-2017-2.jpg'
import imgZebrasLimpopo  from '../../Imagem/Back/Jose-G-Gomes-Pepe-42.jpg'
import imgGirafasFloresta from '../../Imagem/Back/Jose-G-Gomes-Pepe-6.jpg'
import imgZebrasMaputo   from '../../Imagem/Back/Jose-Gomes-Pepe-39-PNAM.jpg'
import imgLibelula       from '../../Imagem/Back/REM.jpg'
import imgNhumbi         from '../../Imagem/Back/REM3.jpg'
import imgTubaraoBaleia  from '../../Imagem/Back/Rafael-Fernandez-Caballero.jpg'

import imgComunidade1 from '../../Imagem/comunidade-costeira.jpg'
import imgComunidade2 from '../../Imagem/Picture7.jpg'
import imgComunidade3 from '../../Imagem/Picture8.jpg'

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

const patrimonioIntro = {
  img: imgTubaraoBaleia,
  alt: 'Tubarão-baleia na Costa de Inhambane, Moçambique',
}

const patrimonio = [
  { name: 'Zebras-das-Planícies',     location: 'Parque Nacional do Limpopo',          img: imgZebrasLimpopo,    size: 'normal'   },
  { name: 'Família de Girafas',       location: 'Parque Nacional do Limpopo',          img: imgGirafasFloresta,  size: 'normal'   },
  { name: 'Girafas',                  location: 'Reserva Especial de Maputo',          img: imgGirafasMaputo,    size: 'featured' },
  { name: 'Zebras',                   location: 'Parque Nacional de Maputo',           img: imgZebrasMaputo,     size: 'normal'   },
  { name: 'Libélula',                 location: 'Zonas Húmidas do Delta do Zambeze',   img: imgLibelula,         size: 'normal'   },
  { name: 'Nhumbi (Gnu-Azul)',        location: 'Parque Nacional de Banhine',          img: imgNhumbi,           size: 'featured' },
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
})

onUnmounted(() => {
  clearInterval(communityTimer)
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

/* ── COMMUNITY CAROUSEL ────────────────────────────────────── */
.community-carousel img {
  position: absolute;
  inset: 0;
  opacity: 0;
  transition: opacity 1.2s ease;
}

.community-carousel img.is-active {
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

/* ── COMMUNITY PROJECT PILLS ───────────────────────────────── */
.community-projects {
  margin-bottom: 32px;
}

.community-projects-label {
  display: block;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.6px;
  text-transform: uppercase;
  color: var(--green-mid);
  margin-bottom: 12px;
}

.project-pills {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.project-pill {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  font-size: 12.5px;
  font-weight: 600;
  color: var(--text-dark);
  background: var(--green-pale);
  border: 1px solid transparent;
  border-radius: 99px;
  padding: 7px 14px;
  transition: background 0.2s, border-color 0.2s, transform 0.2s;
}

.project-pill:hover {
  background: #fff;
  border-color: var(--green-mid);
  transform: translateY(-2px);
}

.project-pill-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: var(--amber);
  flex-shrink: 0;
}

/* ── PATRIMÓNIO / HERITAGE MOSAIC ──────────────────────────── */
.patrimonio-section {
  position: relative;
  padding: 90px 0 110px;
  background: linear-gradient(180deg, var(--offwhite) 0%, var(--green-pale) 160%);
  overflow: hidden;
}

.patrimonio-section::before,
.patrimonio-section::after {
  content: '';
  position: absolute;
  border-radius: 50%;
  filter: blur(90px);
  pointer-events: none;
  z-index: 0;
}

.patrimonio-section::before {
  width: 380px; height: 380px;
  background: var(--green-light);
  opacity: 0.18;
  top: -140px; left: -120px;
}

.patrimonio-section::after {
  width: 440px; height: 440px;
  background: var(--amber);
  opacity: 0.12;
  bottom: -180px; right: -140px;
}

.patrimonio-grid {
  position: relative;
  z-index: 1;
  max-width: 1100px;
  margin: 0 auto;
  padding: 0 24px;
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 20px;
}

.patrimonio-card {
  position: relative;
  border-radius: 20px;
  overflow: hidden;
  aspect-ratio: 2.3 / 1;
  box-shadow: 0 4px 24px rgba(27,67,50,0.08);
}

.patrimonio-card--intro,
.patrimonio-card--featured {
  grid-column: 1 / -1;
}

.patrimonio-card img {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.7s ease;
}

.patrimonio-card:hover img {
  transform: scale(1.06);
}

.patrimonio-shade {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(10,25,15,0.82) 0%, rgba(10,25,15,0.15) 55%, transparent 80%);
}

.patrimonio-shade--intro {
  background: linear-gradient(100deg, rgba(10,25,15,0.92) 0%, rgba(10,25,15,0.6) 40%, rgba(27,67,50,0.15) 75%, rgba(45,106,79,0.05) 100%);
}

/* Intro card content */
.patrimonio-intro-content {
  position: absolute;
  inset: 0;
  z-index: 2;
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 48px;
  max-width: 560px;
  color: #fff;
}

.patrimonio-eyebrow {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  width: fit-content;
  background: rgba(255,255,255,0.12);
  border: 1px solid rgba(255,255,255,0.3);
  backdrop-filter: blur(8px);
  font-size: 12px;
  font-weight: 500;
  letter-spacing: 0.5px;
  padding: 6px 14px;
  border-radius: 99px;
  margin-bottom: 18px;
}

.patrimonio-intro-content h2 {
  font-size: clamp(28px, 4vw, 38px);
  font-weight: 800;
  margin-bottom: 10px;
}

.patrimonio-underline {
  width: 44px; height: 4px;
  background: var(--amber);
  border-radius: 2px;
  margin-bottom: 18px;
}

.patrimonio-intro-content p {
  font-size: 14px;
  line-height: 1.7;
  color: rgba(255,255,255,0.88);
  margin-bottom: 26px;
  max-width: 440px;
}

.patrimonio-stat {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  width: fit-content;
  background: #fff;
  border-radius: 12px;
  padding: 12px 18px;
  box-shadow: 0 4px 24px rgba(0,0,0,0.18);
}

.patrimonio-stat-icon {
  width: 36px; height: 36px;
  background: var(--green-pale);
  border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}

.patrimonio-stat-num { font-size: 16px; font-weight: 800; color: var(--green-mid); }

.patrimonio-stat-label {
  font-size: 11px;
  color: var(--text-gray);
  max-width: 150px;
  line-height: 1.4;
}

/* Species cards: caption lives directly on the photo */
.patrimonio-caption {
  position: absolute;
  left: 0; right: 0; bottom: 0;
  z-index: 2;
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 22px 24px;
}

.patrimonio-caption-icon {
  width: 38px; height: 38px;
  background: rgba(255,255,255,0.15);
  border: 1px solid rgba(255,255,255,0.3);
  backdrop-filter: blur(6px);
  border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}

.patrimonio-caption-title {
  color: #fff;
  font-weight: 800;
  font-size: 16px;
  line-height: 1.3;
  text-align: left;
}

.patrimonio-caption-loc {
  color: rgba(255,255,255,0.78);
  font-size: 12px;
  margin-top: 2px;
  text-align: left;
}

.patrimonio-card--featured .patrimonio-caption-title { font-size: 21px; }
.patrimonio-card--featured .patrimonio-caption-icon { width: 44px; height: 44px; }

@media (max-width: 720px) {
  .patrimonio-grid { grid-template-columns: 1fr; gap: 16px; }

  .patrimonio-card--intro {
    aspect-ratio: auto;
    display: flex;
    flex-direction: column;
  }

  .patrimonio-card--intro img {
    position: static;
    width: 100%;
    height: auto;
    aspect-ratio: 16 / 9;
  }

  .patrimonio-shade--intro { display: none; }

  .patrimonio-intro-content {
    position: static;
    max-width: none;
    padding: 28px 22px;
    background: var(--green-dark);
  }

  .patrimonio-intro-content h2 { font-size: 26px; }
  .patrimonio-intro-content p { max-width: none; }

  .patrimonio-card--featured .patrimonio-caption-title { font-size: 18px; }
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
</style>
