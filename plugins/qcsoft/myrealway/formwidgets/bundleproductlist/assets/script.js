vbus.appDefinition.bundleProductList = {
    data()
    {
        return {
            addItemModalOpen: false,
            availableItems  : [],
            items           : [],
        }
    },
    mounted()
    {
        this.items = JSON.parse($('#' + $(this.$el).data('widget-id') + '-formValue').text())
    },
    methods: {
        addItem()
        {
            this.addItemModalOpen = true

            $.request('onGetAvailableItems', {
                data: {
                    existingItems: this.items.map(item => item.product.id),
                },
            })
        },
        removeItem(index)
        {
            this.items.splice(index, 1)
        },
        onSelectProduct(productId)
        {
            $.request('onGetProductData', {
                data   : {
                    productId: productId,
                },
                success: (data, textStatus, jqXHR) =>
                {
                    this.items.push({
                        product       : data.product,
                        quantity      : 1,
                        price_override: null,
                    })

                    this.addItemModalOpen = false
                },
            })
        },
        quantityInc(item)
        {
            item.quantity++
        },
        quantityDec(item)
        {
            item.quantity > 1 && item.quantity--
        },
    },
}
