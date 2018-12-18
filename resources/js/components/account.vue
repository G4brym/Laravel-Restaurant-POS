<template>
    <div>
        <div class="alert alert-success" v-if="showSuccess">
            <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
            <strong>{{ successMessage }}</strong>
        </div>
        <template v-if="changedUser">
            <div>
                <h2>Edit Account Information</h2>
            </div>
            <br/>
            <div class="form-group">
                <label for="inputEmail">Email</label>
                <div class="form-control" id="inputEmail" style="background-color:#eee">{{ changedUser.email }}</div>
            </div>
            <div class="form-group">
                <label for="inputUsername">Username</label>
                <input
                    type="text" class="form-control" v-model="changedUser.username"
                    name="username" id="inputUsername"
                    placeholder="Username"/>
            </div>
            <div class="form-group">
                <label for="inputFullName">Full name</label>
                <input
                    type="text" class="form-control" v-model="changedUser.name"
                    name="fullName" id="inputFullName"
                    placeholder="Full Name"/>
            </div>

            <div class="form-group" v-if="currentImg">
                <div style="margin-bottom: 5px;"><b>Photo</b></div>
                <img :src="currentImg"
                     alt="Profile Photo"
                     id="accountPhoto"
                     width="128px" height="128px"/>
            </div>
            <div class="form-group" v-else>
                User has no profile photo<br/>
            </div>

            <div>
                <input
                    type="file" style="background-color: white;"
                    name="photo" id="inputAccPhoto" accept='image/*'
                    @change='updateImage()'/>
                <div v-show="showRevert">
                    <a class="btn btn-light" @click.prevent="revertImg()">Revert</a>
                </div>
            </div>
            <br/>
            <div class="form-group">
                <a class="btn btn-primary" @click.prevent="saveUser()">Save</a>
            </div>
        </template>
    </div>
</template>

<script type="text/javascript">
    export default {
        data: function(){
            return {
                currentUser: null,
                changedUser: null,
                successMessage: null,
                showSuccess: false,
                showRevert: false,
                userImg: null,
                currentImg: null,
                file: null
            }
        },
        methods: {
            getInformationFromLoggedUser: function() {
                this.currentUser = this.$store.state.user;
                this.changedUser = JSON.parse(JSON.stringify(this.$store.state.user));
            },
            saveUser: function(){
                let userInfoChanged = false;

                let setUserData = () => {
                    return axios.put('api/users/' + this.currentUser.id, this.changedUser);
                };

                let setUserPhoto = () => {
                    let formData = new FormData();
                    formData.append('photo', this.file);
                    return axios.post('api/users/' + this.currentUser.id + '/uploadPhoto',
                        formData,
                        { headers: {'Content-Type': 'multipart/form-data'} }
                    );
                };

                let submitToStore = (message) => {
                    Object.assign(this.changedUser, this.currentUser);
                    this.$store.commit("setUser", this.currentUser);
                    this.successMessage = message;
                    this.showSuccess = true;
                };

                //////////////////////////////////////////////////////////////

                for (let prop in this.currentUser) {
                    if (prop === 'photo_url') {
                        continue;
                    }

                    if (this.currentUser[prop] !== this.changedUser[prop]) {
                        userInfoChanged = true;
                        break;
                    }
                }

                if (userInfoChanged) {
                    if (this.userImg !== this.currentImg) {
                        axios.all([setUserData(), setUserPhoto()]).then(
                            axios.spread((userData, photoUrl) => {
                                Object.assign(this.currentUser, userData.data.data);
                                this.currentUser.photo_url = photoUrl.data.data;
                                this.updatePhotoSource();
                                submitToStore("User's Account and Photo Updated");
                            })
                        );

                    } else {
                        setUserData().then((response) => {
                            Object.assign(this.currentUser, response.data.data);
                            submitToStore("User's Account Updated");
                        });
                    }
                } else if (this.userImg !== this.currentImg) {
                    setUserPhoto().then((response) => {
                        this.currentUser.photo_url = response.data.data;
                        this.updatePhotoSource();
                        submitToStore("User's Photo Updated");
                    });

                } else {
                    this.successMessage = "No changes were submitted";
                    this.showSuccess = true;
                }
            },
            updateImage: function() {
                let inputPhoto = document.getElementById("inputAccPhoto");
                let accountPhoto = document.getElementById("accountPhoto");

                if (accountPhoto && inputPhoto.files && inputPhoto.files[0]) {
                    this.showRevert = true;

                    let reader = new FileReader();

                    reader.onload = () => {
                        this.file = inputPhoto.files[0];
                        this.currentImg = reader.result;
                    };

                    reader.readAsDataURL(inputPhoto.files[0]);
                }
            },
            revertImg: function() {
                document.getElementById("accountPhoto").setAttribute("src", this.userImg);
                document.getElementById("inputAccPhoto").value = "";
                this.showRevert = false;
                this.currentImg = this.userImg;
            },
            updatePhotoSource: function() {
                this.userImg = this.$store.state.profileFolder + '/' + this.currentUser.photo_url;
                this.currentImg = this.userImg;
            }
        },
        mounted() {
            this.getInformationFromLoggedUser();
            this.updatePhotoSource();
        }
    };
</script>
