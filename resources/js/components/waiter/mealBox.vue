<template>
    <div class="box" :class="{'box-colapsed': !showMeal}">
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
                    <th>Cook</th>
                    <th>State</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <template v-for="order in meal.orders">
                    <tr v-if="order.state === 'pending' || order.state === 'confirmed'"
                        :class="{'table-warning': order.state === 'pending', 'table-green': order.state === 'confirmed'}">
                        <td>{{ order.item }}</td>
                        <td>{{ order.responsible_cook }}</td>
                        <td><span class="label"
                                  :class="{'label-warning': order.state === 'pending', 'label-success': order.state === 'confirmed'}">{{ order.state }}</span>
                        </td>
                        <td>
                            <a class="btn btn-sm btn-danger" v-if="order.state === 'pending'"
                               v-on:click.prevent="deleteOrder(order)">Delete</a>
                        </td>
                    </tr>
                </template>
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
        <!-- /.box-footer-->
    </div>
</template>

<script type="text/javascript">
    // Component code

    module.exports = {
        props: ['meal'],
        data: function(){
            return {
                showMeal: true
            }
        },
        methods: {
            deleteOrder: function (order) {
                this.$emit('delete-click', order);
            },
            toggleMeal: function () {
                if(this.showMeal){
                    this.showMeal = false;
                } else {
                    this.showMeal = true;
                }
            }
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
