<template>
    <div>
        <div v-for="(item, i) in graphData.cells">
            <graph-tree-item :item="item" :index="i" v-slot="{ item, treenode, depth, index }">
                <div style="height: 30px; padding: 2px 0 0 0; margin: 1px 0 0 0; background: #f9f9f9">
                    <div style="display: inline-block; width: 40px">
                        <btn type="secondary"
                             @click="treenode.isOpen = !treenode.isOpen"
                             style="padding: 4px 8px 3px 7px; margin: 0 0 0 10px"
                             v-if="depth === 0"
                        ><i :class="'oc-icon-' + (treenode.isOpen ? 'minus' : 'plus')"></i></btn>
                    </div>
                    <btn type="link"
                         style="padding: 0"
                         v-text="`${index}. ${item.style}(${item.id})`"
                         @click="$parent.graphData.selectedIds = [item.id]"
                    ></btn>
                    <input v-model="item.value.name" style="width: 150px; background: transparent; border: 0"/>
                    <div v-if="item.x && item.y" style="display: inline-block; padding: 1px 0 0 15px">
                        x: <input v-model="item.x" style="width: 45px; border: 1px solid #eee"/>
                        y: <input v-model="item.y" style="width: 45px; border: 1px solid #eee"/>
                    </div>
                </div>
            </graph-tree-item>
        </div>
    </div>
</template>
<script>
    import GraphTreeItem from './graph-tree-item.vue'

    export default {
        props     : ['graphData'],
        data()
        {
            return {
                openChildIds: [],
            }
        },
        components: {
            GraphTreeItem,
        },
    }
</script>
<style lang="scss" scoped>
</style>
