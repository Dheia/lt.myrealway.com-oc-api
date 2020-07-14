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
        <modal v-model="addItemModalOpen" size="lg">
            <oc-lists :list-id="addProductListId"
                      :columns="addProductListData.columns"
                      :records="recordsExceptProductIds"
                      v-if="isListDataLoaded"
                      @record-click="onSelectProduct"
            ></oc-lists>
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
                addItemModalOpen  : false,
                isListDataLoaded  : false,
                addProductListData: [],
            }
        },
        computed  : {
            local()
            {
                console.log(this.value)
                return this.value ? this.value : []
            },
            recordsExceptProductIds: vm =>
            {
                let exceptProductIds = vm.value.map(v => v.product.id)

                return vm.isListDataLoaded ?
                    vm.addProductListData.records.filter(v => !exceptProductIds.includes(v.id)) :
                    []
            }
        },
        methods   : {
            addItem()
            {
                this.addItemModalOpen = true

                $.request('onProductPickerGetList', {
                    success: (data, textStatus, jqXHR) =>
                    {
                        this.addProductListData = data[this.addProductListId]

                        this.isListDataLoaded = true

                        console.log(this.addProductListData)
                    },
                })
            },
            deleteItem(index)
            {
                this.value.splice(index, 1)
            },
            onSelectProduct(record)
            {
                $.request('onGetProductData', {
                    data   : {
                        productId: record.id,
                    },
                    success: (data, textStatus, jqXHR) =>
                    {
                        this.value.push({
                            product         : data.product,
                            quantity        : 1,
                            price_override  : null,
                            bpCustomergroups: Object.keys(this.customergroups).map(v => ({customergroup_id: v})),
                        })

                        this.addItemModalOpen = false
                    },
                })
            },
        }
    }
</script>
