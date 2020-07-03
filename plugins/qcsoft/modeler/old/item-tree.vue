<template>
    <div>
        <btn type="primary" @click="unshift">unshift</btn>
        <div v-for="(item, index) in items"
             style="position: relative; border: 1px solid black"
        >
            <slot :item="item" :list="items" :index="index"></slot>
            <item-tree :items="item.children"
                       v-slot="{ item: subitem, list: sublist, index: subindex }"
                       style="margin: 0 0 0 30px">
                <slot :item="subitem" :list="sublist" :index="subindex"></slot>
            </item-tree>
            <btn @click="items.splice(index, 1)" style="position: absolute; right: 0; top: 0; padding: 3px 8px">x</btn>
        </div>
        <btn type="primary" @click="push">push</btn>
    </div>
</template>
<script>
    export default {
        name   : 'item-tree',
        props  : ['items'],
        methods: {
            unshift()
            {
                this.items.unshift({
                    children: [],
                })
            },
            push()
            {
                this.items.push({
                    children: [],
                })
            },
        },
    }
</script>
