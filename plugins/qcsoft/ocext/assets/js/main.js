import Vue from 'vue'
import vueShell from './vue-shell'
import * as uiv from 'uiv'
import draggable from 'vuedraggable'
import vValidate from './directives/v-validate'

import percentOrExactPrice from './components/percent-or-exact-price.vue'

Vue.use(uiv)

Vue.directive('validate', vValidate)

Vue.component('draggable', draggable)

Vue.component('percent-or-exact-price', percentOrExactPrice)

window.Vue = Vue
