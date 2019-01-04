<template>
    <div>
        <label>Year: </label>
        <select id="year">
            <template v-for="year in years">
                <option>{{year.Years}}</option>
            </template>
        </select>
        <input type="submit" value="Submit" v-on:click.prevent="getInfo()">
        <div>
            <template v-for="(info, index) in info">
                <month-box :info="info" :index="index" ref="performanceRef"></month-box>
            </template>
        </div>
    </div>
</template>

<script type="text/javascript">
    import MonthBox from './months.vue';

    export default {
        data: function(){
            return {
                info: [],
                years: [],
            }
        },
        methods: {
            getYears: function() {
                this.$swal({
                    text: 'Getting things ready!',
                    onBeforeOpen: () => {
                        this.$swal.showLoading();
                    }
                });
                this.$http.get('api/years')
                    .then(response=>{
                        this.$swal({
                            type: 'success',
                            title: 'Success',
                            text: 'Choose an year to proceed!'
                        });
                        this.years = response.data;
                    })
                    .catch(error => {
                        this.$swal({
                            type: 'error',
                            text: 'Oh no, something went wrong!! Try again!'
                        });
                    });
            },
            getInfo: function(){
                var select = document.getElementById("year");
                var option = select.options[select.selectedIndex].text;

                this.$swal({
                    text: 'Loaging performance for: ' + option + ' ...',
                    onBeforeOpen: () => {
                        this.$swal.showLoading();
                    }
                });
                this.$http.get('api/stats', {params: {year: option}})
                    .then(response=>{
                        this.$swal({
                            type: 'success',
                            title: 'Success',
                            text: 'Done!'
                        });
                        this.info = response.data;
                        console.log(this.info);
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
            'month-box': MonthBox,
        },
        mounted() {
            this.getYears();
        },
    }
</script>

<style scoped>

</style>
