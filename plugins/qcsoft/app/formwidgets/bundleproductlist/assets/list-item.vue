<style src="./list-item.scss"></style>
<template>
    <div class="bundle-product-list-item">
        <div class="image-attrs">
            <img class="product-image-col" :src="local.product.main_image"/>
            <div class="product-attrs-col">
                <div class="attr-name" v-text="local.product.name + ' (' + local.product.id + ')'"></div>
                <div class="attr-quantity">
                    <div class="quantity-label">Quantity:</div>
                    <div class="input-group">
                        <div class="input-group-btn">
                            <btn type="default" @click="quantityDec(local)"><i class="icon-minus"></i></btn>
                        </div>
                        <input type="text"
                               class="form-control input-sm"
                               v-model="local.quantity"
                               v-validate="/^\d{0,9}$/"
                        />
                        <div class="input-group-btn">
                            <btn type="default" @click="quantityInc(local)"><i class="icon-plus"></i></btn>
                        </div>
                    </div>
                </div>
                <div class="attr-default_price">Product default price: <span
                        v-text="local.product.default_price"></span></div>
                <div class="attr-price_override">
                    <div class="po-label">Override price:</div>
                    <input type="text"
                           class="form-control input-sm"
                           v-model="local.price_override"
                           v-validate="/^\d{0,7}(\.\d{0,2})?$/"
                    />
                </div>
            </div>
        </div>

        <bp-customergroup-prices v-model="local.bpCustomergroups"
                                 :customergroups="customergroups"
                                 :product="local.product"
        ></bp-customergroup-prices>

        <button type="button" class="drag-handle"><i class="icon-arrows"></i></button>
        <button type="button" class="btn-remove" @click="$emit('delete')"><i class="icon-close"></i></button>

    </div>
</template>
<script>
    import bpCustomergroupPrices from './bp-customergroup-prices.vue'

    export default {
        components: {bpCustomergroupPrices},
        props     : ['value', 'customergroups'],
        computed  : {
            local()
            {
                return this.value ? this.value : {}
            }
        },
        methods   : {
            quantityInc(item)
            {
                item.quantity++
            },
            quantityDec(item)
            {
                item.quantity > 1 && item.quantity--
            },
        },
    }
</script>
