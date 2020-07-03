<template>
    <div>
        <div class="breadcrumbs">
            <btn type="link" @click="$parent.graphData.selectedIds = []">Root</btn>
            <span>&raquo;</span>
            <template v-for="(item, i) in breadcrumbs">
                <btn type="link"
                     v-text="`${item.value.name} (${item.id})`"
                     @click="$parent.graphData.selectedIds = [item.id]"
                ></btn>
                <span v-if="i < breadcrumbs.length - 1">&raquo;</span>
            </template>
        </div>
        <table class="attrs-table">
            <tr v-for="attr in allAttrs">
                <td v-text="attr.name"></td>
                <input v-model="item[attr.name]"
                       class="form-control"
                       :disabled="attr.type === 'label'"
                />
            </tr>
            <tr v-for="valueKey in Object.keys(item.value)">
                <td v-text="valueKey"></td>
                <input v-model="item.value[valueKey]"
                       class="form-control"
                />
            </tr>
        </table>
        <table class="children-table" v-if="item.children.length">
            <tr>
                <th style="padding: 0">
                    <btn type="secondary"
                         @click="childOpenToggleAll"
                         style="padding: 1px 5px 0 5px; margin: 0 3px 0 3px"
                    ><i class="oc-icon-plus"></i></btn>
                </th>
                <th>value.name</th>
                <th v-for="attr in allAttrs" v-text="attr.name"></th>
            </tr>
            <template v-for="(child, i) in item.children">
                <tr :style="{background: i % 2 ? '#fff' : '#e9e9e9'}">
                    <td>
                        <btn type="secondary"
                             @click="childSetOpen(child.id, !childIsOpen(child.id))"
                        ><i class="oc-icon-plus"></i></btn>
                    </td>
                    <td>
                        <input v-model="child.value.name"/>
                    </td>
                    <td style="padding: 0 10px">
                        <btn type="link"
                             v-text="child.id"
                             @click="$parent.graphData.selectedIds = [child.id]"
                        ></btn>
                    </td>
                    <template v-for="attr in allAttrs">
                        <td v-if="attr.type !== 'label'">
                            <input v-model="child[attr.name]" :style="attr.width ? {width: attr.width} : {}"/>
                        </td>
                    </template>
                </tr>
                <tr :style="{background: i % 2 ? '#fff' : '#e9e9e9'}" v-if="childIsOpen(child.id)">
                    <td :colspan="allAttrs.length + 1">
                        <div style="padding: 7px 0 12px 12px"
                             v-text="JSON.stringify(child.value)"
                        ></div>
                    </td>
                </tr>
            </template>
        </table>
    </div>
</template>
<script>
    import Attrs from './util-attrs.js'
    import GraphControlTree from './graph-control-tree.vue';

    export default {
        props     : {
            value: {},
            item : {},
        },
        computed  : {
            breadcrumbs()
            {
                let result = [],
                    current = this.item.$cell

                while (current.$options._componentTag === 'mx-cell')
                {
                    result.unshift(current)

                    current = current.$parent
                }

                return result.map(item => item.val)
            },
            val()
            {
                let result = this.value ? {...this.value} : {}

                result.openChildIds = result.openChildIds || []

                return result
            },
        },
        data()
        {
            return {
                allAttrs: Attrs(),
            }
        },
        methods   : {
            childOpenToggleAll()
            {
                this.value.openChildIds = this.value.openChildIds.length ? [] :
                    this.item.children.map(child => child.id)
            },
            childIsOpen(id, value)
            {
                return this.value.openChildIds.indexOf(id) !== -1
            },
            childSetOpen(id, value)
            {
                let openIndex = this.value.openChildIds.indexOf(id)

                if (value)
                {
                    if (openIndex === -1)
                    {
                        this.value.openChildIds.push(id)
                    }
                }
                else
                {
                    if (openIndex !== -1)
                    {
                        this.value.openChildIds.splice(openIndex, 1)
                    }
                }
            },
        },
        components: {
            GraphControlTree,
        },
    }
</script>
<style lang="scss" scoped>
    .breadcrumbs {
        display: flex;
        background: #eee;
        padding: 5px 10px;
        margin: 0 0 10px 0;

        .btn {
            padding: 0;
        }

        span {
            margin: 0 10px;
        }
    }

    .attrs-table {
        margin: 0 0 10px 0;

        td {
            padding: 0 10px 0 0;
        }

        input {
            padding: 0 10px;
            margin: 0 0 1px 0;
            height: 26px;
        }
    }

    .children-table {
        border-top: 1px solid #ddd;
        border-left: 1px solid #ddd;

        td, th {
            border-right: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
        }

        th {
            padding: 5px 10px 5px 10px;
        }

        .btn-secondary {
            padding: 1px 5px 0 5px;
            margin: 0 4px 0 4px;
        }

        .btn-link {
            padding: 0;
        }

        input {
            padding: 0 10px;
            margin: 0;
            height: 26px;
            width: 100%;
            border: 0;
            background: transparent;

            &:focus {
                outline: 0;
                box-shadow: none;
            }
        }
    }
</style>
