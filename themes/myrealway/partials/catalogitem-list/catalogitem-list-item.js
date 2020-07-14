export default {
    data()
    {
        return {
            catalogitemId: null,
            quantity : 1,
        }
    },
    mounted()
    {
        this.catalogitemId = $(this.$el).data('id')
    },
    methods: {
        addToCart()
        {
            this.app.cart.addItem('catalogitem', this.catalogitemId, this.quantity)
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
