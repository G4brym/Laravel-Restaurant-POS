<template>
    <div>
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Items</h3>
            </div>
            <div class="box-body">
                <div class="panel panel-info">
                    <div class="panel-body">
                        <!--TABLE LIST-->
                        <item-list :items="items" @edit-click="editItem" @delete-click="deleteItem" @add-click="addingItem" @message="childMessage" ref="itemsListRef"></item-list>

                        <div class="alert" :class="typeofmsg" v-if="showMessage">             
                            <button type="button" class="close-btn" v-on:click="showMessage=false">&times;</button>
                            <strong>{{ message }}</strong>
                        </div>

                        <!--TABLE ADD-->    
                        <item-add @insert-error="insertError" @item-canceled="addItemCancel" @item-inserted="addItem" v-if="adding"></item-add>


                        <!--TABLE EDIT-->
                        <item-edit @edit-error="editError" :item="currentItem" @item-saved="savedItem" @item-canceled="cancelEdit" v-if="currentItem"></item-edit>

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
    import ItemEdit from './edit.vue';
    import ItemList from './list.vue';
    import ItemAdd from './add.vue';
    
    export default {
        data: function(){
            return { 
                typeofmsg: "",
                showMessage: false,
                message: "",
                currentItem: null,
                adding: false,
                items: [],
            }
        },
        methods: {
            insertError: function() {
                this.typeofmsg = "alert-danger";
                this.message = "Invalid item number";
                this.showMessage = true;
            },
            editError: function() {
                this.typeofmsg = "alert-danger";
                this.message = "Invalid number";
                this.showMessage = true;
            },
            editItem: function(item){
                this.currentItem = Object.assign({},item);
            },

            deleteItem: function(item){
                this.$http.delete('api/items/'+item.id)
                    .then(response => {
                        this.typeofmsg = "alert-success";
                        this.showMessage = true;
                        this.message = 'Item Deleted';
                        this.getItems();
                    })
                    .catch(error => {
                        this.typeofmsg = "alert-danger";
                        this.message = "Unable to delete";
                        this.showMessage = true;
                    });
            },
            addingItem: function(){
                this.adding = true;
            },
            addItem: function(){
                this.typeofmsg = "alert-success";
                this.message = "Item inserted";
                this.showMessage = true;
                this.adding = false;
                this.getItems();
            },
            addItemCancel: function(){
                this.adding = false;
            },
            savedItem: function(){
                this.currentItem = null;
                this.$refs.itemsListRef.editingItem = null;
                this.typeofmsg = "alert-success";
                this.message = "Item updated";
                this.showMessage = true;
                this.getItems();
            },
            cancelEdit: function(){
                this.currentItem = null;
                this.$refs.itemsListRef.editingItem = null;
            },
            getItems: function(){
                this.$http.get('api/items')
                    .then(response=>{this.items = response.data.data; });
            },
            childMessage: function(message){
                this.message = message;
                this.showMessage = true;
            }
        },
        components: {
            'item-list': ItemList,
            'item-edit': ItemEdit,
            'item-add': ItemAdd
        },
        mounted() {
            this.getItems();
        }

    }
</script>

<style scoped>  

</style>
