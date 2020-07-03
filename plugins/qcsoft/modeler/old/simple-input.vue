<template>
    <div :style="{display: 'inline-block', width: width}">
        <div style="display: flex; align-items: center">
            <span v-text="label" style="margin-right: 10px"></span>
            <input type="text"
                   class="form-control"
                   :value="value"
                   @input="$emit('input', $event.target.value)"
            />
        </div>
    </div>
</template>
<script>
    export default {
        props: ['value', 'label', 'width'],
    }
</script>
