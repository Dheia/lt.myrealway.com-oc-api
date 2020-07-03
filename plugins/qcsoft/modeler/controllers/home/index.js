import Vue from 'vue'
import * as uiv from 'uiv'
import app from './src/app.vue'
import PortalVue from 'portal-vue'
import VueSplit from 'vue-split-panel'
import LoadingSpinner from './src/common/loading-spinner.vue'

Vue.config.productionTip = false

Vue.use(uiv)

Vue.use(PortalVue)

Vue.use(VueSplit)

Vue.component(LoadingSpinner.name, LoadingSpinner)

$(function ()
{
    new Vue({
        data      : {schema: $('.app').data('schema')},
        el        : '#app',
        template  : '<app/>',
        components: {app}
    })
})
