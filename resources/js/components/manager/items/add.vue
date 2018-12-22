<template>
	<div class="jumbotron">
	    <h2>Add Item</h2>
	    <div class="form-group">
	        <label for="inputName">Name</label>
	        <input
	            type="text" class="form-control"
	            name="name" id="itemName"
	            placeholder="Item Name"/>
	    </div>
	    <div class="form-group">
	        <label for="inputType">Type</label>
	        <select class="form-control" id="itemType" name="type">
	            <option>dish</option>
	            <option>drink</option>
	        </select>
	    </div>
	    <div class="form-group">
	        <label for="inputDescription">Description</label>
	        <input
	            type="text" class="form-control"
	            name="description" id="itemDescription"
	            placeholder="Item Description"/>
	    </div>
	    <div class="form-group">
	        <label for="inputPrice">Price</label>
	        <input
	            type="number" class="form-control"
	            name="price" id="itemPrice"
	            placeholder="Item Price"/>
	    </div>
	    <div class="form-group">
			<template v-if="currentImg">
            	<img :src="currentImg" alt="photo" height="120px" width="120px" id="uploadedPhoto"></img>
            </template>
	    </div>
	    <div class="form-group">
        	<input type="file" id="itemPhoto" name="photo" @change='loadImage()'/>
        </div>
	    <div class="form-group">
	        <a class="btn btn-primary" v-on:click.prevent="addItem()">Add</a>
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
                item: [],
            }
        },
	    methods: {
	    	loadImage: function() {
                let inputPhoto = document.getElementById("itemPhoto");
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
	        addItem: function() {
	        	name = document.getElementById("itemName").value;
	        	photo_url = this.currentImg;
	        	type = document.getElementById("itemType").value;
	        	description = document.getElementById("itemDescription").value;
	        	price = document.getElementById("itemPrice").value;
	            this.$http.post('api/items/', {name: name, photo_url: photo_url, type: type, description: description, price: price})
	                .then(response=>{
	                	this.$emit('item-inserted')
	                })
	                .catch(error => {
                    	this.$emit('insert-error')
                    });
	        },
	        cancelEdit: function(){
	        	this.$emit('item-canceled');
	        }
		}
	}
</script>

<style scoped>	

</style>
