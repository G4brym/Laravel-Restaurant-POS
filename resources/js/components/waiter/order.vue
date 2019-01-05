<template>
    <div class="form-group">
        <label :for="'input' + num">Item {{ num }}</label>
        <select class="form-control" v-model="itemName">
            <option v-for="item in this.$store.state.items" :value="item.name">
                {{ item.name }}
            </option>
        </select>
        <span>
            <button class="btn btn-success btn-flat" :disabled="inputLocked" @click="sendOrder">
                <i class="fa fa-check"></i>
            </button>
        </span>
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
