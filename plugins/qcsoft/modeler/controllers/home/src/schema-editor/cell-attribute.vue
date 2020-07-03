<template>
    <div>
    </div>
</template>
<script>
    import CellMixin from './mixin-cell.js'
    import CellAttributeWatch from './cell-attribute-watch.js'

    export default {
        props   : ['attribute'],
        mixins  : [CellMixin, CellAttributeWatch],
        created()
        {
            this.cellMixinCreated()
        },
        destroyed()
        {
            this.cellMixinDestroyed()
        },
        computed: {
            $entity: vm => vm.$parent,
            $editor: vm => vm.$parent.$parent,
        },
        methods : {
            createMxCell()
            {
                this.mxCell = new mxCell(
                    this,
                    new mxGeometry(0, 0, 0, 30),
                    'attribute'
                )

                this.mxCell.setVertex(true)

                this.$editor.mxEditor.graph.addCell(this.mxCell, this.$entity.mxCell)
            },
            mxCellSetValue(value)
            {
                // this.attribute.name = value
            },
            mxCellGetValue()
            {
                return `${this.attribute.name} <strong>${this.attribute.type}</strong> (${this.attribute.id})`
            },
        },
    }
</script>
