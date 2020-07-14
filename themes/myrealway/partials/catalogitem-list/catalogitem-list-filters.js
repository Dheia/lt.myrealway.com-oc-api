export default {
    data   : {
        filterValues  : {},
        allowedFilters: null,
    },
    mounted()
    {
        this.allowedFilters = $(this.$el).data('filters')

        this.filterValuesFromQuery()
    },
    methods: {
        resetFilters()
        {
            this.filterValues = {}

            this.queryFromFilterValues()

            this.updateCatalogitemList()
        },
        onFilterChange(filterSlug, optionSlug)
        {
            if (optionSlug == 0)
            {
                delete this.filterValues[filterSlug]
            }
            else
            {
                this.filterValues[filterSlug] = optionSlug
            }

            this.queryFromFilterValues()

            this.updateCatalogitemList()
        },
        filterValuesFromQuery()
        {
            let result = {}

            location.search.replace(/^\?/, '').split('&').forEach(segment =>
            {
                let [key, value] = segment.split('=')

                if ((this.allowedFilters[key] || []).includes(value))
                {
                    result[key] = value
                }
            })

            this.filterValues = result
        },
        queryFromFilterValues()
        {
            let query = Object.keys(this.filterValues)
                .map(key => `${key}=${this.filterValues[key]}`)
                .join('&')

            if (query.length)
            {
                query = '?' + query
            }

            history.pushState(null, null, `${location.origin}${location.pathname}${query}`)
        },
        updateCatalogitemList()
        {
            $('[data-app="catalogitemList"]')[0].__vue__.reload()
        },
    }
}
