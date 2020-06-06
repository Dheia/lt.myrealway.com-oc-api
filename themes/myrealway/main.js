window.$ = window.jQuery = require('jquery');
require('popper.js');
require('bootstrap');
require('../../modules/system/assets/js/framework-min');
import Vue from 'vue'

import {IconsPlugin, OverlayPlugin, SpinnerPlugin} from 'bootstrap-vue/'

import productList from './partials/product-list/product-list'
import productListCard from './partials/product-list/product-list-card'
import productListFilters from './partials/product-list/product-list-filters'
import cart from './cart'
import floatingCart from './partials/floating-cart/floating-cart'

window.bus = {
    appDefinition: {
        productList,
        productListCard,
        productListFilters,
        cart,
        floatingCart,
    },
    app          : {},
}

Vue.use(IconsPlugin)
Vue.use(OverlayPlugin)
Vue.use(SpinnerPlugin)

Vue.mixin({
    data()
    {
        return bus
    }
})

$(document).on('ajaxComplete', function ()
{
    initApps()
})

$(function ()
{
    initApps()
})

let unnamedCount = 0

function initApps()
{
    $('[data-app]').each(function (i, el)
    {
        if ($(el).attr('data-app-initialized'))
        {
            return
        }

        $(el).attr('data-app-initialized', true)

        var appClass = $(el).data('app'),
            appAlias,
            appDefinition,
            m

        if (!appClass.length)
        {
            unnamedCount++

            appAlias = 'unnamed' + unnamedCount

            appDefinition = {}
        }
        else
        {
            m = appClass.match(/\s*(.+)\s+as\s+(.+)\s*/)

            if (m && m.length)
            {
                appClass = m[1]
                appAlias = m[2]
            }
            else
            {
                appAlias = appClass
            }

            appDefinition = bus.appDefinition[appClass]
        }

        let instance = new Vue(appDefinition)

        if (bus.app[appAlias])
        {
            if (!bus.app[appAlias].length)
            {
                bus.app[appAlias] = [bus.app[appAlias]]
            }

            bus.app[appAlias].push(instance)
        }
        else
        {
            bus.app[appAlias] = instance
        }

        instance.$mount(el)
    })
}