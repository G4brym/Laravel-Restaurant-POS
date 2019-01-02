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
                        <item-list :items="items" @edit-click="editItem" @delete-click="deleteItem" @add-click="addingItem" ref="itemsListRef"></item-list>

                        <paginator :data="paginatorData" @change-page="getItems"></paginator>

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
    import Paginator from './../../paginator.vue';
    
    export default {
        data: function(){
            return { 
                currentItem: null,
                adding: false,
                items: [],
                paginatorData: null,
            }
        },
        methods: {
            insertError: function() {
                this.$swal({
                    type: 'error',
                    text: 'Oh no! We suspect there were missing arguments.'
                });
            },
            editError: function() {
                this.$swal({
                    type: 'error',
                    text: 'Oh no! Probably that name was taken.'
                });
            },
            editItem: function(item){
                this.currentItem = Object.assign({},item);
            },

            deleteItem: function(item){
                this.$http.delete('api/items/'+item.id)
                    .then(response => {
                        this.$swal({
                            type: 'success',
                            title: 'Success',
                            text: 'Item deleted successfully!'
                        });
                        this.getItems();
                    })
                    .catch(error => {
                        this.$swal({
                            type: 'error',
                            text: "Oh no, we hope it doens't happen again!"
                        });
                    });
            },
            addingItem: function(){
                this.adding = true;
            },
            addItem: function(){
                this.$swal({
                    type: 'success',
                    title: 'Success',
                    text: 'Item inserted successfully!'
                });
                this.getItems();
            },
            addItemCancel: function(){
                this.adding = false;
            },
            savedItem: function(){
                this.currentItem = null;
                this.$refs.itemsListRef.editingItem = null;
                this.$swal({
                    type: 'success',
                    title: 'Success',
                    text: 'Item updated successfully!'
                });
                this.getItems();
            },
            cancelEdit: function(){
                this.currentItem = null;
                this.$refs.itemsListRef.editingItem = null;
            },
            getItems: function(page){
                if(page == null){
                    page = 1
                }
                this.$http.get('api/items?page='+page)
                    .then(response => {
                        this.items = response.data.data; 
                        this.paginatorData = response.data;
                    }).catch(error => {
                        this.$swal({
                            type: 'error',
                            text: "Oh no, getting items from DB is not working!"
                        });
                    });
            }
        },
        components: {
            'item-list': ItemList,
            'item-edit': ItemEdit,
            'item-add': ItemAdd,
            'paginator': Paginator
        },
        mounted() {
            this.getItems();
        }

    }
</script>

<style scoped>  

</style>
