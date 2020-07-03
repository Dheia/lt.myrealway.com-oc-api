export default {
    watch: {
        asJson(value, oldValue)
        {
            this.$emit('changed', this, value, oldValue)
        },
    },
}
