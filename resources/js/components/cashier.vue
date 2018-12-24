<template>
    <div>
        <pending-invoices :pendingInvoices="pendingInvoices" ref="pendingInvoicesRef"></pending-invoices>

        <template v-for="(invoice, index) in cashierInvoices">
            <invoice-box :invoice="invoice" :index="index" ref="invoicesRef"></invoice-box>
        </template>
    </div>
</template>

<script type="text/javascript">
    // Component code
    import InvoiceBox from './cashier/invoiceBox.vue';
    import PendingInvoices from './cashier/pendingInvoices.vue';

    export default {
        data: function(){
            return {
                cashierInvoices: [],
                pendingInvoices: [],
            }
        },
        methods: {
            getCashierInvoices: function(){
                this.$http.get('api/invoices')
                    .then(response=>{
                        this.cashierInvoices = response.data.data;
                    });
            },
            getPendingInvoices: function(){
                this.$http.get('api/invoices?pending=true')
                    .then(response=>{
                        this.pendingInvoices = response.data.data;
                    });
            },
        },
        created: function () {
            this.getCashierInvoices();
            this.getPendingInvoices();
        },
        components: {
            'invoice-box': InvoiceBox,
            'pending-invoices': PendingInvoices,
        },
    }
</script>

<style scoped>

</style>
