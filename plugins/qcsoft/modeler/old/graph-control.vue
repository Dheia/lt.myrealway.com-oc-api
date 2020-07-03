<template>
    <div style="padding: 12px 15px">
        <div v-if="!selectedIds.length">
            None selected
<!--            <graph-control-tree :graphData="graphData"></graph-control-tree>-->
<!--            <graphcontrol-treetable :graphData="graphData"></graphcontrol-treetable>-->
        </div>
        <div v-else-if="selectedIds.length === 1">
            <!--<graph-control-item :item="selectedItem"
                                ref="selectedItemControl"
                                v-model="selectedItemControlValue"
            ></graph-control-item>-->
        </div>
        <div v-else>
            <div v-text="'More than one selected: ' + graphData.selectedIds.join(', ')"></div>
        </div>
    </div>
</template>
<script>
    import mxvueHelper from './mxvue/mxvue-helper.js'
    import GraphcontrolTreetable from './graphcontrol/treetable.vue';
    // import GraphControlTree from './graph-control-tree.vue';
    // import GraphControlItem from './graph-control-item.vue';

    export default {
        props     : ['graphData'],
        data()
        {
            return {
                selectedItemControlValue: {
                    openChildIds: [],
                },
            }
        },
        components: {
            GraphcontrolTreetable,
            // GraphControlItem,
            // GraphControlTree,
        },
        mixins    : [
            mxvueHelper(self => self.$parent)
        ],
        mounted()
        {
            this.$nextTick(() =>
                setTimeout(() =>
                {
                    // this.graphData.selectedIds = [10]
                    this.$nextTick(() =>
                    {
                        // this.$refs.selectedItemControl.childOpenToggleAll()
                    })
                }, 100)
            )
        },
        computed  : {
            selectedIds()
            {
                return (this.graphData.selectedIds || [])
            },
            selectedItem()
            {
                return this.selectedIds.length ?
                    this.model.getCell(this.selectedIds[0]).value.vueComponent.val : null
            },
            selectedItems()
            {
                return this.selectedIds.map(id => this.model.getCell(id).value.vueComponent.val)
            },
        },
    }
</script>
