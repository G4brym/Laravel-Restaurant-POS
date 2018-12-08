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
						<table-list :tables="tables" @edit-click="editTable" @delete-click="deleteTable" @add-click="addingTable" @message="childMessage" ref="tablesListRef"></table-list>

	                    <div class="alert" :class="typeofmsg" v-if="showMessage">             
				            <button type="button" class="close-btn" v-on:click="showMessage=false">&times;</button>
				            <strong>{{ message }}</strong>
				        </div>

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
	import TableEdit from './tableEdit.vue';
	import TableList from './tableList.vue';
	import TableAdd from './tableAdd.vue';
	
	export default {
		data: function(){
			return { 
                typeofmsg: "",
                showMessage: false,
                message: "",
		        currentTable: null,
		        adding: false,
		        tables: [],
			}
		},
	    methods: {
	    	insertError: function() {
	    		this.typeofmsg = "alert-danger";
                this.message = "Invalid table number";
                this.showMessage = true;
	    	},
	    	editError: function() {
	    		this.typeofmsg = "alert-danger";
                this.message = "Invalid number";
                this.showMessage = true;
	    	},
	        editTable: function(table){
	            this.currentTable = table;
	        },

	        deleteTable: function(table){
	            axios.delete('api/tables/'+table.table_number)
	                .then(response => {
	                	this.typeofmsg = "alert-success";
	                    this.showMessage = true;
	                    this.message = 'Table Deleted';
	                    this.getTables();
	                })
	                .catch(error => {
                    	this.typeofmsg = "alert-danger";
		                this.message = "Unable to delete";
		                this.showMessage = true;
                    });
	        },
	        addingTable: function(){
	  			this.adding = true;
	        },
	        addTable: function(){
	        	this.typeofmsg = "alert-success";
	  			this.message = "Table inserted";
	            this.showMessage = true;
	  			this.adding = false;
	  			this.getTables();
	        },
	        addTableCancel: function(){
	  			this.adding = false;
	        },
	        savedTable: function(){
	            this.currentTable = null;
	            this.$refs.tablesListRef.editingTable = null;
	            this.typeofmsg = "alert-success";
	            this.message = "Table updated";
	            this.showMessage = true;
	        },
	        cancelEdit: function(){
	            this.currentTable = null;
	            this.$refs.tablesListRef.editingTable = null;
	        },
	        getTables: function(){
	            axios.get('api/tables')
	                .then(response=>{this.tables = response.data.data; });
			},
			childMessage: function(message){
				this.message = message;
	            this.showMessage = true;
			}
	    },
	    components: {
	    	'table-list': TableList,
	    	'table-edit': TableEdit,
	    	'table-add': TableAdd
	    },
	    mounted() {
			this.getTables();
		}

	}
</script>

<style scoped>	

</style>
