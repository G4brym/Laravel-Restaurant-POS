<template>
	<div class="jumbotron">
	    <h2>Edit User</h2>
	    <div class="form-group">
	        <label for="inputName">Name</label>
	        <input
	            type="text" class="form-control" v-model="user.name"
	            name="name" id="userName"
	            placeholder="User Name"/>
	    </div>
	    <div class="form-group">
	        <label for="inputUsername">Username</label>
	        <input
	            type="text" class="form-control" v-model="user.username"
	            name="username" id="username"
	            placeholder="Username"/>
	    </div>
	    <div class="form-group">
	        <label for="inputEmail">Email</label>
	        <input
	            type="email" class="form-control" v-model="user.email"
	            name="email" id="userEmail"
	            placeholder="User Email"/>
	    </div>
	    <div class="form-group">
	        <label for="inputType">Type</label>
	        <select class="form-control" id="userType" name="type" v-model="user.type" >
	            <option>manager</option>
	            <option>waiter</option>
	            <option>cook</option>
	            <option>cashier</option>
	        </select>
	    </div>
	    <div class="form-group">
	    	<template v-if="currentImg">
            	<img :src="currentImg" alt="photo" height="120px" width="120px" id="uploadedPhoto"></img>
            </template>
            <template v-else>
            	<img :src='"/storage/profiles/" + user.photo_url' alt="photo" height="120px" width="120px" id="userCurrentPhoto"></img>
            </template>
	    </div>
	    <div class="form-group">
        	<input type="file" id="userPhoto" name="photo" @change='loadImage()'/>
        </div>
	    <div class="form-group">
	        <a class="btn btn-primary" v-on:click.prevent="saveUser()">Save</a>
	        <a class="btn btn-light" v-on:click.prevent="cancelEdit()">Cancel</a>
		</div>
	</div>
</template>

<script type="text/javascript">
	module.exports={
		props: ['user'],
		data: function(){
            return {
                currentImg: null,
                file: null
            }
        },
	    methods: {
	    	loadImage: function() {
                let inputPhoto = document.getElementById("userPhoto");
                if (inputPhoto.files && inputPhoto.files[0]) {
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
	        saveUser: function(){
	        	this.user.photo_url = this.currentImg;
	            this.$http.put('api/users/'+this.user.id, this.user)
	                .then(response=>{
	                	Object.assign(this.user, response.data.data);
	                	this.$emit('user-saved', this.user)
	                })
	                .catch(error => {
                    	this.$emit('edit-error')
                    });
                this.$http.put
	        },
	        cancelEdit: function(){
	        	this.$http.get('api/users/'+this.user.id)
	                .then(response=>{
	                	Object.assign(this.user, response.data.data);
	                	this.$emit('user-canceled', this.user);
	                });
	        }
		}
	}
</script>

<style scoped>	

</style>
