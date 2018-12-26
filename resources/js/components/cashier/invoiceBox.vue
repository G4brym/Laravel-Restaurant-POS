<template>
    <div class="box" :id="'box' + invoice.id" data-widget="box-widget">
        <div class="box-header with-border">
            <h3 class="box-title">Invoice {{ invoice.id }} - Table {{invoice.table}} - {{invoice.date}}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse" v-on:click.prevent="toggleInvoice()">
                    <i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <p>Waiter: {{invoice.waiter}}</p>
            <p>Costumer: {{invoice.name}} PT{{invoice.nif}}</p>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Sub-total</th>
                </tr>
                </thead>
                <tbody>
                <template v-for="order in invoice.items">
                    <tr>
                        <td>{{order.item.name}}</td>
                        <td>{{order.unit_price}}</td>
                        <td>{{order.quantity}}</td>
                        <td>{{order.sub_total_price}}</td>
                    </tr>
                </template>
                <tr class="table-warning">
                    <td>
                        <a class="btn btn-sm btn-default" v-on:click.prevent="printPDF()">Print to PDF</a>
                    </td>
                    <td></td>
                    <td>Total Price:</td>
                    <td>{{ invoice.total_price }}€</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script type="text/javascript">
    // Component code

    module.exports = {
        props: ['invoice', 'index'],
        data: function(){
            return {
            }
        },
        mounted() {
            $("#box" + this.invoice.id).boxWidget('collapse');
        },
        updated() {
            $("#box" + this.invoice.id).boxWidget('collapse');
        },
        methods: {
            toggleInvoice: function () {
                $("#box" + this.invoice.id).boxWidget('collapse');
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
