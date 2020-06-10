window.vbus = {
    appDefinition: {},
    app          : {},
}

Vue.mixin({
    data()
    {
        return vbus
    }
})

$(document).on('render', function ()
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

            appDefinition = vbus.appDefinition[appClass]
        }

        let instance = new Vue(appDefinition)

        if (vbus.app[appAlias])
        {
            if (!vbus.app[appAlias].length)
            {
                vbus.app[appAlias] = [vbus.app[appAlias]]
            }

            vbus.app[appAlias].push(instance)
        }
        else
        {
            vbus.app[appAlias] = instance
        }

        instance.$mount(el)
    })
}
