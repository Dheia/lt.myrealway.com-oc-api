export default {
    watch: {
        asJson(value, oldValue)
        {
            // console.log('asJson', oldValue, value)
            this.$emit('changed', this, value, oldValue)
        },
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
    },
}
