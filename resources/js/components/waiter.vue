<template>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">My Meals</h3>
        </div>
        <div class="box-body">
            <div class="panel panel-info" v-for="meal in this.$store.state.waiterMeals"  :key="meal.id">
                <div class="panel-heading">
                    <h3 class="panel-title">Meal {{ meal.id }}</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Item (change later to name)</th>
                            <th>Cook</th>
                            <th>State</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="order in meal.orders"  :key="order.id">

                            <td>{{ order.item_id }}</td>
                            <td>{{ order.responsible_cook }}</td>
                            <td>{{ order.state }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            Footer
        </div>
        <!-- /.box-footer-->
    </div>
</template>

<script type="text/javascript">
	// Component code

	module.exports={
        created: function () {
            this.$store.commit('loadWaiterMeals');
        },
	}
</script>

<style scoped>
/*	  Specific style applied only on the component*/
</style>
