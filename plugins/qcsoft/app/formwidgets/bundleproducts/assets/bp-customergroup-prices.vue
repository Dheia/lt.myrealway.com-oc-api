<template>
    <div>
        <div>Prices for customer groups</div>
        <table class="table">
            <tr>
                <th>Group</th>
                <th>Group discount</th>
                <th>Custom discount</th>
            </tr>
            <tr v-for="(item, i) in value" :key="item.customergroup_id">
                <td v-text="customergroups[item.customergroup_id].name" style="padding-right: 10px"></td>
                <td style="position: relative">
                    <span v-text="customergroups[item.customergroup_id].discount_percent + '%'"></span>
                    <btn type="primary" class="oc-icon-undo" @click="value[i].overrideDiscount = {}"></btn>
                </td>
                <td>
                    <percent-or-exact-price :from-price="product.default_price"
                                            v-model="value[i].overrideDiscount"
                    ></percent-or-exact-price>
                </td>
            </tr>
        </table>
    </div>
</template>
<script>
    export default {
        props: ['value', 'customergroups', 'product'],
    }
</script>
<style scoped lang="scss">
    td {
        vertical-align: middle;
    }

    .oc-icon-undo {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        padding: 5px 0 5px 8px;
    }
</style>