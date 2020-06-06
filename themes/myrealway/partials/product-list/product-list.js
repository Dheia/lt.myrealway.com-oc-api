export default {
    data      : {
        isLoading     : false,
        numLoadedPages: 1,
        filteredCount : null,
        perPage       : null,
    },
    mounted()
    {
        this.filteredCount = $(this.$el).data('filtered-count')
        this.perPage = $(this.$el).data('per-page')
    },
    methods   : {
        reload()
        {
            this.numLoadedPages = 0

            $('#product_list_items').html('')

            this.loadNextPage()
        },
        loadNextPage()
        {
            this.isLoading = true

            $.request('productList::onLoadProductListPage', {
                data   : {
                    page: this.numLoadedPages + 1,
                },
                success: (data, textStatus, jqXHR) =>
                {
                    $('#product_list_items').append(data['itemsHtml'])

                    this.filteredCount = data['filteredCount']

                    this.isLoading = false

                    this.numLoadedPages++
                },
            })
        },
    },
}
