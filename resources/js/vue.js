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

import VueSocketio from 'vue-socket.io';
Vue.use(new VueSocketio({
    debug: true,
    connection: 'http://127.0.0.1:8080'
}));

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
const cashier = Vue.component('cashier', require('./components/cashier'));
const account = Vue.component('account', require('./components/account/account.vue'));
const login = Vue.component('login', require('./components/login.vue'));
const logout = Vue.component('logout', require('./components/logout.vue'));
const itemsMenu = Vue.component('itemsMenu', require('./components/itemsMenu.vue'));
const cookOrders = Vue.component('cookOrders', require('./components/cook/cookOrders.vue'));
Vue.component('shift-counter', require('./components/shift_counter.vue'));
Vue.component('shift-button', require('./components/shift_button.vue'));
Vue.component('notifications', require('./components/notifications'));

const routes = [
    { path: '/', redirect: '/itemsMenu', name: 'root'},
    { path: '/waiter', component: waiter, name: 'waiter', meta: { requiresAuth: true, isWaiter: true }},
    { path: '/cashier', component: cashier, name: 'cashier', meta: { requiresAuth: true, isCashier: true }},
    { path: '/tables', component: table, name: 'table', meta: { requiresAuth: true, isManager: true }},
    { path: '/items', component: item, name: 'item', meta: { requiresAuth: true, isManager: true }},
    { path: '/users', component: user, name: 'users', meta: { requiresAuth: true, isManager: true }},
    { path: '/account', component: account, name: 'account', meta: { requiresAuth: true }},
    { path: '/login', component: login, name: 'login', meta: { guest: true }},
    { path: '/logout', component: logout, name: 'logout', meta: { requiresAuth: true }},
    { path: '/itemsMenu', component: itemsMenu, name: 'itemsMenu'},
    { path: '/cookOrders', component: cookOrders, name: 'cookOrders', meta: { requiresAuth: true, isCook: true }},
];

const router = new VueRouter({
    routes:routes
});

router.beforeEach((to, from, next) => {
    if(to.matched.some(record => record.meta.requiresAuth)) {
        let user = JSON.parse(sessionStorage.getItem('user'));
        if (!user) {
            next("/login");

        } else {
            if ((to.matched.some(record => record.meta.isCook) && user.type !== 'cook') ||
               (to.matched.some(record => record.meta.isCashier) && user.type !== 'cashier') ||
               (to.matched.some(record => record.meta.isManager) && user.type !== 'manager') ||
               (to.matched.some(record => record.meta.isWaiter) && user.type !== 'waiter')) {

                next('/');

            } else {
                next();
            }
        }
        return;

    } else if(to.matched.some(record => record.meta.guest)) {
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
    methods: {
        notifyCounter: function() {
            this.$refs.shiftCounter.updateCounter(this.$store.state.user);
        },
        loadActiveData: function() {
            if (this.$store.state.user.type === 'cook') {
                this.$store.dispatch('loadOrders').then(() => {
                    console.log('"cook" connect');
                    this.$socket.emit('joinCook');
                });

            } else if (this.$store.state.user.type === 'waiter') {
                this.$store.dispatch('loadOrders');
            }
        },
        clearUserData: function(logout) {
            if (this.$store.state.user.type === 'cook') {
                if (!logout || (logout && this.$store.state.user.shift_active)) {
                    this.$socket.emit('leaveCook');
                }
            }

            this.$store.dispatch('clearUserData').then(() => {
                if (logout) {
                    this.$store.commit('clearUserAndToken')
                }
            });
        }
    },
    sockets: {
        connect: function() {
            console.log('socket connected');
        },
        propagateCookOrder: function(order) {
            this.$refs.notifications.addNotif("Order " + order.item.name +
                " has been picked up by another cook",
                "fa-coffee text-red", "/#/cookOrders");
            this.$store.commit('deleteOrder', order);
        }
    },
    created() {
        // console.log('-----');
        // console.log(this.$store.state.user);
        this.$store.commit('loadTokenAndUserFromSession');
        this.$store.commit('loadProfilesFolder');
        this.$store.dispatch('loadItems');
        if (this.$store.state.user && this.$store.state.user.shift_active) {
            this.loadActiveData();
        }
    }
}).$mount('#app');

