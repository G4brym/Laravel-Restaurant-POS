<template>
    <ul class="pagination no-margin">
        <li :class="{'disabled': !this.firstPage}"><a :href="this.firstPage" v-on:click.prevent="changePage(firstPage)">«</a></li>
        <li v-if="this.prevPage2"><a :href="this.prevPage2" v-on:click.prevent="changePage(prevPage2)">{{prevPage2}}</a></li>
        <li v-if="this.prevPage"><a :href="this.prevPage" v-on:click.prevent="changePage(prevPage)">{{prevPage}}</a></li>
        <li class="active"><a href="#">{{currentPage}}</a></li>
        <li v-if="this.nextPage"><a :href="this.nextPage" v-on:click.prevent="changePage(nextPage)">{{nextPage}}</a></li>
        <li v-if="this.nextPage2"><a :href="this.nextPage2" v-on:click.prevent="changePage(nextPage2)">{{nextPage2}}</a></li>
        <li :class="{'disabled': !this.lastPage}"><a :href="this.lastPage" v-on:click.prevent="changePage(lastPage)">»</a></li>
    </ul>
</template>

<script type="text/javascript">
    export default {
        props: ['data'],
        data: function(){
            return {
                firstPage: null,
                lastPage: null,
                nextPage: null,
                nextPage2: null,
                prevPage: null,
                prevPage2: null,
                currentPage: 1,
            }
        },
        watch: {
            data: function(newVal, oldVal) {
                if(newVal !== null){
                    this.currentPage = newVal.meta.current_page;

                    if(this.currentPage != 1){
                        this.firstPage = 1
                    } else {
                        this.firstPage = null
                    }
                    if(this.currentPage != newVal.meta.last_page){
                        this.lastPage = newVal.meta.last_page
                    } else {
                        this.lastPage = null
                    }

                    if(this.currentPage+1 <= newVal.meta.last_page){
                        this.nextPage = this.currentPage+1
                    } else {
                        this.nextPage = null;
                    }
                    if(this.currentPage+2 <= newVal.meta.last_page){
                        this.nextPage2 = this.currentPage+2
                    } else {
                        this.nextPage2 = null;
                    }

                    if(this.currentPage-1 >= 1){
                        this.prevPage = this.currentPage-1
                    } else {
                        this.prevPage = null;
                    }
                    if(this.currentPage-2 >= 1){
                        this.prevPage2 = this.currentPage-2
                    } else {
                        this.prevPage2 = null;
                    }
                }
            }
        },
        methods: {
            changePage: function (page) {
                this.$emit('change-page', page);
            }
        },
    }
</script>

<style scoped>

</style>
