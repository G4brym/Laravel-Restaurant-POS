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
	        <label for="inputType">Type</label>
	        <select class="form-control" id="userType" name="type">
	            <option>dish</option>
	            <option>drink</option>
	        </select>
	    </div>
	    <div class="form-group">
	        <label for="inputDescription">Description</label>
	        <input
	            type="text" class="form-control"
	            name="description" id="userDescription"
	            placeholder="User Description"/>
	    </div>
	    <div class="form-group">
	        <label for="inputPrice">Price</label>
	        <input
	            type="number" class="form-control"
	            name="price" id="userPrice"
	            placeholder="User Price"/>
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
	        	photo_url = this.currentImg;
	        	type = document.getElementById("userType").value;
	        	description = document.getElementById("userDescription").value;
	        	price = document.getElementById("userPrice").value;
	            this.$http.post('api/users/', {name: name, photo_url: photo_url, type: type, description: description, price: price})
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
