<template>
    <div>
    </div>
</template>
<script>
    export default {
        props  : ['attribute'],
        created()
        {
            this.mxCell = new mxCell(
                this,
                // new mxGeometry(this.entity.x, this.entity.y, this.entity.width, this.entity.height),
                new mxGeometry(0, 0, 100, 40),
                'attribute'
            )

            this.mxCell.setVertex(true)

            let parent = this.$parent.mxCell

            this.$parent.$parent.mxEditor.graph.addCell(this.mxCell, parent)

        },
        methods: {
            mxCellSetValue(value)
            {
                this.attribute.name = value
            },
            mxCellGetValue()
            {
                return this.attribute.name
            },
        },
        mounted()
        {
        },
    }
</script>
