<template>
    <div>
        <div v-text="entity.name"></div>
        <div v-text="attribute.name"></div>
    </div>
</template>
<script>
    export default {
        props   : ['attribute', 'editor'],
        methods : {},
        computed: {
            entity: vm => vm.editor.entities.find(v => v.id === vm.attribute.entity_id)
        },
    }
</script>
<style scoped lang="scss">
</style>
