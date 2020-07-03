<template>
    <div>
        <btn type="primary" @click="qwer">qwer</btn>
        <div style="position: absolute; left: 0; right: 0; top: 40px; bottom: 0">
            <mx-editor ref="$editor">
            </mx-editor>
        </div>
    </div>
</template>
<script>
    import MxGraph from './mxvue/mx-graph.vue'
    import MxGraphModel from './mxvue/mx-graph-model.vue'
    import MxGraphView from './mxvue/mx-graph-view.vue'
    import MxStylesheet from './mxvue/mx-stylesheet.vue'
    import cellStyles from './cell-styles.js'

    export default {
        components: {
            MxStylesheet,
            MxGraphView,
            MxGraph,
            MxGraphModel,
        },
        mounted()
        {
            cellStyles(this.graph)
            // console.log(this.$refs.$editor.$refs.$graphModel)
            this.cells = this.graphModel.cells
        },
        data()
        {
            return {
                cells: [],
            }
        },
        watch:{
            cells:{
                deep:true,
                handler(value){
                    let cell = new mxCell({
                            name        : 'qwerasdf',
                            vueComponent: this,
                        },
                        new mxGeometry(0, 0, 250, 150),
                        'entity'
                    )

                    cell.setVertex(true)

                    this.graph.addCell(cell, this.graph.getDefaultParent())

                },
            }
        },
        methods   : {
            qwer()
            {
                this.$nextTick(() =>
                {
                    let cell = new mxCell({
                            name        : 'qwerasdf',
                            vueComponent: this,
                        },
                        new mxGeometry(0, 0, 250, 150),
                        'entity'
                    )

                    cell.setVertex(true)

                    this.graph.addCell(cell, this.graph.getDefaultParent())


                    let cell2 = new mxCell({
                            name        : 'asdfzxcv',
                            vueComponent: this,
                        },
                        new mxGeometry(0, 0, 120, 50),
                        'attribute'
                    )

                    cell2.setVertex(true)

                    this.graph.addCell(cell2, cell)

                    console.log(this.graphModel.cells)
                })
            },
        },
        computed  : {
            editor()
            {
                return this.$refs.$editor.$options.mx
            },
            graph()
            {
                return this.$refs.$editor.$refs.$graph.$options.mx
            },
            graphModel()
            {
                return this.$refs.$editor.$refs.$graphModel.$options.mx
            },
            graphView()
            {
                return this.$refs.$editor.$refs.$graphView.$options.mx
            },
            stylesheet()
            {
                return this.$refs.$editor.$refs.$stylesheet.$options.mx
            },
        },
    }
</script>
