<template>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Menu</h3>
        </div>
        <div class="box-body">
            <!--MENU LIST-->
            <template v-if="items">
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
                            <td>{{ item.name }}</td>
                            <td>{{ item.type }}</td>
                            <td>{{ item.description }}</td>
                            <td>{{ item.price }}</td>
                            <td><img :src='"/storage/items/" + item.photo_url' alt="imagem" height="120px" width="120px"/></td>
                        </tr>
                    </tbody>
                </table>
            </template>
            <div class="text-center" v-else>
                Loading...
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">    
    export default {
        data: function() {
            return { 
		        items: null
			};
        },
        methods: {
            setItems: function() {
                if (this.$store.state.items) {
                    this.items = this.$store.state.items;
                } else {
                    setTimeout(this.setItems, 200);
                }
			},
        },
        mounted() {
            this.setItems();
		}
    }
</script>

<style scoped>	

</style>
