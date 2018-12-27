<template>
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Submit your new password</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="form-group">
                <button class="btn btn-default" @click="$router.push('account')" :disabled="disable">< Back</button>
            </div>
            <div class="form-group" :class="{'has-error': !validPassword}">
                <label for="inputPassword">Password</label>
                <input
                    type="password" class="form-control" v-model="newPassword"
                    name="password" id="inputPassword" @keyup.13="focusConfirm"
                    placeholder="Password" @change="checkPassword"/>
                <span v-show="!validPassword" class="help-block">
                    <span v-for="line in errors">
                        <template v-if='line === "\n"'>
                            <br/>
                        </template>
                        <template v-else>
                            {{ line }}
                        </template>
                    </span>
                </span>
            </div>
            <div class="form-group" :class="{'has-error': differentError}">
                <label for="inputConfirmPassword">Confirm Password</label>
                <input
                    type="password" class="form-control" ref="confPwdBox"
                    name="confirmPassword" id="inputConfirmPassword"
                    placeholder="Confirm password" @keyup.13="changePassword"
                    @keyup="differentError = $event.target.value !== newPassword"/>
                <span v-show="differentError" class="help-block">
                    Password must match
                </span>
            </div>
            <div class="form-group">
                <button class="btn btn-primary" @click="changePassword" :disabled="disable">Submit</button>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
</template>

<script>
    export default {
        data: function() {
            return {
                validPassword: true,
                newPassword: null,
                differentError: false,
                errors: [],
                id: null,
                hash: null,
                disable: false
            }
        },
        methods: {
            focusConfirm: function() {
                this.$refs.confPwdBox.focus();
            },
            checkPassword: function() {
                let errors = [];
                if (this.newPassword.length < 3) {
                    errors.push("Password must have at least 3 characters");
                }

                if (!this.newPassword.match(/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ0-9_-]+$/)) {
                    if (errors.length !== 0) {
                        errors.push("\n");
                    }
                    errors.push("Password can only contain letters, numbers and underscores");
                }

                if (errors.length === 0) {
                    this.validPassword = true;
                } else {
                    this.errors = errors;
                    this.validPassword = false;
                }
            },
            changePassword: function() {
                this.disable = true;
                if (!this.validPassword || !this.differentError) {
                    /////////////////////////////////////////
                    // SweetAlert
                    this.$swal({
                        type: 'warning',
                        title: 'Invalid information',
                        text: "Please, insert valid data"
                    });
                    /////////////////////////////////////////
                }

                this.disable = true;

                /////////////////////////////////////////
                // SweetAlert
                const toast = this.$swal.mixin({
                    toast: true,
                    position: 'top',
                    showConfirmButton: false
                });
                toast({
                    title: 'Setting up new password...',
                    onBeforeOpen: () => {
                        this.$swal.showLoading();
                    }
                });
                /////////////////////////////////////////

                this.$http.put(`/api/users/${this.$store.state.user.id}/changePassword`,
                    { 'password': this.newPassword }
                ).then((response) => {
                    this.$store.commit('setUser', response.data.data);
                    this.disable = false;

                    /////////////////////////////////////////
                    // SweetAlert
                    const toast = this.$swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false
                    });
                    toast({
                        type: 'success',
                        title: 'New password has ben submitted'
                    });
                    /////////////////////////////////////////

                }).catch(() => {
                    this.disable = false;

                    /////////////////////////////////////////
                    // SweetAlert
                    this.$swal({
                        type: 'error',
                        title: 'Oops',
                        text: "Something went wrong..."
                    });
                    /////////////////////////////////////////
                });
            }
        }
    }
</script>

<style scoped>

</style>
