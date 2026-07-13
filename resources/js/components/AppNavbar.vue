<template>
  <nav :class="['app-nav', variant, { 'menu-open': menuOpen }]">
    <router-link to="/" class="nav-logo" @click="menuOpen = false">
      <img src="../Imagem/Logo BIOFUND sem fundo.png" alt="" class="nav-logo-img"/>
    </router-link>

    <div class="nav-links" :class="{ 'is-open': menuOpen }">
      <router-link to="/" @click="menuOpen = false">Início</router-link>
      <router-link to="/submeterReclamacao" @click="menuOpen = false">Submeter</router-link>
      <router-link to="/visualizarReclamacao" @click="menuOpen = false">Consultar</router-link>
      <button class="btn-restricted btn-restricted--mobile" @click="handleAccess">
        {{ auth.isAuthenticated ? 'Painel' : 'Acesso Restrito' }}
      </button>
    </div>

    <button class="btn-restricted btn-restricted--desktop" @click="handleAccess">
      {{ auth.isAuthenticated ? 'Painel' : 'Acesso Restrito' }}
    </button>

    <button class="nav-toggle" :class="{ 'is-open': menuOpen }" aria-label="Abrir menu" @click="menuOpen = !menuOpen">
      <span></span><span></span><span></span>
    </button>
  </nav>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

defineProps({
  variant: { type: String, default: 'sticky' }
})

const router = useRouter()
const auth = useAuthStore()
const menuOpen = ref(false)

function handleAccess() {
  menuOpen.value = false
  if (auth.isAuthenticated && auth.dashboardRoute !== '/') {
    router.push(auth.dashboardRoute)
  } else {
    auth.clearSession()
    router.push('/acessoRestrito')
  }
}
</script>

<style scoped>
.app-nav {
  position: relative;
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
  gap: 10px;
  text-decoration: none;
  color: var(--text-dark);
  font-weight: 700;
  font-size: 17px;
  letter-spacing: -0.3px;
}

.nav-logo-img {
  width: 90px;
  height: 90px;
  object-fit: contain;
  border-radius: 6px;
  flex-shrink: 0;
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

.btn-restricted--mobile { display: none; }

.nav-toggle {
  display: none;
  flex-direction: column;
  justify-content: center;
  gap: 5px;
  width: 32px;
  height: 32px;
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
  flex-shrink: 0;
}

.nav-toggle span {
  display: block;
  width: 100%;
  height: 2px;
  background: var(--text-dark);
  border-radius: 2px;
  transition: transform 0.2s, opacity 0.2s;
}

.nav-toggle.is-open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
.nav-toggle.is-open span:nth-child(2) { opacity: 0; }
.nav-toggle.is-open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

@media (max-width: 860px) {
  .app-nav { padding: 0 20px; }

  .btn-restricted--desktop { display: none; }
  .nav-toggle { display: flex; }

  .nav-links {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    flex-direction: column;
    align-items: stretch;
    gap: 0;
    margin-left: 0;
    background: var(--white);
    border-bottom: 1px solid var(--card-border);
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.25s ease;
  }

  .nav-links.is-open { max-height: 320px; }

  .nav-links a {
    padding: 14px 20px;
    border-top: 1px solid var(--card-border);
  }

  .btn-restricted--mobile {
    display: inline-flex;
    justify-content: center;
    margin: 14px 20px 18px;
  }
}
</style>
