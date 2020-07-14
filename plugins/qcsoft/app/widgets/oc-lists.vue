<template>
    <div>
        <div class="control-list">
            <table class="table data oc-list">
                <thead>
                <tr>
                    <th v-for="column in columns" class="<!--sort-desc active-->">
                        <span v-text="column.label"></span>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="record in records" @click="$emit('record-click', record)">
                    <td v-for="column in columns">
                        <div v-if="column.config.widget"
                             :is="column.config.widget"
                             :column="column"
                             :record="record"
                             :value="record.columnValues[column.columnName]"
                        ></div>
                        <div v-else
                             v-text="record.columnValues[column.columnName]"
                        ></div>
                    </td>
                </tr>
                </tbody>
            </table>
            <pre v-text="JSON.stringify(this.columns, null, 4)"></pre>
            <!--<div>
                <pre v-text="JSON.stringify(Object.keys(this.columns).map(key=>this.columns[key].columnName), null, 4)"></pre>
                <pre v-text="JSON.stringify($props.records, null, 4)"></pre>
            </div>-->
        </div>
    </div>
</template>
<script>
    export default {
        name : 'oc-lists',
        props: [
            'columnTotal',
            'columns',
            'cssClasses',
            'noRecordsMessage',
            'pageCurrent',
            'recordTotal',
            'records',
            'showCheckboxes',
            'showPageNumbers',
            'showPagination',
            'showSetup',
            'showSorting',
            'showTree',
            'sortColumn',
            'sortDirection',
            'treeLevel',
        ],
    }
</script>
<style lang="scss">
    .oc-list {
        tr {
            cursor: pointer;
        }

        tr:hover {
            td {
                background: #4ea5e0 !important;
            }
        }
    }
</style>
