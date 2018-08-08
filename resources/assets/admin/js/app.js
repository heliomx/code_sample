import Vue from 'vue';
import VueRouter from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';
import App from './App.vue';
import VueLocalStorage from 'vue-localstorage'
import { SharedData } from './shared';

//Vuetify
import Vuetify from 'vuetify';
import colors from 'vuetify/es5/util/colors';
import 'vuetify/dist/vuetify.min.css';

import wysiwyg from "vue-wysiwyg";


// Admin Pages
import Home from './pages/Home.vue';
import Dashboard from './pages/Dashboard.vue';
import Register from './pages/Register.vue';
import Login from './pages/Login.vue';
import Layout from './pages/Layout.vue';
import NewPassword from './pages/NewPassword.vue';

// Clients
    import ListClients from './pages/clients/ListClients';
    import CreateEditClient from './pages/clients/CreateEditClient';

// Programs
    import ListPrograms from './pages/programs/ListPrograms';
    import ListDownloads from './pages/programs/ListDownloads';
    import CreateEditProgram from './pages/programs/CreateEditProgram';
    import UploadPrograms from './pages/programs/UploadPrograms';

// Content
    import HomeContent from './pages/content/HomeContent';
    import WhoWeAreContent from './pages/content/WhoWeAreContent';

// Contacts
    import EditContact from './pages/contacts/EditContact';
    import ListContacts from './pages/contacts/ListContacts';

// Users
    import CreateEditUser from './pages/users/CreateEditUser';
    import ListUsers from './pages/users/ListUsers';


Vue.use(VueRouter);
Vue.use(VueAxios, axios);
Vue.use(Vuetify, {
    theme: {
        primary: colors.teal.base,
        secondary: '#fe9924',
        accent: colors.indigo.accent1
    }
});
Vue.use(wysiwyg, {
    hideModules: { 
        underline: true,
        justifyLeft: true,
        justifyCenter: true,
        justifyRight: true,
        headings: true,
        code: true,
        orderedList: true,
        unorderedList: true,
        image: true,
        table: true,
        removeFormat: true,
        separator: true
     },
});
Vue.use(VueLocalStorage);
Vue.use(SharedData);

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
            path: '/trocarSenha',
            name: 'newPassword',
            component: NewPassword
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
                    path: '/cadastro',
                    name: 'clientForm',
                    component: CreateEditClient
                },

                // Clients
                {
                    path: '/clientes',
                    name: 'clients',
                    component: ListClients,
                },
                {
                    path: 'clientes/cadastrar',
                    name: 'createClient',
                    component: CreateEditClient
                },
                {
                    path: 'clientes/:id',
                    name: 'editClient',
                    component: CreateEditClient
                },
                

                // Programs
                {
                    path: '/programas',
                    name: 'programs',
                    component: ListPrograms,
                },
                {
                    path: 'programas/downloads',
                    name: 'downloadPrograms',
                    component: ListDownloads
                },
                {
                    path: 'programas/cadastrar',
                    name: 'createProgram',
                    component: CreateEditProgram
                },
                {
                    path: 'programas/enviar',
                    name: 'uploadPrograms',
                    component: UploadPrograms
                },
                {
                    path: 'programas/:id',
                    name: 'editProgram',
                    component: CreateEditProgram
                },

                // Content
                {
                    path: 'conteudo/home',
                    name: 'homeContent',
                    component: HomeContent
                },
                {
                    path: 'conteudo/quemsomos',
                    name: 'whoWeAreContent',
                    component: WhoWeAreContent
                },


                {
                    path: '/registro',
                    name: 'register',
                    component: Register,
                },

                // Contacts
                {
                    path: '/faleconosco',
                    name: 'listContacts',
                    component: ListContacts
                },

                {
                    path: 'faleconosco/:id',
                    name: 'editContact',
                    component: EditContact
                },

                // Users
                {
                    path: '/usuarios',
                    name: 'listUsers',
                    component: ListUsers
                },

                {
                    path: '/usuarios/cadastrar',
                    name: 'createUser',
                    component: CreateEditUser
                },

                {
                    path: '/usuarios/:id',
                    name: 'editUser',
                    component: CreateEditUser
                },

                



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