<template>
    <div>
        <div class="relation-entities">
            <div class="from-label">From:</div>
            <div v-text="entityFrom.plugin_code" class="entity-from-plugin-code"></div>
            <div v-text="`${entityFrom.name} . ${attributeFrom.name}`" class="attribute-from"></div>
            <div class="to-label">To:</div>
            <div v-text="entityTo.plugin_code"
                 v-if="entityFrom.plugin_code !== entityTo.plugin_code"
                 class="entity-to-plugin-code"></div>
            <div v-text="`${entityTo.name} . ${attributeTo.name}`" class="attribute-to"></div>
        </div>
        <pre v-text="JSON.stringify(relation, null, 4)"></pre>
    </div>
</template>
<script>
    export default {
        props   : ['editor', 'relation'],
        methods : {},
        computed: {
            attributeFrom: vm => vm.editor.attributes.find(v => v.id === vm.relation.attribute_from_id),
            entityFrom   : vm => vm.editor.entities.find(v => v.id === vm.attributeFrom.entity_id),
            attributeTo  : vm => vm.editor.attributes.find(v => v.id === vm.relation.attribute_to_id),
            entityTo     : vm => vm.editor.entities.find(v => v.id === vm.attributeTo.entity_id),
        },
    }
</script>
<style scoped lang="scss">
    .from-label {
        margin-top: 10px;
    }

    .to-label {
        margin-top: 5px;
        padding-top: 5px;
        border-top: 1px solid rgba(0, 0, 0, .1);
    }

    .entity-from-plugin-code {
    }

    .attribute-from {
    }

    .entity-to-plugin-code {
    }

    .attribute-to {
        border-bottom: 1px solid rgba(0, 0, 0, .1);
        padding-bottom: 5px;
    }
</style>
