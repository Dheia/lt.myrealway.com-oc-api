<script>
    export default {
        name : 'graph-tree-item',
        props: {
            item    : {},
            treenode: {
                default()
                {
                    return {
                        isOpen    : true,
                        isSelected: false,
                        isEditing : false,
                    }
                }
            },
            depth   : {
                default: 0,
            },
            index   : {
                default: -1,
            },
        },
        render(createElement)
        {
            let result = []

            result.push(
                this.$scopedSlots.default({
                    item    : this.item,
                    treenode: this.treenode,
                    depth   : this.depth,
                    index   : this.index,
                })
            )

            if (this.treenode.isOpen)
            {
                for (let i = 0; i < this.item.children.length; i++)
                {
                    let child = this.item.children[i]

                    if (child.id)
                    {
                        result.push(
                            createElement('graph-tree-item', {
                                props      : {
                                    item : child,
                                    depth: this.depth + 1,
                                    index: i,
                                },
                                scopedSlots: {
                                    default: this.$scopedSlots.default,
                                },
                            })
                        )
                    }
                }
            }

            return createElement('div', result)
        },
    }
</script>
