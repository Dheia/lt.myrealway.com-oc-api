<template>
    <div>
        <div v-for="(child, i) in val.children">
            <mx-cell v-model="val.children[i]"
                     :key="cell.ukey"
                     :is-mx-listener-running="isMxListenerRunning"
            ></mx-cell>
        </div>
    </div>
</template>
<script>
    import mxvueHelper from './mxvue-helper.js'
    import watchers from './mx-cell-watchers.js'

    let ukey = 0

    export default {
        name    : 'mx-cell',
        mixins  : [
            watchers,
            mxvueHelper(self => self.$parent)
        ],
        props   : ['value', 'is-mx-listener-running'],
        data()
        {
            ukey++

            return {
                ukey
            }
        },
        computed: {
            val()
            {
                let s = this.value || {}

                s.id = s.id || null
                // s.parent_id
                s.x = s.x || 0
                s.y = s.y || 0
                s.width = s.width || 100
                s.height = s.height || 100
                s.children = s.children || []
                s.value = s.value || {}
                s.$cell = this

                return s
            },
            cell()
            {
                return this.$options.cell
            },
        },
        created()
        {
            this.$options.cell = new mxCell({
                    ...this.val.value,
                    vueComponent: this,
                },
                new mxGeometry(this.val.x, this.val.y, this.val.width, this.val.height),
                this.val.style
            )
        },
        mounted()
        {
            this.cell.setVertex(true)

            this.graph.stopEditing(false)

            var parent

            switch (this.$parent.$options._componentTag)
            {
                case 'mx-graph':
                    parent = this.graph.getDefaultParent()
                    break
                case 'mx-cell':
                    parent = this.$parent.cell
            }

            this.graph.addCell(this.cell, parent)

            this.cell.geometry.alternateBounds = new mxRectangle(
                0,
                0,
                this.cell.geometry.width,
                this.cell.geometry.height
            )

            this.$nextTick(() =>
            {
                this.$emit('input', {
                    ...this.val,
                    id: this.cell.id
                })
            })
        },
    }
</script>
