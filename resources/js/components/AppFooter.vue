<template>
  <footer>
    <div class="footer-grid">
      <div class="footer-brand">
        <router-link to="/" class="footer-logo">
          <img src="../Imagem/Logo BIOFUND sem fundo.png" alt="Biofund" class="footer-logo-img"/>
        </router-link>
        <p>Instituição privada de utilidade pública que mobiliza recursos para a conservação. </p>
      </div>

      <div class="footer-col">
        <h5>Links Rápidos</h5>
        <router-link to="/">Página Inicial</router-link>
        <router-link to="/submeterReclamacao">Reportar Incidente</router-link>
        <router-link to="/visualizarReclamacao">Acompanhar Caso</router-link>
      </div>

      <div class="footer-col">
        <h5>Institucional</h5>
        <a href="#">
          <svg width="13" height="13" fill="none" viewBox="0 0 14 14"><rect x="1" y="3" width="12" height="9" rx="1.5" stroke="currentColor" stroke-width="1.3"/><path d="M1 5L7 9l6-4" stroke="currentColor" stroke-width="1.3"/></svg>
          BIOFUND 
        </a>
        <a href="#">
          <svg width="13" height="13" fill="none" viewBox="0 0 14 14"><rect x="1" y="3" width="12" height="9" rx="1.5" stroke="currentColor" stroke-width="1.3"/><path d="M1 5L7 9l6-4" stroke="currentColor" stroke-width="1.3"/></svg>
          Políticas de Privacidade
        </a>
        <a href="#">
          <svg width="13" height="13" fill="none" viewBox="0 0 14 14"><rect x="1" y="3" width="12" height="9" rx="1.5" stroke="currentColor" stroke-width="1.3"/><path d="M1 5L7 9l6-4" stroke="currentColor" stroke-width="1.3"/></svg>
          Contacto Direto
        </a>
      </div>

      <div class="footer-col">
        <h5>Siga-nos</h5>
        <div class="social-links">
          <a href="#" class="social-link">
            <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
          </a>
          <a href="#" class="social-link">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/></svg>
          </a>
          <a href="#" class="social-link">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
          </a>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      © 2026 BIOFUND. Todos os direitos reservados.
    </div>
  </footer>
</template>

<script setup>
</script>

<style scoped>
footer {
  background: var(--white);
  border-top: 1px solid var(--card-border);
  padding: 56px 64px 32px;
}

.footer-grid {
  display: grid;
  grid-template-columns: 1.6fr 1fr 1fr 1fr;
  gap: 40px;
  max-width: 1100px;
  margin: 0 auto 40px;
}

.footer-logo {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  text-decoration: none;
  color: var(--text-dark);
  font-weight: 700;
  font-size: 15px;
  margin-bottom: 14px;
}

.footer-logo-img {
  width: 90px;
  height: 90px;
  object-fit: contain;
  border-radius: 6px;
  flex-shrink: 0;
}

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

.social-links { display: flex; gap: 14px; }

.social-link {
  width: 34px;
  height: 34px;
  border: 1px solid #D0DDD3;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  color: var(--text-gray);
  transition: border-color 0.2s, color 0.2s;
}

.social-link:hover { border-color: var(--green-mid); color: var(--green-mid); }

.footer-bottom {
  border-top: 1px solid var(--card-border);
  padding-top: 24px;
  max-width: 1100px;
  margin: 0 auto;
  text-align: center;
  font-size: 12px;
  color: var(--text-light);
}

@media (max-width: 820px) {
  footer { padding: 44px 24px 28px; }
  .footer-grid { grid-template-columns: 1fr 1fr; gap: 32px; }
  .footer-brand { grid-column: 1 / -1; }
}

@media (max-width: 520px) {
  .footer-grid { grid-template-columns: 1fr; gap: 28px; }
}
</style>
