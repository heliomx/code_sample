import Vue from 'vue/dist/vue.common';
import VueRouter from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';
import App from './App.vue';
import VueLocalStorage from 'vue-localstorage'
import { SharedData } from './shared';
import VueScrollTo from 'vue-scrollto';

Vue.use(VueScrollTo);

//Vuetify
import Vuetify from 'vuetify';
import colors from 'vuetify/es5/util/colors';
import 'vuetify/dist/vuetify.min.css';

import '../sass/app.scss';

// Public Pages
import Home from './pages/Home.vue';
import Program from './pages/Program.vue';
import WhoWeAre from './pages/WhoWeAre.vue';
import Register from './pages/Register.vue';
import ContactForm from './pages/ContactForm.vue';

Vue.use(VueRouter);
Vue.use(VueAxios, axios);
Vue.use(Vuetify, {
    theme: {
        primary: '#2d870c',
        secondary: '#fe9924',
        accent: '#caecc8'
    }
});
Vue.use(VueLocalStorage);
Vue.use(SharedData);

axios.defaults.baseURL = '/api';

const router = new VueRouter({
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/programas',
            name: 'programs',
            component: Home
        },
        {
            path: '/programas/:id',
            name: 'program',
            component: Program
        },
        {
            path: '/quemsomos',
            name: 'whoWeAre',
            component: WhoWeAre
        },
        {
            path: '/cadastro',
            name: 'register',
            component: Register
        },
        {
            path: '/faleconosco',
            name: 'contact',
            component: ContactForm
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