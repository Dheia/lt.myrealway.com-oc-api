<template>
    <div>
        <cell-attribute
                v-for="attribute in entity.attributes"
                :attribute="attribute"
                :key="attribute.ukey"
                :is-mx-listener-running="isMxListenerRunning"
        ></cell-attribute>
    </div>
</template>
<script>
    import CellAttribute from './cell-attribute.vue';

    export default {
        components: {CellAttribute},
        props     : ['entity', 'isMxListenerRunning'],
        created()
        {
            this.mxCell = new mxCell(
                this,
                new mxGeometry(this.entity.x, this.entity.y, this.entity.width, this.entity.height),
                'entity'
            )

            this.mxCell.setVertex(true)

            let parent = this.$parent.mxEditor.graph.getDefaultParent()

            this.$parent.mxEditor.graph.addCell(this.mxCell, parent)
        },
        methods   : {
            $attributes()
            {
                return this.$children.filter(c => c.$options._componentTag === 'cell-attribute')
            },
            mxCellSetValue(value)
            {
                this.entity.name = value
            },
            mxCellGetValue()
            {
                return this.entity.name
            },
        },
        watch     : {
            'entity.name'(value)
            {
                this.$parent.mxEditor.graph.cellLabelChanged(this.mxCell, value)
            },
            'entity.x'(value, oldValue)
            {
                if (!this.isMxListenerRunning)
                {
                    this.$parent.mxEditor.graph.cellsMoved([this.mxCell], value - oldValue, 0)
                }
            },
            'entity.y'(value, oldValue)
            {
                if (!this.isMxListenerRunning)
                {
                    this.$parent.mxEditor.graph.cellsMoved([this.mxCell], 0, value - oldValue)
                }
            },
            'entity.width'(value)
            {
                if (!this.isMxListenerRunning)
                {
                    let geometry = this.mxCell.geometry.clone()

                    geometry.width = value

                    this.$parent.mxEditor.graph.cellsResized([this.mxCell], [geometry], false)
                }
            },
            'entity.isSelected'(value)
            {

            },
        },
    }
</script>
