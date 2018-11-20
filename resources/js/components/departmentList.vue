<template>
	<table class="table table-striped">
	    <thead>
	        <tr>
	            <th>Name</th>
	            <th>Date</th>
	        </tr>
	    </thead>
	    <tbody>
	        <tr v-for="department in this.$store.state.departments"  :key="department.id">
                <td>{{ department.name }}</td>
                <td>{{ department.date }}</td>
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