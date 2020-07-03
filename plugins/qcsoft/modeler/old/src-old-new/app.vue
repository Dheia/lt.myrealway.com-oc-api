<template>
    <div>
        <schema-editor class="editor" :entities="entities"></schema-editor>
        <app-debug :data="$data"></app-debug>
    </div>
</template>
<script>
    import SchemaEditor from './schema-editor/schema-editor.vue'
    import AppDebug from './debug.vue'

    export default {
        components: {
            SchemaEditor,
            AppDebug,
        },
        data      : vm => ({
            entities: [{
                name      : 'qwer123',
                attributes: [{
                    name: 'qwer',
                }, {
                    name: 'asdf',
                }, {
                    name: 'zxcv',
                }],
                x         : 100,
                y         : 100,
                width     : 200,
                height    : 100,
            },{
                name      : 'asdf234',
                attributes: [{
                    name: 'qwer',
                }, {
                    name: 'asdf',
                }, {
                    name: 'zxcv',
                }],
                x         : 200,
                y         : 300,
                width     : 200,
                height    : 100,
            }],
        }),
        methods   : {},
        mounted()
        {
        },
    }
</script>
<style scoped lang="scss">
    .editor {
        position: absolute;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
    }
</style>
