<template>
    <div>
        <app-navbar></app-navbar>
        <div class="app-layout">
            <div class="app-sidebar">
                <app-sidebar></app-sidebar>
            </div>
            <div class="app-main">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>
<script>
    import AppNavbar from './AppNavbar.vue'
    import AppSidebar from './AppSidebar.vue'

    export default {
        components: {
            AppNavbar,
            AppSidebar
        },
    }
</script>
<style lang="scss">
    .app-layout {
        display: flex;
        height: calc(100vh - 56px);
    }

    .app-sidebar {
        width: 200px;
        flex-shrink: 0;
        flex-grow: 0;
    }

    .app-main {
        flex-shrink: 1;
        flex-grow: 1;
        overflow: scroll;
        padding: 5px 0;
    }
</style>
