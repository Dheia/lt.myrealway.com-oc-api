<template>
    <div>
        <div ref="container" class="container"></div>
        <div class="sidebar">
            <se-sidebar :entities="entities"></se-sidebar>
        </div>
        <cell-entity
                v-for="(entity, i) in entities"
                :entity="entity"
                :key="entity.ukey"
                :is-mx-listener-running="isMxListenerRunning"
        ></cell-entity>
    </div>
</template>
<script>
    import SeSidebar from './sidebar.vue'
    import CellEntity from './cell-entity.vue'
    import cellStyles from './cell-styles.js'

    export default {
        components: {
            SeSidebar,
            CellEntity,
        },
        props     : ['entities'],
        data      : vm => ({
            isMxListenerRunning: false,
        }),
        methods   : {
            $entities()
            {
                return this.$children.filter(c => c.$options._componentTag === 'cell-entity')
            },
        },
        created()
        {
            this.mxEditor = new mxEditor()

            cellStyles(this.mxEditor.graph)

            ////////////////////////////////////////////////////////////////////////////////
            // Cell labels
            ////////////////////////////////////////////////////////////////////////////////
            mxCell.prototype.setValue = function (value)
            {
                this.value.mxCellSetValue(value)
            }

            mxCell.prototype.getValue = function ()
            {
                return this.value.mxCellGetValue()
            }

            ////////////////////////////////////////////////////////////////////////////////
            // Selection: mx -> vue
            ////////////////////////////////////////////////////////////////////////////////
            this.mxEditor.graph.getSelectionModel()
                .addListener(mxEvent.CHANGE, (selectionModel, evt) =>
                {
                    this.$entities().forEach($entity =>
                    {
                        this.$set($entity.entity, 'isSelected', selectionModel.cells.includes($entity.mxCell))

                        $entity.$attributes().forEach($attribute =>
                        {
                            this.$set($attribute.attribute, 'isSelected', selectionModel.cells.includes($attribute.mxCell))
                        })
                    })
                })

            ////////////////////////////////////////////////////////////////////////////////
            // Coordinates: mx -> vue
            ////////////////////////////////////////////////////////////////////////////////
            this.mxEditor.graph.addListener(mxEvent.MOVE_CELLS, (graph, eventObject) =>
            {
                this.isMxListenerRunning = true

                let p = eventObject.properties

                for (let i = 0; i < p.cells.length; i++)
                {
                    let v = p.cells[i].value.entity

                    if (!v)
                    {
                        continue    // Must be an attribute or other non-entity cell
                    }

                    v.x = parseInt(v.x) + parseInt(p.dx)
                    v.y = parseInt(v.y) + parseInt(p.dy)
                }

                this.$nextTick(() =>
                {
                    this.isMxListenerRunning = false
                })
            })

            ////////////////////////////////////////////////////////////////////////////////
            // Movable
            ////////////////////////////////////////////////////////////////////////////////
            this.mxEditor.graph.isCellMovable = function (cell)
            {
                return cell.style === 'entity'
            }

            ////////////////////////////////////////////////////////////////////////////////
            // Auto resize entity to fit its attributes
            ////////////////////////////////////////////////////////////////////////////////
            this.mxEditor.layoutSwimlanes = true
            this.mxEditor.createSwimlaneLayout = function ()
            {
                let layout = new mxStackLayout(this.graph, false)

                layout.fill = true
                layout.resizeParent = true
                layout.isVertexMovable = () => true

                return layout
            }

        },
        mounted()
        {
            this.mxEditor.setGraphContainer(this.$refs.container)

            this.mxEditor.graph.refresh()
        },
    }
</script>
<style scoped lang="scss">
    .editor {
        position: absolute;
        left: 0;
        top: 0;
        right: 400px;
        bottom: 0;
        background: white;
    }

    .sidebar {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        width: 400px;
    }
</style>
