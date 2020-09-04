export default {
    data   : {
        total_count: null,
        items      : [],
    },
    mounted()
    {
        this.load()
    },
    methods: {
        addItem(catalogitemId, quantity)
        {
            $.request('cart::onAddItem', {
                data   : {
                    catalogitemId,
                    quantity,
                },
                success: (data, textStatus, jqXHR) =>
                {
                    this.total_count = data.total_count
                    this.items = data.cartitems
                },
            })
        },
        load()
        {
            $.request('cart::onGetCurrent', {
                success: (data, textStatus, jqXHR) =>
                {
                    this.total_count = data.total_count
                    this.items = data.cartitems
                },
            })
        },
    },
}
