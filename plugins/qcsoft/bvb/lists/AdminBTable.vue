<script>
    import {BTable} from 'bootstrap-vue'

    export default {
        props: BTable.options.props,
        name : 'admin-b-table',
        render(h)
        {
            return h('b-table', {
                class      : 'admin-b-table',
                props      : this.$props,
                on         : this.$listeners,
                scopedSlots: this.$scopedSlots,
            })
        },
    }
</script>
<style lang="scss">
    .admin-b-table {
        .column-id {
            width: 50px;
        }

        th {
            border-right: 1px solid #dee2e6;
        }

        td {
            padding-top: 0;
            padding-bottom: 0;
            vertical-align: middle;
        }
    }
</style>
