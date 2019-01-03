<template>
    <div>
        <label>State:</label>
        <select id="filter">
          <option value="all">Active and Terminated</option>
          <option value="pending">Active</option>
          <option value="terminated">Terminated</option>
          <option value="paid">Paid</option>
          <option value="not_paid">Not Paid</option>
        </select>
        <label>Date:</label>
        <input id="date" type="date" name="date">
        <label>Waiter:</label>
        <input id="waiter" type="text" name="waiter">
        <input type="submit" value="Submit" v-on:click.prevent="getMeals()">
        <br>
        <template v-if="meals.length == 0">
            <p>There are no meals</p>
        </template>
        <template v-else>
            <template v-for="(meal, index) in meals">
                <meal-box :meal="meal" :index="index" ref="mealsRef"></meal-box>
            </template>   
        </template>
        <paginator v-show="meals.length != 0" :data="paginatorData" @change-page="getMeals"></paginator>
    </div>
</template>

<script type="text/javascript">
    import MealBox from './mealBox.vue';
    import Paginator from './../../paginator.vue';

    export default {
        data: function(){
            return {
                meals: [],
                paginatorData: null,
            }
        },
        methods: {
            getMeals: function(page){
                var select = document.getElementById("filter");
                var option = select.options[select.selectedIndex].text;

                var dateForm = document.getElementById("date");
                var date = dateForm.value;

                var waiterForm = document.getElementById("waiter");
                var waiter = waiterForm.value;

                if(page == null){
                    page = 1
                }
                
                this.$swal({
                    text: 'Loading Meals',
                    onBeforeOpen: () => {
                        this.$swal.showLoading();
                    }
                });
                this.$http.get('api/meals', {params: {page: page, filter: option, date: date, waiterName: waiter}})
                    .then(response=>{
                        this.$swal({
                            type: 'success',
                            title: 'Success',
                            text: 'Done!'
                        });
                        this.meals = response.data.data;
                        this.paginatorData = response.data;
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
            'meal-box': MealBox,
            'paginator': Paginator,
        },
        mounted() {
            this.getMeals();
        },
    }
</script>

<style scoped>

</style>
