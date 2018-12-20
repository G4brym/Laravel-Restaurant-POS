<template>
    <div>
        <template v-for="(meal, index) in waiterMeals">
            <meal-box :meal="meal" :index="index" @terminate-meal="terminateMeal" @delete-click="deleteOrder"></meal-box>
        </template>
    </div>
</template>

<script type="text/javascript">
    // Component code
    import MealBox from './mealBox.vue';

    export default {
        props: ['waiterMeals'],
        methods: {
            deleteOrder: function (order) {
                this.$emit('delete-click', order);
            },
            terminateMeal: function (meal, index) {
                this.$emit('terminate-meal', meal, index);
            }
        },
        components: {
            'meal-box': MealBox
        },
    }
</script>

<style scoped>

</style>
