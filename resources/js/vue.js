/*jshint esversion: 6 */

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.Vue = require('vue');

window.pdfMake = require('pdfmake/build/pdfmake.js');
var pdfFonts = require('pdfmake/build/vfs_fonts.js');
pdfMake.vfs = pdfFonts.pdfMake.vfs;

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import VueSweetalert2 from 'vue-sweetalert2';
Vue.use(VueSweetalert2);

import VueSocketio from 'vue-socket.io';
if(window.location.hostname == 'dad.technic.pt'){
    Vue.use(new VueSocketio({
        debug: false,
        connection: 'http://dad.technic.pt:8080'
    }));
} else {
    Vue.use(new VueSocketio({
        debug: true,
        connection: 'http://127.0.0.1:8080'
    }));
}

import axios from 'axios'

Vue.prototype.$http = axios;

/*import VueSocketio from 'vue-socket.io';
Vue.use(new VueSocketio({
    debug: true,
    connection: env('YOUR_SERVER_URL') + ':8080'
}));*/

import store from './stores/global-store';

const performance = Vue.component('performance', require('./components/manager/performance/performance.vue'));
const stat = Vue.component('stat', require('./components/manager/stats/stats.vue'));
const meal = Vue.component('meal', require('./components/manager/meals/meals.vue'));
const invoice = Vue.component('invoice', require('./components/manager/invoices/invoices.vue'));
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
const verify = Vue.component('verify', require('./components/verify.vue'));
const password = Vue.component('verify', require('./components/account/password.vue'));
Vue.component('shift-counter', require('./components/shift_counter.vue'));
Vue.component('shift-button', require('./components/shift_button.vue'));
Vue.component('notifications', require('./components/notifications.vue'));

const routes = [
    { path: '/', redirect: '/itemsMenu', name: 'root'},
    { path: '/waiter', component: waiter, name: '/waiter', meta: { requiresAuth: true, isWaiter: true }},
    { path: '/cashier', component: cashier, name: 'cashier', meta: { requiresAuth: true, isCashier: true }},
    { path: '/tables', component: table, name: 'table', meta: { requiresAuth: true, isManager: true }},
    { path: '/items', component: item, name: 'item', meta: { requiresAuth: true, isManager: true }},
    { path: '/users', component: user, name: 'users', meta: { requiresAuth: true, isManager: true }},
    { path: '/invoices', component: invoice, name: 'invoice', meta: { requiresAuth: true, isManager: true }},
    { path: '/stats', component: stat, name: 'stat', meta: { requiresAuth: true, isManager: true }},
    { path: '/performance', component: performance, name: 'performance', meta: { requiresAuth: true, isManager: true }},
    { path: '/meals', component: meal, name: 'meal', meta: { requiresAuth: true, isManager: true }},
    { path: '/account', component: account, name: 'account', meta: { requiresAuth: true }},
    { path: '/login', component: login, name: 'login', meta: { guest: true }},
    { path: '/logout', component: logout, name: 'logout', meta: { requiresAuth: true }},
    { path: '/itemsMenu', component: itemsMenu, name: 'itemsMenu'},
    { path: '/cookOrders', component: cookOrders, name: 'cookOrders', meta: { requiresAuth: true, isCook: true }},
    { path: '/verify', component: verify, name: 'verify', meta: { guest: true }},
    { path: '/password', component: password, name: 'password', meta: { requiresAuth: true }}
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
            switch (this.$store.state.user.type) {
                case 'cook':
                    this.$store.dispatch('loadOrders').then(() => {
                        console.log('"cook" connect');
                        this.$socket.emit('joinCook');
                    });
                    break;
                case 'waiter':
                    this.$store.dispatch('loadPreparedOrdersAndMeals', {
                        socket: this.$socket,
                        id: this.$store.state.user.id
                    });
                    break;
                case 'cashier':
                    this.$store.dispatch('loadPendingInvoices').then(() => {
                        this.$socket.emit('joinCashier');
                    });

                    break;
                    /*case 'manager':
                        this.$socket.emit('joinManager');
                        break;
                    */
            }
        },
        clearUserData: function(logout) {
            if (!logout || (logout && this.$store.state.user.shift_active)) {
                switch (this.$store.state.user.type) {
                    case 'cook':
                        this.$socket.emit('leaveCook');
                        break;
                    case 'waiter':
                        this.$socket.emit('leaveWaiter', this.$store.state.user.id);
                        break;
                    case 'cashier':
                        this.$socket.emit('leaveCashier');
                        break;
                    /*case 'manager':
                        this.$socket.emit('leaveManager');
                        break;
                    */
                }
            }

            this.$store.dispatch('clearUserData', {
                type: this.$store.state.user.type
            }).then(() => {
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
        propagateCookOrderToCooks: function(order) {
            this.$refs.notifications.addNotif(`Order (${order.item.name}) has been picked up by another cook`,
                (order.item.type === 'dish' ? 'fa-cutlery' : 'fa-glass')
                + " text-red", "/#/cookOrders");


            this.$store.commit('deleteOrder', order)
        },
        propagateConfirmedOrder: function(order){
            this.$refs.notifications.addNotif(`New order (${order.item.name}) has been added`,
                (order.item.type === 'dish' ? 'fa-cutlery' : 'fa-glass')
                + " text-red", "/#/cookOrders");

            this.$store.commit('addOrder', order);
        },
        propagateCookOrderToWaiter: function(order) {
            this.$store.commit('updateWaiterOrder', order);
            if (order.state === 'prepared') {
                this.$refs.notifications.addNotif(`Order (${order.item.name}) has been prepared`,
                    (order.item.type === 'dish' ? 'fa-cutlery' : 'fa-glass')
                    + " text-red", "/#/waiter");

                this.$store.commit('addWaiterPreparedOrder', order);
            }
        },
        propagatePendingInvoice: function(invoice) {
            this.$refs.notifications.addNotif(`New pending (${invoice.id}) invoice has been added`,
                "fa-briefcase text-red", "/#/cashier");

            this.$store.commit('addPendingInvoice', invoice);
        },
        propagateRemovePendingInvoice: function(invoice) {
            this.$refs.notifications.addNotif(`Invoice (${invoice.id}) has been paid by another cashier`,
                "fa-briefcase text-red", "/#/cashier");

            this.$store.commit('removePendingInvoice', invoice);
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
