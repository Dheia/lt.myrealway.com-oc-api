export default {
    watch: {
        'val.style'(value, oldValue)
        {
            // if (!this.isMxListenerRunning)
            // {
            this.model.setStyle(this.cell, value)
            // }
        },
        'val.x'(value, oldValue)
        {
            if (!this.isMxListenerRunning)
            {
                this.graph.cellsMoved([this.cell], value - oldValue, 0)
            }
        },
        'val.y'(value, oldValue)
        {
            if (!this.isMxListenerRunning)
            {
                this.graph.cellsMoved([this.cell], 0, value - oldValue)
            }
        },
        'val.width'(value)
        {
            if (!this.isMxListenerRunning)
            {
                let geometry = this.cell.geometry.clone()

                geometry.width = value

                this.graph.cellsResized([this.cell], [geometry], false)
            }
        },
        'val.height'(value)
        {
            if (!this.isMxListenerRunning)
            {
                let geometry = this.cell.geometry.clone()

                geometry.height = value

                this.graph.cellsResized([this.cell], [geometry], false)
            }
        },
        'val.value': {
            deep: true,
            handler(value)
            {
                if (!this.isMxListenerRunning)
                {
                    this.graph.cellLabelChanged(this.cell, {...this.cell.value, ...this.val.value})
                }
            },
        },
    }
}
