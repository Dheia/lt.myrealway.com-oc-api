<template>
    <div>
        <div v-text="'mx-graph: ' + Object.keys($options.mx).join(', ')" style="border: 1px solid black"></div>
        <slot></slot>
    </div>
</template>
<script>
    export default {
        props: ['container', 'model', 'renderHint', 'stylesheet'],
        created()
        {
            this.$options.mx = this.$parent.$options.mx.graph
        },
    }
</script>
