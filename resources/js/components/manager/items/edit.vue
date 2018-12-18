<template>
	<div class="jumbotron">
	    <h2>Edit Item</h2>
	    <div class="form-group">
	        <label for="inputNumber">Número da mesa</label>
	        <input
	            type="number" class="form-control" v-model="item.item_number"
	            name="number" id="inputNumber"
	            placeholder="inputNumber"/>
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
                currentItemNumber: null,
            }
        },
		created: function () {
            this.currentItemNumber = this.item.item_number;
        },
	    methods: {
	        saveItem: function(){
	            axios.put('api/items/'+this.currentItemNumber, {item_number: document.getElementById("inputNumber").value})
	                .then(response=>{
	                	Object.assign(this.item, response.data.data);
	                	this.$emit('item-saved', this.item)
	                })
	                .catch(error => {
                    	this.$emit('edit-error')
                    });
	        },
	        cancelEdit: function(){
	        	axios.get('api/items/'+this.currentItemNumber)
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