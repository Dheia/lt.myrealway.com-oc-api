<script>
    export default {
        name    : 'graphcontrol-treetable-item',
        props   : {
            value: {
                default: () => [],
            },
            items: {
                default: () => [],
            },
            tag  : {
                default: 'div',
            },
        },
        computed: {
            val()
            {
                return this.value || {}
            }
        },
        render(createElement)
        {
            return createElement(this.tag, this.renderSub(createElement, this.items, 0))
        },
        methods : {
            renderSub(createElement, items, depth)
            {
                let result = [],
                    node

                for (let i = 0; i < items.length; i++)
                {
                    const item = items[i]

                    if (!item.id)
                    {
                        continue
                    }
                    // console.log('this.val',this.val)
                    // let node = this.val.find(existing => existing.id === item.id)
                    node = this.val[item.id]

                    if (!node)
                    {
                        node = {
                            $item     : item,
                            isOpen    : true,
                            isSelected: false,
                        }

                        // this.$emit('input', [...this.value, node])
                        this.$emit('input', {
                            ...this.value,
                            [item.id]: node
                        })
                    }

                    result.push(
                        this.$scopedSlots.default({
                            item : item,
                            node : node,
                            depth: depth,
                            index: i,
                        })
                    )

                    if (node.isOpen)
                    {
                        this.renderSub(createElement, item.children || [], depth + 1)
                            .forEach(child =>
                            {
                                result.push(child)
                            })
                    }
                }

                return result
            }
        }
    }
</script>
