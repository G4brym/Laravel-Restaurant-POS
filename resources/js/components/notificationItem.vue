<template>
    <li>
        <a href="#">
            <i class="fa fa-users text-aqua" :class="classes"></i> {{ text }}
        </a>
    </li>
</template>

<script>
    export default {
        props: ["classes", "text"],
        data: function() {
            return {

            }
        }
    }
</script>

<style scoped>

</style>
