<template>
    <div class="form-group">
        <label :for="'input' + num">Item Name {{ num }}</label>
        <div class="input-group">
            <input type="text" class="form-control" v-model.trim="itemName"
                   :disabled="inputLocked"
                   :id="'input' + num" :list="list"
                   placeholder="Item name"/>
            <span class="input-group-btn" v-if="!confirmed">
                <button class="btn btn-success btn-flat" :disabled="inputLocked" @click="sendOrder">
                    <i class="fa fa-check"></i>
                </button>
            </span>
            <span class="input-group-btn" v-else>
                <button class="btn btn-warning btn-flat" :disabled="changeLocked" @click="editOrder">
                    <i class="fa fa-edit"></i>
                </button>
                <button class="btn btn-danger btn-flat" :disabled="changeLocked" @click="destroyOrder">
                    <i class="fa fa-trash"></i>
                </button>
            </span>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['num', 'mealId', 'list'],
        data: function() {
            return {
                itemName: null,
                confirmed: false,
                timeout: null,
                orderId: null,
                inputLocked: false,
                changeLocked: false,
            }
        },
        methods: {
            sendOrder: function() {
                this.inputLocked = true;
                this.$http.post(
                    '/api/orders',
                    {
                        'itemName': this.itemName,
                        'mealId': this.mealId
                    }
                ).then((response) => {
                    if (response.data) {
                        this.confirmed = true;

                        this.timeout = setInterval(() => {
                            this.confirmOrder();
                        }, 5000);
                        
                        this.orderId = response.data.data.id;
                        this.$emit('set-order-info', response.data.data);
                    }

                }).catch(() => {
                    this.unknownError();
                });
            },
            confirmOrder: function() {
                clearInterval(this.timeout);
                this.changeLocked = true;
                this.$http.put(
                    `/api/orders/${this.orderId}/confirm`
                ).catch(() => {
                    this.unknownError();
                });
            },
            editOrder: function() {
                clearInterval(this.timeout);
                this.$http.delete(
                    `/api/orders/${this.orderId}`
                ).then(() => {
                    this.confirmed = false;
                    this.inputLocked = false;
                    this.orderId = null;
                    this.$emit('set-order-info', {});

                }).catch(() => {
                    this.unknownError();
                });
            },
            destroyOrder: function() {
                clearInterval(this.timeout);
                this.$http.delete(
                    `/api/orders/${this.orderId}`
                ).then(() => {
                    this.confirmed = false;
                    this.inputLocked = false;
                    this.orderId = null;
                    this.$emit('set-order-info', null);

                }).catch(() => {
                    this.unknownError();
                });
            },
            unknownError: function() {
                this.$swal({
                    type: 'error',
                    title: 'Oops',
                    text: "Something went wrong..."
                });
            }
        }
    }
</script>

<style scoped>

</style>
