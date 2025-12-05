import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import App from './App.vue'
import LoginView from "./components/LoginView.vue";
import CreateRoute from "./components/CreateRouteForm.vue";
import AnalyticsView from "./components/AnalyticsView.vue";


const routes = [
    {path: '/', component: CreateRoute},
    {path: '/login', component: LoginView},
    {path: '/CreateRoute', component: CreateRoute},
    {path: '/Analytics', component: AnalyticsView},
]
const router = createRouter({
    history: createWebHistory(),
    routes,
});

const app = createApp(App).use(router).mount('#app')