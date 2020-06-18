<template>
    <div>
        <div class="clearfix">
            <button type="button" @click="addItem" class="btn btn-default oc-icon-plus">Add product</button>
        </div>
        <draggable :list="value" handle=".drag-handle" style="display: flex">
            <div v-for="(item, i) in value" :key="item.product.id">
                <list-item v-model="value[i]"
                           :customergroups="customergroups"
                           @delete="deleteItem(i)"
                ></list-item>
            </div>
        </draggable>
        <modal v-model="addItemModalOpen">
            <div :id="addProductListId"></div>
            <div slot="footer">
                <btn @click="addItemModalOpen = false">Cancel</btn>
            </div>
        </modal>
    </div>
</template>
<script>
    import listItem from './list-item.vue'

    export default {
        components: {listItem},
        props     : ['value', 'customergroups', 'addProductListId'],
        data()
        {
            return {
                addItemModalOpen: false,
            }
        },
        computed  : {
            local()
            {
                return this.value ? this.value : []
            }
        },
        methods   : {
            addItem()
            {
                this.addItemModalOpen = true

                $.request('onGetAvailableItems', {
                    data: {
                        existingItems: this.value.map(item => item.product.id),
                    },
                })
            },
            deleteItem(index)
            {
                this.value.splice(index, 1)
            },
            onSelectProduct(productId)
            {
                $.request('onGetProductData', {
                    data   : {
                        productId: productId,
                    },
                    success: (data, textStatus, jqXHR) =>
                    {
                        this.value.push({
                            product         : data.product,
                            quantity        : 1,
                            price_override  : null,
                            bpCustomergroups: null,
                        })

                        this.addItemModalOpen = false
                    },
                })
            },
        }
    }
</script>
