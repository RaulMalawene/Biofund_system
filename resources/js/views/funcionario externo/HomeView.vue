<template>
  <div class="home">
    <AppNavbar variant="fixed" />

    <!-- HERO -->
    <section class="hero">
      <div class="hero-bg"></div>
      <div class="hero-overlay"></div>
      <div class="hero-content">
        <div class="hero-badge">MDR-Mecanismo de Dialogo e Reclamacao</div>
        <h1>
          Registe a sua
          <span>reclamação</span>
        </h1>
        <p>Fortalecer a transparência e a participação das partes interessadas nas iniciativas de Conservação da Biodiversidade em MOÇAMBIQUE</p>
        <div class="hero-buttons">
          <button class="btn-primary" @click="$router.push('/submeterReclamacao')">
            Submeter Reclamação
            <svg width="14" height="14" fill="none" viewBox="0 0 16 16"><path d="M3 8h10M9 4l4 4-4 4" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </button>
          <button class="btn-outline-white" @click="$router.push('/visualizarReclamacao')">Consultar Reclamação</button>
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
          <p>Promovendo a inclusão, participação e engajamento das partes interessadas</p>
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
        <h2>Porquê denunciar?</h2>
        <div class="why-underline"></div>
        <p>A sua participação é fundamental para a preservação do nosso património. Cada reporte ajuda a direcionar esforços de conservação onde eles são mais necessários.</p>

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

        <button class="btn-green" @click="$router.push('/submeterReclamacao')">Fazer Minha Parte</button>
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
            <div class="why-stat-label">Casos resolvidos com sucesso no último ano em todo Moçambique.</div>
          </div>
        </div>
      </div>
    </section>

    <!-- SPECIES SECTION -->
    <section class="species-section reveal">
      <h2>Património a Proteger</h2>
      <div class="species-underline"></div>
      <p>Conheça algumas das espécies emblemáticas que o Biofund ajuda a salvaguardar através da vigilância participativa.</p>

      <div class="species-grid">
        <div class="species-card" v-for="s in species" :key="s.name">
          <img :src="s.img" :alt="s.name" />
          <div class="species-overlay"></div>
          <div class="species-info">
            <h4>{{ s.name }}</h4>
            <span>{{ s.location }}</span>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA BANNER -->
    <section class="cta-section reveal">
      <div class="cta-banner">
        <svg class="cta-deco" width="280" height="340" viewBox="0 0 280 340" fill="none">
          <ellipse cx="140" cy="130" rx="90" ry="110" fill="white"/>
          <ellipse cx="100" cy="90" rx="60" ry="80" fill="white"/>
          <ellipse cx="180" cy="100" rx="65" ry="75" fill="white"/>
          <rect x="125" y="220" width="30" height="110" fill="white"/>
        </svg>
        <h2>Pronto para colaborar?</h2>
        <p>Cada denúncia é um passo para um Moçambique mais verde e sustentável. Não ignore o dano ambiental, reporte-o hoje.</p>
        <div class="cta-buttons">
          <button class="btn-amber" @click="$router.push('/submeterReclamacao')">Submeter Agora</button>
          <button class="btn-outline-cta">Como Funciona</button>
        </div>
      </div>
    </section>

    <AppFooter />
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import AppNavbar from '@/components/AppNavbar.vue'
import AppFooter from '@/components/AppFooter.vue'

import imgNiassa    from '../../Imagem/reserva do Niassa.jpg'
import imgDugongo   from '../../Imagem/dugongo.jpg'
import imgGorongosa from '../../Imagem/parque de gorongosa.jpg'
import imgLimpopo   from '../../Imagem/parque de limpopo.jpg'

