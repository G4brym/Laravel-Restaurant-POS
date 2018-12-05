<template>
    <div>
		<div class="box">
	        <div class="box-header with-border">
	            <h3 class="box-title">Mesas</h3>
	        </div>
	        <div class="box-body">
	            <div class="panel panel-info">
	                <div class="panel-body">
	                	<!--TABLE LIST-->
						<table-list :tables="tables" @edit-click="editTable" @delete-click="deleteTable" @message="childMessage" ref="tablesListRef"></table-list>

						<div class="alert alert-success" v-if="showSuccess">
			                <button type="button" class="close-btn" v-on:click="showSuccess=false">&times;</button>
			                <strong>{{ successMessage }}</strong>
			            </div>

			            <!--TABLE EDIT-->
			            <table-edit :table="currentTable" :departments="this.$store.state.departments"  @table-saved="savedTable" @table-canceled="cancelEdit" v-if="currentTable"></table-edit>

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
	
	export default {
		data: function(){
			return { 
		        showSuccess: false,
		        successMessage: '',
		        currentTable: null,
		        tables: [],
			}
		},
	    methods: {
	        editTable: function(table){
	            this.currentTable = table;
	            this.showSuccess = false;
	        },
	        deleteTable: function(table){
	            axios.delete('api/tables/'+table.id)
	                .then(response => {
	                    this.showSuccess = true;
	                    this.successMessage = 'Table Deleted';
	                    this.getTables();
	                });
	        },
	        savedTable: function(){
	            this.currentTable = null;
	            this.$refs.tablesListRef.editingTable = null;
	            this.showSuccess = true;
	            this.successMessage = 'Table Saved';
	        },
	        cancelEdit: function(){
	            this.currentTable = null;
	            this.$refs.tablesListRef.editingTable = null;
	            this.showSuccess = false;
	        },
	        getTables: function(){
	            axios.get('api/tables')
	                .then(response=>{this.tables = response.data.data; });
			},
			childMessage: function(message){
				this.showSuccess = true;
	            this.successMessage = message;
			}
	    },
	    components: {
	    	'table-list': TableList,
	    	'table-edit': TableEdit
	    },
	    mounted() {
			this.getTables();
		}

	}
</script>

<style scoped>	
p {
	font-size: 2em;
	text-align: center;
}
</style>
