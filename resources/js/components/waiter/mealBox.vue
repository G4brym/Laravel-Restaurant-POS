<template>
    <div class="box" :id="'box' + meal.id" data-widget="box-widget">
        <div class="box-header with-border">
            <h3 class="box-title">Meal {{ meal.id }} - Table {{meal.table_number_id}}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse" v-on:click.prevent="toggleMeal()">
                    <i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Cooker</th>
                    <th>State</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                    <tr v-for="order in (this.showAllOrders ? orders : compactedOrders)"
                        :class="{'table-warning': order.state === 'pending',
                                 'table-green': order.state === 'confirmed'}"
                        :key="order.id">
                        <td><i class="fa" :class="{'fa-glass': order.item.type == 'drink', 'fa-cutlery': order.item.type == 'dish'}" aria-hidden="true"></i> {{ order.item.name }}</td>
                        <td>{{ order.item.price }}€</td>
                        <td>{{ order.responsible_cook ? order.responsible_cook.name : 'No cook yet' }}</td>
                        <td><span class="label label-info"
                                  :class="{'label-warning': order.state === 'pending', 'label-success': order.state === 'confirmed'}">{{ order.state }}</span>
                        </td>
                        <td>
                            <a class="btn btn-sm btn-danger" v-if="order.state === 'pending'"
                               @click.prevent="deleteOrder(order)">Delete</a>
                        </td>
                    </tr>
                <tr>
                    <td>Total Price:</td>
                    <td>{{ totalPrice }}€</td>
                    <td></td>
                    <td></td>
                    <td>
                        <a class="btn btn-sm btn-success" v-if="this.showAllOrders"
                           @click="showAllOrders = !showAllOrders">
                            Less Details
                        </a>
                        <a class="btn btn-sm btn-success" v-if="!this.showAllOrders"
                           @click="showAllOrders = !showAllOrders">
                            More Details
                        </a>
                        <a class="btn btn-sm btn-danger" @click="terminateMeal">Terminate</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script type="text/javascript">
    // Component code

    module.exports = {
        props: ['meal', 'mealIndex'],
        data: function(){
            return {
                showAllOrders: false,
                totalPrice: 0,
                compactedOrders: [],
                expanded: true,
                safeTerminate: false
            }
        },
        computed: {
            orders: function() {
                return this.$store.state.waiter.meals[this.mealIndex].orders;
            }
        },
        watch: {
            orders: function() {
                this.generateCompactedOrders();
            }
        },
        mounted: function() {
            this.generateCompactedOrders();
        },
        methods: {
            generateCompactedOrders: function() {
                let compressedOrders = [];
                let tmp_price = 0;
                let safeTermination = true;

                for (let i = 0; i < this.orders.length; i++) {
                    tmp_price += parseFloat(this.orders[i].item.price);
                    if(this.orders[i].state === 'pending' || this.orders[i].state === 'confirmed'){
                        compressedOrders.push(this.orders[i])
                    }

                    if(this.orders[i].state !== 'delivered'){
                        safeTermination = false;
                    }
                }
                this.totalPrice = tmp_price.toFixed(2);

                this.compactedOrders = Object.assign([], compressedOrders);
                this.safeTerminate = safeTermination;
            },
            terminateMeal: function () {
                if(!this.safeTerminate){
                    this.$swal({
                        title: 'Are you sure?',
                        text: "One or more orders are still not delivered",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, Terminate it!'
                    }).then((result) => {
                        if (result.value) {
                            this.$emit('terminate-meal');
                        }
                    })
                } else {
                    this.$emit('terminate-meal');
                }
            },
            deleteOrder: function (order) {
                this.$emit('delete-click', order);
            },
            toggleMeal: function () {
                if(this.expanded){
                    $("#box" + this.meal.id).boxWidget('collapse');
                } else {
                    $("#box" + this.meal.id).boxWidget('expand');
                }

            }
        }
    }
</script>

<style scoped>
    .table-green {
        background-color: #F1F8E9 !important;
    }

    .table-warning {
        background-color: #FFF3E0 !important;
    }
</style>
