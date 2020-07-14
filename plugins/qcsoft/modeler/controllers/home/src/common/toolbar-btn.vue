<template>
    <btn @click="$emit('click')"
         :class="`toolbar-btn oc-icon-${icon}`"
         :disabled="disabled"
    ></btn>
</template>
<script>
    export default {
        name : 'toolbar-btn',
        props: ['icon', 'disabled'],
    }
</script>
<style lang="scss">

    .btn.toolbar-btn {
        margin: 0 0 0 1px;
        padding: 2px 12px 0 15px;
        /*border: 0;*/
        box-shadow: none;
        background: rgba(0, 0, 0, .35);
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

</style>
