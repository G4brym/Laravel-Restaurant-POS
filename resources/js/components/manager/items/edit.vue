<template>
	<div class="jumbotron">
	    <h2>Edit Item</h2>
	    <div class="form-group">
	        <label for="inputName">Name</label>
	        <input
	            type="text" class="form-control" v-model="item.name"
	            name="name" id="itemName"
	            placeholder="Item Name"/>
	    </div>
	    <div class="form-group">
	        <label for="inputType">Type</label>
	        <select class="form-control" id="itemType" name="type" v-model="item.type" >
	            <option>dish</option>
	            <option>drink</option>
	        </select>
	    </div>
	    <div class="form-group">
	        <label for="inputDescription">Description</label>
	        <input
	            type="text" class="form-control" v-model="item.description"
	            name="description" id="itemDescription"
	            placeholder="Item Description"/>
	    </div>
	    <div class="form-group">
	        <label for="inputPrice">Price</label>
	        <input
	            type="number" class="form-control" v-model="item.price"
	            name="price" id="itemPrice"
	            placeholder="Item Price"/>
	    </div>
	    <div class="form-group">
	    	<template v-if="currentImg">
            	<img :src="currentImg" alt="photo" height="120px" width="120px" id="uploadedPhoto"></img>
            </template>
            <template v-else>
            	<img :src='"/storage/items/" + item.photo_url' alt="photo" height="120px" width="120px" id="itemCurrentPhoto"></img>
            </template>
	    </div>
	    <div class="form-group">
        	<input type="file" id="itemPhoto" name="photo" @change='loadImage()'/>
        </div>
	    <div class="form-group">
	        <a class="btn btn-primary" v-on:click.prevent="saveItem()">Save</a>
	        <a class="btn btn-light" v-on:click.prevent="cancelEdit()">Cancel</a>
		</div>
	</div>
</template>

<script type="text/javascript">
	module.exports={
		props: ['item'],
		data: function(){
            return {
                currentImg: null,
                file: null
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
	        saveItem: function(){
	        	this.item.photo_url = this.currentImg;
	            this.$http.put('api/items/'+this.item.id, this.item)
	                .then(response=>{
	                	Object.assign(this.item, response.data.data);
	                	this.$emit('item-saved', this.item)
	                })
	                .catch(error => {
                    	this.$emit('edit-error')
                    });
                this.$http.put
	        },
	        cancelEdit: function(){
	        	this.$http.get('api/items/'+this.item.id)
	                .then(response=>{
	                	Object.assign(this.item, response.data.data);
	                	this.$emit('item-canceled', this.item);
	                });
	        }
		}
	}
</script>

<style scoped>	

</style>
