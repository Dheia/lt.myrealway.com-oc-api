window.$ = window.jQuery = require('jquery');
require('popper.js');
require('bootstrap');
require('../../modules/system/assets/js/framework-min');
import Vue from 'vue'

import {IconsPlugin, OverlayPlugin, SpinnerPlugin} from 'bootstrap-vue/'

import mainLoadIndicator from './layouts/main-load-indicator'
import catalogitemList from './partials/catalogitem-list/catalogitem-list'
import catalogitemListItem from './partials/catalogitem-list/catalogitem-list-item'
import catalogitemListFilters from './partials/catalogitem-list/catalogitem-list-filters'
import cart from './cart'
import floatingCart from './partials/floating-cart/floating-cart'

window.bus = {
    appDefinition: {
        mainLoadIndicator,
        catalogitemList,
        catalogitemListItem,
        catalogitemListFilters,
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

    loadPage(url)
})

window.addEventListener('popstate', function (e)
{
    loadPage(location.href)
})

$(document).on('ajaxComplete', function ()
{
    initApps()
})

$(function ()
{
    initApps()
})

function loadPage(url)
{
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
}

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
