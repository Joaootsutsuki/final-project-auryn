import "./styles/app.css"
import { createApp } from 'vue';
import App from './components/App.vue';
import VueTheMask from 'vue-the-mask';
import router from './router.js';

const app = createApp(App);
app.use(router);
app.use(VueTheMask);
app.mount("#app");
