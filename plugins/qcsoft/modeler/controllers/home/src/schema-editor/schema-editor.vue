<template>
    <div>
        <div class="editor">
            <div ref="container" class="graph-container"></div>
            <cell-entity v-for="(entity, i) in editor.entities"
                         :entity="entity"
                         :key="'entity' + entity.id"
                         :is-mx-listener-running="isMxListenerRunning"
                         @added="onAdded('entity', ...arguments)"
                         @changed="onChanged('entity', ...arguments)"
                         @removed="onRemoved('entity', ...arguments)"
            >
                <cell-attribute v-for="attribute in editor.attributes.filter(v => v.entity_id === entity.id)"
                                :attribute="attribute"
                                :key="'attribute' + attribute.id"
                                :is-mx-listener-running="isMxListenerRunning"
                                @added="onAdded('attribute', ...arguments)"
                                @changed="onChanged('attribute', ...arguments)"
                                @removed="onRemoved('attribute', ...arguments)"
                ></cell-attribute>
            </cell-entity>
            <cell-relation v-for="relation in editor.relations"
                           :relation="relation"
                           :key="'relation' + relation.id"
                           :is-mx-listener-running="isMxListenerRunning"
                           @added="onAdded('relation', ...arguments)"
                           @changed="onChanged('relation', ...arguments)"
                           @removed="onRemoved('relation', ...arguments)"
            ></cell-relation>

            <mounting-portal v-for="entity in editor.entities"
                             :key="'portal' + entity.id"
                             v-if="isPortalReady.includes(entity.id)"
                             :mountTo="`#entity_portal_${entity.id}`"
            >
                <div :style="`position: relative; margin-left: -1px; height: 28px; line-height: 28px; width: ${entity.width}px`">
                    <slot name="entity" :entity="entity"></slot>
                </div>
            </mounting-portal>
        </div>
    </div>
</template>
<script>
    import editClasses from './edit-classes.js'
    import CreateMxEditor from './create-mx-editor.js'
    import CellEntity from './cell-entity.vue'
    import CellAttribute from './cell-attribute.vue'
    import CellRelation from './cell-relation.vue'

    export default {
        components: {
            CellEntity,
            CellAttribute,
            CellRelation,
        },
        props     : ['loadValue', 'editor'],
        mixins    : [CreateMxEditor],
        data      : vm => ({
            isPortalReady      : [],
            isMxListenerRunning: false,
        }),
        computed  : {
            $entities: vm => vm.$children.filter(v => v.$options._componentTag === 'cell-entity'),
        },
        created()
        {
            this.mxEditor = this.createMxEditor()

            editClasses.forEach(v =>
            {
                this.$set(this.editor, v.pl, [...this.loadValue[v.pl]])
            })

            this.$set(this.editor, 'selection', {
                entities  : [],
                attributes: [],
                relations : [],
            })

            this.$set(this.editor, 'changes', {
                entity   : [],
                attribute: [],
                relation : [],
            })
        },
        mounted()
        {
            this.mxEditor.setGraphContainer(this.$refs.container)

            this.mxEditor.graph.refresh()
        },
        methods   : {
            onAdded(type, vm)
            {
                this.$emit('added', type, vm)
            },
            onChanged(type, vm, value, oldValue)
            {
                let valueObj = JSON.parse(value),
                    oldValueObj = JSON.parse(oldValue)

                let changes = []

                // console.log('onChanged', type, vm, oldValue, value, changes)

                Object.keys(valueObj).forEach(k =>
                {
                    if (oldValueObj[k] !== undefined && oldValueObj[k] !== valueObj[k])
                    {
                        changes.push([k, oldValueObj[k], valueObj[k]])
                    }
                })

                if (changes.length)
                {
                    // console.log('changed', type, vm, oldValue, value, changes)
                    this.$emit('changed', type, vm, oldValue, value, changes)
                }
            },
            onRemoved(type, vm)
            {
                this.$emit('removed', type, vm)
            },
        },
    }
</script>
<style scoped lang="scss">
    .editor {
        /*position: absolute;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;*/

    }

    .graph-container {
        /*position: absolute;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;*/

    }
</style>
