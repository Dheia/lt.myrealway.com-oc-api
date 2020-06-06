export default {
    data()
    {
        return {
            productId: null,
            quantity : 1,
        }
    },
    mounted()
    {
        this.productId = $(this.$el).data('id')
    },
    methods: {
        addToCart()
        {
            this.app.cart.addItem('product', this.productId, this.quantity)
        },
        quantityDec()
        {
            this.quantity = this.quantity <= 1 ? 1 : this.quantity - 1
        },
        quantityInc()
        {
            this.quantity++
        },
    },
}
