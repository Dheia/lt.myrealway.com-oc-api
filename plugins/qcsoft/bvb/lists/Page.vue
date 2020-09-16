<template>
    <div>
        <b-table
                :items="records"
                :fields="fields"
                @sort-changed="onSortChanged"
                no-local-sorting
        >
            <template v-slot:cell(owner.catalogitem.main_image.thumb)="item">
                <div v-if="item.item.owner.catalogitem"
                     v-text="item.item.owner.catalogitem.main_image.file_name"></div>
                <img :src="item.unformatted"/>
            </template>
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
                    key     : 'path',
                    label   : 'path',
                    sortable: true,
                },
                {
                    key     : 'owner.__typename',
                    label   : 'owner.__typename',
                    sortable: true,
                },
                {
                    key     : 'owner.id',
                    label   : 'owner.id',
                    sortable: true,
                },
                {
                    key     : 'owner.catalogitem.main_image.thumb',
                    label   : 'main_image.thumb',
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
    page_count (selectWhereIn: ["owner_type", "custompage", "bundle"])
    page (selectWhereIn: ["owner_type", "custompage", "bundle"]) {
        id
        path
        owner {
            __typename
            ... on Bundle {
                id
                catalogitem {
                    id
                    name
                    main_image {
                        file_name
                        thumb(w: 100, h: 100, mode: "crop")
                    }
                }
            }
            ... on Product {
                id
                catalogitem {
                    id
                    name
                }
            }
            ... on Custompage {
                id
                name
                content
            }
        }
    }
}
`
            }, response =>
            {
                this.records = JSON.parse(response.responseText).data.page
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
