<template>
    <div>
        <div v-text="JSON.stringify(changes, null, 4)"></div>
    </div>
</template>
<script>
    export default {
        props: ['changes'],
    }
</script>
<style scoped lang="scss">

</style>
