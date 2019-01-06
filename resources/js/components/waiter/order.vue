<template>
    <div>
        <label :for="'input_' + mealId + '_' + num">Item {{ num }}</label>
        <div class="input-group">
            <select class="form-control" :id="'input_' + mealId + '_' + num" v-model="itemName">
                <option v-for="item in this.$store.state.items" :value="item.name">
                    {{ item.name }}
                </option>
            </select>
            <span class="input-group-btn">
                <button class="btn btn-success btn-flat" :disabled="inputLocked" @click="sendOrder">
                    <i class="fa fa-check"></i>
                </button>
            </span>
        </div>
        <br/>
    </div>
</template>

<script>
    export default {
        props: ['num', 'mealId', 'list'],
        data: function() {
            return {
                itemName: null,
                inputLocked: false,
            }
        },
        methods: {
            sendOrder: function() {
                if (this.itemName === null) {
                    return;
                }
                this.inputLocked = true;
                this.$http.post(
                    '/api/orders',
                    {
                        'itemName': this.itemName,
                        'mealId': this.mealId
                    }
                ).then((response) => {
                    if (response.data) {
                        this.$emit('set-order-info', response.data.data);
                    }

                }).catch(() => {
                    this.$swal({
                        type: 'error',
                        title: 'Oops',
                        text: "Something went wrong..."
                    });
                });
            }
        }
    }
</script>

<style scoped>

</style>
