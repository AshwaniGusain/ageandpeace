let mix = require('laravel-mix');
let path = 'snap/form/resources/assets/';
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
    .js(path + 'js/components/CoordinatesInput.vue', 'public/assets/snap/js/components/form')
    .js(path + 'js/components/CreateEdit.vue', 'public/assets/snap/js/components/form')
    .js(path + 'js/components/CurrencyInput.vue', 'public/assets/snap/js/components/form')
    .js(path + 'js/components/DateInput.vue', 'public/assets/snap/js/components/form')
    .js(path + 'js/components/DateTimeInput.vue', 'public/assets/snap/js/components/form')
    .js(path + 'js/components/DependentInput.vue', 'public/assets/snap/js/components/form')
    .js(path + 'js/components/DualMultiSelectsInput.vue', 'public/assets/snap/js/components/form')
    .js(path + 'js/components/Form.vue', 'public/assets/snap/js/components/form')
    .js(path + 'js/components/FileInput.vue', 'public/assets/snap/js/components/form')
    .js(path + 'js/components/EditInlinePopover.vue', 'public/assets/snap/js/components/form')
    .js(path + 'js/components/KeyValueInput.vue', 'public/assets/snap/js/components/form')
    .js(path + 'js/components/ListInput.vue', 'public/assets/snap/js/components/form')
    .js(path + 'js/components/MirrorInput.vue', 'public/assets/snap/js/components/form')
    .js(path + 'js/components/NumberInput.vue', 'public/assets/snap/js/components/form')
    .js(path + 'js/components/RangeInput.vue', 'public/assets/snap/js/components/form')
    .js(path + 'js/components/RepeatableInput.vue', 'public/assets/snap/js/components/form')
    .js(path + 'js/components/Select2Input.vue', 'public/assets/snap/js/components/form')
    .js(path + 'js/components/TagInput.vue', 'public/assets/snap/js/components/form')
    .js(path + 'js/components/SlugInput.vue', 'public/assets/snap/js/components/form')
    .js(path + 'js/components/TableInput.vue', 'public/assets/snap/js/components/form')
    .js(path + 'js/components/TextInput.vue', 'public/assets/snap/js/components/form')
    .js(path + 'js/components/TemplateInput.vue', 'public/assets/snap/js/components/form')
    .js(path + 'js/components/TextareaInput.vue', 'public/assets/snap/js/components/form')
    .js(path + 'js/components/TimeInput.vue', 'public/assets/snap/js/components/form')
    .js(path + 'js/components/ToggleInput.vue', 'public/assets/snap/js/components/form')
    .js(path + 'js/components/WysiwygInput.vue', 'public/assets/snap/js/components/form')
    .copyDirectory(path + 'js/components/redactor', 'public/assets/snap/js/components/redactor')
    .copy(path + 'vendor/*', 'public/assets/snap/vendor')
    .version();