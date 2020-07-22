<template>
    <div>
        <admin-b-table
                :items="records"
                :fields="fields"
                @sort-changed="onSortChanged"
        >
            <template v-slot:cell(is_in_type)="cell">
                <b-form-checkbox v-model="cell.item.is_in_bundles"
                >In bundles
                </b-form-checkbox>
                <b-form-checkbox v-model="cell.item.is_in_products"
                >In products
                </b-form-checkbox>
            </template>
            <template v-slot:cell(filteroptions)="cell">
                <column-filteroptions :cell="cell"></column-filteroptions>
            </template>
        </admin-b-table>
    </div>
</template>
<script>
    import ColumnFilteroptions from './filter/ColumnFilteroptions.vue'

    export default {
        components: {ColumnFilteroptions},
        data      : vm => ({
            fields : [
                {
                    key     : 'id',
                    label   : 'Id',
                    sortable: true,
                },
                {
                    key     : 'name',
                    label   : 'Name',
                    sortable: true,
                },
                {
                    key     : 'sort_order',
                    label   : 'Order',
                    sortable: true,
                },
                {
                    key     : 'is_in_type',
                    label   : 'For type',
                    sortable: true,
                },
                {
                    key     : 'filteroptions',
                    label   : 'Options',
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
    filter (selectWith: ["filteroptions"]) {
        id
        name
        sort_order
        is_in_bundles
        is_in_products
        filteroptions {
            id
            name
            is_in_bundles
            is_in_products
        }
    }
}
`
            }, response =>
            {
                this.records = JSON.parse(response.responseText).data.filter
            })

//             this.ocRequest('onGetListData', {
//                 query: `
// {
//     filter (selectWith: ["catalogitem"]) {
//         id
//         name
//         sort_order
//         is_in_bundles
//         is_in_products
//     }
// }
// `
//             }, response =>
//             {
//                 this.records = JSON.parse(response.responseText).data.filter
//             })
        },
        methods   : {
            onSortChanged()
            {
                console.log(arguments)
            },
        },
    }
</script>
