<template>
    <div>
        <div v-text="'mx-stylesheet: ' + Object.keys($options.mx).join(', ')" style="border: 1px solid black"></div>
        <slot></slot>
    </div>
</template>
<script>
    export default {
        created()
        {
            this.$options.mx = this.$parent.$options.mx.graph.stylesheet
        },
    }
</script>
