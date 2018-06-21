import Vue from 'vue/dist/vue.common';
import VueRouter from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';
import App from './App.vue';
import VueLocalStorage from 'vue-localstorage'
import { SharedData } from './shared';

//Vuetify
import Vuetify from 'vuetify';
import colors from 'vuetify/es5/util/colors';
import 'material-design-icons-iconfont/dist/material-design-icons.css';
import 'vuetify/dist/vuetify.min.css';

import '../sass/app.scss';

// Public Pages
import Home from './pages/Home.vue';
import Program from './pages/Program.vue';
import WhoWeAre from './pages/WhoWeAre.vue';

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
            path: '/programas/:id',
            name: 'program',
            component: Program
        },
        {
            path: '/quemsomos',
            name: 'whoWeAre',
            component: WhoWeAre
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