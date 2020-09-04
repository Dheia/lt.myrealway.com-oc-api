window.$ = window.jQuery = require('jquery');
require('popper.js');
require('bootstrap');
require('../../modules/system/assets/js/framework-min');
import Vue from 'vue'

import ShopCart from './components/ShopCart.vue'

Vue.component('ShopCart', ShopCart)

import {IconsPlugin, OverlayPlugin, SpinnerPlugin, TabsPlugin} from 'bootstrap-vue'

require('slick-carousel')

// import Swiper, {Autoplay, Navigation} from 'swiper'
//
// Swiper.use([Autoplay, Navigation])

import mainLoadIndicator from './layouts/main-load-indicator'
import pageProduct from './partials/page-product/page-product'
import catalogitemList from './partials/catalogitem-list/catalogitem-list'
import catalogitemListItem from './partials/catalogitem-list/catalogitem-list-item'
import catalogitemListFilters from './partials/catalogitem-list/catalogitem-list-filters'
import cart from './cart'
import floatingCart from './partials/floating-cart/floating-cart'

window.bus = {
    appDefinition: {
        mainLoadIndicator,
        pageProduct,
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
Vue.use(TabsPlugin)

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

    setTimeout(() =>
    {
        initSliders()
    })
})

$(function ()
{
    initApps()

    initSliders()

    // $().fancybox({
    //     selector: '[data-fancybox]'
    // });
})

function initSliders()
{
    $('[data-slider]').each((i, item) =>
    {
        var settings = $(item).data('slider')
        console.log(settings)
        $(item).not('.slick-initialized').slick({
            prevArrow: '<button class="btn btn-primary goto-prev"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chevron-left" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/></svg></button>',
            nextArrow: '<button class="btn btn-primary goto-next"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chevron-right" fill="white" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/></svg></button>',
            ...settings
        })
    })
}

// function initSlidersSwiper()
// {
//     $('[data-slider]').each((i, item) =>
//     {
//         var settings = $(item).data('slider'),
//             swiperContainer = $(item).find('.swiper-container')[0],
//             nextEl = $(item).find('.swiper-button-next')[0],
//             prevEl = $(item).find('.swiper-button-prev')[0]
//
//         new Swiper(swiperContainer, {
//             loop         : true,
//             speed        : 1000,
//             effect       : 'slide',
//             slidesPerView: 1,
//             navigation   : {
//                 nextEl,
//                 prevEl,
//             },
//             ...settings
//         })
//     })
// }

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

export default Vue
