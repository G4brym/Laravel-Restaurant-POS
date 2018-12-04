<template>
	<table class="table table-striped">
		<div class="jumbotron">
			<h1>Lista mesas</h1>
		</div>
	    <thead>
	        <tr>
	            <th>Numero da mesa</th>
	        </tr>
	    </thead>
	    <tbody>
	        <tr v-for="table in this.$store.state.tables">
                <td>{{ table.table_number }}</td>
	        </tr>
	    </tbody>
	</table>
</template>

<script type="text/javascript">
	// Component code (not registered)
	module.exports={
		
	}
</script>

<style scoped>
/*	  Specific style applied only on the component*/
</style>