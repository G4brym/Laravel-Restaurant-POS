<template>
	<div>
		<div class="box">
	        <div class="box-header with-border">
	            <h3 class="box-title">Mesas</h3>
	        </div>
	        <div class="box-body">
	            <div class="panel panel-info">
	                <div class="panel-body">
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
						                <a class="btn btn-sm btn-primary" v-on:click.prevent="">Edit</a>
						                <a class="btn btn-sm btn-danger" v-on:click.prevent="">Delete</a>
						        	</td>
						        </tr>
						        
						    </tbody>
	                    </table>
	                </div>
	            </div>
	        </div>
	    </div>
	        <!-- /.box-body -->
	    <div class="box-footer">
            Footer
        </div>
        <!-- /.box-footer-->
    </div>
</template>

<script type="text/javascript">
	// Component code (not registered)
	module.exports={
		created: function () {
            this.$store.commit('loadTables');
        },
	}
</script>

<style scoped>
/*	  Specific style applied only on the component*/
</style>