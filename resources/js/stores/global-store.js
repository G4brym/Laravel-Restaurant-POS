/*jshint esversion: 6 */

import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    state: { 
        token: "",
        user: null, 
        departments: [],
        orders: []
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
        setUser: (state, user) => {
            state.user =  user;
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
        loadDepartments: (state) => {
            axios.get('api/departments')
                .then(response => {
                    state.departments = response.data.data;
                });
        },
        loadOrders: (state) => {
            axios.get('api/orders')
                .then(response => {
                    state.ordersw = response.data.data;
                });
        }
    } 
});
