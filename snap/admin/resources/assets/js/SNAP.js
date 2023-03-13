
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


window._ = require('lodash');
window.Popper = require('popper.js').default;

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });


window.Vue = require('vue');
window.Vue.mixin({
    methods: {
        copyAttrs: function(attrs, $input){
            Object.entries(attrs).forEach(function([key,val]){
                $input.attr(key, val);
            });
        }
    }
});
window.SNAP = {};
const Util = require('./core/Util');
const Config = require('./core/Config');
const Debug = require('./core/Debug');
const Lang = require('./core/Lang');
const Errors = require('./core/Errors');
const Storage = require('./core/Storage');
const Cache = require('./core/Cache');
const Paths = require('./core/Paths');
const Event = require('./core/Event');
const Http = require('./core/Http');
const State = require('./core/State');
const Ui = require('./core/Ui');


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('snap-data-table', require('./components/DataTable.vue'));

/**************************************************/
/* APP                                            */
/**************************************************/
var App = {};

App.initialize = function(config){
    config = $.extend(true, this._config, config);

    $(this).trigger('before:initialize', [this, config]);

    // attach util object
    this.util = Util;

    // attach config object to App.config
    this.config = new Config(config);

    // attach debug object to App.debug
    this.debug = new Debug(config.debug.level, config.debug.clear);

    // attach lang object to App.lang
    this.lang = new Lang(config.lang);

    // attach errors object to App.errors
    this.errors = new Errors();

    // attach state object to App.state
    this.state = State;

    // attach storage object to App.storage
    this.storage = Storage;

    // attach cache object to App.cache
    this.cache = new Cache();

    // attach path object to App.path
    this.paths = new Paths(config.urlPaths);

    // attach a Vue.js object as an event bus
    this.event = new Event();

    // attach Http object
    this.http = Http;

    // attach a base Vue.js UI component (must be last)
    this.ui = new Ui({'el': this.config.get('el')});

};

App.log = function(msg) {
    App.debug.log(msg)
};

App.on = function(event, callback){
    var self = this;
    return $(this).on(event, function(e){
        callback.call(self, e);
    });
};

window.SNAP = App;
// window.SNAP.initialize(SNAP_CONFIG);
