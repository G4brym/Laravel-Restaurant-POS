<template>
    <div class="box box-info" v-if="meal">
        <div class="box-header with-border">
            <h3 class="box-title">Meal {{ meal.id }} - Table {{meal.table_number_id}}</h3>
        </div>
        <div class="box-body">
            <template v-for="order in orders" v-if="order.info">
                <order :num="order.num" :meal-id="meal.id" :list="'itemsList'"
                       @set-order-info="setOrderInfo(order, $event)"></order>
            </template>

            <datalist id="itemsList">
                <option v-for="item in this.$store.state.items">{{ item.name }}</option>
            </datalist>
            <button class="btn btn-link" @click="addOrderElement">   + Add order to meal</button>
        </div>
        <div class="box-footer">
            <button type="button" class="btn btn-primary pull-left" @click="doneMeal">Done</button>
        </div>
    </div>
</template>

<script>
    import orderElem from './order.vue';
    export default {
        components: {
            'order': orderElem
        },
        data: function() {
            return {
                meal: null,
                orders: [],
                counter: 0
            }
        },
        methods: {
            setOrderInfo: function(order, info) {
                order.info = info;
            },
            getNewOrder: function() {
                return {
                    'info': {},
                    'num': ++this.counter
                };
            },
            addOrderElement: function() {
                this.orders.push(this.getNewOrder())
            },
            doneMeal: function() {
                this.$router.push('/waiter');
            }
        },
        mounted: function() {
            this.$swal({
                title: 'Table number?',
                input: 'number',
                showCancelButton: true,
                confirmButtonText: 'Submit',
                showLoaderOnConfirm: true,
                allowOutsideClick: false,
                preConfirm: (tableNumber) => {
                    return this.$http.get(`/api/tables/${tableNumber}/check`)
                        .then(response => {
                            console.log(response);
                            if (response.data.data) {
                                if (response.data.data === 'taken') {
                                    return {'status': 'taken'};

                                } else {
                                    this.meal = response.data.data;
                                    this.orders.push(this.getNewOrder());
                                    return {'status': 'success'};
                                }
                            }
                        })
                        .catch(payload => {
                            return {'status': 'fail', 'code': payload.response.status};
                        });
                }
            }).then((result) => {
                if (result.dismiss === this.$swal.DismissReason.cancel) {
                    this.$router.push('/waiter');

                } else if (result.value.status === 'taken') {
                    this.$router.push('/waiter');

                    /////////////////////////////////////////
                    // SweetAlert
                    this.$swal({
                        type: 'error',
                        title: 'Table is taken',
                        text: "An active meal already exists on this table"
                    });
                    /////////////////////////////////////////

                } else if (result.value.status === 'success') {
                    /////////////////////////////////////////
                    // SweetAlert
                    const toast = this.$swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    toast({
                        title: `Active meal created at table number ${this.meal.table_number_id}`,
                        type: 'success'
                    });
                    /////////////////////////////////////////

                } else {
                    this.$router.push("/waiter");
                    if (result.value.code === 404) {
                        /////////////////////////////////////////
                        // SweetAlert
                        this.$swal({
                            type: 'error',
                            title: 'Non-existing table',
                            text: "The submitted table number does not exist"
                        });
                        /////////////////////////////////////////

                    } else {
                        /////////////////////////////////////////
                        // SweetAlert
                        this.$swal({
                            type: 'error',
                            title: 'Oops',
                            text: "Something went wrong..."
                        });
                        /////////////////////////////////////////
                    }
                }
            });
        }
    }
</script>

<style scoped>

</style>
