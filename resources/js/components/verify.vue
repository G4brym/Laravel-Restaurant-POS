<template>
    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">Submit your new password</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <template v-if="id && hash">
                <div class="form-group" :class="{'has-error': !validPassword}">
                    <label for="inputPassword">Password</label>
                    <input
                        type="password" class="form-control" v-model="newPassword"
                        name="password" id="inputPassword"
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
                        type="password" class="form-control"
                        name="confirmPassword" id="inputConfirmPassword"
                        placeholder="Confirm password"
                        @keyup="differentError = $event.target.value !== newPassword"/>
                    <span v-show="differentError" class="help-block">
                        Password must match
                    </span>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" :disabled="isDisabled" @click="verify">Submit</button>
                </div>
            </template>
            <div class="text-center" v-else>
                Please wait a moment...
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
                isDisabled: false
            }
        },
        methods: {
            checkPassword: function () {
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
            verify: function () {
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

                this.isDisabled = true;

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

                this.$http.post('/api/verify', {
                    'id': this.id,
                    'hash': this.hash,
                    'password': this.newPassword
                }).then(() => {
                    this.$router.push('login');

                    /////////////////////////////////////////
                    // SweetAlert
                    const toast = this.$swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false
                    });
                    toast({
                        type: 'success',
                        title: 'Password set. You can now log in.'
                    });
                    /////////////////////////////////////////
                }).catch(() => {
                    this.isDisabled = false;
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
        },
        mounted: function() {
            if (!this.$route.query.id || !this.$route.query.hash) {
                this.$router.push('itemsMenu');
            }

            this.$http.post('/api/verify/check',
                {
                    'id': this.$route.query.id,
                    'hash': this.$route.query.hash
                }
            ).then(() => {
                this.id = this.$route.query.id;
                this.hash = this.$route.query.hash;

            }).catch(() => {
                this.$router.push('itemsMenu');

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
</script>

<style scoped>

</style>
