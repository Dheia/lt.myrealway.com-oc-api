<template>
    <div class="percent-or-exact-price">
        <div class="input-group">
            <input type="text"
                   class="form-control"
                   :disabled="local.valueType !== 'percent'"
                   @input="setValue"
                   :value="percentValue"
            >
            <span class="input-group-addon"
                  @click="setValueType('percent')"
            >%</span>
        </div>
        <div class="input-group">
            <input type="text"
                   class="form-control"
                   :disabled="local.valueType !== 'exactPrice'"
                   @input="setValue"
                   :value="exactPriceValue"
            >
            <span class="input-group-addon"
                  @click="setValueType('exactPrice')"
            >&euro;</span>
        </div>
    </div>
</template>
<script>
    export default {
        model   : {
            prop: 'result',
        },
        props   : ['result', 'from-price'],
        computed: {
            local()
            {
                return this.result ? this.result : {}
            },
            percentValue()
            {
                if (this.local.value === '' || this.local.value === undefined)
                {
                    return ''
                }

                return this.local.valueType === 'percent' ? this.local.value :
                    (10000 - Math.round(this.local.value / (this.fromPrice / 10000))) / 100
            },
            exactPriceValue()
            {
                if (this.local.value === '' || this.local.value === undefined)
                {
                    return ''
                }

                return this.local.valueType === 'exactPrice' ? this.local.value :
                    Math.round(this.fromPrice * (100 - this.local.value)) / 100
            },
        },
        methods : {
            setValue(e)
            {
                var s = {...this.local}

                if (s.valueType === 'percent' && this.percentValidate(e.target.value))
                {
                    s.value = e.target.value
                }
                else if (s.valueType === 'exactPrice' && this.exactPriceValidate(e.target.value))
                {
                    s.value = e.target.value
                }

                this.$emit('input', s)
            },
            setValueType(valueType)
            {
                var s = {...this.local}

                s.value = this[valueType + 'Value']
                s.valueType = valueType

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
