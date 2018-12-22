<template>
    <div>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Users</h3>
            </div>
            <div class="box-body">
                <div class="panel panel-info">
                    <div class="panel-body">
                        <!--TABLE LIST-->
                        <user-list :users="users" @edit-click="editUser" @unblock-user="unblockUser" @block-user="blockUser" @delete-click="deleteUser" @add-click="addingUser" @message="childMessage" ref="usersListRef"></user-list>

                        <div class="alert" :class="typeofmsg" v-if="showMessage">             
                            <button type="button" class="close-btn" v-on:click="showMessage=false">&times;</button>
                            <strong>{{ message }}</strong>
                        </div>

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
                typeofmsg: "",
                showMessage: false,
                message: "",
                currentUser: null,
                adding: false,
                users: [],
            }
        },
        methods: {
            unblockUser: function(user) {
                this.$http.put('api/users/blockUnblock/'+user.id, {operation: 0})
                    .then(response => {
                        this.typeofmsg = "alert-success";
                        this.message = "User Unblocked";
                        this.showMessage = true;
                        this.getUsers();
                    })
                    .catch(error => {
                        this.typeofmsg = "alert-danger";
                        this.message = "Error Unbloking";
                        this.showMessage = true;
                    });
            },
            blockUser: function(user) {
                this.$http.put('api/users/blockUnblock/'+user.id, {operation: 1})
                    .then(response => {
                        this.typeofmsg = "alert-success";
                        this.message = "User Blocked";
                        this.showMessage = true;
                        this.getUsers();
                    })
                    .catch(error => {
                        this.typeofmsg = "alert-danger";
                        this.message = "Error Bloking";
                        this.showMessage = true;
                    });
            },
            insertError: function() {
                this.typeofmsg = "alert-danger";
                this.message = "Missing arguments";
                this.showMessage = true;
            },
            editError: function() {
                this.typeofmsg = "alert-danger";
                this.message = "Invalid name";
                this.showMessage = true;
            },
            editUser: function(user){
                this.currentUser = Object.assign({},user);
            },

            deleteUser: function(user){
                this.$http.delete('api/users/'+user.id)
                    .then(response => {
                        this.typeofmsg = "alert-success";
                        this.showMessage = true;
                        this.message = 'User Deleted';
                        this.getUsers();
                    })
                    .catch(error => {
                        this.typeofmsg = "alert-danger";
                        this.message = "Unable to delete";
                        this.showMessage = true;
                    });
            },
            addingUser: function(){
                this.adding = true;
            },
            addUser: function(){
                this.typeofmsg = "alert-success";
                this.message = "User inserted";
                this.showMessage = true;
                this.adding = false;
                this.getUsers();
            },
            addUserCancel: function(){
                this.adding = false;
            },
            savedUser: function(){
                this.currentUser = null;
                this.$refs.usersListRef.editingUser = null;
                this.typeofmsg = "alert-success";
                this.message = "User updated";
                this.showMessage = true;
                this.getUsers();
            },
            cancelEdit: function(){
                this.currentUser = null;
                this.$refs.usersListRef.editingUser = null;
            },
            getUsers: function(){
                this.$http.get('api/users')
                    .then(response=>{this.users = response.data.data; });
            },
            childMessage: function(message){
                this.message = message;
                this.showMessage = true;
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
