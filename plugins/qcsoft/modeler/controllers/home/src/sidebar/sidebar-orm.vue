<template>
    <div>
        <div class="sidebar-orm">
            <div class="orm-toolbar">
                <btn @click="$emit('action', {type: 'orm-apply'})"
                     class="btn-load-orm oc-icon-forward"
                ></btn>
                <btn @click="$emit('action', {type: 'orm-load'})"
                     class="btn-apply-orm oc-icon-refresh"
                     :disabled="sidebar.isOrmLoading"
                ></btn>
                <loading-spinner v-if="sidebar.isOrmLoading"
                                 class="loading-spinner"
                ></loading-spinner>
                <btn @click="$emit('action', {type: 'orm-hide'})"
                     class="btn-apply-orm oc-icon-close"
                ></btn>
            </div>
            <div class="orm-models">
                <div class="orm-model"
                     v-for="item in ormAllChanges"
                     @click="$emit('action', {type: 'orm-select-model', item})"
                >
                    <div :class="`oc-icon-${item.type === 'created' ? 'plus' : 'refresh'}`"></div>
                    <div v-text="item.model.modelName"></div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['editor', 'result', 'storage', 'sidebar', 'orm-all-changes'],
    }
</script>
<style scoped lang="scss">
    .btn-apply-orm, .btn-load-orm {
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

    .btn-load-orm {
        margin-left: 5px;
    }

    .btn-apply-orm {
        margin-left: 15px;
    }

    .sidebar-orm {
        position: absolute;
        left: 0;
        top: 40px;
        right: 0;
        bottom: 0;
    }

    .orm-toolbar {
        height: 40px;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 3px -2px rgba(0, 0, 0, 0.5);
        padding: 0 0 5px 0;

        .loading-spinner {
            margin-left: 25px;
        }
    }

    .orm-models {
        position: absolute;
        left: 5px;
        top: 45px;
        right: 0;
        bottom: 0;
        overflow: scroll;
    }

    .orm-model {
        display: flex;
        cursor: pointer;

        &:hover {
            text-decoration: underline;
        }
    }

</style>
