<template>
    <div class="box box-info" v-if="this.$store.state.user.shift_active === 0">
        <div class="box-header with-border">
            <div class="text-center">
                You are not doing a shift
            </div>
        </div>
    </div>
    <div v-else>
        <button class="btn btn-lg btn-block btn-info" @click="newMeal">Create new meal</button>
        <br/>
        <prepared-orders :preparedOrders="preparedOrders" @deliver-click="deliverOrder" ref="preparedOrdersRef"></prepared-orders>

        <template v-for="meal in newWaiterMeals">
            <waiter-new-meal :meal="meal" @created-meal="createdMeal(meal, $event)" :key="meal.id"></waiter-new-meal>
        </template>

        <template v-for="(meal, index) in waiterMeals">
            <meal-box :meal="meal" :mealIndex="index" @terminate-meal="terminateMeal(meal)" :key="meal.id"></meal-box>
        </template>
    </div>
</template>


<script type="text/javascript">
    // Component code
    import PreparedOrders from './waiter/preparedOrders.vue';
    import MealBox from './waiter/mealBox.vue';
    import NewMeal from './waiter/waiterNewMeal.vue';

    export default {
        computed: {
            waiterMeals: function() {
                return this.$store.state.waiter.meals;
            },
            newWaiterMeals: function() {
                return this.$store.state.waiter.newMeals;
            },
            preparedOrders: function() {
                return this.$store.state.waiter.preparedOrders;
            }
        },
        methods: {
            terminateMeal: function (meal) {
                this.$http.post('api/meals/' + meal.id + '/terminate')
                    .then(response => {
                        if (response.status === 200) {
                            this.getWaiterMeals();
                            this.$socket.emit('propagateTerminateOrder');

                            this.$swal({
                                type: 'success',
                                title: 'Terminated',
                                text: 'This meal was terminated',
                            });
                        } else {
                            this.$swal({
                                type: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                            });
                        }
                    });
            },
            deliverOrder: function (order, index) {
                this.$http.post('api/orders/' + order.id + '/deliver')
                    .then(response => {
                        if (response.status === 200) {
                            order.state = "delivered";
                            this.$store.commit('removeWaiterPreparedOrders', index);
                            this.$socket.emit('propagateWaiterDeliveries');
                            this.$store.commit('updateWaiterOrder', order);

                            this.$swal({
                                type: 'success',
                                title: 'Delivered',
                                text: 'This order was delivered',
                            });
                        } else {
                            this.$swal({
                                type: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                            });
                        }
                    });
            },
            newMeal: function() {
                this.$swal({
                    title: 'Table number?',
                    input: 'number',
                    showCancelButton: true,
                    confirmButtonText: 'Submit',
                    showLoaderOnConfirm: true,
                    allowOutsideClick: false,
                    preConfirm: (tableNumber) => {
                        return this.$http.get(`/api/tables/${tableNumber}/check`)
                            .then(response => {
                                console.log(response);
                                if (response.data.data) {
                                    if (response.data.data === 'taken') {
                                        return {'status': 'taken'};

                                    } else {
                                        this.$store.commit('addNewWaiterMeal', response.data.data);
                                        return {'status': 'success',
                                                'data': response.data.data};
                                    }
                                }
                            })
                            .catch(payload => {
                                return {'status': 'fail', 'code': payload.response.status};
                            });
                    }
                }).then((result) => {
                    if (result.value.status === 'taken') {
                        /////////////////////////////////////////
                        // SweetAlert
                        this.$swal({
                            type: 'error',
                            title: 'Table is taken',
                            text: "An active meal already exists on this table"
                        });
                        /////////////////////////////////////////

                    } else if (result.value.status === 'success') {
                        /////////////////////////////////////////
                        // SweetAlert
                        const toast = this.$swal.mixin({
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        toast({
                            title: `Active meal created at table number ${result.value.data.table_number_id}`,
                            type: 'success'
                        });
                        /////////////////////////////////////////

                    } else {
                        if (result.value.code === 404) {
                            /////////////////////////////////////////
                            // SweetAlert
                            this.$swal({
                                type: 'error',
                                title: 'Non-existing table',
                                text: "The submitted table number does not exist"
                            });
                            /////////////////////////////////////////

                        } else {
                            /////////////////////////////////////////
                            // SweetAlert
                            this.$swal({
                                type: 'error',
                                title: 'Oops',
                                text: "Something went wrong..."
                            });
                            /////////////////////////////////////////
                        }
                    }
                });
            },
            createdMeal: function (meal, orders) {
                this.$store.commit('removeNewWaiterMeal', meal);
                meal.orders = orders;
                this.$store.commit('addWaiterMeal', meal);
            },
            getPreparedOrders: function(){
                this.$http.get('api/orders?states=prepared')
                    .then(response=>{
                        this.$store.commit('setWaiterPreparedOrders', response.data.data);
                    });
            },
            getWaiterMeals: function(){
                this.$http.get('api/meals?waiter=true&unfinished=true')
                    .then(response=>{
                        let meals = response.data.data;
                        meals.forEach((meal) => {
                            meal.orders.forEach((order) => {
                                if (order.state === "pending") {
                                    this.$http.put(`/api/orders/${order.id}/confirm`)
                                    order.state = "confirmed";
                                    this.$socket.emit('propagateConfirmedOrder', order);
                                }
                            })
                        });

                        this.$store.commit('setWaiterMeals', meals);
                    });
            },
        },
        created: function () {
            if (!this.$store.state.waiter.preparedOrders) {
                this.getPreparedOrders();
            }

            if (!this.$store.state.waiterMeals) {
                this.getWaiterMeals();
            }
        },
        components: {
            'prepared-orders': PreparedOrders,
            'meal-box': MealBox,
            'waiter-new-meal': NewMeal
        },
    }
</script>

<style scoped>

</style>
