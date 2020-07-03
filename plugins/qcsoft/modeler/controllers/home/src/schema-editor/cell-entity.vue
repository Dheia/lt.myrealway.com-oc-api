<template>
    <div>
        <slot></slot>
    </div>
</template>
<script>
    import CellMixin from './mixin-cell.js'
    import CellEntityWatch from './cell-entity-watch.js'
    import CellAttribute from './cell-attribute.vue';

    export default {
        components: {CellAttribute},
        props     : ['entity', 'isMxListenerRunning'],
        mixins    : [CellMixin, CellEntityWatch],
        created()
        {
            this.cellMixinCreated()
        },
        destroyed()
        {
            this.cellMixinDestroyed()
        },
        computed  : {
            $attributes: vm => vm.$children.filter(v => v.$options._componentTag === 'cell-attribute'),
            $editor    : vm => vm.$parent,
        },
        methods   : {
            createMxCell()
            {
                this.mxCell = new mxCell(
                    this,
                    new mxGeometry(this.entity.x, this.entity.y, this.entity.width, this.entity.height),
                    'entity'
                )

                this.mxCell.setVertex(true)

                let parent = this.$editor.mxEditor.graph.getDefaultParent()

                this.$editor.mxEditor.graph.addCell(this.mxCell, parent)
            },
            mxCellSetValue(value)
            {
                this.entity.name = value
            },
            mxCellGetValue()
            {
                this.$parent.isPortalReady.push(this.entity.id)

                return `<div id="entity_portal_${this.entity.id}"></div>`
            },
        },
    }
</script>
