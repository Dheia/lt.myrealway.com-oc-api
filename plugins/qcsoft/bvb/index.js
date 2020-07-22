import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import './index.scss'

import Vue from 'vue'
import VueRouter from 'vue-router'
import PortalVue from 'portal-vue'
import VueSplit from 'vue-split-panel'
import VueColumnsResizable from 'vue-columns-resizable'
import App from './components/App.vue'
import {
    IconsPlugin,
    BadgePlugin,
    ButtonPlugin,
    FormCheckboxPlugin,
    FormPlugin,
    FormInputPlugin,
    LayoutPlugin,
    ListGroupPlugin,
    NavbarPlugin,
    PopoverPlugin,
    TablePlugin
} from 'bootstrap-vue'
import ocRequest from './ocRequest.js'

import AdminBTable from './lists/AdminBTable.vue'

import BundleList from './lists/Bundle.vue'
import ProductList from './lists/Product.vue'
import FilterList from './lists/Filter.vue'
import CategoryList from './lists/Category.vue'
import GenericpageList from './lists/Genericpage.vue'
import PageList from './lists/Page.vue'
import CatalogitemList from './lists/Catalogitem.vue'

Vue.component(AdminBTable.name, AdminBTable)

Vue.config.productionTip = false
Vue.use(VueRouter)
Vue.use(PortalVue)
Vue.use(VueSplit)
Vue.use(VueColumnsResizable)

Vue.use(IconsPlugin)
Vue.use(BadgePlugin)
Vue.use(ButtonPlugin)
Vue.use(FormCheckboxPlugin)
Vue.use(FormPlugin)
Vue.use(FormInputPlugin)
Vue.use(LayoutPlugin)
Vue.use(ListGroupPlugin)
Vue.use(NavbarPlugin)
Vue.use(PopoverPlugin)
Vue.use(TablePlugin)

Vue.mixin(ocRequest)

const router = new VueRouter({
    routes: [
        {
            path     : '/bundles',
            component: BundleList,
        },
        {
            path     : '/products',
            component: ProductList,
        },
        {
            path     : '/filters',
            component: FilterList,
        },
        {
            path     : '/categories',
            component: CategoryList,
        },
        {
            path     : '/genericpages',
            component: GenericpageList,
        },
        {
            path     : '/pages',
            component: PageList,
        },
        {
            path     : '/catalogitems',
            component: CatalogitemList,
        },
    ],
})

setTimeout(() =>
{
    new Vue({
        router,
        data      : {},
        el        : '#app',
        template  : '<App/>',
        components: {App},
    })
}, 1000)
