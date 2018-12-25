<template>
    <div>
        <prepared-orders :preparedOrders="preparedOrders" @deliver-click="deliverOrder" ref="preparedOrdersRef"></prepared-orders>

        <template v-for="(meal, index) in waiterMeals">
            <meal-box :meal="meal" :index="index" @terminate-meal="terminateMeal" @delete-click="deleteOrder" ref="mealsRef"></meal-box>
        </template>
    </div>
</template>


<script type="text/javascript">
    // Component code
    import PreparedOrders from './waiter/preparedOrders.vue';
    import MealBox from './waiter/mealBox.vue';

    export default {
        data: function(){
            return {
                preparedOrders: [],
                waiterMeals: [],
            }
        },
        methods: {
            deleteOrder: function (order) {
                this.$swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        // TODO Gabriel
                        this.$http.delete('api/users/' + user.id)
                            .then(response => {
                                this.$swal({
                                    type: 'success',
                                    title: 'Deleted',
                                    text: 'This order was deleted',
                                });
                            });
                    }
                })
            },
            terminateMeal: function (meal, index) {
                this.$http.post('api/meals/' + meal.id + '/terminate')
                    .then(response => {
                        if (response.status == 200) {
                            this.getWaiterMeals();

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
                        if (response.status == 200) {
                            this.preparedOrders.splice(index, 1);

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
            getPreparedOrders: function(){
                this.$http.get('api/orders?states=prepared')
                    .then(response=>{
                        this.preparedOrders = response.data.data;
                    });
            },
            getWaiterMeals: function(){
                this.$http.get('api/meals?waiter=true&unfinished=true')
                    .then(response=>{
                        this.waiterMeals = response.data.data;
                    });
            },
        },
        created: function () {
            this.getPreparedOrders();
            this.getWaiterMeals();
        },
        components: {
            'prepared-orders': PreparedOrders,
            'meal-box': MealBox
        },
    }
</script>

<style scoped>

</style>
