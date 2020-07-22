import OcLists from './widgets/oc-lists.vue'
import ListwidgetImage from './listwidgets/image.vue'
import vValidate from './vuebackend/directives/v-validate'
import percentOrExactPrice from './vuebackend/components/percent-or-exact-price.vue'

import bundleProductList from './formwidgets/bundleproductlist/assets/script'
// import FilterOptions from './formwidgets/filteroptions/partials/script.js'

Vue.component(OcLists.name, OcLists)
Vue.component(ListwidgetImage.name, ListwidgetImage)

Vue.directive('validate', vValidate)

Vue.component('percent-or-exact-price', percentOrExactPrice)

window.vbus.appDefinition.bundleProductList = bundleProductList
