import { createRouter, createWebHistory } from 'vue-router'
import HomeView               from '@/views/funcionario externo/HomeView.vue'
import SubmeterReclamacaoView  from '@/views/funcionario externo/SubmeterReclamacaoView.vue'
import VisualizarReclamacaoView from '@/views/funcionario externo/VisualizarReclamacaoView.vue'
import AcessoRestritoView      from '@/views/AcessoRestritoView.vue'
import DashboardAdmin          from '@/views/administrador/DashboardAdmin.vue'
import GestaoUtilizadores      from '@/views/administrador/GestaoUtilizadores.vue'
import HistoricoOcorrencia     from '@/views/administrador/HistoricoOcorrencia.vue'
import Validacao               from '@/views/administrador/Validacao.vue'

const routes = [
    // ── Públicas ─────────────────────────────────────────────
    { path: '/',                     component: HomeView },
    { path: '/submeterReclamacao',   component: SubmeterReclamacaoView },
    { path: '/visualizarReclamacao', component: VisualizarReclamacaoView },

    // ── Login ─────────────────────────────────────────────────
    {
        path: '/acessoRestrito',
        component: AcessoRestritoView,
        meta: { guestOnly: true },
    },

    // ── Protegidas (requerem autenticação) ────────────────────
    {
        path: '/admin/dashboard',
        component: DashboardAdmin,
        meta: { requiresAuth: true, roles: ['admin', 'gestor', 'funcionario'] },
    },
    {
        path: '/admin/utilizadores',
        component: GestaoUtilizadores,
        meta: { requiresAuth: true, roles: ['admin'] },
    },
    {
        path: '/admin/historico',
        component: HistoricoOcorrencia,
        meta: { requiresAuth: true, roles: ['admin', 'gestor'] },
    },
    {
        path: '/admin/validacao',
        component: Validacao,
        meta: { requiresAuth: true, roles: ['admin', 'gestor'] },
    },

    // Rota catch-all: redireciona para a home
    { path: '/:pathMatch(.*)*', redirect: '/' },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior: () => ({ top: 0 }),
})

// ── Guarda de navegação ───────────────────────────────────────
router.beforeEach((to, _from, next) => {
    const token = localStorage.getItem('mdr_token')
    const user  = (() => {
        try { return JSON.parse(localStorage.getItem('mdr_user') ?? 'null') }
        catch { return null }
    })()
    const isAuthenticated = !!token

    // [Debug] Remove em produção
    console.log('[Router]', {
        to: to.path,
        token: !!token,
        user,
        role: user?.role,
        routeRoles: to.meta.roles,
    })

    // ── Rota protegida ────────────────────────────────────────
    if (to.meta.requiresAuth) {

        // Não autenticado → redireciona para login
        if (!isAuthenticated) {
            return next({ path: '/acessoRestrito', query: { redirect: to.fullPath } })
        }

        // Token existe mas dados do utilizador estão em falta ou corrompidos
        if (!user) {
            localStorage.removeItem('mdr_token')
            localStorage.removeItem('mdr_user')
            return next({ path: '/acessoRestrito', query: { redirect: to.fullPath } })
        }

        // Verifica se o role tem permissão para esta rota
        if (to.meta.roles && !to.meta.roles.includes(user.role)) {
            // Já está no dashboard → deixa passar para evitar loop infinito
            if (to.path === '/admin/dashboard') return next()
            return next('/admin/dashboard')
        }

        // Autenticado e com permissão → deixa passar
        return next()
    }

    // ── Rota só para não-autenticados (ex: login) ─────────────
    if (to.meta.guestOnly && isAuthenticated) {
        return next('/admin/dashboard')
    }

    // ── Rota pública → deixa passar ───────────────────────────
    next()
})

export default router