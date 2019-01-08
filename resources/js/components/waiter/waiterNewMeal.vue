<template>
    <div class="box box-info" v-if="meal">
        <div class="box-header with-border">
            <h3 class="box-title">Meal {{ meal.id }} - Table {{meal.table_number_id}}</h3>
        </div>
        <div class="box-body">
            <table class="table table-striped" v-if="completedOrders.length !== 0">
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
                <template v-for="order in completedOrders">
                    <tr :class="{'table-warning': order.state === 'pending',
                                 'table-green': order.state === 'confirmed'}" :key="order.id">
                        <td><i class="fa" :class="{'fa-glass': order.item.type === 'drink',
                                                    'fa-cutlery': order.item.type === 'dish'}"
                               aria-hidden="true"></i>
                            {{ order.item.name }}
                        </td>
                        <td>{{ order.item.price }}€</td>
                        <td>
                            {{ order.responsible_cook ? order.responsible_cook.name : 'No cook yet' }}
                        </td>
                        <td>
                            <span class="label label-info"
                                  :class="{'label-warning': order.state === 'pending',
                                           'label-success': order.state === 'confirmed'}">
                                {{ order.state }}
                            </span>
                        </td>
                        <td>
                            <a class="btn btn-sm btn-danger" v-if="order.state === 'pending'"
                               @click="deleteOrder($event.target, order)">Delete</a>
                        </td>
                    </tr>
                </template>
                </tbody>
            </table>
            <template v-for="order in newOrders" v-if="order.info">
                <order :num="order.num" :meal-id="meal.id" :list="'itemsList'"
                       @set-order-info="setOrderInfo(order, $event)" :key="order.num"/>
            </template>

            <button class="btn btn-link" @click="addOrderElement">   + Add order to meal</button>
        </div>
        <div class="box-footer">
            <button type="button" class="btn btn-primary pull-left"
                    @click="doneMeal">Done</button>
        </div>
    </div>
</template>

<script>
    import orderElem from './order.vue';

    export default {
        props: ['meal'],
        components: {
            'order': orderElem
        },
        data: function() {
            return {
                newOrders: [],
                completedOrders: [],
                timeouts: {},
                counter: 0
            }
        },
        methods: {
            setOrderInfo: function(order, completedOrder) {
                this.deleteElem(this.newOrders, order);
                this.completedOrders.push(completedOrder);
                this.timeouts[completedOrder.id] =
                    setTimeout(() => {
                        this.confirmOrder(completedOrder);
                    }, 5000);
            },
            getNewOrder: function() {
                return {
                    'info': {},
                    'num': ++this.counter
                };
            },
            addOrderElement: function() {
                this.newOrders.push(this.getNewOrder());
            },
            deleteOrder: function(htmlElem, order) {
                clearTimeout(this.timeouts[order.id]);
                delete this.timeouts[order.id];

                htmlElem.setAttribute("disabled", "");
                this.$http.delete(
                    `/api/orders/${order.id}`
                ).then(() => {
                    this.deleteElem(this.completedOrders, order);

                }).catch((payload) => {
                    if (payload.response.status) {
                        order.state = "confirmed";
                    }

                    /////////////////////////////////////////
                    // SweetAlert
                    const toast = this.$swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    toast({
                        title: `Could not delete order`,
                        type: 'error'
                    });
                    /////////////////////////////////////////
                });
            },
            confirmOrder: function(order) {
                clearTimeout(this.timeouts[order.id]);
                delete this.timeouts[order.id];
                order.state = "confirmed";
                return order;
            },
            doneMeal: function() {
                this.completedOrders.forEach((order) => {
                    if (order.state === "pending") {
                        this.confirmOrder(order);
                    }

                    this.$http.put(
                        `/api/orders/${order.id}/confirm`
                    ).then((response) => {
                        if (response.status !== 202) {
                            this.$socket.emit('propagateConfirmedOrder', order);
                        }
                    })
                    .catch(() => {
                        this.unknownError();
                    });
                });

                this.$emit('created-meal', this.completedOrders);
            },
            deleteElem: function(array, elem) {
                array.splice(array.indexOf(elem), 1);
            },
            unknownError: function() {
                this.$swal({
                    type: 'error',
                    title: 'Oops',
                    text: "Something went wrong..."
                });
            }
        },
        mounted: function() {
            this.addOrderElement();
        }
    }
</script>

<style scoped>

</style>
