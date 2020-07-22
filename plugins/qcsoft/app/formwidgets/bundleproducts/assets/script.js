import bundleProductList from './bundle-product-list.vue'

export default {
    components: {
        bundleProductList,
    },
    data()
    {
        return {
            items           : [],
            customergroups  : [],
            addProductListId: null,
        }
    },
    mounted()
    {
        this.items = JSON.parse($('#' + $(this.$el).data('widget-id') + '-formValue').text())
        this.customergroups = JSON.parse($('#' + $(this.$el).data('widget-id') + '-customergroups').text())
        this.addProductListId = $(this.$el).data('add-product-list-id')
    },
    computed  : {
        saveValue()
        {
            return JSON.stringify({
                status: 'ok',
                items : this.items.map(bundleProduct =>
                {
                    return {
                        product_id      : bundleProduct.product.id,
                        quantity        : bundleProduct.quantity,
                        price_override  : bundleProduct.price_override,
                        bpCustomergroups: bundleProduct.bpCustomergroups,
                    }
                }),
            }, null, 2)
        },
    },
}
