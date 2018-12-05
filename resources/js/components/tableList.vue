<template>
    <table class="table table-striped">
        <thead>
	        <tr>
	            <th>table_number</th>
	            <th>created_at</th>
	            <th>updated_at</th>
	        </tr>
	    </thead>
	    <tbody>
	        <tr v-for="table in this.$store.state.tables">
                <td>{{ table.table_number }}</td>
                <td>{{ table.created_at }}</td>
                <td>{{ table.updated_at }}</td>
                <td>
	                <a class="btn btn-sm btn-primary" v-on:click.prevent="editTable(table)">Edit</a>
	                <a class="btn btn-sm btn-danger" v-on:click.prevent="deleteTable(table)">Delete</a>
	        	</td>
	        </tr>
	        
	    </tbody>
    </table>
</template>

<script type="text/javascript">
	// Component code (not registered)
	module.exports={
		props: ['tables'],
		data: function(){
			return { 
				editingTable: null
			}
		},
        methods: {
            editTable: function(table){
                this.editingTable = table;
                this.$emit('edit-click', table);
            },		
            deleteTable: function(table){
                this.editingTable = table;
                this.$emit('delete-click', table);
			},
        },		
	}
</script>

<style scoped>
/*	  Specific style applied only on the component*/
</style>