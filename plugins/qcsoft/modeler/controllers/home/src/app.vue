<template>
    <div>
        <split class="splitpane"
               @onDragEnd="onSplitpaneResized"
               :gutter-size="20"
        >
            <split-area :size="storage.splitpanes[0]" style="overflow: hidden; position: relative">
                <div class="editor">
                    <schema-editor :load-value="loadValue"
                                   :editor="editor"
                                   :start-zoom="startZoom"
                                   :start-x="startX"
                                   :start-y="startY"
                                   @added="onSchemaObjectAdded"
                                   @changed="onSchemaObjectChanged"
                                   @removed="onSchemaObjectRemoved"
                    >
                        <template #entity="{ entity }">
                            <div class="graph-entity-title">
                                <div class="entity-title-inner">
                                    <div v-text="`${entity.name} (${entity.id})`"
                                         class="title-text"
                                    ></div>
                                    <div class="oc-icon-sitemap"
                                         v-if="entity.options.some(v => v.type === 'nested_tree')"
                                    ></div>
                                </div>
                            </div>
                        </template>
                    </schema-editor>
                </div>
                <portal-target name="main-area"></portal-target>
            </split-area>
            <split-area :size="storage.splitpanes[1]" style="overflow: hidden; position: relative">
                <div class="sidebar">
                    <app-sidebar v-bind="$data"></app-sidebar>
                </div>
            </split-area>
        </split>

        <root-module v-bind="$data"></root-module>
        <selection-module v-bind="$data"></selection-module>
        <migrations-module v-bind="$data"></migrations-module>
        <orm-module v-bind="$data"></orm-module>
        <ctrl-module v-bind="$data"></ctrl-module>

    </div>
</template>
<script>
    import SchemaEditor from './schema-editor/schema-editor.vue'
    import AppSidebar from './sidebar/sidebar.vue'
    import RootModule from './modules/root.vue'
    import SelectionModule from './modules/selection.vue'
    import MigrationsModule from './modules/migrations.vue'
    import OrmModule from './modules/orm.vue'
    import CtrlModule from './modules/ctrl.vue'

    export default {
        props     : ['startZoom', 'startX', 'startY'],
        components: {
            SchemaEditor,
            AppSidebar,
            RootModule,
            SelectionModule,
            MigrationsModule,
            OrmModule,
            CtrlModule,
        },
        data()
        {
            let storage = JSON.parse(localStorage.getItem('modeler')) || {}

            storage.splitpanes = storage.splitpanes || [70, 30]

            return {
                loadValue: {...this.$root.schema},
                editor   : {},
                storage  : storage,
            }
        },
        mounted()
        {
            ////////////////////////////////////////////////////////////////////////////////
            // Ctrl + S
            ////////////////////////////////////////////////////////////////////////////////
            document.addEventListener('keydown', e =>
            {
                if (e.key === 's' && e.ctrlKey)
                {
                    e.preventDefault()

                    let sendData = {...this.editor}

                    delete sendData.changes

                    this.isLoading = true

                    $.request('onSave', {
                        data    : {
                            data: sendData,
                        },
                        success : (data, textStatus, jqXHR) =>
                        {
                            this.$notify('Saved')
                        },
                        complete: () =>
                        {
                            this.isLoading = false
                        }
                    })

                    // let saveData = this.loadValue.entities.map(item => ({
                    //     id: item.id,
                    //     x : item.x,
                    //     y : item.y,
                    //     w : item.w,
                    // }))
                }
            })
        },
        methods   : {
            onSplitpaneResized(sizes)
            {
                this.storage.splitpanes = sizes
            },
            onSchemaObjectAdded(type, vm)
            {
                console.log(`added ${type}`, vm)
            },
            onSchemaObjectChanged(type, vm, oldValue, value, changes)
            {
                console.log(`changed ${type}`, ...arguments)
                // let c = this.editor.changes[type]
                //
                // if (!c.some(i => i.id == vm[type].id))
                // {
                //     c.push(JSON.parse(oldValue))
                // }
            },
            onSchemaObjectRemoved(type, vm)
            {
                console.log(`removed ${type}`, vm)
            },
        },
        computed  : {
            storageJson: vm =>
            {
                return JSON.stringify(vm.storage)
            },

        },
        watch     : {
            storageJson(value, oldValue)
            {
                localStorage.setItem('modeler', value)
            },
        },
    }
</script>
<style scoped lang="scss">
    .splitpane {
        position: absolute;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        overflow: hidden;
    }

    .main-area {
        position: absolute;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
    }

    .editor {
        max-height: 100%;
    }

    .sidebar {
        position: absolute;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
    }

    .graph-entity-title {
        display: flex;
        height: 30px;
        border-left: 1px solid white;
        border-right: 1px solid #7af;

        .entity-title-inner {
            display: flex;
            align-items: center;
            width: 100%;
            background: #def;
            border-left: 1px solid #7af;
            border-top: 1px solid #7af;
        }

        .title-text {
            flex-grow: 1;
            padding-left: 7px;
            border-bottom: 1px solid #cdf;
        }
    }
</style>
