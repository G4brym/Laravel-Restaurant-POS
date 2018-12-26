<template>
	<table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Description</th>
                <th>Price</th>
                <th>Photo</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in items" :key="item.id">
                <template v-if="item.deleted_at === null">
                    <td>{{ item.name }}</td>
                    <td>{{ item.type }}</td>
                    <td>{{ item.description }}</td>
                    <td>{{ item.price }}</td>
                    <td><img :src='"/storage/items/" + item.photo_url' alt="imagem" height="120px" width="120px"></img></td>
                    <td>
    	                <a class="btn btn-sm btn-primary" v-on:click.prevent="editItem(item)">Edit</a>
    	                <a class="btn btn-sm btn-danger" v-on:click.prevent="deleteItem(item)">Delete</a>
            		</td>
                </template>
            </tr>
            <a class="btn btn-sm btn-primary" v-on:click.prevent="addItem()">Add new item</a>
        </tbody>
    </table>
</template>

<script type="text/javascript">
	module.exports={
		props: ['items'],
		data: function(){
			return { 
				editingItem: null
			}
		},
        methods: {
            editItem: function(item){
                this.editingItem = item; 
                this.$emit('edit-click', item);
            },		
            deleteItem: function(item){
                this.editingItem = item;
                this.$emit('delete-click', item);
			},
			addItem: function(){
				this.$emit('add-click');
			},
        },		
	}
</script>

<style scoped>

</style>