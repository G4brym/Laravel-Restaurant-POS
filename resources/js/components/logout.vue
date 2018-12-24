<template>
    <button class="pull-right btn btn-flat btn-default" @click="logout()">
        <i class="fa fa-sign-out"></i><span>Logout</span>
    </button>
</template>

<script type="text/javascript">
    export default {
        methods: {
            logout() {
                /////////////////////////////////////////
                // SweetAlert
                const toast = this.$swal.mixin({
                    toast: true,
                    position: 'top',
                    showConfirmButton: false
                });
                toast({
                    title: 'Logging out...',
                    onBeforeOpen: () => {
                        this.$swal.showLoading();
                    }
                });
                /////////////////////////////////////////

                this.$http.post('api/logout')
                    .then(() => {
                        this.$root.clearUserData(true);
                        this.$router.push("itemsMenu");

                        /////////////////////////////////////////
                        // SweetAlert
                        const toast = this.$swal.mixin({
                            toast: true,
                            position: 'top',
                            showConfirmButton: false,
                            timer: 3000
                        });
                        toast({
                            type: 'success',
                            title: 'Signed out successfully'
                        });
                        /////////////////////////////////////////);
                    })
                    .catch(error => {
                        this.$root.clearUserData(true);
                        this.$router.push("itemsMenu");
                        console.log(error);

                        /////////////////////////////////////////
                        // SweetAlert
                        this.$swal({
                            type: 'error',
                            title: 'Logout failed',
                            text: 'However, local credentials have been discarded'
                        });
                        /////////////////////////////////////////
                    });
                }
        }
    }
</script>
