<template>
  <nav :class="['app-nav', variant]">
    <router-link to="/" class="nav-logo">
      <svg width="28" height="28" viewBox="0 0 32 32" fill="none">
        <path d="M6 26C6 26 8 14 20 10C28 7 28 4 28 4C28 4 30 16 20 20C12 24 10 28 10 28" fill="#52B788"/>
        <path d="M10 28C10 28 14 20 20 20" stroke="#2D6A4F" stroke-width="1.5" stroke-linecap="round"/>
      </svg>
      BioQueixa
    </router-link>

    <div class="nav-links">
      <router-link to="/">Início</router-link>
      <router-link to="/submeterReclamacao">Submeter</router-link>
      <router-link to="/visualizarReclamacao">Consultar</router-link>
    </div>

    <button class="btn-restricted">Acesso Restrito</button>
  </nav>
</template>

<script setup>
defineProps({
  variant: { type: String, default: 'sticky' }
})
</script>

<style scoped>
.app-nav {
  z-index: 100;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 48px;
  height: 60px;
  background: var(--white);
  border-bottom: 1px solid var(--card-border);
}

.app-nav.fixed {
  position: fixed;
  top: 0; left: 0; right: 0;
}

.app-nav.sticky {
  position: sticky;
  top: 0;
  height: 58px;
  box-shadow: 0 1px 8px rgba(0,0,0,0.06);
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

.nav-links a:hover,
.nav-links a.router-link-exact-active {
  color: var(--green-mid);
  font-weight: 700;
}

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

.btn-restricted:hover {
  background: var(--green-mid);
  color: var(--white);
}
</style>
