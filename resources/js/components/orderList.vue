<template>
	<table class="table table-striped">
	    <thead>
	        <tr>
	            <th>Name</th>
	            <th>Date</th>
	        </tr>
	    </thead>
	    <tbody>
	        <tr v-for="order in this.$store.state.orders"  :key="order.id">
                <td>{{ order.name }}</td>
                <td>{{ order.date }}</td>
	        </tr>
	    </tbody>
	</table>
</template>

<script type="text/javascript">
	// Component code
	module.exports={
	}
</script>

<style scoped>
/*	  Specific style applied only on the component*/
</style>
