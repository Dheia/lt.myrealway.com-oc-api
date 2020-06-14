<template>
    <div class="percent-or-exact-price">
        <div class="input-group">
            <input type="text"
                   class="form-control"
                   :disabled="local.valueType !== 'percent'"
                   v-model="percentValue"
                   v-validate="percentValidate"
            >
            <span class="input-group-addon"
                  @click="setValueType('percent')"
            >%</span>
        </div>
        <div class="input-group">
            <input type="text"
                   class="form-control"
                   :disabled="local.valueType !== 'exactPrice'"
                   v-model="exactPriceValue"
                   v-validate="exactPriceValidate"
            >
            <span class="input-group-addon"
                  @click="setValueType('exactPrice')"
            >&euro;</span>
        </div>
    </div>
</template>
<script>
    export default {
        props   : ['value', 'from-price'],
        computed: {
            local()
            {
                return this.value ? this.value : {}
            },
        },
        data()
        {
            return {
                percentValue   : '',
                exactPriceValue: '',
                converting     : false,
            }
        },
        methods : {
            setValueType(type)
            {
                var s = {...this.local}

                s.valueType = type

                this.$emit('input', s)
            },
            percentValidate(value)
            {
                return value.match(/^\d{0,3}(\.\d{0,2})?$/) && (value >= 0) && (value <= 100)
            },
            exactPriceValidate(value)
            {
                return value.match(/^\d{0,9}(\.\d{0,2})?$/) && (value >= 0) && (value <= this.fromPrice)
            },
        },
        watch   : {
            percentValue(value)
            {
                if (this.converting)
                {
                    return
                }

                this.converting = true

                this.exactPriceValue = Math.round(this.fromPrice * (100 - value)) / 100

                this.$nextTick(() =>
                {
                    this.converting = false
                })
            },
            exactPriceValue(value)
            {
                if (this.converting)
                {
                    return
                }

                this.converting = true

                this.percentValue = (10000 - Math.round(value / (this.fromPrice / 10000))) / 100

                this.$nextTick(() =>
                {
                    this.converting = false
                })
            },
        },
    }
</script>
<style scoped lang="scss">
    .percent-or-exact-price {
    }

    .input-group {
        margin-bottom: 1px;
    }

    .form-control {
        height: 26px;
    }

    .input-group-addon {
        cursor: pointer;
        width: 26px;
        height: 26px;
        padding: 0;

        &:hover {
            background: #ccc;
        }
    }
</style>
