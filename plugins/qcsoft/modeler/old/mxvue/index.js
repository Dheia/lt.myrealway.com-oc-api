import mxEditor from './mx-editor.vue'
import mxCell from './mx-cell.vue'

export default {
    install: function (Vue, options)
    {
        Vue.component('mxEditor', mxEditor)
        Vue.component('mxCell', mxCell)
    }
}
