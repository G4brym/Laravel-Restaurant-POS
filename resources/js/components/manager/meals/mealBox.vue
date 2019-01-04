<template>
    <div class="box" :id="'box' + meal.id" data-widget="box-widget">
        <div class="box-header with-border">
            <h3 class="box-title">Meal {{ meal.id }} - Table {{meal.table_number_id}} - State {{meal.state}}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse" v-on:click.prevent="toggleMeal()">
                    <i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <p>Waiter: {{meal.responsible_waiter}}</p>
            <p>Date: {{meal.start.slice(0,10)}}</p>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Cooker</th>
                    <th>State</th>
                </tr>
                </thead>
                <tbody>
                <template v-for="order in orders">
                    <tr :class="{'table-warning': order.state === 'pending', 'table-green': order.state === 'confirmed'}">
                        <td><i class="fa" :class="{'fa-glass': order.item.type == 'drink', 'fa-cutlery': order.item.type == 'dish'}" aria-hidden="true"></i> {{ order.item.name }}</td>
                        <td>{{ order.item.price }}€</td>
                        <td>{{ order.responsible_cook ? order.responsible_cook.name : 'No cook yet' }}</td>
                        <td>
                            <span class="label label-info" :class="{'label-warning': order.state === 'pending', 'label-success': order.state === 'confirmed'}">{{ order.state }}
                              </span>
                        </td>
                        <td>
                            <a class="btn btn-sm btn-danger" v-if="order.state === 'pending'"
                               v-on:click.prevent="deleteOrder(order)">Delete</a>
                        </td>
                    </tr>
                </template>
                <tr>
                    <td>Total Price:</td>
                    <td>{{ totalPrice }}€</td>
                    <td></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script type="text/javascript">
    // Component code

    module.exports = {
        props: ['meal', 'index'],
        data: function(){
            return {
                totalPrice: 0,
                compactedOrders: [],
                orders: [],
                expanded: true,
            }
        },
        methods: {
            toggleMeal: function () {
                if(this.expanded){
                    $("#box" + this.meal.id).boxWidget('collapse');
                } else {
                    $("#box" + this.meal.id).boxWidget('expand');
                }

            }
        },
        mounted() {
            let tmp_price = 0;

            for (var i = 0; i < this.meal.orders.length; i++) {
                tmp_price += parseFloat(this.meal.orders[i].item.price);
                if(this.meal.orders[i].state === 'pending' || this.meal.orders[i].state === 'confirmed'){
                    this.compactedOrders.push(this.meal.orders[i])
                }

            }
            this.totalPrice = tmp_price.toFixed(2);
            this.orders = this.compactedOrders;
        },
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
