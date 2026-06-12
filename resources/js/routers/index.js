import { createRouter, createWebHistory } from 'vue-router'
import HomeView               from '@/views/funcionario externo/HomeView.vue'
import SubmeterReclamacaoView  from '@/views/funcionario externo/SubmeterReclamacaoView.vue'
import VisualizarReclamacaoView from '@/views/funcionario externo/VisualizarReclamacaoView.vue'
import AcessoRestritoView      from '@/views/AcessoRestritoView.vue'
import DashboardAdmin          from '@/views/administrador/DashboardAdmin.vue'
import GestaoUtilizadores      from '@/views/administrador/GestaoUtilizadores.vue'
import HistoricoOcorrencia     from '@/views/administrador/HistoricoOcorrencia.vue'
import Validacao               from '@/views/administrador/Validacao.vue'
import Projectos               from '@/views/administrador/projectos.vue'
import Categoria               from '@/views/administrador/Categoria.vue'
import DashboardGestor         from '@/views/gestor/DashboardGestor.vue'
import ValidacaoGestor         from '@/views/gestor/ValidacaoGestor.vue'
import HistoricoGestor         from '@/views/gestor/HistoricoGestor.vue'
import ReclamacaoFuncionario   from '@/views/funcionario interno/reclamacao.vue'
import HistoricoFuncionario    from '@/views/funcionario interno/HistoricoFuncionario.vue'
import DashboardObservador     from '@/views/observador/DashboardObservador.vue'

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

    // Páginas exclusivas do administrador
    {
        path: '/admin/dashboard',
        component: DashboardAdmin,
        meta: { requiresAuth: true, roles: ['admin'], keepAlive: true },
    },
    {
        path: '/admin/utilizadores',
        component: GestaoUtilizadores,
        meta: { requiresAuth: true, roles: ['admin'], keepAlive: true },
    },
    {
        path: '/admin/historico',
        component: HistoricoOcorrencia,
        meta: { requiresAuth: true, roles: ['admin'], keepAlive: true },
    },
    {
        path: '/admin/validacao',
        component: Validacao,
        meta: { requiresAuth: true, roles: ['admin'], keepAlive: true },
    },
    {
        path: '/admin/projectos',
        component: Projectos,
        meta: { requiresAuth: true, roles: ['admin'], keepAlive: true },
    },
    {
        path: '/admin/categorias',
        component: Categoria,
        meta: { requiresAuth: true, roles: ['admin'], keepAlive: true },
    },
    {
        path: '/gestor/dashboard',
        component: DashboardGestor,
        meta: { requiresAuth: true, roles: ['gestor'], keepAlive: true },
    },
    {
        path: '/gestor/validacao',
        component: ValidacaoGestor,
        meta: { requiresAuth: true, roles: ['gestor'], keepAlive: true },
    },
    {
        path: '/gestor/historico',
        component: HistoricoGestor,
        meta: { requiresAuth: true, roles: ['gestor'], keepAlive: true },
    },
    {
        path: '/funcionario/reclamacao',
        component: ReclamacaoFuncionario,
        meta: { requiresAuth: true, roles: ['funcionario'], keepAlive: true },
    },
    {
        path: '/funcionario/historico',
        component: HistoricoFuncionario,
        meta: { requiresAuth: true, roles: ['funcionario'], keepAlive: true },
    },

    // Páginas exclusivas do observador
    {
        path: '/observador/dashboard',
        component: DashboardObservador,
        meta: { requiresAuth: true, roles: ['observador'], keepAlive: true },
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
    const isAuthenticated = !!token && !!user

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

        // Não autenticado (ou token sem dados de utilizador) → redireciona para login
        if (!isAuthenticated) {
            // Limpa eventual token órfão (token sem user data)
            if (token && !user) {
                localStorage.removeItem('mdr_token')
                localStorage.removeItem('mdr_user')
            }
            return next({ path: '/acessoRestrito', query: { redirect: to.fullPath } })
        }

        // Verifica se o role tem permissão para esta rota
        if (to.meta.roles && !to.meta.roles.includes(user.role)) {
            const homeByRole = {
                admin:       '/admin/dashboard',
                gestor:      '/gestor/dashboard',
                funcionario: '/funcionario/reclamacao',
                observador:  '/observador/dashboard',
            }
            const home = homeByRole[user.role] ?? '/acessoRestrito'
            if (to.path === home) return next()
            return next(home)
        }

        // Autenticado e com permissão → deixa passar
        return next()
    }

    // ── Rota só para não-autenticados (ex: login) ─────────────
    if (to.meta.guestOnly && isAuthenticated) {
        const homeByRole = {
            admin:       '/admin/dashboard',
            gestor:      '/gestor/dashboard',
            funcionario: '/funcionario/reclamacao',
        }
        const home = homeByRole[user?.role] ?? '/acessoRestrito'
        return next(home)
    }

    // ── Rota pública → deixa passar ───────────────────────────
    next()
})

export default router
