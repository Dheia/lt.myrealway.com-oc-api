const path = require('path')

const VueLoaderPlugin = require('vue-loader/lib/plugin')

module.exports = env =>
{
    return {
        entry    : './index.js',
        output   : {
            filename: 'main.js',
            path    : path.resolve(__dirname, 'assets/dist'),
        },
        // devtool  : 'source-map',
        devServer: {
            allowedHosts    : [
                '*'
            ],
            disableHostCheck: true,
            port            : 8083,
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
