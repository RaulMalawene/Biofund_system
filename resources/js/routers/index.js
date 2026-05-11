import { createRouter, createWebHistory } from 'vue-router';
import HomeView                from '@/views/HomeView.vue';
import SubmeterReclamacaoView  from '@/views/SubmeterReclamacaoView.vue';
import VisualizarReclamacaoView from '@/views/VisualizarReclamacaoView.vue';

const routes = [
  { path: '/',                      component: HomeView },
  { path: '/submeterReclamacao',    component: SubmeterReclamacaoView },
  { path: '/visualizarReclamacao',  component: VisualizarReclamacaoView },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior: () => ({ top: 0 }),
});

export default router;
