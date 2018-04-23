import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';
import App from './App.vue';

//Vuetify
import Vuetify from 'vuetify';
import colors from 'vuetify/es5/util/colors';
import 'vuetify/dist/vuetify.min.css';
import 'material-design-icons-iconfont/dist/material-design-icons.css';

// Admin Pages
import Home from './pages/Home.vue';
import Dashboard from './pages/Dashboard.vue';
import Register from './pages/Register.vue';
import Login from './pages/Login.vue';
import Layout from './pages/Layout.vue';
// Clients
    import ListClients from './pages/clients/ListClients';
    import CreateClient from './pages/clients/CreateClient';

Vue.use(VueRouter);
Vue.use(VueAxios, axios);
Vue.use(Vuetify, {
    theme: {
        primary: colors.teal.base,
        secondary: colors.teal.lighten4,
        accent: colors.indigo.accent1
    }
});
axios.defaults.baseURL = '/api';

const router = new VueRouter({
    routes: [
        {
            path: '/',
            component: Login
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
            meta: {
                auth: false
            }
        },
        {
            mode: 'abstract',
            path: '',
            component: Layout,
            meta: {
                auth: true
            },
            children: [
                {
                    path: '/dashboard',
                    name: 'dashboard',
                    component: Dashboard,
                },
                {
                    path: '/clientes',
                    name: 'clients',
                    component: ListClients,
                },
                {
                    path: 'clientes/cadastrar',
                    name: 'create',
                    component: CreateClient
                },
                {
                    path: '/registro',
                    name: 'register',
                    component: Register,
                }
            ]
        }
    ]
});
Vue.router = router
Vue.use(require('@websanova/vue-auth'), {
   auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
   http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
   router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
});
App.router = Vue.router
new Vue(App).$mount('#app');