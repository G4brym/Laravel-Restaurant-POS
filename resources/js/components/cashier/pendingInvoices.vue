<template>
    <div class="box" :class="{'box-colapsed': !showPending}">
        <div class="box-header with-border">
            <h3 class="box-title">Pending Invoices</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse" v-on:click.prevent="togglePendingBox()">
                    <i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Table</th>
                    <th>Waiter</th>
                    <th>Total Price</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <template v-for="(invoice, index) in pendingInvoices">
                    <tr class="table-warning">
                        <td>{{ invoice.table }}</td>
                        <td>{{ invoice.waiter }}</td>
                        <td>{{ invoice.total_price }}</td>
                        <td>
                            <a class="btn btn-sm btn-success" v-on:click.prevent="markPaid(invoice, index)">Mark as Paid</a>
                        </td>
                    </tr>
                </template>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script type="text/javascript">
    // Component code

    module.exports = {
        props: ['pendingInvoices'],
        data: function(){
            return {
                showPending: true
            }
        },
        methods: {
            markPaid: function (invoice, index) {
                this.$emit('mark-paid', invoice, index);
            },
            togglePendingBox: function () {
                if(this.showPending){
                    this.showPending = false;
                } else {
                    this.showPending = true;
                }
            },
        },
    }
</script>

<style scoped>

</style>
