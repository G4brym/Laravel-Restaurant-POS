<template>
    <div class="box" :id="'box' + invoice.id" data-widget="box-widget">
        <div class="box-header with-border">
            <h3 class="box-title">Invoice {{ invoice.id }}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse" v-on:click.prevent="toggleInvoice()">
                    <i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <p>Waiter: {{invoice.waiter}}</p>
            <p>Date: {{invoice.date}}</p>
            <p>State: {{invoice.state}}</p>
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
                        <template v-if="invoice.state == 'paid'">
                            <td>
                                <a class="btn btn-sm btn-default" v-on:click.prevent="printPDF(invoice)">Print to PDF</a>
                            </td>
                        </template>
                        <template v-else>
                            <td></td>
                        </template>
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
            },printPDF: function (invoice) {
                var items = [
                    // Table Header
                    [
                        {
                            text: 'Product',
                            style: 'itemsHeader'
                        },
                        {
                            text: 'Qty',
                            style: [ 'itemsHeader', 'center']
                        },
                        {
                            text: 'Price',
                            style: [ 'itemsHeader', 'center']
                        },
                        {
                            text: 'Discount',
                            style: [ 'itemsHeader', 'center']
                        },
                        {
                            text: 'Total',
                            style: [ 'itemsHeader', 'center']
                        }
                    ],
                ];

                console.log(items)
                for(index = 0; index < invoice.items.length; ++index){
                    let item = invoice.items[index];

                    items.push([
                        [
                            {
                                text: item.item.name,
                                style:'itemTitle'
                            }
                        ],
                        {
                            text:item.quantity,
                            style:'itemNumber'
                        },
                        {
                            text:item.item.price + '€',
                            style:'itemNumber'
                        },
                        {
                            text: '0%',
                            style:'itemNumber'
                        },
                        {
                            text: item.sub_total_price + '€',
                            style:'itemTotal'
                        }
                    ]);
                }

                console.log(items)
                pdfMake.createPdf({


                    header: {
                        columns: [
                            { text: 'Diogo Agostinho', style: 'documentHeaderLeft' },
                            { text: 'Gabriel Massadas', style: 'documentHeaderCenter' },
                            { text: 'Luis Pires', style: 'documentHeaderRight' }
                        ]
                    },
                    footer: {
                        columns: [
                            { text: '', style: 'documentFooterLeft' },
                            { text: 'Processado por Computador', style: 'documentFooterCenter' },
                            { text: 'https://github.com/G4brym/DAD', style: 'documentFooterRight' }
                        ]
                    },
                    content: [
                        // Header
                        {
                            columns: [
                                [
                                    {
                                        text: invoice.state + ' INVOICE',
                                        style: 'invoiceTitle',
                                        width: '*'
                                    },
                                    {
                                        stack: [
                                            {
                                                columns: [
                                                    {
                                                        text:'Invoice #',
                                                        style:'invoiceSubTitle',
                                                        width: '*'

                                                    },
                                                    {
                                                        text:invoice.id,
                                                        style:'invoiceSubValue',
                                                        width: 100

                                                    }
                                                ]
                                            },
                                            {
                                                columns: [
                                                    {
                                                        text:'Date Issued',
                                                        style:'invoiceSubTitle',
                                                        width: '*'
                                                    },
                                                    {
                                                        text:invoice.date,
                                                        style:'invoiceSubValue',
                                                        width: 100
                                                    }
                                                ]
                                            }
                                        ]
                                    }
                                ],
                            ],
                        },
                        // Billing Headers
                        {
                            columns: [
                                {
                                    text: 'Billing From',
                                    style:'invoiceBillingTitle',

                                },
                                {
                                    text: 'Billing To',
                                    style:'invoiceBillingTitle',

                                },
                            ]
                        },
                        // Billing Details
                        {
                            columns: [
                                {
                                    text: invoice.waiter + ' \n Restaurante, Lda.',
                                    style: 'invoiceBillingDetails'
                                },
                                {
                                    text: invoice.name + ' \n PT' + invoice.nif,
                                    style: 'invoiceBillingDetails'
                                },
                            ]
                        },
                        // Line breaks
                        '\n\n',
                        // Items
                        {
                            table: {
                                // headers are automatically repeated if the table spans over multiple pages
                                // you can declare how many rows should be treated as headers
                                headerRows: 1,
                                widths: [ '*', 40, 'auto', 40, 'auto', 80 ],

                                body: items
                            }, // table
                            //  layout: 'lightHorizontalLines'
                        },
                        // TOTAL
                        {
                            table: {
                                // headers are automatically repeated if the table spans over multiple pages
                                // you can declare how many rows should be treated as headers
                                headerRows: 0,
                                widths: [ '*', 80 ],

                                body: [
                                    [
                                        {
                                            text:'TOTAL',
                                            style:'itemsFooterTotalTitle'
                                        },
                                        {
                                            text: invoice.total_price + '€',
                                            style:'itemsFooterTotalValue'
                                        }
                                    ],
                                ]
                            }, // table
                            layout: 'lightHorizontalLines'
                        },
                        // Signature
                        {
                            columns: [
                                {
                                    text:'',
                                },
                                {
                                    stack: [
                                        {
                                            text: '_________________________________',
                                            style:'signaturePlaceholder'
                                        },
                                        {
                                            text: invoice.waiter,
                                            style:'signatureName'

                                        }
                                    ],
                                    width: 180
                                },
                            ]
                        }
                    ],
                    styles: {
                        // Document Header
                        documentHeaderLeft: {
                            fontSize: 10,
                            margin: [5,5,5,5],
                            alignment:'left'
                        },
                        documentHeaderCenter: {
                            fontSize: 10,
                            margin: [5,5,5,5],
                            alignment:'center'
                        },
                        documentHeaderRight: {
                            fontSize: 10,
                            margin: [5,5,5,5],
                            alignment:'right'
                        },
                        // Document Footer
                        documentFooterLeft: {
                            fontSize: 10,
                            margin: [5,5,5,5],
                            alignment:'left'
                        },
                        documentFooterCenter: {
                            fontSize: 10,
                            margin: [5,5,5,5],
                            alignment:'center'
                        },
                        documentFooterRight: {
                            fontSize: 10,
                            margin: [5,5,5,5],
                            alignment:'right'
                        },
                        // Invoice Title
                        invoiceTitle: {
                            fontSize: 22,
                            bold: true,
                            alignment:'right',
                            margin:[0,0,0,15]
                        },
                        // Invoice Details
                        invoiceSubTitle: {
                            fontSize: 12,
                            alignment:'right'
                        },
                        invoiceSubValue: {
                            fontSize: 12,
                            alignment:'right'
                        },
                        // Billing Headers
                        invoiceBillingTitle: {
                            fontSize: 14,
                            bold: true,
                            alignment:'left',
                            margin:[0,20,0,5],
                        },
                        // Billing Details
                        invoiceBillingDetails: {
                            alignment:'left'

                        },
                        invoiceBillingAddressTitle: {
                            margin: [0,7,0,3],
                            bold: true
                        },
                        invoiceBillingAddress: {

                        },
                        // Items Header
                        itemsHeader: {
                            margin: [0,5,0,5],
                            bold: true
                        },
                        // Item Title
                        itemTitle: {
                            bold: true,
                        },
                        itemSubTitle: {
                            italics: true,
                            fontSize: 11
                        },
                        itemNumber: {
                            margin: [0,5,0,5],
                            alignment: 'center',
                        },
                        itemTotal: {
                            margin: [0,5,0,5],
                            bold: true,
                            alignment: 'center',
                        },

                        // Items Footer (Subtotal, Total, Tax, etc)
                        itemsFooterSubTitle: {
                            margin: [0,5,0,5],
                            bold: true,
                            alignment:'right',
                        },
                        itemsFooterSubValue: {
                            margin: [0,5,0,5],
                            bold: true,
                            alignment:'center',
                        },
                        itemsFooterTotalTitle: {
                            margin: [0,5,0,5],
                            bold: true,
                            alignment:'right',
                        },
                        itemsFooterTotalValue: {
                            margin: [0,5,0,5],
                            bold: true,
                            alignment:'center',
                        },
                        signaturePlaceholder: {
                            margin: [0,70,0,0],
                        },
                        signatureName: {
                            bold: true,
                            alignment:'center',
                        },
                        signatureJobTitle: {
                            italics: true,
                            fontSize: 10,
                            alignment:'center',
                        },
                        notesTitle: {
                            fontSize: 10,
                            bold: true,
                            margin: [0,50,0,3],
                        },
                        notesText: {
                            fontSize: 10
                        },
                        center: {
                            alignment:'center',
                        },
                    },
                    defaultStyle: {
                        columnGap: 20,
                    }
                }).download();
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
