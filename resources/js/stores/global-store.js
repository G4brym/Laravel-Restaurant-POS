/*jshint esversion: 6 */

import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        token: "",
        user: null,
        departments: [],
        orders: null,
        items: null,
        tables: [],
    },
    mutations: {
        clearUserAndToken: (state) => {
            state.user = null;
            state.token = "";
            sessionStorage.removeItem('user');
            sessionStorage.removeItem('token');
            axios.defaults.headers.common.Authorization = undefined;
        },
        clearUser: (state) => {
            state.user = null;
            sessionStorage.removeItem('user');
        },
        clearToken: (state) => {
            state.token = "";
            sessionStorage.removeItem('token');
            axios.defaults.headers.common.Authorization = undefined;
        },
        clearOrders (state) {
            state.orders = null;
        },
        setUser: (state, user) => {
            state.user = Object.assign({}, user);
            sessionStorage.setItem('user', JSON.stringify(user));
        },
        setToken: (state, token) => {
            state.token= token;
            sessionStorage.setItem('token', token);
            axios.defaults.headers.common.Authorization = "Bearer " + token;
        },
        loadTokenAndUserFromSession: (state) => {
            state.token = "";
            state.user = null;
            let token = sessionStorage.getItem('token');
            let user = sessionStorage.getItem('user');
            if (token) {
                state.token = token;
                axios.defaults.headers.common.Authorization = "Bearer " + token;
            }
            if (user) {
                state.user = JSON.parse(user);
            }
        },
        loadOrders (state, orders) {
            state.orders = orders;
        },
        loadTables: (state) => {
            axios.get('api/tables')
                .then(response => {
                    state.tables = response.data.data;
                });
        },
        loadProfilesFolder: (state) => {
            state.profileFolder = "/storage/profiles";
        },
        loadItems (state, items) {
            state.items = items;
        },
        updateOrders (state, order) {
            for (let index in state.orders) {
                if (order.id === state.orders[index].id) {
                    state.orders.splice(index, 1);
                    state.orders.unshift(order);
                }
            }
        },
        deleteOrder (state, order) {
            for (let index in state.orders) {
                if (order.id === state.orders[index].id) {
                    Vue.delete(state.orders, index);
                }
            }
        },
    },
    actions: {
        clearUserData (context) {
            if (context.state.orders) {
                context.commit('clearOrders');
            }
        },
        loadOrders (context) {
            axios.get('api/orders')
                .then(response => {
                    context.commit('loadOrders', response.data.data);
                });
        },
        loadItems ({ commit }) {
            axios.get('api/items')
                .then(response => {
                    commit('loadItems', response.data.data);
                });
        }
    }
});
