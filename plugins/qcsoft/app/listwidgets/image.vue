<template>
    <div>
        <div :style="imgContainerStyleLocal">
            <img :src="value" :style="imgStyleLocal"/>
        </div>
    </div>
</template>
<script>
    export default {
        props   : ['column', 'record', 'value'],
        name    : 'listwidget-image',
        computed: {
            imgStyleLocal         : vm => (vm.column.config.imgStyle || {}),
            imgContainerStyleLocal: vm => (vm.column.config.imgContainerStyle || {}),
        },
    }
</script>
<style lang="scss">

</style>
