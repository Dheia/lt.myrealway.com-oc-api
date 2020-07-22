<template>
    <div>
        <div>

        </div>
        <admin-b-table
                :items="records"
                :fields="fields"
                @sort-changed="onSortChanged"
                no-local-sorting
                v-columns-resizable
        >
            <template v-slot:cell(main_image.thumb)="data">
                <img :src="data.unformatted"/>
            </template>
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
                    key     : 'main_image.thumb',
                    label   : 'main_image.thumb',
                    sortable: true,
                },
                {
                    key     : 'name',
                    label   : 'name',
                    sortable: true,
                },
                {
                    key     : 'item.__typename',
                    label   : 'item.__typename',
                    sortable: true,
                },
                {
                    key     : 'item.id',
                    label   : 'item.id',
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
    catalogitem (selectOrderBy: ["name", "desc"]) {
        id
        name
        main_image {
            file_name
            thumb(w: 50, h: 50, mode: "crop")
        }
        item {
            __typename
            ... on Bundle {
                id
            }
            ... on Product {
                id
            }
        }
    }
}
`
            }, response =>
            {
                this.records = JSON.parse(response.responseText).data.catalogitem
            })

//             this.ocRequest('onGetListData', {
//                 query: `
// {
//     catalogitem (selectOrderBy: ["name", "desc"], selectWith: ["item"]]) {
//         id
//         name
//     }
// }
// `
//             }, response =>
//             {
//                 this.records = JSON.parse(response.responseText).data.bundle
//             })
        },
        methods: {
            onSortChanged()
            {
                console.log(arguments)
            },
        },
    }
</script>
