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
        waiter: {
            meals: null,
            newMeals: [],
            preparedOrders: null
        },
        cashier: {
            invoices: null,
            pendingInvoices: null
        }
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
            state.token = token;
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
        addOrder (state, order) {
            state.orders.push(order);
        },
        updateOrders (state, order) {
            let found = false;
            let indexFirstConfirm = null;
            for (let index in state.orders) {
                if (!found && state.orders[index].state === 'confirmed') {
                    indexFirstConfirm = index;
                    found = true;
                }

                if (order.id === state.orders[index].id) {
                    state.orders.splice(index, 1);
                    state.orders.splice(indexFirstConfirm, 0, order);
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
        setWaiterMeals (state, meals) {
            state.waiter.meals = meals;
        },
        addWaiterMeal (state, meal) {
            state.waiter.meals.push(meal);
        },
        clearWaiterMeals (state) {
            state.waiter.meals = null;
        },
        removeWaiterMeal (state, meal) {
            state.waiter.meals.splice(state.waiter.meals.indexOf(meal), 1);
        },
        addNewWaiterMeal (state, meal) {
            state.waiter.newMeals.push(meal);
        },
        removeNewWaiterMeal (state, meal) {
            state.waiter.newMeals.splice(state.waiter.newMeals.indexOf(meal), 1);
        },
        loadWaiterPreparedOrders (state, orders) {
            state.waiter.preparedOrders = orders;
        },
        addWaiterPreparedOrder (state, order) {
            state.waiter.preparedOrders.push(order);
        },
        removeWaiterPreparedOrders (state, orderIndex) {
            state.waiter.preparedOrders.splice(orderIndex, 1);
        },
        updateWaiterOrder (state, order) {
            for (let i = 0; i < state.waiter.meals.length; i++) {
                if (order.meal !== state.waiter.meals[i].id) {
                    continue;
                }

                for (let j = 0; j < state.waiter.meals[i].orders.length; j++) {
                    if (state.waiter.meals[i].orders[j].id === order.id) {
                        Vue.set(state.waiter.meals[i].orders, j, order);
                        break;
                    }
                }
                break;
            }
        },
        loadPendingInvoices (state, invoices) {
            state.cashier.pendingInvoices = invoices;
        },
        addPendingInvoice (state, invoice) {
            state.cashier.pendingInvoices.push(invoice);
        },
        removePendingInvoice (state, invoice) {
            state.cashier.pendingInvoices.splice(state.cashier.pendingInvoices.indexOf(invoice), 1);
        },
        loadInvoices (state, invoices) {
            state.cashier.invoices = invoices;
        }
    },
    actions: {
        clearUserData (context, { type }) {
            if (type === 'waiter' || type === 'cook') {
                if (context.state.orders) {
                    context.commit('clearOrders');
                }

                if (type === 'waiter') {
                    context.commit('clearWaiterMeals');
                }
            }

        },
        loadOrders ({ commit }) {
            axios.get('api/orders')
                .then(response => {
                    commit('loadOrders', response.data.data);
                });
        },
        loadItems ({ commit }) {
            axios.get('api/items')
                .then(response => {
                    commit('loadItems', response.data.data);
                });
        },
        loadPreparedOrdersAndMeals (context, { socket, id }) {
            axios.all([
                axios.get('api/orders?states=prepared'),
                axios.get('api/meals?waiter=true&unfinished=true')
                ])
                .then(axios.spread(function (orders, meals) {
                    context.commit('loadWaiterPreparedOrders', orders.data.data);

                    let pendingOrders = [];
                    let mealsArray = meals.data.data;
                    mealsArray.forEach((meal) => {
                        meal.orders.forEach((order) => {
                            if (order.state === "pending") {
                                axios.put(`/api/orders/${order.id}/confirm`);
                                order.state = "confirmed";
                                pendingOrders.push(order);
                            }
                        })
                    });

                    context.commit('setWaiterMeals', mealsArray);

                    socket.emit('joinWaiter', id);
                    for (let order of pendingOrders) {
                        socket.emit('propagateConfirmedOrder', order);
                    }
                }));
        },
        loadPendingInvoices ({ commit }) {
            axios.get('api/invoices?pending=true')
                .then(response => {
                    commit('loadPendingInvoices', response.data.data);
                });
        },
        loadInvoices (context, { page }) {
            return new Promise((resolve) => {
                // Do something here... lets say, a http call using vue-resource
                axios.get(`api/invoices?page=${page}`).then(response => {
                    context.commit('loadInvoices', response.data.data);
                    resolve(response.data);
                });
            });
        }
    }
});
