<template>
    <div>
    </div>
</template>
<script>
    import CellMixin from './mixin-cell.js'
    import CellAttributeWatch from './cell-attribute-watch.js'
    import MxCellValue from './cell-attribute-mx-cell-value'
    import './cell-attribute-mx-cell-style.scss'

    export default {
        props   : ['attribute', 'index'],
        mixins  : [CellMixin, CellAttributeWatch, MxCellValue],
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
        },
    }
</script>
