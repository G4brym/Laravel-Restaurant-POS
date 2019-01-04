<template>
    <div class="box" :id="'box' + info.month" data-widget="box-widget">
        <div class="box-header with-border">
            <h3 class="box-title" v-if="info.month == 1"> January </h3>
            <h3 class="box-title" v-else-if="info.month == 2"> February </h3>
            <h3 class="box-title" v-else-if="info.month == 3"> March </h3>
            <h3 class="box-title" v-else-if="info.month == 4"> April </h3>
            <h3 class="box-title" v-else-if="info.month == 5"> May </h3>
            <h3 class="box-title" v-else-if="info.month == 6"> June </h3>
            <h3 class="box-title" v-else-if="info.month == 7"> July </h3>
            <h3 class="box-title" v-else-if="info.month == 8"> August </h3>
            <h3 class="box-title" v-else-if="info.month == 9"> September </h3>
            <h3 class="box-title" v-else-if="info.month == 10"> October </h3>
            <h3 class="box-title" v-else-if="info.month == 11"> November </h3>
            <h3 class="box-title" v-else> December </h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                        title="Collapse" v-on:click.prevent="toogle()">
                    <i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nº Orders</th>
                        <th>Nº Meals</th>
                        <th>Avg Meal Time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>    
                        <td>{{ info.nOrders }}</td>
                        <td>{{ info.nMeals }}</td>
                        <td>{{ info.avgMealTime }}</td>
                    </tr>       
                </tbody>
            </table>
        </div>
    </div>
</template>

<script type="text/javascript">
    // Component code

    module.exports = {
        props: ['info', 'index'],
        data: function(){
            return {
            }
        },
        mounted() {
            $("#box" + this.info.month).boxWidget('collapse');
        },
        updated() {
            $("#box" + this.info.month).boxWidget('collapse');
        },
        methods: {
            toogle: function () {
                $("#box" + this.info.month).boxWidget('collapse');
            }
        },
    }
</script>

<style scoped>

</style>
