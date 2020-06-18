import vValidate from './vuebackend/directives/v-validate'
import percentOrExactPrice from './vuebackend/components/percent-or-exact-price.vue'

import bundleProductList from './formwidgets/bundleproductlist/assets/script'

Vue.directive('validate', vValidate)

Vue.component('percent-or-exact-price', percentOrExactPrice)

window.vbus.appDefinition.bundleProductList = bundleProductList
