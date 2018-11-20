<template>
	<table class="table table-striped">
	    <thead>
	        <tr>
	            <th>Name</th>
	            <th>Email</th>
	            <th>Age</th>
	            <th>Department</th>
	            <th>Actions</th>
	        </tr>
	    </thead>
	    <tbody>
	        <tr v-for="user in users"  :key="user.id" :class="{activerow: editingUser === user}">
	            <td>{{ user.name }}</td>
	            <td>{{ user.email }}</td>
	            <td>{{ user.age }}</td>
	            <td>{{ user.department }}</td>
	            <td>
					<a class="btn btn-sm btn-success" v-on:click.prevent="definePlayer(user,1)">P1</a>
					<a class="btn btn-sm btn-success" v-on:click.prevent="definePlayer(user,2)">P2</a>
	                <a class="btn btn-sm btn-primary" v-on:click.prevent="editUser(user)">Edit</a>
	                <a class="btn btn-sm btn-danger" v-on:click.prevent="deleteUser(user)">Delete</a>
	            </td>
	        </tr>
	    </tbody>
	</table>
</template>

<script type="text/javascript">
	// Component code (not registered)
	module.exports={
		props: ['users'],
		data: function(){
			return { 
				editingUser: null
			}
		},
        methods: {
            editUser: function(user){
                this.editingUser = user;
                this.$emit('edit-click', user);
            },		
            deleteUser: function(user){
                this.editingUser = null;
                this.$emit('delete-click', user);
			},
			definePlayer: function(user,player){
				this.$root.$data['player'+player] = user;
				this.$emit('message', user.name+' selected as Player'+player);
			}
        },		
	}
</script>

<style scoped>
	tr.activerow {
  		background: #123456  !important;
  		color: #fff          !important;
}

</style>