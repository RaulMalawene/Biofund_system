<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BioFund – Registe a sua reclamação ambiental</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/3.4.21/vue.global.prod.min.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --green-dark:   #1B4332;
      --green-mid:    #2D6A4F;
      --green-light:  #52B788;
      --green-pale:   #D8F3DC;
      --amber:        #F4A52A;
      --amber-dark:   #D4880D;
      --white:        #FFFFFF;
      --offwhite:     #F8FAF8;
      --text-dark:    #1A1A1A;
      --text-gray:    #555B5A;
      --text-light:   #888E8C;
      --card-border:  #E4EDE6;
    }

    html { scroll-behavior: smooth; }

    body {
      font-family: 'Poppins', sans-serif;
      color: var(--text-dark);
      background: var(--white);
      overflow-x: hidden;
    }

    /* ── NAVBAR ─────────────────────────────────────────────── */
    nav {
      position: fixed;
      top: 0; left: 0; right: 0;
      z-index: 100;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 48px;
      height: 60px;
      gap: 0;
      background: var(--white);
      border-bottom: 1px solid #E4EDE6;
    }

    .nav-logo {
      display: flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
      color: var(--text-dark);
      font-weight: 700;
      font-size: 17px;
      letter-spacing: -0.3px;
    }

    .nav-logo svg { width: 28px; height: 28px; }

    .nav-links {
      display: flex;
      align-items: center;
      gap: 28px;
      margin-left: 32px;
    }

    .nav-links a {
      text-decoration: none;
      color: var(--text-gray);
      font-size: 13.5px;
      font-weight: 500;
      transition: color 0.2s;
    }
    .nav-links a:hover { color: var(--green-mid); }

    .btn-restricted {
      background: transparent;
      color: var(--green-mid);
      border: 1.5px solid var(--green-mid);
      border-radius: 6px;
      padding: 7px 18px;
      font-family: 'Poppins', sans-serif;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.2s, color 0.2s;
    }
    .btn-restricted:hover { background: var(--green-mid); color: var(--white); }

    /* ── HERO ────────────────────────────────────────────────── */
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
      background-image: url('https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?w=1600&q=80');
      background-size: cover;
      background-position: center 40%;
      filter: brightness(0.6);
    }

    .hero-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(to bottom,
        rgba(10,25,15,0.35) 0%,
        rgba(10,25,15,0.55) 100%
      );
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
      color: var(--white);
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
      color: var(--white);
      margin-bottom: 18px;
    }

    .hero h1 span {
      color: var(--amber);
      display: block;
    }

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
      color: var(--white);
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
      color: var(--white);
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
      border-color: var(--white);
    }

    /* ── FEATURE CARDS (overlap hero) ───────────────────────── */
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
    .feature-card:hover { transform: translateY(-3px); }
    .feature-card:hover { background: var(--offwhite); }

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

    /* ── WHY SECTION ─────────────────────────────────────────── */
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

    .why-section > .why-left > p {
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
      color: var(--white);
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

    /* Right side image */
    .why-right {
      position: relative;
      border-radius: 16px;
      overflow: hidden;
      height: 420px;
    }

    .why-right img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .why-stat {
      position: absolute;
      bottom: 24px;
      left: 24px;
      background: var(--white);
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

    .why-stat-num {
      font-size: 17px;
      font-weight: 800;
      color: var(--green-mid);
    }
    .why-stat-label {
      font-size: 11px;
      color: var(--text-gray);
      max-width: 140px;
      line-height: 1.4;
    }

    /* ── SPECIES SECTION ─────────────────────────────────────── */
    .species-section {
      padding: 80px 64px;
      background: var(--offwhite);
      text-align: center;
    }

    .species-section h2 {
      font-size: 32px;
      font-weight: 800;
      margin-bottom: 8px;
    }

    .species-underline {
      width: 44px;
      height: 4px;
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

    .species-card img {
      width: 100%; height: 100%;
      object-fit: cover;
      transition: transform 0.4s;
    }
    .species-card:hover img { transform: scale(1.06); }

    .species-overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(to top, rgba(0,0,0,0.65) 0%, transparent 55%);
    }

    .species-info {
      position: absolute;
      bottom: 14px;
      left: 14px;
      color: var(--white);
      text-align: left;
    }

    .species-info h4 {
      font-size: 15px;
      font-weight: 700;
      margin-bottom: 2px;
    }

    .species-info span {
      font-size: 11px;
      font-weight: 400;
      color: rgba(255,255,255,0.75);
      letter-spacing: 0.3px;
    }

    /* ── CTA BANNER ──────────────────────────────────────────── */
    .cta-section {
      padding: 64px;
    }

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

    /* decorative tree */
    .cta-deco {
      position: absolute;
      bottom: 0; right: 40px;
      opacity: 0.07;
      pointer-events: none;
    }

    .cta-banner h2 {
      font-size: 38px;
      font-weight: 800;
      color: var(--white);
      margin-bottom: 18px;
    }

    .cta-banner p {
      color: rgba(255,255,255,0.82);
      font-size: 15px;
      line-height: 1.7;
      max-width: 500px;
      margin: 0 auto 36px;
    }

    .cta-buttons {
      display: flex;
      gap: 12px;
      justify-content: center;
    }

    .btn-amber {
      background: var(--amber);
      color: var(--white);
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
      color: var(--white);
      border: 2px solid rgba(255,255,255,0.6);
      border-radius: 8px;
      padding: 11px 28px;
      font-family: 'Poppins', sans-serif;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: border-color 0.2s, background 0.2s;
    }
    .btn-outline-cta:hover {
      border-color: var(--white);
      background: rgba(255,255,255,0.08);
    }

    /* ── FOOTER ──────────────────────────────────────────────── */
    footer {
      background: var(--white);
      border-top: 1px solid #E4EDE6;
      padding: 56px 64px 32px;
    }

    .footer-grid {
      display: grid;
      grid-template-columns: 1.6fr 1fr 1fr 1fr;
      gap: 40px;
      max-width: 1100px;
      margin: 0 auto 40px;
    }

    .footer-brand .nav-logo { color: var(--text-dark); margin-bottom: 14px; display: inline-flex; }

    .footer-brand p {
      font-size: 12.5px;
      color: var(--text-gray);
      line-height: 1.65;
      max-width: 220px;
    }

    .footer-col h5 {
      font-size: 13px;
      font-weight: 700;
      color: var(--text-dark);
      margin-bottom: 16px;
    }

    .footer-col a {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 12.5px;
      color: var(--text-gray);
      text-decoration: none;
      margin-bottom: 10px;
      transition: color 0.2s;
    }
    .footer-col a:hover { color: var(--green-mid); }

    .social-links {
      display: flex;
      gap: 14px;
    }

    .social-link {
      width: 34px; height: 34px;
      border: 1px solid #D0DDD3;
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      text-decoration: none;
      color: var(--text-gray);
      transition: border-color 0.2s, color 0.2s;
    }
    .social-link:hover { border-color: var(--green-mid); color: var(--green-mid); }

    .footer-bottom {
      border-top: 1px solid #E4EDE6;
      padding-top: 24px;
      max-width: 1100px;
      margin: 0 auto;
      text-align: center;
      font-size: 12px;
      color: var(--text-light);
    }

    /* scroll reveal */
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
</head>
<body>
<div id="app">

  <!-- NAVBAR -->
  <nav>
    <a href="/" class="nav-logo">
      <!-- leaf icon -->
      <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M6 26 C6 26 8 14 20 10 C28 7 28 4 28 4 C28 4 30 16 20 20 C12 24 10 28 10 28" fill="#52B788"/>
        <path d="M10 28 C10 28 14 20 20 20" stroke="#2D6A4F" stroke-width="1.5" stroke-linecap="round"/>
      </svg>
      Biofund
    </a>
    <div class="nav-links">
      <a href="/" style="color:var(--green-mid);font-weight:700">Início</a>
      <a href="/submeterReclamacao">Submeter</a>
      <a href="/visualizarReclamacao">Consultar</a>
    </div>
    <button class="btn-restricted">Acesso Restrito</button>
  </nav>

  <!-- HERO -->
  <section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <div class="hero-badge">Iniciativa de Conservação Biofund</div>
      <h1>
        Registe a sua
        <span>reclamação ambiental</span>
      </h1>
      <p>Ajude-nos a proteger a biodiversidade de Moçambique. O BioQueixa é o seu canal direto para reportar incidentes e monitorar a saúde dos nossos ecossistemas.</p>
      <div class="hero-buttons">
        <button class="btn-primary" onclick="window.location.href='/submeterReclamacao'">
          Submeter Reclamação
          <svg width="14" height="14" fill="none" viewBox="0 0 16 16"><path d="M3 8h10M9 4l4 4-4 4" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
        <button class="btn-outline-white" onclick="window.location.href='/visualizarReclamacao'">Consultar Reclamação</button>
      </div>
    </div>
  </section>

  <!-- FEATURE CARDS -->
  <div class="features-wrap reveal">
    <div class="features-grid">
      <div class="feature-card">
        <div class="feature-icon">
          <!-- warning triangle -->
          <svg width="36" height="36" fill="none" viewBox="0 0 36 36">
            <path d="M18 5 L33 30 H3 Z" stroke="#2D6A4F" stroke-width="2" stroke-linejoin="round"/>
            <line x1="18" y1="14" x2="18" y2="22" stroke="#2D6A4F" stroke-width="2.5" stroke-linecap="round"/>
            <circle cx="18" cy="26" r="1.5" fill="#2D6A4F"/>
          </svg>
        </div>
        <h3>Reportar Incidente</h3>
        <p>Denuncie pesca ilegal, poluição, caça furtiva ou qualquer dano aos nossos ecossistemas naturais.</p>
        <a href="/submeterReclamacao" class="feature-link">
          Começar Submissão
          <svg width="14" height="14" fill="none" viewBox="0 0 16 16"><path d="M3 8h10M9 4l4 4-4 4" stroke="#2D6A4F" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
      </div>

      <div class="feature-card" style="background:#F0FAF4;">
        <div class="feature-icon">
          <!-- search icon -->
          <svg width="36" height="36" fill="none" viewBox="0 0 36 36">
            <circle cx="16" cy="16" r="9" stroke="#2D6A4F" stroke-width="2"/>
            <line x1="23" y1="23" x2="31" y2="31" stroke="#2D6A4F" stroke-width="2.5" stroke-linecap="round"/>
          </svg>
        </div>
        <h3>Acompanhar Estado</h3>
        <p>Verifique o progresso da sua reclamação e as medidas tomadas pelas autoridades de conservação.</p>
        <a href="/visualizarReclamacao" class="feature-link">
          Consultar Agora
          <svg width="14" height="14" fill="none" viewBox="0 0 16 16"><path d="M3 8h10M9 4l4 4-4 4" stroke="#2D6A4F" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
      </div>

      <div class="feature-card">
        <div class="feature-icon">
          <!-- shield icon -->
          <svg width="36" height="36" fill="none" viewBox="0 0 36 36">
            <path d="M18 4 L30 9 V18 C30 24 24 29 18 32 C12 29 6 24 6 18 V9 Z" stroke="#2D6A4F" stroke-width="2" stroke-linejoin="round"/>
            <path d="M13 18 L17 22 L23 14" stroke="#2D6A4F" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <h3>Missão Biofund</h3>
        <p>Saiba como a Biofund está a gerir os recursos financeiros para a biodiversidade moçambicana.</p>
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
        <div class="benefit-item">
          <div class="benefit-check">
            <svg fill="none" viewBox="0 0 12 12"><path d="M2 6 L5 9 L10 3" stroke="#2D6A4F" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </div>
          <div>
            <h4>Transparência</h4>
            <p>Processos claros e rastreáveis desde a submissão até à resolução final.</p>
          </div>
        </div>
        <div class="benefit-item">
          <div class="benefit-check">
            <svg fill="none" viewBox="0 0 12 12"><path d="M2 6 L5 9 L10 3" stroke="#2D6A4F" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </div>
          <div>
            <h4>Impacto Real</h4>
            <p>Dados que geram ações imediatas no terreno por equipas especializadas.</p>
          </div>
        </div>
        <div class="benefit-item">
          <div class="benefit-check">
            <svg fill="none" viewBox="0 0 12 12"><path d="M2 6 L5 9 L10 3" stroke="#2D6A4F" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </div>
          <div>
            <h4>Anonimato</h4>
            <p>Possibilidade de reportar sem revelar a identidade, garantindo segurança.</p>
          </div>
        </div>
        <div class="benefit-item">
          <div class="benefit-check">
            <svg fill="none" viewBox="0 0 12 12"><path d="M2 6 L5 9 L10 3" stroke="#2D6A4F" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </div>
          <div>
            <h4>Monitoria</h4>
            <p>Acompanhamento estatístico público da saúde ambiental nacional.</p>
          </div>
        </div>
      </div>

      <button class="btn-green" onclick="window.location.href='/submeterReclamacao'">Fazer Minha Parte</button>
    </div>

    <div class="why-right">
      <img
        src="https://images.unsplash.com/photo-1551970634-747846a548cb?w=800&q=80"
        alt="Guarda florestal em Moçambique"
      />
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
    <p>Conheça algumas das espécies emblemáticas que o BioQueixa ajuda a salvaguardar através da vigilância participativa.</p>

    <div class="species-grid">
      <div class="species-card">
        <img src="https://images.unsplash.com/photo-1564760055775-d63b17a55c44?w=600&q=80" alt="Elefante Africano" />
        <div class="species-overlay"></div>
        <div class="species-info"><h4>Elefante Africano</h4><span>Reserva do Niassa</span></div>
      </div>
      <div class="species-card">
        <img src="https://images.unsplash.com/photo-1559827291-72ee739d0d9a?w=600&q=80" alt="Dugongo" />
        <div class="species-overlay"></div>
        <div class="species-info"><h4>Dugongo</h4><span>Bazaruto</span></div>
      </div>
      <div class="species-card">
        <img src="https://images.unsplash.com/photo-1546182990-dffeafbe841d?w=600&q=80" alt="Leão" />
        <div class="species-overlay"></div>
        <div class="species-info"><h4>Leão</h4><span>Parque da Gorongosa</span></div>
      </div>
      <div class="species-card">
        <img src="https://images.unsplash.com/photo-1551316679-9c6ae9dec224?w=600&q=80" alt="Hipopótamo" />
        <div class="species-overlay"></div>
        <div class="species-info"><h4>Hipopótamo</h4><span>Parque do Limpopo</span></div>
      </div>
    </div>
  </section>

  <!-- CTA BANNER -->
  <section class="cta-section reveal">
    <div class="cta-banner">
      <!-- decorative background tree SVG -->
      <svg class="cta-deco" width="280" height="340" viewBox="0 0 280 340" fill="none">
        <ellipse cx="140" cy="130" rx="90" ry="110" fill="white"/>
        <ellipse cx="100" cy="90" rx="60" ry="80" fill="white"/>
        <ellipse cx="180" cy="100" rx="65" ry="75" fill="white"/>
        <rect x="125" y="220" width="30" height="110" fill="white"/>
      </svg>

      <h2>Pronto para colaborar?</h2>
      <p>Cada denúncia é um passo para um Moçambique mais verde e sustentável. Não ignore o dano ambiental, reporte-o hoje.</p>
      <div class="cta-buttons">
        <button class="btn-amber" onclick="window.location.href='/submeterReclamacao'">Submeter Agora</button>
        <button class="btn-outline-cta">Como Funciona</button>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer>
    <div class="footer-grid">
      <div class="footer-brand">
        <a href="/" class="nav-logo">
          <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" width="22" height="22">
            <path d="M6 26 C6 26 8 14 20 10 C28 7 28 4 28 4 C28 4 30 16 20 20 C12 24 10 28 10 28" fill="#52B788"/>
            <path d="M10 28 C10 28 14 20 20 20" stroke="#2D6A4F" stroke-width="1.5" stroke-linecap="round"/>
          </svg>
          BioQueixa
        </a>
        <p>Protegendo o património natural de Moçambique através da transparência e participação cidadã.</p>
      </div>

      <div class="footer-col">
        <h5>Links Rápidos</h5>
        <a href="/">Página Inicial</a>
        <a href="/submeterReclamacao">Reportar Incidente</a>
        <a href="/visualizarReclamacao">Acompanhar Caso</a>
      </div>

      <div class="footer-col">
        <h5>Institucional</h5>
        <a href="#">
          <svg width="14" height="14" fill="none" viewBox="0 0 14 14"><rect x="1" y="3" width="12" height="9" rx="1.5" stroke="currentColor" stroke-width="1.3"/><path d="M1 5 L7 9 L13 5" stroke="currentColor" stroke-width="1.3"/></svg>
          Biofund Moçambique
        </a>
        <a href="#">
          <svg width="14" height="14" fill="none" viewBox="0 0 14 14"><rect x="1" y="3" width="12" height="9" rx="1.5" stroke="currentColor" stroke-width="1.3"/><path d="M1 5 L7 9 L13 5" stroke="currentColor" stroke-width="1.3"/></svg>
          Políticas de Privacidade
        </a>
        <a href="#">
          <svg width="14" height="14" fill="none" viewBox="0 0 14 14"><rect x="1" y="3" width="12" height="9" rx="1.5" stroke="currentColor" stroke-width="1.3"/><path d="M1 5 L7 9 L13 5" stroke="currentColor" stroke-width="1.3"/></svg>
          Contacto Direto
        </a>
      </div>

      <div class="footer-col">
        <h5>Siga-nos</h5>
        <div class="social-links">
          <a href="#" class="social-link">
            <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
          </a>
          <a href="#" class="social-link">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/></svg>
          </a>
          <a href="#" class="social-link">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
          </a>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      © 2025 BioQueixa. Todos os direitos reservados. Inspirado na conservação ambiental de Moçambique.
    </div>
  </footer>

</div>

<script>
const { createApp } = Vue;

createApp({
  mounted() {
    // scroll reveal
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.classList.add('visible');
          observer.unobserve(e.target);
        }
      });
    }, { threshold: 0.12 });

    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
  }
}).mount('#app');
</script>
</body>
</html>
