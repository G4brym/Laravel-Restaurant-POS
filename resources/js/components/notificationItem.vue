<template>
    <li>
        <a :href="href">
            <i class="fa" :class="classes"></i> {{ text }}
        </a>
    </li>
</template>

<script>
    export default {
        props: ["classes", "text", "href"],
    }
</script>

<style scoped>

</style>
