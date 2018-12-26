<template>
    <div>
        <pending-invoices :pendingInvoices="pendingInvoices" @mark-paid="markInvoicePaid" ref="pendingInvoicesRef"></pending-invoices>

        <div>
            <template v-for="(invoice, index) in cashierInvoices">
                <invoice-box :invoice="invoice" :index="index" ref="invoicesRef"></invoice-box>
            </template>

            <paginator :data="paginatorData" @change-page="getCashierInvoices"></paginator>
        </div>
    </div>
</template>

<script type="text/javascript">
    // Component code
    import InvoiceBox from './cashier/invoiceBox.vue';
    import PendingInvoices from './cashier/pendingInvoices.vue';
    import Paginator from './paginator.vue';

    export default {
        data: function(){
            return {
                cashierInvoices: [],
                pendingInvoices: [],
                paginatorData: null,
            }
        },
        methods: {
            getCashierInvoices: function(page){
                if(page == null){
                    page = 1
                }

                this.$http.get('api/invoices?page='+page)
                    .then(response=>{
                        this.cashierInvoices = response.data.data;

                        this.paginatorData = response.data;
                    });
            },
            markInvoicePaid: function(invoice, index, name, nif){
                this.$http.post('api/invoices/'+invoice.id+'/markpaid', {'name': name, 'nif': nif})
                    .then(response=>{
                        if (response.status == 200) {
                            this.getPendingInvoices()
                            this.getCashierInvoices()

                            $('#markAsPaid').modal('hide');

                            this.$swal({
                                type: 'success',
                                title: 'Paied',
                                text: 'Invoice marked as paied',
                            });
                        } else {
                            this.$swal({
                                type: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                            });
                        }
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
            'paginator': Paginator,
        },
    }
</script>

<style scoped>

</style>
