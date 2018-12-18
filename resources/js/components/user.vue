<template>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{ title }}</h3>
        </div>
        <div class="box-body">
            <user-list :users="users" @edit-click="editUser" @delete-click="deleteUser" @message="childMessage" ref="usersListRef"></user-list>

            <div class="alert alert-success" v-if="showSuccess">
                <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
                <strong>{{ successMessage }}</strong>
            </div>
            <user-edit :user="currentUser" :departments="this.$store.state.departments"  @user-saved="savedUser" @user-canceled="cancelEdit" v-if="currentUser"></user-edit>

        </div>
        <!-- /.box-body -->
        <!--<div class="box-footer">
            Footer
        </div>-->
        <!-- /.box-footer-->
    </div>
</template>

<script type="text/javascript">
	import UserList from './userList.vue';
	import UserEdit from './userEdit.vue';
	
	export default {
		data: function(){
			return { 
		        title: 'List Users',
		        showSuccess: false,
		        successMessage: '',
		        currentUser: null,
		        users: [],
			}
		},
	    methods: {
	        editUser: function(user){
	            this.currentUser = user;
	            this.showSuccess = false;
	        },
	        deleteUser: function(user){
	            axios.delete('api/users/'+user.id)
	                .then(response => {
	                    this.showSuccess = true;
	                    this.successMessage = 'User Deleted';
	                    this.getUsers();
	                });
	        },
	        savedUser: function(){
	            this.currentUser = null;
	            this.$refs.usersListRef.editingUser = null;
	            this.showSuccess = true;
	            this.successMessage = 'User Saved';
	        },
	        cancelEdit: function(){
	            this.currentUser = null;
	            this.$refs.usersListRef.editingUser = null;
	            this.showSuccess = false;
	        },
	        getUsers: function(){
	            axios.get('api/users')
	                .then(response=>{this.users = response.data.data; });
			},
			childMessage: function(message){
				this.showSuccess = true;
	            this.successMessage = message;
			}
	    },
	    components: {
	    	'user-list': UserList,
	    	'user-edit': UserEdit
	    },
	    mounted() {
			this.getUsers();
		}

	}
</script>

<style scoped>	
p {
	font-size: 2em;
	text-align: center;
}
</style>
