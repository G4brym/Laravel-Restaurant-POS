<template>
    <li>
        <button v-show="showShiftButton" :class="shiftButtonClass" @click="flipShiftActive()">
            {{ buttonMessage }}
        </button>
    </li>
</template>

<script>
    export default {
        data: function() {
            return {
                showShiftButton: false,
                buttonMessage: "",
                shiftButtonClass: null
            }
        },
        methods: {
            setButtonStatus: function(shift_active) {
                if (shift_active) {
                    this.shiftButtonClass = "btn-danger";
                    this.buttonMessage = "End shift";
                } else {
                    this.shiftButtonClass = "btn-success";
                    this.buttonMessage = "Start shift";
                }
                this.showShiftButton = true;
            },
            flipShiftActive: function(user) {
                return axios.put('api/users/' + this.$store.state.user.id + "/toggleShift", null)
                    .then((response) => {
                        let responseUser = Object.assign({}, response.data.data);
                        this.$store.commit("setUser", responseUser);
                    })
            },
        },
        mounted() {
            setInterval(() => {
                if (this.$store.state.user) {
                    this.setButtonStatus(this.$store.state.user.shift_active);
                } else {
                    this.showShiftButton = false;
                }
            }, 1000);
        }
    }
</script>

<style scoped>
    button {
        margin-right: 15px;
        margin-top: 12px;
    }
</style>
