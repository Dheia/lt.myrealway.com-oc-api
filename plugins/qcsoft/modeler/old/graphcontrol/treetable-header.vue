<template>
    <table>
        <tr>
            <th style="padding: 0">
                <btn type="secondary"
                     @click="toggleRoot"
                     style="padding: 1px 5px 0 5px; margin: 0 3px 0 3px"
                ><i class="oc-icon-plus"></i></btn>
            </th>
            <th>value.name</th>
            <th v-for="column in columns" v-text="column.name"></th>
        </tr>
    </table>
</template>
<script>
    export default {
        props  : ['columns'],
        methods: {
            toggleRoot()
            {
                // this.value.openChildIds = this.value.openChildIds.length ? [] :
                //     this.item.children.map(child => child.id)
            },
        }
    }
</script>
