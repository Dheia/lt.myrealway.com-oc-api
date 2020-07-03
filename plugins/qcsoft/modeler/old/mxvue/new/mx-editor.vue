<template>
    <div>
        <div ref="container" style="background: white; position: absolute; left: 0; right: 800px; top: 0; bottom: 0"></div>
        <div style="position: absolute; width: 800px; right: 0; top: 0; bottom: 0">
        <div v-text="'mx-editor: ' + Object.keys($options.mx).join(', ')"
             style="border: 1px solid black"></div>
        <slot></slot>
        <mx-graph ref="$graph"
        ></mx-graph>
        <mx-graph-model ref="$graphModel">
        </mx-graph-model>
        <mx-graph-view ref="$graphView">
        </mx-graph-view>
        <mx-stylesheet ref="$stylesheet">
        </mx-stylesheet>
        <mx-graph-selection-model ref="$graphSelectionModel"
        ></mx-graph-selection-model>
    </div>
    </div>
</template>
<script>
    import MxGraph from './mx-graph.vue'
    import MxGraphModel from './mx-graph-model.vue'
    import MxGraphView from './mx-graph-view.vue'
    import MxStylesheet from './mx-stylesheet.vue'
    import MxGraphSelectionModel from './mx-graph-selection-model.vue'

    export default {
        components: {
            MxGraph,
            MxGraphModel,
            MxGraphView,
            MxStylesheet,
            MxGraphSelectionModel,
        },
        props     : ['config'],
        created()
        {
            this.$options.mx = new mxEditor()
        },
        mounted()
        {
            this.$options.mx.setGraphContainer(this.$refs.container)
        },
    }
</script>
