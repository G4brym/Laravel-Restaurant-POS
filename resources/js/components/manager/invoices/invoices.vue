<template>
    <div>
        <label>State:</label>
        <select id="filter">
          <option value="pending">Pending</option>
          <option value="paid">Paid</option>
          <option value="not_paid">Not Paid</option>
        </select>
        <label>Date:</label>
        <input id="date" type="date" name="date">
        <input type="submit" value="Submit" v-on:click.prevent="getInvoices()">
        <br>

        <div>
            <template v-if="invoices.length == 0">
                <p>There are no invoices</p>
            </template>
            <template v-else>
                <template v-for="(invoice, index) in invoices">
                        <invoice-box :invoice="invoice" :index="index" ref="invoicesRef"></invoice-box>
                </template>   
            </template>
        </div>
    </div>
</template>

<script type="text/javascript">
    import InvoiceBox from './invoiceBox.vue';
    import Paginator from './../../paginator.vue';

    export default {
        data: function(){
            return {
                invoices: [],
                paginatorData: null,
            }
        },
        methods: {
            getInvoices: function(page){
                var select = document.getElementById("filter");
                var option = select.options[select.selectedIndex].text;

                var dateForm = document.getElementById("date");
                var date = dateForm.value;

                if(page == null){
                    page = 1
                }

                this.$http.get('api/invoices', {params: {page: page, filter: option, date: date}})
                    .then(response=>{
                        this.invoices = response.data.data;
                        this.paginatorData = response.data;
                    });
            },
        },
        mounted: function () {
            this.getInvoices();
        },
        components: {
            'invoice-box': InvoiceBox,
            'paginator': Paginator,
        },
    }
</script>

<style scoped>

</style>
