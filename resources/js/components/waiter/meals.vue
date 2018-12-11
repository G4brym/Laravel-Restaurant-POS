<template>
    <div>
        <template v-for="meal in waiterMeals">
            <meal-box :meal="meal"></meal-box>
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
            }
        },
        components: {
            'meal-box': MealBox
        },
    }
</script>

<style scoped>

</style>
