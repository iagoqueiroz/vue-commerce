require('./bootstrap');

window.Vue = require('vue');

import VueAuth from '@websanova/vue-auth'
import VueAxios from 'vue-axios'
import VueRouter from 'vue-router'
import auth from './auth'
import router from './router'

Vue.component('app', require('./components/App.vue').default);

Vue.router = router;
Vue.use(VueRouter);
Vue.use(VueAxios, axios);
window.axios.defaults.baseURL = `http://localhost:8000/api/v1`;
Vue.use(VueAuth, auth);

const app = new Vue({
    el: '#app',
    router
});