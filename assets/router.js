
import { createRouter, createWebHistory } from 'vue-router';
import Home from './views/HomeView.vue';
import Cadastro from './views/CadastroView.vue';


const routes = [
    {path: '/', component: Home},
    {path: '/cadastro', component: Cadastro},

]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router;