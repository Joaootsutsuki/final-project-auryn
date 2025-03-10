
import { createRouter, createWebHistory } from 'vue-router';
import Home from './views/HomeView.vue';
import Cadastro from './views/CadastroView.vue';
import NotFound from './views/NotFound.vue';


const routes = [
    {path: '/', component: Home},
    {path: '/cadastro', component: Cadastro},
    {path: '/:pathMatch(.*)*', component: NotFound}
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router;