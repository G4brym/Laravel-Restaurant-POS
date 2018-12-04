<template>
	<div>
		<div class="jumbotron">
				<h1>Lista mesas</h1>
		</div>
		<table class="table table-striped">
		    <thead>
		        <tr>
		            <th>Numero da mesa</th>
		        </tr>
		    </thead>
		    <tbody>
		        <tr v-for="table in this.$store.state.tables">
	                <td>{{ table.table_number }}</td>
	                <td>{{ table.deleted_at }}</td>
	                <td>{{ table.created_at }}</td>
	                <td>{{ table.updated_at }}</td>
		        </tr>
		    </tbody>
		</table>
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