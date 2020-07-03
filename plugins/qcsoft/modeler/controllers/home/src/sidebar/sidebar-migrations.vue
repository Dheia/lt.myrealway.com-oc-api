<template>
    <div>
        <div class="sidebar-migrations">
            <div class="migrations-toolbar">
                <btn @click="$emit('action', {type: 'migrations-apply'})"
                     class="btn-load-migrations oc-icon-forward"
                ></btn>
                <btn @click="$emit('action', {type: 'migrations-load'})"
                     class="btn-apply-migrations oc-icon-refresh"
                     :disabled="sidebar.isMigrationsLoading"
                ></btn>
                <loading-spinner v-if="sidebar.isMigrationsLoading"
                                 class="loading-spinner"
                ></loading-spinner>
            </div>
            <div class="migrations-json"
                 v-text="result.migrations"
            ></div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['editor', 'result', 'storage', 'sidebar'],
    }
</script>
<style scoped lang="scss">
    .btn-apply-migrations, .btn-load-migrations {
        margin: 0 0 0 1px;
        padding: 2px 12px 0 15px;
        /*border: 0;*/
        box-shadow: none;
        background: rgba(0, 0, 0, .05);
        color: #005;
        display: block;

        &:hover {
            background: rgba(0, 0, 0, .1);
        }

        &::before {
            margin-right: 0;
            line-height: 150%;
            font-size: 150%;
        }
    }

    .btn-load-migrations {
        margin-left: 5px;
    }

    .btn-apply-migrations {
        margin-left: 15px;
    }

    .sidebar-migrations {
        position: absolute;
        left: 0;
        top: 40px;
        right: 0;
        bottom: 0;
    }

    .migrations-toolbar {
        height: 40px;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 3px -2px rgba(0, 0, 0, 0.5);
        padding: 0 0 5px 0;

        .loading-spinner {
            margin-left: 25px;
        }
    }

    .migrations-json {
        position: absolute;
        white-space: pre;
        left: 5px;
        top: 45px;
        right: 0;
        bottom: 0;
        overflow: scroll;
    }

</style>
