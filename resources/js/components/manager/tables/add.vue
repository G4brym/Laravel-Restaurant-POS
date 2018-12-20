<template>
	<div class="jumbotron">
	    <h2>Adding a new table</h2>
	    <div class="form-group">
	        <label for="inputNumber">Table Number</label>
	        <input
	            type="number" class="form-control"
	            name="number" id="inputNumber"/>
	    </div>
	    <div class="form-group">
	        <a class="btn btn-primary" v-on:click.prevent="addTable()">Add</a>
	        <a class="btn btn-light" v-on:click.prevent="addTableCancel()">Cancel</a>
	    </div>
	</div>
</template>

<script type="text/javascript">
	module.exports={
		methods: {
	        addTable: function() {
	            this.$http.post('api/tables/', {table_number: document.getElementById("inputNumber").value})
					.then(response => {
				   		this.$emit('table-inserted')
				   	})
					.catch(error => {
					    this.$emit('insert-error')
					});  
	        },
	        addTableCancel: function() {
	        	this.$emit('table-canceled', this.table);
	        }
		}
	}
</script>

<style scoped>	

</style>
