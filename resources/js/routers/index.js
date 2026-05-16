import { createRouter, createWebHistory } from 'vue-router'
import HomeView              from '@/views/funcionario externo/HomeView.vue'
import SubmeterReclamacaoView from '@/views/funcionario externo/SubmeterReclamacaoView.vue'
import VisualizarReclamacaoView from '@/views/funcionario externo/VisualizarReclamacaoView.vue'
import AcessoRestritoView    from '@/views/AcessoRestritoView.vue'
import DashboardAdmin        from '@/views/administrador/DashboardAdmin.vue'

const routes = [
    // ── Públicas ─────────────────────────────────────────────
    { path: '/',                     component: HomeView },
    { path: '/submeterReclamacao',   component: SubmeterReclamacaoView },
    { path: '/visualizarReclamacao', component: VisualizarReclamacaoView },

    // ── Login ─────────────────────────────────────────────────
    {
        path: '/acessoRestrito',
        component: AcessoRestritoView,
        meta: { guestOnly: true },   // redireciona autenticados para o dashboard
    },

    // ── Protegidas (requerem autenticação) ────────────────────
    {
        path: '/admin/dashboard',
        component: DashboardAdmin,
        meta: { requiresAuth: true, roles: ['admin'] },
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

    // Rota que requer autenticação
    if (to.meta.requiresAuth) {
        if (!isAuthenticated) {
            return next({ path: '/acessoRestrito', query: { redirect: to.fullPath } })
        }

        // Verifica se o role tem permissão
        if (to.meta.roles && user && !to.meta.roles.includes(user.role)) {
            // Redireciona para o dashboard correcto do role
            const dashboards = {
                admin:       '/admin/dashboard',
                gestor:      '/admin/dashboard',
                funcionario: '/admin/dashboard',
            }
            return next(dashboards[user.role] ?? '/')
        }
    }

    // Rota só para não-autenticados (ex: página de login)
    if (to.meta.guestOnly && isAuthenticated) {
        const dashboards = {
            admin:       '/admin/dashboard',
            gestor:      '/admin/dashboard',
            funcionario: '/admin/dashboard',
        }
        return next(dashboards[user?.role] ?? '/')
    }

    next()
})

export default router