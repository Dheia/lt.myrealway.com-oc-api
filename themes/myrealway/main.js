window.$ = window.jQuery = require('jquery');
require('popper.js');
require('bootstrap');
require('../../modules/system/assets/js/framework-min');
import Vue from 'vue'

import {IconsPlugin, OverlayPlugin, SpinnerPlugin} from 'bootstrap-vue/'

import mainLoadIndicator from './layouts/main-load-indicator'
import productList from './partials/product-list/product-list'
import productListItem from './partials/product-list/product-list-item'
import productListFilters from './partials/product-list/product-list-filters'
import cart from './cart'
import floatingCart from './partials/floating-cart/floating-cart'

window.bus = {
    appDefinition: {
        mainLoadIndicator,
        productList,
        productListItem,
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

$(document).on('click', '[data-ajax-link]', function (e)
{
    e.preventDefault()

    let url = $(e.currentTarget).attr('href')

    history.pushState(null, null, url)

    bus.app.mainLoadIndicator.loading = true

    $.ajax(url, {
        data: {
            noLayout: true,
        },
        success(data, textStatus, jqXHR)
        {
            bus.app.mainLoadIndicator.loading = false

            setTimeout(() =>
            {
                $('#page').html(data)

                initApps()

            }, 0)
        },
    })
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
