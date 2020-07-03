<template>
    <div :class="['debug', {'is-open': isOpen}]" @click="isOpen = !isOpen">
        <pre v-text="data"></pre>
    </div>
</template>
<script>
    export default {
        props: ['data'],
        data : vm => ({
            isOpen: false,
        }),
    }
</script>
<style scoped lang="scss">
    .debug {
        position: absolute;
        left: 0;
        top: 0;
        width: 30px;
        height: 30px;
        overflow: hidden;
        background: white;
        border: 1px solid #aaa;

        &.is-open {
            width: 400px;
            bottom: 0;
            height: auto;
            overflow: scroll;
        }
    }
</style>
