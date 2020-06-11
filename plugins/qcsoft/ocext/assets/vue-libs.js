Vue.use(VueMask.VueMaskPlugin)

Vue.mixin({
    methods: {
        moneyMask(value)
        {
            let passedDotIndex = 0,
                result = []

            for (let i = 0; i < value.length; i++)
            {
                if (value[i].match(/\d/))
                {
                    if (passedDotIndex < 4)
                    {
                        result.push(/\d/)
                    }
                }
                else if (value[i] === '.')
                {
                    if (passedDotIndex === 0)
                    {
                        result.push(/\./)

                        passedDotIndex++
                    }
                }

                if (passedDotIndex)
                {
                    passedDotIndex++
                }
            }

            return result
        }
    }
})

