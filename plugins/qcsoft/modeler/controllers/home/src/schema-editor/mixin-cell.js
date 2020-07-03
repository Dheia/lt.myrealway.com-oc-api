import editClasses from './edit-classes.js'

let noEmitDestroyed = []

export default {
    computed: {
        type     : vm => vm.$options._componentTag.replace(/^cell-/, ''),
        editClass: vm => editClasses.find(v => v.name === vm.type),
        value    : vm => vm[vm.type],
        asJson   : vm => JSON.stringify(vm.value),
    },
    methods : {
        cellMixinCreated()
        {
            if (!this.value.id)
            {
                let nextId = this.$editor.editor[this.editClass.pl]
                    .reduce((id, e) => e.id > id ? e.id : id, 0) + 1

                this.$set(this[this.type], 'id', nextId)

                noEmitDestroyed.push(nextId)

                this.$emit('added', this)
            }
            else
            {
                this.createMxCell()
            }
        },
        cellMixinDestroyed()
        {
            let indexOf = noEmitDestroyed.indexOf(this.value.id)

            if (indexOf !== -1)
            {
                noEmitDestroyed.splice(indexOf, 1)
            }
            else
            {
                this.$emit('removed', this)
            }
        },
    },
}
