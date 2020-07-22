<template>
    <div>
        <b-button :id="'bundle_cell_bundle_products_' + cell.item.id"
                  variant="link"
                  class="px-0"
                  v-text="cell.value.length + ' product' + (cell.value.length > 1 ? 's' : '')"
        ></b-button>
        <b-popover
                :target="'bundle_cell_bundle_products_' + cell.item.id"
                placement="left"
                triggers="click blur"
                :show.sync="popoverShow"
        >
            <template v-slot:title>
                <b-button @click="onClose" class="close" aria-label="Close">
                    <span class="d-inline-block" aria-hidden="true">&times;</span>
                </b-button>
                Bundle products
            </template>
            <div v-for="bp in cell.value">
                <img :src="bp.product.catalogitem.main_image.thumb"/>
                <span v-text="bp.product.catalogitem.name"></span>
                x
                <span v-text="bp.quantity"></span>
            </div>
        </b-popover>
    </div>
</template>
<script>
    export default {
        props  : ['cell'],
        data()
        {
            return {
                popoverShow: false
            }
        },
        methods: {
            onClose()
            {
                this.popoverShow = false
            },
        }
    }
</script>
