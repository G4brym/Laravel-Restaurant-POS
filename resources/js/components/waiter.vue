<template>
    <div>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Prepared Orders</h3>

                <!--<div class="box-tools pull-right">-->
                <!--<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"-->
                <!--title="Collapse" v-on:click.prevent="toggleBox(meal.id)">-->
                <!--<i class="fa fa-minus"></i></button>-->
                <!--</div>-->
            </div>
            <div class="box-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Item</th>
                        <th>Cook</th>
                        <th>State</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="order in this.$store.state.preparedOrders">
                        <tr class="table-warning">
                            <td>{{ order.item }}</td>
                            <td>{{ order.responsible_cook }}</td>
                            <td><span class="label" :class="label-warning">{{ order.state }}</span></td>
                            <td>
                                <a class="btn btn-sm btn-warning" v-on:click.prevent="deliverOrder(order)">Deliver</a>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="box" v-for="meal in this.$store.state.waiterMeals"  :key="meal.id">
            <div class="box-header with-border">
                <h3 class="box-title">Meal {{ meal.id }} - Table {{meal.table_number_id}}</h3>

                <!--<div class="box-tools pull-right">-->
                    <!--<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"-->
                            <!--title="Collapse" v-on:click.prevent="toggleBox(meal.id)">-->
                        <!--<i class="fa fa-minus"></i></button>-->
                <!--</div>-->
            </div>
            <div class="box-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Item</th>
                        <th>Cook</th>
                        <th>State</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="order in meal.orders">
                        <tr v-if="order.state === 'pending' || order.state === 'confirmed'" :class="{'table-warning': order.state === 'pending', 'table-green': order.state === 'confirmed'}">
                            <td>{{ order.item }}</td>
                            <td>{{ order.responsible_cook }}</td>
                            <td><span class="label" :class="{'label-warning': order.state === 'pending', 'label-success': order.state === 'confirmed'}">{{ order.state }}</span></td>
                            <td>
                                <a class="btn btn-sm btn-danger" v-if="order.state === 'pending'" v-on:click.prevent="deleteOrder(order)">Delete</a>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                Footer
            </div>
            <!-- /.box-footer-->
        </div>
        <!-- /.box -->
    </div>
</template>

<script type="text/javascript">
	// Component code

	module.exports={
        created: function () {
            this.$store.commit('loadWaiterMeals');
            this.$store.commit('loadPreparedOrders');
        },
        methods: {
            deleteOrder: function(order){
                this.$emit('order-delete-click', order);
            },
        },
	}
</script>

<style scoped>
    .table-green{
        background-color: #F1F8E9 !important;
    }
    .table-warning{
        background-color: #FFF3E0 !important;
    }
</style>
