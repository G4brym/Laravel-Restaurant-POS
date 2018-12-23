<template>
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Orders</h3>
        </div>
        <div class="box-body">
            <template v-if="orders">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Item</th>
                        <th>State</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr class="table-warning" v-for="order in orders" :key="order.id">
                            <td>{{ order.item.name }}</td>
                            <td>
                                <span class="label"
                                      :class="order.state === 'confirmed' ? 'label-success' : 'label-warning'">
                                    {{ order.state }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </template>
            <div class="text-center" v-else>
                Loading...
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        computed: {
            orders: function() {
                return this.$store.state.orders;
            }
        }
    }
</script>

<style scoped>

</style>
