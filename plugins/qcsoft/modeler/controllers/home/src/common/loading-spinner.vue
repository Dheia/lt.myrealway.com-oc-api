<template>
    <div class="loading-spinner"
         :style="{width: size + 'px', height: size + 'px'}"
    ></div>
</template>
<script>
    export default {
        name : 'loading-spinner',
        props: {
            size: {
                default: 32,
            },
        },
    }
</script>
<style scoped lang="scss">
    .loading-spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>