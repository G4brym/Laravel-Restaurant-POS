<template>
    <div>
		<div class="box">
	        <div class="box-header with-border">
	            <h3 class="box-title">Restaurant Tables</h3>
	        </div>
	        <div class="box-body">
	            <div class="panel panel-info">
	                <div class="panel-body">
	                	<!--TABLE LIST-->
						<table-list :tables="tables" @edit-click="editTable" @delete-click="deleteTable" @add-click="addingTable" ref="tablesListRef"></table-list>

						<paginator :data="paginatorData" @change-page="getTables"></paginator>

			            <!--TABLE ADD-->	
			            <table-add @insert-error="insertError" @table-canceled="addTableCancel" @table-inserted="addTable" v-if="adding"></table-add>


			            <!--TABLE EDIT-->
			            <table-edit @edit-error="editError" :table="currentTable" @table-saved="savedTable" @table-canceled="cancelEdit" v-if="currentTable"></table-edit>

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
	import TableEdit from './edit.vue';
	import TableList from './list.vue';
	import TableAdd from './add.vue';
	import Paginator from './../../paginator.vue';
	
	export default {
		data: function(){
			return { 
		        currentTable: null,
		        adding: false,
		        tables: [],
		        paginatorData: null,
			}
		},
	    methods: {
	    	insertError: function() {
	    		this.$swal({
                    type: 'error',
                    text: 'Oh no! We suspect that number is taken.'
                });
	    	},
	    	editError: function() {
	    		this.$swal({
                    type: 'error',
                    text: 'Oh no! Probably that number was taken.'
                });
	    	},
	        editTable: function(table){
	            this.currentTable = Object.assign({},table);
	        },

	        deleteTable: function(table){
	            this.$http.delete('api/tables/'+table.table_number)
	                .then(response => {
                        this.$swal({
                            type: 'success',
                            title: 'Success',
                            text: 'Table deleted successfully!'
                        });
                        this.getTables();
                    })
                    .catch(error => {
                        this.$swal({
                            type: 'error',
                            text: "Oh no, we hope it doens't happen again!"
                        });
                    });
	        },
	        addingTable: function(){
	  			this.adding = true;
	        },
	        addTable: function(){
	        	this.$swal({
                    type: 'success',
                    title: 'Success',
                    text: 'Table inserted successfully!'
                });
	  			this.getTables();
	        },
	        addTableCancel: function(){
	  			this.adding = false;
	        },
	        savedTable: function(){
	            this.currentTable = null;
	            this.$refs.tablesListRef.editingTable = null;
	            this.$swal({
                    type: 'success',
                    title: 'Success',
                    text: 'Table updated successfully!'
                });
	            this.getTables();
	        },
	        cancelEdit: function(){
	            this.currentTable = null;
	            this.$refs.tablesListRef.editingTable = null;
	        },
	        getTables: function(page){
	        	if(page == null){
                    page = 1
                }
	            this.$http.get('api/tables?page='+page)
                    .then(response => {
                        this.tables = response.data.data; 
                        this.paginatorData = response.data;
                    }).catch(error => {
                        this.$swal({
                            type: 'error',
                            text: "Oh no, getting tables from DB is not working!"
                        });
                    });
			}
	    },
	    components: {
	    	'table-list': TableList,
	    	'table-edit': TableEdit,
	    	'table-add': TableAdd,
	    	'paginator': Paginator
	    },
	    mounted() {
			this.getTables();
		}

	}
</script>

<style scoped>	

</style>