const benefits = [
  { title: 'Transparência', desc: 'Processos claros e rastreáveis desde a submissão até à resolução final.' },
  { title: 'Impacto Real',  desc: 'Dados que geram ações imediatas no terreno por equipas especializadas.' },
  { title: 'Anonimato',     desc: 'Possibilidade de reportar sem revelar a identidade, garantindo segurança.' },
  { title: 'Monitoria',     desc: 'Acompanhamento estatístico público da saúde ambiental nacional.' },
]

const species = [
  { name: 'Elefante Africano', location: 'Reserva do Niassa',   img: imgNiassa    },
  { name: 'Dugongo',           location: 'Bazaruto',            img: imgDugongo   },
  { name: 'Leão',              location: 'Parque da Gorongosa', img: imgGorongosa },
  { name: 'Hipopótamo',        location: 'Parque do Limpopo',   img: imgLimpopo   },
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

/* ── SPECIES SECTION ───────────────────────────────────────── */
.species-section {
  padding: 80px 64px;
  background: var(--offwhite);
  text-align: center;
}

.species-section h2 { font-size: 32px; font-weight: 800; margin-bottom: 8px; }

.species-underline {
  width: 44px; height: 4px;
  background: var(--amber);
  border-radius: 2px;
  margin: 0 auto 16px;
}

.species-section > p {
  color: var(--text-gray);
  font-size: 14px;
  margin-bottom: 44px;
}

.species-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
  max-width: 1100px;
  margin: 0 auto;
}

.species-card {
  position: relative;
  border-radius: 12px;
  overflow: hidden;
  height: 220px;
  cursor: pointer;
}

.species-card img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s; }
.species-card:hover img { transform: scale(1.06); }

.species-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(0,0,0,0.65) 0%, transparent 55%);
}

.species-info {
  position: absolute;
  bottom: 14px; left: 14px;
  color: #fff;
  text-align: left;
}

.species-info h4 { font-size: 15px; font-weight: 700; margin-bottom: 2px; }

.species-info span {
  font-size: 11px;
  font-weight: 400;
  color: rgba(255,255,255,0.75);
  letter-spacing: 0.3px;
}

/* ── CTA BANNER ────────────────────────────────────────────── */
.cta-section { padding: 64px; }

.cta-banner {
  max-width: 1100px;
  margin: 0 auto;
  background: linear-gradient(135deg, #1B4332 0%, #2D6A4F 55%, #1B5E40 100%);
  border-radius: 20px;
  padding: 72px 56px;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.cta-banner::before {
  content: '';
  position: absolute;
  top: -60px; right: -60px;
  width: 320px; height: 320px;
  border-radius: 50%;
  background: rgba(255,255,255,0.04);
  pointer-events: none;
}

.cta-banner::after {
  content: '';
  position: absolute;
  bottom: -80px; left: -40px;
  width: 260px; height: 260px;
  border-radius: 50%;
  background: rgba(255,255,255,0.04);
  pointer-events: none;
}

.cta-deco {
  position: absolute;
  bottom: 0; right: 40px;
  opacity: 0.07;
  pointer-events: none;
}

.cta-banner h2 { font-size: 38px; font-weight: 800; color: #fff; margin-bottom: 18px; }

.cta-banner p {
  color: rgba(255,255,255,0.82);
  font-size: 15px;
  line-height: 1.7;
  max-width: 500px;
  margin: 0 auto 36px;
}

.cta-buttons { display: flex; gap: 12px; justify-content: center; }

.btn-amber {
  background: var(--amber);
  color: #fff;
  border: none;
  border-radius: 8px;
  padding: 13px 28px;
  font-family: 'Poppins', sans-serif;
  font-size: 14px;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-amber:hover { background: var(--amber-dark); }

.btn-outline-cta {
  background: transparent;
  color: #fff;
  border: 2px solid rgba(255,255,255,0.6);
  border-radius: 8px;
  padding: 11px 28px;
  font-family: 'Poppins', sans-serif;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: border-color 0.2s, background 0.2s;
}

.btn-outline-cta:hover { border-color: #fff; background: rgba(255,255,255,0.08); }

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
