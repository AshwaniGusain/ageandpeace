let mix = require('laravel-mix');
let LiveReloadPlugin = require('webpack-livereload-plugin');
let ImageminPlugin = require('imagemin-webpack-plugin').default;
let CopyWebpackPlugin = require('copy-webpack-plugin');

require('./resources/assets/snap/webpack.mix');





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
    .js('resources/assets/js/app.js', 'public/assets/js')
    .js('resources/assets/js/dashboard.js', 'public/assets/js')
    .sass('resources/assets/sass/app.scss', 'public/assets/css')
    .options({
        processCssUrls: false
    })
    .version()
;

mix.webpackConfig({
    plugins: [
        new LiveReloadPlugin(),

        new CopyWebpackPlugin([
            {
                from: 'resources/assets/svg/**/*.svg', // FROM
                to: 'assets/svg/', // TO
                flatten: true,
            },
            // {
            //   from: 'resources/images/', // FROM
            //   to: 'images/assets/', // TO
            //   ignore: ['*.svg'],
            //   flatten: true,
            // },
        ]),
    ],
});

// if (mix.inProduction()) {
mix.webpackConfig({
    plugins: [
        new ImageminPlugin({
            test: /\.svg/i,
            svgo: {
                removeTitle: true,
                // removeAttrs: { attrs: 'fill' }
            },
        }),
    ],
});
// }
