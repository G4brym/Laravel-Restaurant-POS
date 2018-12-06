<template>
	<div class="jumbotron">
	    <h2>Edit Table</h2>
	    <div class="form-group">
	        <label for="inputNumber">Número da mesa</label>
	        <input
	            type="number" class="form-control" v-model="table.table_number"
	            name="number" id="inputNumber"
	            placeholder="inputNumber"/>
	    </div>
	    <div class="form-group">
	        <a class="btn btn-primary" v-on:click.prevent="saveTable()">Save</a>
	        <a class="btn btn-light" v-on:click.prevent="cancelEdit()">Cancel</a>
	    </div>
	</div>
</template>

<script type="text/javascript">
	module.exports={
		props: ['table'],
	    methods: {
	        saveTable: function(){
	            axios.put('api/tables/'+this.table.table_number, this.table)
	                .then(response=>{
	                	Object.assign(this.table, response.data.data);
	                	this.$emit('table-saved', this.table)
	                });
	        },
	        cancelEdit: function(){
	        	axios.get('api/tables/'+this.table.table_number)
	                .then(response=>{
	                	Object.assign(this.table, response.data.data);
	                	this.$emit('table-canceled', this.table);
	                });
	        }
		}
	}
</script>

<style scoped>	

</style>