<template>
    <div>
        <div v-text="'mx-graph-selection-model: ' + Object.keys($options.mx).join(', ')" style="border: 1px solid black"></div>
        <slot></slot>
    </div>
</template>
<script>
    export default {
        created()
        {
            this.$options.mx = this.$parent.$options.mx.graph.selectionModel
        },
    }
</script>
