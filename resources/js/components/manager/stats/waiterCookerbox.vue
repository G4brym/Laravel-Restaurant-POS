<template>
    <div class="box" :id="'box' + stat.id" data-widget="box-widget">
        <div class="box-header with-border">
            <h3 class="box-title">ID: {{ stat.id }} - {{stat.name}}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse" v-on:click.prevent="toogle()">
                    <i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>AVG Orders p/day</th>
                        <template v-if="stat.type == 'waiter'">
                            <th>AVG Meals p/day</th>
                        </template>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <template v-for="order in stat.avgOrders">
                            <td>{{ order.ordersPerDay }}</td>
                        </template>
                        <template v-if="stat.type == 'waiter'" v-for="meal in stat.avgMeals">
                            <td>{{ meal.mealsPerDay }}</td>
                        </template>  
                    </tr>       
                </tbody>
            </table>
        </div>
    </div>
</template>

<script type="text/javascript">
    // Component code

    module.exports = {
        props: ['stat', 'index'],
        data: function(){
            return {
            }
        },
        mounted() {
            $("#box" + this.stat.id).boxWidget('collapse');
        },
        updated() {
            $("#box" + this.stat.id).boxWidget('collapse');
        },
        methods: {
            toogle: function () {
                $("#box" + this.stat.id).boxWidget('collapse');
            }
        },
    }
</script>

<style scoped>

</style>
