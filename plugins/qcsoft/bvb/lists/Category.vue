<template>
    <div>
        <b-table
                :items="records"
                :fields="fields"
                @sort-changed="onSortChanged"
                no-local-sorting
        >
        </b-table>
    </div>
</template>
<script>
    export default {
        data   : vm => ({
            fields : [
                {
                    key     : 'id',
                    label   : 'id',
                    sortable: true,
                },
                {
                    key     : 'name',
                    label   : 'name',
                    sortable: true,
                },
            ],
            records: []
        }),
        mounted()
        {
            this.ocRequest('onGetListData', {
                query: `
{
    category_count
    category {
        id
        name
    }
}
`
            }, response =>
            {
                this.records = JSON.parse(response.responseText).data.category
            })
        },
        methods: {
            onSortChanged()
            {
                console.log(arguments)
            },
        },
    }
</script>
