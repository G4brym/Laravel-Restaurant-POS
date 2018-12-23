<template>
	<div class="jumbotron">
	    <h2>Add User</h2>
	    <div class="form-group">
	        <label for="inputName">Name</label>
	        <input
	            type="text" class="form-control"
	            name="name" id="userName"
	            placeholder="User Name"/>
	    </div>
	    <div class="form-group">
	        <label for="inputUsername">Username</label>
	        <input
	            type="text" class="form-control"
	            name="username" id="username"
	            placeholder="Username"/>
	    </div>
	    <div class="form-group">
	        <label for="inputPassword">Password</label>
	        <input
	            type="password" class="form-control"
	            name="password" id="userPassword"
	            placeholder="Password"/>
	    </div>
	    <div class="form-group">
	        <label for="inputPassword">Confirmation Password</label>
	        <input
	            type="password" class="form-control"
	            name="confirmationPassword" id="userConfirmationPassword"
	            placeholder="Confirmation Password"/>
	    </div>
	    <div class="form-group">
	        <label for="inputEmail">Email</label>
	        <input
	            type="email" class="form-control"
	            name="email" id="userEmail"
	            placeholder="User Email"/>
	    </div>
	    <div class="form-group">
	        <label for="inputType">Type</label>
	        <select class="form-control" id="userType" name="type" >
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
	    </div>
	    <div class="form-group">
        	<input type="file" id="userPhoto" name="photo" @change='loadImage()'/>
        </div>
	    <div class="form-group">
	        <a class="btn btn-primary" v-on:click.prevent="addUser()">Add</a>
	        <a class="btn btn-light" v-on:click.prevent="cancelEdit()">Cancel</a>
		</div>
	</div>
</template>

<script type="text/javascript">
	module.exports={
		data: function(){
            return {
                currentImg: null,
                file: null,
                user: [],
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
	        addUser: function() {
	        	name = document.getElementById("userName").value;
	        	username = document.getElementById("username").value;
	        	email = document.getElementById("userEmail").value;
	        	type = document.getElementById("userType").value;
	        	password = document.getElementById("userPassword").value;
	        	confirmationPassword = document.getElementById("userType").value;
	        	photo_url = this.currentImg;
	           	
	            this.$http.post('api/users/', {name: name, photo_url: photo_url, type: type, username: username, password: password, confirmationPassword: confirmationPassword, email: email})
	                .then(response=>{
	                	this.$emit('user-inserted')
	                })
	                .catch(error => {
                    	this.$emit('insert-error')
                    });
	        },
	        cancelEdit: function(){
	        	this.$emit('user-canceled');
	        }
		}
	}
</script>

<style scoped>	

</style>
