const path = require('path')

const VueLoaderPlugin = require('vue-loader/lib/plugin')

module.exports = env =>
{
    return {
        entry    : './main.js',
        output   : {
            filename: 'main.js',
            path    : path.resolve(__dirname, 'dist'),
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
