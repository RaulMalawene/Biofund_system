<template>
  <nav :class="['app-nav', variant]">
    <router-link to="/" class="nav-logo">
      <img src="../Imagem/logo_biofund_2.png" alt="" class="nav-logo-img"/>
    </router-link>

    <div class="nav-links">
      <router-link to="/">Início</router-link>
      <router-link to="/submeterReclamacao">Submeter</router-link>
      <router-link to="/visualizarReclamacao">Consultar</router-link>
    </div>

    <button class="btn-restricted" @click="handleAccess">
      {{ auth.isAuthenticated ? 'Painel' : 'Acesso Restrito' }}
    </button>
  </nav>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

defineProps({
  variant: { type: String, default: 'sticky' }
})

const router = useRouter()
const auth = useAuthStore()

function handleAccess() {
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
  width: 36px;
  height: 36px;
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
</style>
