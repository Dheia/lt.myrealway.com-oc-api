<template>
    <div>
        <div class="pl-2 pb-2 pt-1">
            <b-button href="/backend/qcsoft/app/bundles/create">
                <b-icon icon="plus"/>
                Create
            </b-button>
        </div>
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
                <img :src="value" style="min-height: 50px"/>
            </template>
            <template v-slot:cell(catalogitem.name)="{ item, value }">
                <a :href="'/backend/qcsoft/app/bundles/update/' + item.id"
                   v-text="value"
                   target="_self"></a>
            </template>
            <template v-slot:cell(page.path)="{ value }">
                <a :href="'/' + value" v-text="value" target="_blank"></a>
            </template>
            <template v-slot:cell(bundle_products)="cell">
                <column-products :cell="cell"></column-products>
            </template>
        </admin-b-table>
    </div>
</template>
<script>
    import ColumnProducts from './bundle/ColumnProducts.vue'

    export default {
        components: {ColumnProducts},
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
                {
                    key     : 'bundle_products',
                    label   : 'Products',
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
    bundle (
        selectWith: [
            "catalogitem", "catalogitem.main_image", "bundle_products", "bundle_products.product",
            "bundle_products.product.catalogitem", "bundle_products.product.catalogitem.main_image",
            "page"
        ],
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
        bundle_products {
            quantity
            product {
                catalogitem {
                    name
                    main_image {
                        thumb (w: 35, h: 35, mode: "crop")
                    }
                }
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
                this.records = JSON.parse(response.responseText).data.bundle
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
