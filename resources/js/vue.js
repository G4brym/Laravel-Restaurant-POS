/*jshint esversion: 6 */

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import VueRouter from 'vue-router';

window.Vue = require('vue');
Vue.use(VueRouter);
const userList = Vue.component('user-list',
    require('./components/userList.vue'));
const userEdit = Vue.component('user-edit',
    require('./components/editUser.vue'));
const users = Vue.component('users',
    require('./components/users.vue'));
const game = Vue.component('tictactoe',
    require('./components/tictactoe.vue'));

const routes = [
    {path: '/', redirect: '/users'},
    {path: '/users', component: users},
    {path: '/tictactoe', component: game}
];

const router = new VueRouter({ routes });

const app = new Vue({
    router,
    data: {
        player1 : undefined,
        player2 : undefined
    },
    el: "#app"
});