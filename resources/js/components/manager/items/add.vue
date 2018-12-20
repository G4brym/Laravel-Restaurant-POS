<template>
	<div class="jumbotron">
	    <h2>Adding a new item</h2>
	    <div class="form-group">
	        <label for="inputNumber">Item Number</label>
	        <input
	            type="number" class="form-control"
	            name="number" id="inputNumber"/>
	    </div>
	    <div class="form-group">
	        <a class="btn btn-primary" v-on:click.prevent="addItem()">Add</a>
	        <a class="btn btn-light" v-on:click.prevent="addItemCancel()">Cancel</a>
	    </div>
	</div>
</template>

<script type="text/javascript">
	module.exports={
		methods: {
	        addItem: function() {
	            this.$http.post('api/items/', {item_number: document.getElementById("inputNumber").value})
					.then(response => {
				   		this.$emit('item-inserted')
				   	})
					.catch(error => {
					    this.$emit('insert-error')
					});  
	        },
	        addItemCancel: function() {
	        	this.$emit('item-canceled', this.item);
	        }
		}
	}
</script>

<style scoped>	

</style>
