import { createRouter, createWebHistory } from 'vue-router';
import HomeView                from '@/views/funcionario externo/HomeView.vue';
import SubmeterReclamacaoView  from '@/views/funcionario externo/SubmeterReclamacaoView.vue';
import VisualizarReclamacaoView from '@/views/funcionario externo/VisualizarReclamacaoView.vue';
import AcessoRestritoView      from '@/views/AcessoRestritoView.vue';
import DashboardAdmin          from '@/views/administrador/DashboardAdmin.vue';

const routes = [
  { path: '/',                      component: HomeView },
  { path: '/submeterReclamacao',    component: SubmeterReclamacaoView },
  { path: '/visualizarReclamacao',  component: VisualizarReclamacaoView },
  { path: '/acessoRestrito',        component: AcessoRestritoView },
  { path: '/admin/dashboard',       component: DashboardAdmin },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior: () => ({ top: 0 }),
});

export default router;
