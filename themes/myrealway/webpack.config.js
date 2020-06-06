const path = require('path')

module.exports = env =>
{
    return {
        entry    : './main.js',
        output   : {
            filename: 'main.js',
            path    : path.resolve(__dirname, 'assets/dist'),
        },
        devServer: {
            allowedHosts    : [
                '*'
            ],
            disableHostCheck: true,
            port            : 8080,
        },
        resolve  : {
            alias: {
                vue: env.type === 'prod' ? 'vue/dist/vue.min.js' : 'vue/dist/vue.js',
            }
        }
    }
}
