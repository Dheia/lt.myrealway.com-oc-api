vbus.appDefinition.columnInput = {
    data()
    {
        return {
            loading  : false,
            recordKey: null,
            value    : null,
            newValue : null,
            attribute: null,
        }
    },
    mounted()
    {
        this.recordKey = $(this.$el).data('record-key')

        this.value = this.newValue = $(this.$el).data('value')

        this.attribute = $(this.$el).data('attribute')
    },
    methods: {
        onSave()
        {
            if (this.value == this.newValue)
            {
                return
            }

            this.loading = true

            $.request('onColumnInputSave', {
                data   : {
                    recordKey: this.recordKey,
                    newValue : this.newValue,
                    attribute: this.attribute,
                },
                success: () =>
                {
                    this.value = this.newValue

                    this.loading = false
                },
            })
        },
        onCancel()
        {
            this.newValue = this.value
        },
    }

}
