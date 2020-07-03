<template>
    <div>
        <div v-text="'mx-graph-model: ' + Object.keys($options.mx).join(', ')" style="border: 1px solid black"></div>
        <div v-for="cell in $options.mx.cells"
             v-text="cell.id + ': ' + cell.value"
        ></div>
        <slot></slot>
    </div>
</template>
<script>
    export default {
        props: ['root'],
        created()
        {
            this.$options.mx = this.$parent.$options.mx.graph.model
        },
    }
</script>
