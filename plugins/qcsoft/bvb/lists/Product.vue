<template>
    <div>
        <admin-b-table
                :items="records"
                :fields="fields"
                @sort-changed="onSortChanged"
                v-columns-resizable
                striped
        >
            <template v-slot:cell(id)="{ value }">
                <div class="column-id" v-text="value"></div>
            </template>
            <template v-slot:cell(catalogitem.main_image.thumb)="{ value }">
                <img :src="value"/>
            </template>
            <template v-slot:cell(page.path)="{ value }">
                <a :href="'/' + value" v-text="value" target="_blank"></a>
            </template>
            <template v-slot:cell(bundle_products)="cell">
                <products-column :cell="cell"></products-column>
            </template>
        </admin-b-table>
    </div>
</template>
<script>
    import ProductsColumn from './bundle/ColumnProducts.vue'

    export default {
        components: {ProductsColumn},
        data      : vm => ({
            fields : [
                {
                    key     : 'id',
                    label   : 'Id',
                    sortable: true,
                },
                {
                    key     : 'catalogitem.main_image.thumb',
                    label   : 'Image',
                    sortable: true,
                },
                {
                    key     : 'catalogitem.name',
                    label   : 'Name',
                    sortable: true,
                },
                {
                    key     : 'page.path',
                    label   : 'Path',
                    sortable: true,
                },
            ],
            records: [],
        }),
        mounted()
        {
            this.ocRequest('onGetListData', {
                query: `
{
    product (
        selectWith: ["catalogitem", "catalogitem.main_image", "page"],
        selectScopes: [["withComposites"]]
    ) {
        id
        catalogitem {
            id
            name
            main_image {
                thumb (w: 50, h: 50, mode: "crop")
            }
        }
        page {
            id
            path
        }
    }
}
`
            }, response =>
            {
                this.records = JSON.parse(response.responseText).data.product
            })
        },
        methods   : {
            onSortChanged()
            {
                // console.log('qwer', arguments)
            },
        },
    }
</script>
