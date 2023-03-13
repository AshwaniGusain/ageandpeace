let mix = require('laravel-mix');
let path = 'snap/docs/resources/assets/';

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

mix
    .sass(path + 'sass/docs.scss', 'public/assets/snap/docs/css/docs.css')
    .version()
;