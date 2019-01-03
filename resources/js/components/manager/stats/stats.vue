<template>
    <div>
        <label>List of:</label>
        <select id="type" @change="getList">
          <option value="cookers">Cookers</option>
          <option value="waiters">Waiters</option>
        </select>
        <div>
            <template v-for="(stat, index) in list">
                <waitercooker-box :stat="stat" :index="index" ref="statRef"></waitercooker-box>
            </template>
            <paginator v-show="list.length != 0" :data="paginatorData" @change-page="getList"></paginator>
        </div>
    </div>
</template>

<script type="text/javascript">
    import WaiterCookerBox from './waiterCookerbox.vue';
    import Paginator from './../../paginator.vue';

    export default {
        data: function(){
            return {
                list: [],
                paginatorData: null,
            }
        },
        methods: {
            getList: function(page){
                var select = document.getElementById("type");
                var option = select.options[select.selectedIndex].text;

                if(page == null){
                    page = 1
                }

                this.$swal({
                    text: 'Loading Statistics',
                    onBeforeOpen: () => {
                        this.$swal.showLoading();
                    }
                });
                this.$http.get('api/stats', {params: {page: page, type: option}})
                    .then(response=>{
                        this.$swal({
                            type: 'success',
                            title: 'Success',
                            text: 'Done!'
                        });
                        this.list = response.data.data;

                        var myPaginator = {
                            links: {
                                first: response.data.first_page_url,
                                last: response.data.last_page_url,
                                next: response.data.next_page_url,
                                prev: response.data.prev_page_url
                            },
                            meta: {
                                current_page: response.data.current_page,
                                from: response.data.from,
                                last_page: response.data.last_page,
                                path: response.data.path,
                                per_page: response.data.per_page,
                                to: response.data.to,
                                total: response.data.total
                            }
                        };
                        this.paginatorData = myPaginator;
                    })
                    .catch(error => {
                        this.$swal({
                            type: 'error',
                            text: 'Oh no, something went wrong!! Try again!'
                        });
                    });
            },
        },
        components: {
            'waitercooker-box': WaiterCookerBox,
            'paginator': Paginator,
        },
        mounted() {
            this.getList();
        },
    }
</script>

<style scoped>

</style>
