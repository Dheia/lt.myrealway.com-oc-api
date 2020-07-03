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
                                   @added="onSchemaObjectAdded"
                                   @changed="onSchemaObjectChanged"
                                   @removed="onSchemaObjectRemoved"
                    >
                        <template #entity="{ entity }">
                            <div class="graph-entity-title">
                                <div v-text="`${entity.name} (${entity.id})`"
                                     class="title-text"
                                ></div>
                                <div class="oc-icon-sitemap"
                                     v-if="entity.options.some(v => v.type === 'nested_tree')"
                                ></div>
                            </div>
                        </template>
                    </schema-editor>
                </div>
                <div class="diff" v-show="showingDiff">
                    <div v-html="showingDiff"></div>
                </div>
            </split-area>
            <split-area :size="storage.splitpanes[1]" style="overflow: hidden; position: relative">
                <div class="sidebar">
                    <app-sidebar :editor="editor"
                                 :result="result"
                                 :storage="storage"
                                 :debug="debug"
                                 :sidebar="sidebar"
                                 @action="onSidebarAction"
                    ></app-sidebar>
                </div>
            </split-area>
        </split>
    </div>
</template>
<script>
    import SchemaEditor from './schema-editor/schema-editor.vue'
    import AppSidebar from './sidebar/sidebar.vue'
    import * as Diff2Html from 'diff2html'

    export default {
        components: {
            SchemaEditor,
            AppSidebar,
        },
        data()
        {
            let storage = JSON.parse(localStorage.getItem('modeler')) || {}

            storage.splitpanes = storage.splitpanes || [70, 30]

            return {
                loadValue  : {...this.$root.schema},
                editor     : {},
                storage    : storage,
                result     : {
                    migrations: '',
                },
                sidebar    : {
                    isMigrationsLoading: false,
                    isOrmLoading       : false,
                },
                debug      : [],
                showingDiff: null,
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
                    this.debug.push('ctrl+s pressed')

                    e.preventDefault()

                    this.loadOrm()
                }
            })

            // this.loadMigrations()
            this.loadOrm()
        },
        methods   : {
            onSidebarAction(action)
            {
                this[`on-${action.type}`](action)
            },
            'on-migrations-apply'(action)
            {
                this.$notify.error('Under construction')
            },
            'on-migrations-load'(action)
            {
                this.loadMigrations()
            },
            'on-orm-load'(action)
            {
                this.loadOrm()
            },
            loadMigrations()
            {
                let sendData = {...this.editor}

                delete sendData.changes

                this.sidebar.isMigrationsLoading = true

                $.request('onGetMigrations', {
                    data    : {
                        data: sendData,
                    },
                    success : (data, textStatus, jqXHR) =>
                    {
                        this.result.migrations = data.result
                    },
                    complete: () =>
                    {
                        this.sidebar.isMigrationsLoading = false
                    }
                })
            },
            loadOrm()
            {
                let sendData = {...this.editor}

                delete sendData.changes

                this.sidebar.isOrmLoading = true

                $.request('onGetOrm', {
                    data    : {
                        data: sendData,
                    },
                    success : (data, textStatus, jqXHR) =>
                    {
                        // this.$notify.success('Saved')
                        // // this.showingDiff = 'qwer'
                        this.showingDiff = Diff2Html.html(JSON.parse(data.result)[0].diff, {
                            matching    : 'lines',
                            outputFormat: 'side-by-side',
                        })
                        this.result.orm = JSON.parse(data.result)
                    },
                    complete: () =>
                    {
                        this.sidebar.isOrmLoading = false
                    }
                })
            },
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

    .diff {
        background: white;
        position: absolute;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        overflow: scroll;
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
        background: rgba(0, 255, 0, .3);

        .title-text {
            flex-grow: 1;
            padding-left: 5px;
        }
    }
</style>
