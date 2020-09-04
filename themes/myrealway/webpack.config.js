const path = require('path')

const VueLoaderPlugin = require('vue-loader/lib/plugin')

module.exports = env =>
{
    return {
        entry    : {
            main : './main.js',
        },
        output   : {
            filename: '[name].js',
            path    : path.resolve(__dirname, 'assets/dist'),
        },
        devServer: {
            allowedHosts    : [
                '*'
            ],
            disableHostCheck: true,
            port            : 8081,
        },
        resolve  : {
            alias: {
                vue: env.type === 'prod' ? 'vue/dist/vue.min.js' : 'vue/dist/vue.js',
            }
        },
        module   : {
            rules: [
                {
                    test  : /\.vue$/,
                    loader: 'vue-loader'
                },
                {
                    test: /\.s?css$/,
                    use : [
                        'vue-style-loader',
                        'css-loader',
                        'sass-loader'
                    ]
                }
            ]
        },
        plugins  : [
            new VueLoaderPlugin()
        ],
    }
}
