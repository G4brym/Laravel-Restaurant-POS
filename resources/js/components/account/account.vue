<template>
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">Edit Account Information</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body" v-if="changedUser">
            <div class="form-group">
                <label for="inputEmail">Email</label>
                <div class="form-control" id="inputEmail" style="background-color:#eee">{{ changedUser.email }}</div>
            </div>
            <account-validate input-id="inputUsername" v-model="changedUser.username"
                              field-name="username" length="2"
                              :regex="new RegExp('^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ0-9_-]+$')"
                              regex-error="Username can only contain letters, numbers and underscores"
                              @update-valid="validUsername = $event"></account-validate>

            <account-validate input-id="inputName" v-model="changedUser.name"
                              field-name="name" length="3"
                              :regex="new RegExp('^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$')"
                              regex-error="Name can only contain letters and whitespaces"
                              @update-valid="validName = $event"></account-validate>

            <div class="form-group" >
                <div style="margin-bottom: 5px;"><b>Photo</b></div>
                <template v-if="currentImg">
                    <img :src="currentImg" alt="Profile Photo" id="accountPhoto"/>
                </template>
                <template v-else>
                    {{this.file ? "Invalid file" : "User has no profile photo"}}<br/>
                </template>
            </div>

            <div class="form-group">
                <input type="file" id="inputAccPhoto" name="photo" @change='loadImage()'/>
            </div>
            <div v-show="showRevert">
                <button class="btn btn-default" @click="revertImg()">Revert</button>
            </div>
            <br/>
            <div class="form-group">
                <button class="btn btn-primary" @click="saveUser()">Save</button>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
</template>

<script type="text/javascript">
    import accountValidate from "./accountValidate";
    export default {
        data: function(){
            return {
                changedUser: null,
                showRevert: false,
                userImg: null,
                currentImg: null,
                file: null,
                validUsername: true,
                validName: true
            }
        },
        components: {
            'account-validate': accountValidate
        },
        methods: {
            getInformationFromLoggedUser: function() {
                this.changedUser = JSON.parse(JSON.stringify(this.$store.state.user));
            },
            saveUser: function(){
                if (!this.validUsername || !this.validName ) {
                    /////////////////////////////////////////
                    // SweetAlert
                    this.$swal({
                        type: 'error',
                        title: 'Invalid information',
                        text: "Please, insert valid data"
                    });
                    /////////////////////////////////////////
                    return;
                }

                if (this.file && !this.file.type.startsWith("image")) {
                    /////////////////////////////////////////
                    // SweetAlert
                    this.$swal({
                        type: 'error',
                        title: 'Invalid file',
                        text: "Did you upload a photo?"
                    });
                    /////////////////////////////////////////
                    return;
                }

                let user = this.$store.state.user;
                let userInfoChanged = false;

                let setUserData = () => {
                    return this.$http.put('api/users/' + user.id, this.changedUser);
                };

                let setUserPhoto = () => {
                    let formData = new FormData();
                    formData.append('photo', this.file);
                    return this.$http.post('api/users/' + user.id + '/uploadPhoto',
                        formData,
                        { headers: {'Content-Type': 'multipart/form-data'} }
                    );
                };

                let submitToStore = (message) => {
                    this.$store.commit("setUser", this.changedUser);

                    /////////////////////////////////////////
                    // SweetAlert
                    this.$swal({
                        type: 'success',
                        title: 'Account updated!',
                        text: message
                    });
                    /////////////////////////////////////////
                };

                //////////////////////////////////////////////////////////////

                for (let prop in user) {
                    if (prop === 'photo_url') {
                        continue;
                    }

                    if (user.hasOwnProperty(prop) && user[prop] !== this.changedUser[prop]) {
                        userInfoChanged = true;
                        break;
                    }
                }

                if (userInfoChanged) {
                    if (this.userImg !== this.currentImg) {
                        setUserData().then((response) => {
                            Object.assign(this.changedUser, response.data.data);
                            setUserPhoto().then((response) => {
                                this.changedUser.photo_url = response.data.data;
                                submitToStore("User's Account and Photo Updated");
                                this.updatePhotoSource();
                            })
                            .catch(() => {
                                this.$swal({
                                    type: 'error',
                                    title: 'Oops',
                                    text: "Something went wrong..."
                                });
                            });
                        })
                        .catch((response) => {
                            if (response.response.data && response.response.data.errors) {
                                this.$swal({
                                    type: 'error',
                                    title: 'Username taken',
                                    text: "Please, choose another one"
                                });

                            } else {
                                this.$swal({
                                    type: 'error',
                                    title: 'Oops',
                                    text: "Something went wrong..."
                                });
                            }
                        });

                    } else {
                        setUserData().then((response) => {
                            Object.assign(this.changedUser, response.data.data);
                            submitToStore("User's Account Updated");
                        })
                        .catch((response) => {
                            if (response.response.data && response.response.data.errors) {
                                this.$swal({
                                    type: 'error',
                                    title: 'Username taken',
                                    text: "Please, choose another one"
                                });

                            } else {
                                this.$swal({
                                    type: 'error',
                                    title: 'Oops',
                                    text: "Something went wrong..."
                                });
                            }
                        });
                    }
                } else if (this.userImg !== this.currentImg) {
                    setUserPhoto().then((response) => {
                        this.changedUser.photo_url = response.data.data;
                        submitToStore("User's Photo Updated");
                        this.updatePhotoSource();
                    })
                    .catch(() => {
                        this.$swal({
                            type: 'error',
                            title: 'Oops',
                            text: "Something went wrong..."
                        });
                    });

                } else {
                    this.$swal({
                        type: 'info',
                        title: 'Info',
                        text: 'No changes were submitted'
                    });
                }
            },
            loadImage: function() {
                let inputPhoto = document.getElementById("inputAccPhoto");

                if (inputPhoto.files && inputPhoto.files[0]) {
                    this.showRevert = true;

                    let reader = new FileReader();

                    reader.onload = () => {
                        this.file = inputPhoto.files[0];
                        if (this.file.type.startsWith("image")) {
                            this.currentImg = reader.result;
                        }
                        else {
                            this.currentImg = null;
                        }
                    };

                    reader.readAsDataURL(inputPhoto.files[0]);
                }
            },
            revertImg: function() {
                document.getElementById("inputAccPhoto").value = "";
                this.file = null;
                this.showRevert = false;
                this.currentImg = this.userImg;
            },
            updatePhotoSource: function() {
                if (this.$store.state.user.photo_url) {
                    this.userImg = this.$store.state.profileFolder + '/' + this.$store.state.user.photo_url;
                    this.currentImg = this.userImg;
                }
            },
        },
        mounted() {
            this.getInformationFromLoggedUser();
            this.updatePhotoSource();
        }
    };
</script>

<style scoped>
    img {
        max-height: 256px;
        max-width: 256px;
    }
</style>
