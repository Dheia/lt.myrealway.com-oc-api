<template>
    <div>
        <div class="sidebar">
            <div class="sidebar-tabs">
                <btn-group>
                    <btn v-for="(v, k) in tabs"
                         :key="k"
                         @click="setSelectedTab(k)"
                         v-text="v"
                         :class="['btn-link', selectedTab === k ? 'sidebar-tab-active' : '']"
                    ></btn>
                </btn-group>
            </div>
            <div v-show="selectedTab === 'root'">
                <sidebar-root :editor="editor"></sidebar-root>
            </div>
            <div v-show="selectedTab === 'selection'">
                <template v-if="!selectedCount">
                    <div>
                        Nothing selected
                    </div>
                </template>
                <template v-else-if="selectedCount === 1">
                    <div v-if="selection.entities.length === 1">
                        <sidebar-entity :editor="editor"
                                        :entity="selection.entities[0]"
                        ></sidebar-entity>
                    </div>

                    <div v-if="selection.attributes.length === 1">
                        <sidebar-attribute :editor="editor"
                                           :attribute="selection.attributes[0]"
                        ></sidebar-attribute>
                    </div>

                    <div v-if="selection.relations.length === 1">
                        <sidebar-relation :editor="editor"
                                          :relation="selection.relations[0]"
                        ></sidebar-relation>
                    </div>
                </template>
                <template v-else>
                    <div style="position:absolute; left:0; top: 45px; right: 0; bottom: 0;overflow: scroll">
                        <div>Multiple items selected</div>
                        entities:
                        <pre v-text="selection.entities"></pre>
                        attributes:
                        <pre v-text="selection.attributes"></pre>
                        relations:
                        <pre v-text="selection.relations"></pre>
                    </div>
                </template>
            </div>
            <div v-show="selectedTab === 'migrations'">
                <sidebar-migrations
                        :editor="editor"
                        :result="result"
                        :storage="storage"
                        :sidebar="sidebar"
                        @action="$emit('action', arguments[0])"
                ></sidebar-migrations>
            </div>
            <div v-show="selectedTab === 'orm'">
                <sidebar-orm
                        :editor="editor"
                        :result="result"
                        :storage="storage"
                        :sidebar="sidebar"
                        @action="$emit('action', arguments[0])"
                ></sidebar-orm>
            </div>
            <div v-show="selectedTab === 'debug'">
                <pre v-text="debug"></pre>
            </div>
        </div>
    </div>
</template>
<script>
    import editClasses from './../schema-editor/edit-classes.js'
    import ChangedItems from './changed-items.vue'
    import SidebarRoot from './sidebar-root.vue'
    import SidebarEntity from './sidebar-entity.vue'
    import SidebarAttribute from './sidebar-attribute.vue'
    import SidebarRelation from './sidebar-relation.vue'
    import SidebarMigrations from './sidebar-migrations.vue'
    import SidebarOrm from './sidebar-orm.vue'

    export default {
        components: {
            SidebarMigrations,
            SidebarOrm,
            ChangedItems,
            SidebarRoot,
            SidebarEntity,
            SidebarAttribute,
            SidebarRelation,
        },
        props     : ['editor', 'result', 'storage', 'sidebar', 'debug'],
        data      : vm => ({
            tabs: {
                root      : 'Root',
                selection : 'Selection',
                migrations: 'Migrations',
                orm       : 'Orm',
                debug     : 'Debug',
            },
        }),
        methods   : {
            setSelectedTab(tabKey)
            {
                this.$set(this.storage, 'selectedTab', tabKey)
            },
        },
        computed  : {
            selectedTab  : vm => vm.storage.selectedTab || 'root',
            entitiesJson : vm => JSON.stringify(vm.editor.entities),
            selection    : vm =>
            {
                let result = []

                editClasses.forEach(cls =>
                {
                    result[cls.pl] = vm.editor[cls.pl].filter(v => vm.editor.selection[cls.pl].includes(v.id))
                })

                return result
            },
            selectedCount: vm => vm.selection.entities.length +
                vm.selection.attributes.length + vm.selection.relations.length,
        },
    }
</script>
<style scoped lang="scss">
    .sidebar {
        position: absolute;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        background: white;
    }

    .sidebar-tabs {
        border-bottom: 1px solid rgba(0, 0, 0, .1);

        .btn-link {
            font-size: 100%;

            &:hover {
                background: rgba(0, 0, 0, .1);
            }
        }
    }

    .sidebar-tab-active {
        background: rgba(0, 0, 0, .05);
    }
</style>
