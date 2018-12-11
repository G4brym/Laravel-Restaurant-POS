<template>
    <div>
        <prepared-orders :preparedOrders="preparedOrders" @deliver-click="deliverOrder" ref="preparedOrdersRef"></prepared-orders>

        <meals :waiterMeals="waiterMeals" @delete-click="deleteOrder" ref="mealsRef"></meals>
    </div>
</template>

<script type="text/javascript">
    // Component code
    import PreparedOrders from './waiter/preparedOrders.vue';
    import Meals from './waiter/meals.vue';

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
                        // TODO
                        axios.delete('api/users/' + user.id)
                            .then(response => {
                                Swal(
                                    'Deleted!',
                                    'Your file has been deleted.',
                                    'success'
                                )
                            });
                    }
                })
            },
            deliverOrder: function (order, index) {
                axios.post('api/orders/' + order.id + '/deliver')
                    .then(response => {
                        if (response.status == 200) {
                            this.preparedOrders.splice(index, 1);
                            //this.getPreparedOrders();
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
                axios.get('api/orders?states=prepared')
                    .then(response=>{
                        this.preparedOrders = response.data.data;
                    });
            },
            getWaiterMeals: function(){
                axios.get('api/meals?waiter=true&unfinished=true')
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
            'meals': Meals
        },
    }
</script>

<style scoped>

</style>
