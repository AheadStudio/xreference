let mix = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js')
   .js('resources/assets/js/xreference.js', 'public/js/xreference.js')
   .stylus('resources/assets/stylus/app.styl', 'public/css/stylus.css');
   
mix.sass('resources/assets/sass/app.scss', 'public/css/sass.css');
   
mix.styles([
    'public/css/stylus.css',
    'public/css/sass.css',
    'public/css/font-awesome.min.css'
], 'public/css/app.css');


