<template>
    <div>
        <div class="stretch flex-column">
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
            <div class="grow-1 shrink-1 relative">
                <portal-target name="sidebar-tab-root" v-show="selectedTab === 'root'"></portal-target>
                <portal-target name="sidebar-tab-selection" v-show="selectedTab === 'selection'"></portal-target>
                <portal-target name="sidebar-tab-migrations" v-show="selectedTab === 'migrations'"></portal-target>
                <portal-target name="sidebar-tab-orm" v-show="selectedTab === 'orm'"></portal-target>
                <portal-target name="sidebar-tab-ctrl" v-show="selectedTab === 'ctrl'"></portal-target>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props   : ['editor', 'storage'],
        data    : vm => ({
            tabs: {
                root      : 'Root',
                selection : 'Selection',
                migrations: 'Migrations',
                orm       : 'Orm',
                ctrl      : 'Controllers',
            },
        }),
        methods : {
            setSelectedTab(tabKey)
            {
                this.$set(this.storage, 'selectedTab', tabKey)
            },
        },
        computed: {
            selectedTab : vm => vm.storage.selectedTab || 'root',
            entitiesJson: vm => JSON.stringify(vm.editor.entities),
        },
    }
</script>
<style scoped lang="scss">
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
