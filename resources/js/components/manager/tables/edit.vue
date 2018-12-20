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
		data: function(){
            return {        
                currentTableNumber: null,
            }
        },
		created: function () {
            this.currentTableNumber = this.table.table_number;
        },
	    methods: {
	        saveTable: function(){
	            this.$http.put('api/tables/'+this.currentTableNumber, {table_number: document.getElementById("inputNumber").value})
	                .then(response=>{
	                	Object.assign(this.table, response.data.data);
	                	this.$emit('table-saved', this.table)
	                })
	                .catch(error => {
                    	this.$emit('edit-error')
                    });
	        },
	        cancelEdit: function(){
	        	this.$http.get('api/tables/'+this.currentTableNumber)
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
