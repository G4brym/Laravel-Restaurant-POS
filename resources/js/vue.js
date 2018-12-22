/*jshint esversion: 6 */

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import VueSweetalert2 from 'vue-sweetalert2';
Vue.use(VueSweetalert2);

import axios from 'axios'

Vue.prototype.$http = axios;

/*import VueSocketio from 'vue-socket.io';
Vue.use(new VueSocketio({
    debug: true,
    connection: env('YOUR_SERVER_URL') + ':8080'
}));*/

import store from './stores/global-store';

const table = Vue.component('tableMain', require('./components/manager/tables/table.vue'));
const item = Vue.component('item', require('./components/manager/items/item.vue'));
const user = Vue.component('user', require('./components/manager/users/user.vue'));
const waiter = Vue.component('waiter', require('./components/waiter'));
const account = Vue.component('account', require('./components/account/account.vue'));
const login = Vue.component('login', require('./components/login.vue'));
const logout = Vue.component('logout', require('./components/logout.vue'));
const itemsMenu = Vue.component('itemsMenu', require('./components/itemsMenu.vue'));
Vue.component('shift-counter', require('./components/shift_counter.vue'));
Vue.component('shift-button', require('./components/shift_button.vue'));
Vue.component('notifications', require('./components/notifications'));

const routes = [
    { path: '/', redirect: '/itemsMenu', name: 'root'},
    { path: '/waiter', component: waiter, name: 'waiter'},
    { path: '/tables', component: table, name: 'table'},
    { path: '/items', component: item, name: 'item'},
    { path: '/users', component: user, name: 'users'},
    { path: '/account', component: account, name: 'account'},
    { path: '/login', component: login, name: 'login'},
    { path: '/logout', component: logout, name: 'logout'},
    { path: '/itemsMenu', component: itemsMenu, name: 'itemsMenu'},
];

const router = new VueRouter({
    routes:routes
});

router.beforeEach((to, from, next) => {
    if ((to.name == 'account') || (to.name == 'logout')) {
        if (!sessionStorage.getItem('user')) {
            next("/login");
            return;
        }
    }
    if (to.name == 'login') {
        if (sessionStorage.getItem('user')) {
            next("/");
            return;
        }
    }
    next();
});

// Change the base URL to your REST API URL
// or leave undefined if it is the same URL
// as the site
// axios.defaults.baseURL = 'http://my.api';

const app = new Vue({
    router,
    data: {},
    store,
    created() {
        // console.log('-----');
        // console.log(this.$store.state.user);
        this.$store.commit('loadTokenAndUserFromSession');
        this.$store.commit('loadProfilesFolder');
        this.$store.commit('loadItems');
        //console.log(this.$store.state.user);
    },
    methods: {
        notifyCounter() {
            this.$refs.shiftCounter.updateCounter(this.$store.state.user);
        }
    }
}).$mount('#app');

