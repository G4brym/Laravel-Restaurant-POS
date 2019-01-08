<template>
    <div>
        <pending-invoices :pendingInvoices="pendingInvoices" @mark-paid="markInvoicePaid" ref="pendingInvoicesRef"></pending-invoices>

        <div>
            <template v-for="(invoice, index) in cashierInvoices">
                <invoice-box :invoice="invoice" :index="index" ref="invoicesRef"></invoice-box>
            </template>

            <paginator :data="paginatorData" @change-page="getCashierInvoices" ref="cashierPaginator"></paginator>
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
                paginatorData: null
            }
        },
        computed: {
            cashierInvoices: function() {
                return this.$store.state.cashier.invoices;
            },
            pendingInvoices: function() {
                return this.$store.state.cashier.pendingInvoices;
            }
        },
        methods: {
            getCashierInvoices: function(page){
                if (page === null){
                    page = 1
                }

                this.$store.dispatch('loadInvoices', {page:page}).then((paginatorData) => {
                    this.paginatorData = paginatorData;
                });
            },
            markInvoicePaid: function(invoice, index, name, nif){
                this.$http.post('api/invoices/'+invoice.id+'/markpaid', {'name': name, 'nif': nif})
                    .then(response=>{
                        if (response.status === 200) {
                            this.getCashierInvoices(null);
                            this.$store.commit('removePendingInvoice', invoice);
                            this.$socket.emit('propagateRemovePendingInvoice', invoice);

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
        },
        created: function () {
            this.getCashierInvoices(null);
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
