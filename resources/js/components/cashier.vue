<template>
    <div>
        <pending-invoices :pendingInvoices="pendingInvoices" ref="pendingInvoicesRef"></pending-invoices>

        <div>
            <template v-for="(invoice, index) in cashierInvoices">
                <invoice-box :invoice="invoice" :index="index" ref="invoicesRef"></invoice-box>
            </template>
            <ul class="pagination no-margin text-center">
                <li :class="{'disabled': !this.firstPage}"><a :href="this.firstPage" v-on:click.prevent="getCashierInvoices(firstPage)">«</a></li>
                <li v-if="this.prevPage2"><a :href="this.prevPage2" v-on:click.prevent="getCashierInvoices(prevPage2)">{{prevPage2}}</a></li>
                <li v-if="this.prevPage"><a :href="this.prevPage" v-on:click.prevent="getCashierInvoices(prevPage)">{{prevPage}}</a></li>
                <li class="active"><a href="#">{{currentPage}}</a></li>
                <li v-if="this.nextPage"><a :href="this.nextPage" v-on:click.prevent="getCashierInvoices(nextPage)">{{nextPage}}</a></li>
                <li v-if="this.nextPage2"><a :href="this.nextPage2" v-on:click.prevent="getCashierInvoices(nextPage2)">{{nextPage2}}</a></li>
                <li :class="{'disabled': !this.lastPage}"><a :href="this.lastPage" v-on:click.prevent="getCashierInvoices(lastPage)">»</a></li>
            </ul>
        </div>
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
                firstPage: null,
                lastPage: null,
                nextPage: null,
                nextPage2: null,
                prevPage: null,
                prevPage2: null,
                currentPage: 1,
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

                        this.currentPage = response.data.meta.current_page;

                        if(this.currentPage != 1){
                            this.firstPage = 1
                        } else {
                            this.firstPage = null
                        }
                        if(this.currentPage != response.data.meta.last_page){
                            this.lastPage = response.data.meta.last_page
                        } else {
                            this.lastPage = null
                        }

                        if(this.currentPage+1 <= response.data.meta.last_page){
                            this.nextPage = this.currentPage+1
                        } else {
                            this.nextPage = null;
                        }
                        if(this.currentPage+2 <= response.data.meta.last_page){
                            this.nextPage2 = this.currentPage+2
                        } else {
                            this.nextPage2 = null;
                        }

                        if(this.currentPage-1 >= 1){
                            this.prevPage = this.currentPage-1
                        } else {
                            this.prevPage = null;
                        }
                        if(this.currentPage-2 >= 1){
                            this.prevPage2 = this.currentPage-2
                        } else {
                            this.prevPage2 = null;
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
        },
    }
</script>

<style scoped>

</style>
