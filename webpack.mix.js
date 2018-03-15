const { mix } = require('laravel-mix');

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

mix.react('resources/assets/js/app.js', 'public/assets/bundle/js')
    .sass('resources/assets/sass/app.scss', 'public/assets/bundle/css')
    .version()
    .disableNotifications();

if (!mix.inProduction()) {
    mix.webpackConfig({
        devtool: 'source-map'
    }).sourceMaps();

    mix.options({
        processCssUrls: false
    });
}

mix.autoload({
    jquery: ['$', 'window.jQuery', 'jQuery'],
    toastr : ['toastr'],
    bootbox : ['bootbox']
});