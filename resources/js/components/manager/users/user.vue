<template>
    <div>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Users</h3>
            </div>
            <div class="box-body">
                <div class="panel panel-info">
                    <div class="panel-body">
                        <select id="filter" @change="getUsers()">
                          <option value="all">All</option>
                          <option value="blocked">Blocked</option>
                          <option value="unblocked">Unblocked</option>
                          <option value="soft_deleted">Soft Deleted</option>
                        </select>
                        <!--TABLE LIST-->
                        <user-list :users="users" @edit-click="editUser" @unblock-user="unblockUser" @block-user="blockUser" @delete-click="deleteUser" @add-click="addingUser" ref="usersListRef"></user-list>

                        <!--TABLE ADD-->    
                        <user-add @insert-error="insertError" @user-canceled="addUserCancel" @user-inserted="addUser" v-if="adding"></user-add>


                        <!--TABLE EDIT-->
                        <user-edit @edit-error="editError" :user="currentUser" @user-saved="savedUser" @user-canceled="cancelEdit" v-if="currentUser"></user-edit>

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
    import UserEdit from './edit.vue';
    import UserList from './list.vue';
    import UserAdd from './add.vue';
    
    export default {
        data: function(){
            return { 
                currentUser: null,
                adding: false,
                users: [],
            }
        },
        methods: {
            unblockUser: function(user) {
                this.$swal({
                    text: 'Unbloking user with id: ' + user.id + ' ...',
                    onBeforeOpen: () => {
                        this.$swal.showLoading();
                    }
                });
                this.$http.put('api/users/blockUnblock/'+user.id, {operation: 0})
                    .then(response => {
                       this.$swal({
                            type: 'success',
                            title: 'Success',
                            text: 'User unblocked successfully!'
                        });
                        this.getUsers();
                    })
                    .catch(error => {
                        this.$swal({
                            type: 'error',
                            text: 'Oh no, something went wrong!! Try again!'
                        });
                    });
            },
            blockUser: function(user) {
                this.$swal({
                    text: 'Bloking user with id: ' + user.id + ' ...',
                    onBeforeOpen: () => {
                        this.$swal.showLoading();
                    }
                });
                this.$http.put('api/users/blockUnblock/'+user.id, {operation: 1})
                    .then(response => {
                        this.$swal({
                            type: 'success',
                            title: 'Success',
                            text: 'User blocked successfully!'
                        });
                        this.getUsers();
                    })
                    .catch(error => {
                        this.$swal({
                            type: 'error',
                            text: 'Oh no, something went wrong!! Try again!'
                        });
                    });
            },
            insertError: function() {
                this.$swal({
                    type: 'error',
                    text: 'Oh no! We suspect missing arguments.'
                });
            },
            editError: function() {
                this.$swal({
                    type: 'error',
                    text: 'Oh no! Probably that name was taken.'
                });
            },
            editUser: function(user){
                this.currentUser = Object.assign({},user);
            },

            deleteUser: function(user){
                this.$http.delete('api/users/'+user.id)
                    .then(response => {
                        this.$swal({
                            type: 'success',
                            title: 'Success',
                            text: 'User deleted successfully!'
                        });
                        this.getUsers();
                    })
                    .catch(error => {
                        this.$swal({
                            type: 'error',
                            text: "Oh no, we hope it doens't happen again!"
                        });
                    });
            },
            addingUser: function(){
                this.adding = true;
            },
            addUser: function(){
                this.$swal({
                    type: 'success',
                    title: 'Success',
                    text: 'User inserted successfully!'
                });
                this.getUsers();
            },
            addUserCancel: function(){
                this.adding = false;
            },
            savedUser: function(){
                this.currentUser = null;
                this.$refs.usersListRef.editingUser = null;
                this.$swal({
                    type: 'success',
                    title: 'Success',
                    text: 'User updated successfully!'
                });
                this.getUsers();
            },
            cancelEdit: function(){
                this.currentUser = null;
                this.$refs.usersListRef.editingUser = null;
            },
            getUsers: function(){
                var select = document.getElementById("filter");
                var option = select.options[select.selectedIndex].text;
                this.$http.get('api/users', {params: {filter: option}}) 
                .then(response => {
                    this.users = response.data.data; 
                }).catch(error => {
                    this.$swal({
                        type: 'error',
                        text: "Oh no, getting users from DB is not working!"
                    });
                });
            }
        },
        components: {
            'user-list': UserList,
            'user-edit': UserEdit,
            'user-add': UserAdd
        },
        mounted() {
            this.getUsers();
        }

    }
</script>

<style scoped>  

</style>
