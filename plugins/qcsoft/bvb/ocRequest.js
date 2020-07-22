export default {
    methods: {
        ocRequest(handler, params, callback)
        {
            function reqLoad()
            {
                callback(this)
            }

            let req = new XMLHttpRequest()

            req.addEventListener('load', reqLoad)

            params = Object.keys(params).map(k => (k + '=' + params[k])).join('&')

            let url = location.href.replace(/#.*/, '') + '?' + params

            req.open('POST', url)

            req.setRequestHeader('X-OCTOBER-REQUEST-HANDLER', handler)
            req.setRequestHeader('X-CSRF-TOKEN', getXSRFToken())
            req.setRequestHeader('X-Requested-With', 'XMLHttpRequest')

            req.send()

            function getXSRFToken()
            {
                return document.querySelector('meta[name=csrf-token]').getAttribute('content')
            }
        },
    },
}
