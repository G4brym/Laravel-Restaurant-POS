<template>
    <li>
        <button class="btn" :class="shiftButtonClass" @click="flipShiftActive()">
            {{ buttonMessage }}
        </button>
    </li>
</template>

<script>
    export default {
        data: function() {
            return {
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
            },
            flipShiftActive: function() {
                let message = null;

                /////////////////////////////////////////
                // SweetAlert
                if (this.$store.state.user.shift_active) {
                    message = "Ending shift...";

                } else {
                    message = "Starting shift...";
                }

                const toast = this.$swal.mixin({
                    toast: true,
                    position: 'top',
                    showConfirmButton: false
                });

                toast({
                    title: message,
                    onBeforeOpen: () => {
                        this.$swal.showLoading();
                    },
                });
                /////////////////////////////////////////

                return this.$http.put('api/users/' + this.$store.state.user.id + "/toggleShift", null)
                    .then((response) => {
                        let responseUser = Object.assign({}, response.data.data);
                        this.$store.commit("setUser", responseUser);
                        this.setButtonStatus(this.$store.state.user.shift_active);
                        this.$root.notifyCounter();

                        if (this.$store.state.user.shift_active) {
                            message = "Shift has started";
                            this.$root.loadActiveData();
                        } else {
                            message = "Shift has ended";
                            this.$root.clearUserData(false);
                        }

                        /////////////////////////////////////////
                        // SweetAlert
                        const toast = this.$swal.mixin({
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        toast({
                            title: message,
                            type: 'info'
                        });
                        /////////////////////////////////////////

                    })
                    .catch(() => {
                        /////////////////////////////////////////
                        // SweetAlert
                        this.$swal({
                            type: 'error',
                            title: 'Oops',
                            text: "Something went wrong..."
                        });
                        /////////////////////////////////////////

                    });
            },
        },
        mounted() {
            this.setButtonStatus(this.$store.state.user.shift_active);
        }
    }
</script>

<style scoped>
    button {
        margin-right: 15px;
        margin-top: 8px;
    }
</style>
