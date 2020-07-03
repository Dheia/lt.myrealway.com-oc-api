<template>
    <div>
    </div>
</template>
<script>
    import CellMixin from './mixin-cell.js'
    import CellRelationWatch from './cell-relation-watch.js'

    export default {
        props   : ['relation', 'isMxListenerRunning'],
        mixins  : [CellMixin, CellRelationWatch],
        created()
        {
            this.cellMixinCreated()
        },
        destroyed()
        {
            this.cellMixinDestroyed()
        },
        computed: {
            $editor    : vm => vm.$parent,
            $entities  : vm => vm.$editor.$children.filter(v => v.$options._componentTag === 'cell-entity'),
            $attributes: vm => vm.$entities.reduce((acc, cur) => acc.concat(
                cur.$children.filter(w => w.$options._componentTag === 'cell-attribute')
            ), []),
        },
        methods : {
            createMxCell()
            {
                let geo = new mxGeometry()

                // geo.relative = true

                this.mxCell = new mxCell(this, geo, 'relation_' + this.relation.type)

                this.mxCell.setEdge(true)

                let attributeFrom = this.$attributes.find(v => v.attribute.id === this.relation.attribute_from_id)
                let attributeTo = this.$attributes.find(v => v.attribute.id === this.relation.attribute_to_id)

                let parent = this.$editor.mxEditor.graph.getDefaultParent()

                this.$editor.mxEditor.graph.addEdge(this.mxCell, parent, attributeFrom.mxCell, attributeTo.mxCell)
            },
            mxCellSetValue(value)
            {
                // this.entity.name = value
            },
            mxCellGetValue()
            {
                return ''//this.entity.name
            },
        },
    }
</script>
