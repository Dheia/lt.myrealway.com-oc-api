export default {
    watch: {
        asJson(value, oldValue)
        {
            this.$emit('changed', this, value, oldValue)
        },
        'attribute.name'(value)
        {
            this.$editor.mxEditor.graph.cellLabelChanged(this.mxCell, value)
        },
        'attribute.type'(value)
        {
            this.$editor.mxEditor.graph.cellLabelChanged(this.mxCell, value)
        },
    },
}
