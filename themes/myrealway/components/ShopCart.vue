<template>
    <div>
        <div v-for="item in cartitems"
             v-text="item.id"
        ></div>
    </div>
</template>
<script>
    export default {
        data   : vm => ({
            cartitems: [],
        }),
        mounted()
        {
            this.fetchCartitems()
        },
        methods: {
            fetchCartitems()
            {
                this.cartitems = [
                    {
                        id   : 123,
                        label: 'qwer',
                    },
                    {
                        id   : 234,
                        label: 'asdf',
                    },
                ]
            },
        },
    }
</script>
<style>

</style>
