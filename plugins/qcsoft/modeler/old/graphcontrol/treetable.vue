<template>
    <table>
        <!--<tr v-for="deb in treetableDebug()">
            <td colspan="5" v-text="deb"></td>
        </tr>-->
        <treetable-header :columns="columns"></treetable-header>
        <treetable-render
                tag="tbody"
                :items="graphData.cells"
                v-model="rows"
                #default="{ item, node, depth }"
        >
            <tr>
                <td>
                    <btn type="secondary"
                         @click="node.isOpen = !node.isOpen"
                         style="padding: 4px 8px 3px 7px; margin: 0 0 0 10px"
                         v-if="(item.children || []).length"
                    ><i :class="'oc-icon-' + (node.isOpen ? 'minus' : 'plus')"></i></btn>
                </td>
                <td v-text="item.value.name" :style="{color: depth ? 'red' : 'blue'}"></td>
            </tr>
        </treetable-render>
    </table>
</template>
<script>
    import TreetableRender from './treetable-render.vue'
    import TreetableHeader from './treetable-header.vue'

    export default {
        props     : {
            graphData: {},
            columns  : [],
        },
        components: {
            TreetableHeader,
            TreetableRender,
        },
        data()
        {
            return {
                rows: {},
            }
        },
        methods   : {
            // rowsDebug()
            // {
            //     return Object.entries(this.rows)
            //         .map(item =>
            //         {
            //             return Object.keys(item)
            //                 .map(key => `${key}=${
            //                     Object.keys(item[key])
            //                         .map(value => value)
            //                         .join(',')
            //                 }`)
            //         })
            // },
        },
    }
</script>
