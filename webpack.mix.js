const { mix } = require('laravel-mix');
const path = require('path');
const ExtractTextPlugin = require("extract-text-webpack-plugin");


/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.autoload({
    jquery : ['$', 'jquery' , 'jQuery', 'jQuery', 'window.jQuery'],
    toastr : ['toastr'],
    bootbox : ['bootbox']
});

mix.react('resources/assets/src/app.js', 'public/assets/bundle')
    .version()
    .disableNotifications();

mix.webpackConfig({
    module: {
        rules: [
            {
                test:  /\.s[ac]ss$/,
                loader: ExtractTextPlugin.extract({
                    fallback: 'style-loader',
                    use : [
                        {
                            loader : 'css-loader',
                        },
                        {
                            loader : 'postcss-loader',
                        },
                        {
                            loader : 'sass-loader',

                        }
                    ]
                })
            }
        ]
    },
    plugins: [
        new ExtractTextPlugin('[name].[contenthash].css')
    ],
    devtool : 'source-map'
}).sourceMaps();

mix.options({
    processCssUrls: false
});

