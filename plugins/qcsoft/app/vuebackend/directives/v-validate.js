export default {
    bind(el, binding, vnode)
    {
        el._oldvalue = el.value
    },
    update(el, binding, vnode)
    {
        let valid = typeof binding.value === 'function' ?
            binding.value(el.value) :
            el.value.match(binding.value)

        if (valid)
        {
            el._oldvalue = el.value
        }
        else
        {
            let selectionStart = el.selectionStart

            el.value = el._oldvalue

            el.dispatchEvent(new Event('input', {bubbles: true}))

            el.setSelectionRange(selectionStart - 1, selectionStart - 1)
        }
    },
}
