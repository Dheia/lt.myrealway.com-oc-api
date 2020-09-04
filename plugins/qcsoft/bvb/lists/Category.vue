<template>
    <div>
        <div class="pl-2 pb-2 pt-1">
            <b-button href="/backend/qcsoft/app/categories/create">
                <b-icon icon="plus"/>
                Create
            </b-button>
        </div>
        <admin-b-table
                :items="records"
                :fields="fields"
                @sort-changed="onSortChanged"
                striped
        >
        </admin-b-table>
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
<style lang="scss">
    .admin-b-table {
        td {
            padding-top: 7px;
            padding-bottom: 7px;
        }
    }
</style>
