<template>
    <div>
        <div ref="container" style="height: 100%"></div>
        <div v-for="(cell, i) in val.cells">
            <mx-cell v-model="val.cells[i]"
                     :key="cell.ukey"
                     :is-mx-listener-running="isMxListenerRunning"
            ></mx-cell>
        </div>
    </div>
</template>
<script>
    import mxvueHelper from './mxvue-helper.js'
    import wheelZoom from './wheel-zoom.js'

    var editorIndex = 0

    export default {
        props   : {
            'value'  : {},
            wheelZoom: {
                type    : Boolean,
                required: true,
                // default : true,
            },
        },
        mixins  : [
            mxvueHelper(self => self.$options)
        ],
        data()
        {
            return {
                isMxListenerRunning: false,
                cells              : [],
            }
        },
        watch   : {
            value: {
                deep: true,
                handler(value)
                {
                    console.log('this.value', value)
                },
            },
        },
        computed: {
            val()
            {
                let result = this.value || {}

                result.cells = result.cells || []
                result.selectedIds = result.selectedIds || []

                return result
            },
        },
        created()
        {
            editorIndex++

            this.$options.editorIndex = editorIndex

            this.$options.editor = new mxEditor()
        },
        mounted()
        {
            wheelZoom.addListener(this)

            ////////////////////////////////////////////////////////////////////////////////
            // Reactivity: labels
            ////////////////////////////////////////////////////////////////////////////////
            this.graph.addListener(mxEvent.LABEL_CHANGED, (graph, eventObject) =>
            {
                let p = eventObject.properties

                p.cell.value.vueComponent.value.value.name = p.value
            })

            ////////////////////////////////////////////////////////////////////////////////
            // Reactivity: selection
            ////////////////////////////////////////////////////////////////////////////////
            this.graph.getSelectionModel().addListener(mxEvent.CHANGE, (selectionModel, evt) =>
            {
                let s = {
                    ...this.val,
                    selectedIds: selectionModel.cells.map(cell => cell.id)
                }

                this.$emit('input', s)
            })

            ////////////////////////////////////////////////////////////////////////////////
            // Reactivity: coordinates
            ////////////////////////////////////////////////////////////////////////////////
            this.graph.addListener(mxEvent.MOVE_CELLS, (graph, eventObject) =>
            {
                this.isMxListenerRunning = true

                let p = eventObject.properties

                for (let i = 0; i < p.cells.length; i++)
                {
                    let v = p.cells[i].value.vueComponent.value

                    v.x = parseInt(v.x) + parseInt(p.dx)
                    v.y = parseInt(v.y) + parseInt(p.dy)
                }

                this.$nextTick(() =>
                {
                    this.isMxListenerRunning = false
                })
            })

            // this.graph.addListener(mxEvent.RESIZE_CELLS, (graph, eventObject) =>
            // {
            //     console.log('RESIZE_CELLS', eventObject.properties)
            //
            //     this.isMxListenerRunning = true
            //
            //     // let p = eventObject.properties
            //     //
            //     // for (let i = 0; i < p.cells.length; i++)
            //     // {
            //     //     let v = p.cells[i].value.vueComponent.value
            //     //
            //     //     v.x = parseInt(v.x) + parseInt(p.dx)
            //     //     v.y = parseInt(v.y) + parseInt(p.dy)
            //     // }
            //     //
            //     this.$nextTick(() =>
            //     {
            //         this.isMxListenerRunning = false
            //     })
            // })
            // this.graph.addListener(mxEvent.CELLS_ADDED, () =>
            // {
            //     // console.log('CELLS_ADDED', arguments)
            // })
            //

            this.editor.setGraphContainer(this.$refs.container)

            this.update()
        },
        methods : {
            update()
            {
                this.$emit('input', {...this.val})
            },
        }
    }
</script>
