let mix = require('laravel-mix');
let path = 'snap/admin/resources/assets/';

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
    .js(path + 'js/SNAP.js', 'public/assets/snap/js')
    .js(path + 'js/init.js', 'public/assets/snap/js')
    .js(path + 'js/components/resource/Calendar.vue', 'public/assets/snap/js/components/resource/')
    .js(path + 'js/components/resource/DataTable.vue', 'public/assets/snap/js/components/resource/')
    .js(path + 'js/components/resource/Filters.vue', 'public/assets/snap/js/components/resource/')
    .js(path + 'js/components/resource/HierarchicalListing.vue', 'public/assets/snap/js/components/resource/')
    .js(path + 'js/components/resource/MapList.vue', 'public/assets/snap/js/components/resource/')
    .js(path + 'js/components/resource/OthersNavigation.vue', 'public/assets/snap/js/components/resource/')
    .js(path + 'js/components/resource/ResourceCompare.vue', 'public/assets/snap/js/components/resource/')
    .js(path + 'js/components/resource/ResourceIndex.vue', 'public/assets/snap/js/components/resource/')
    .js(path + 'js/components/resource/RestoreVersions.vue', 'public/assets/snap/js/components/resource/')
    .js(path + 'js/components/resource/ResourcePreview.vue', 'public/assets/snap/js/components/resource/')
    .js(path + 'js/components/common/*.vue', 'public/assets/snap/js/components/common.js')
    .copy(path + 'vendor/*', 'public/assets/snap/vendor')
    .sass(path + 'sass/admin.scss', 'public/assets/snap/css/admin.css')
    .copy('node_modules/font-awesome/fonts/', 'public/assets/snap/fonts')
    .options({ processCssUrls: false})
    .version()
    ;