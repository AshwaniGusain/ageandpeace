/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 3);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./snap/admin/resources/assets/js/components/resource/DataTable.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./snap/admin/resources/assets/js/components/resource/DataTable.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

Vue.component('snap-data-table', {
  // template: '<div class="datatable-container"></div>',
  props: {
    'url': {
      type: String,
      required: false
    },
    'inline': {
      required: false,
      "default": false
    }
  },
  mounted: function mounted() {
    var self = this;
    var $elem = $(this.$el);
    this.ref = this; // this.ajaxUrl = $elem.find('.data-table-url').val();

    this.ajaxUrl = this.url; // Wrap it so we can get a parent and replace it with AJAX calls
    // $elem.wrap('<div class="datatable-container"></div>');
    // this.$parentElem = $elem.parent();

    $(document).on('click', 'th.sortable', function (e) {
      self.sortBy(this);
    });
    $(document).on('click', "td[class^='table-col']", function (e) {
      if ($(this).find('a').length === 0) {
        e.preventDefault();
        var actionsCol = $(this).parent().find('td.actions');

        if (actionsCol.length) {
          if (self.inline) {
            $('a:first', actionsCol[0]).trigger('click');
          } else {
            var firstLink = $('a:first', actionsCol[0]).attr('href');

            if (firstLink) {
              window.location = firstLink;
            }
          }
        }
      }
    }); // Using jQuery events was more reliable than using @click

    $(document).on('click', ".column-toggle", function (e) {
      e.stopImmediatePropagation();
      self.toggleColumnDisplay($(this).val());
    });
    $('#limit').on('change', function (e) {
      SNAP.event.$emit('submit-form');
    });
    SNAP.event.$on('datatable-refresh', function () {
      self.loadData({
        sort: self.sort
      });
    });
    this.$nextTick(function () {
      var hiddenColumns = SNAP.storage.getLocal(SNAP.ui.getStorageKey('hidden_columns'));

      if (hiddenColumns) {
        hiddenColumns.forEach(function (value) {
          $('.dropdown input[data-column="' + value + '"]', self.$el).click();
        });
      }
    });
  },
  data: function data() {
    return {
      id: null,
      order: 'asc',
      col: null,
      sort: null,
      columns: [],
      ref: null
    };
  },
  methods: {
    sortBy: function sortBy(th) {
      var self = this;
      var $th = $(th);
      var $elem = $(this.$el);
      this.col = $th.data('column');
      this.order = $th.hasClass('asc') ? 'desc' : 'asc';
      var o = this.order === 'desc' ? '-' : '';
      this.sort = o + this.col; // remove all active states

      $elem.find('.active').removeClass('active'); // set active state to only this column

      $th.addClass("active");
      var params = 'sort=' + this.sort + '&' + window.location.search.substring(1);
      SNAP.event.$emit('datatable-data-requested', self.el);
      this.loadData(params, function () {
        $th.removeClass('asc desc').addClass(self.order + ' active'); // Update pagination with sort parameter

        $('.pagination .page-item > a').each(function () {
          var search = window.location.search.substring(1);
          var params = JSON.parse('{"' + decodeURI(search).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g, '":"') + '"}');
          params.sort = self.sort;
          this.search = '?' + $.param(params);
        });
      });
    },
    setTHClasses: function setTHClasses(th, order) {
      $(th).removeClass("asc desc").addClass(order + ' active');
    },
    toggleColumnDisplay: function toggleColumnDisplay(column) {
      var selector = 'th[data-column="' + column + '"], .table-col-' + column;

      if ($(selector).is(':hidden')) {
        $(selector).show();
      } else {
        $(selector).hide();
      } // Persist


      var hidden = [];
      $('.column-toggle', this.$el).each(function (i) {
        if (!$(this).prop('checked')) {
          hidden.push($(this).data('column'));
        }
      });
      SNAP.storage.setLocal(SNAP.ui.getStorageKey('hidden_columns'), hidden);
    },
    loadData: function loadData(params, callback) {
      var self = this; //let $elem = $(this.$el);
      // @TODO Loading image
      // $elem.prepend('LOADING...');

      $.get(this.ajaxUrl, params, function (html) {
        SNAP.event.$emit('datatable-data-received', self.$el);
        $(self.$el).empty(); // This is to instantiate Vue.js components that may be used
        // in the table as formatters.

        var EmbeddedComponent = Vue.extend({
          template: html
        }); // Need to setup new reference here because it when mounted it gets lost

        self.ref = new EmbeddedComponent().$mount(self.ref.$el); //$(self.$el).html(html);

        self.show = true; // self.$forceUpdate();

        if (callback) callback();
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js":
/*!********************************************************************!*\
  !*** ./node_modules/vue-loader/lib/runtime/componentNormalizer.js ***!
  \********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "default", function() { return normalizeComponent; });
/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

function normalizeComponent (
  scriptExports,
  render,
  staticRenderFns,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier, /* server only */
  shadowMode /* vue-cli only */
) {
  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (render) {
    options.render = render
    options.staticRenderFns = staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = 'data-v-' + scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = shadowMode
      ? function () { injectStyles.call(this, this.$root.$options.shadowRoot) }
      : injectStyles
  }

  if (hook) {
    if (options.functional) {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functioal component in vue file
      var originalRender = options.render
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return originalRender(h, context)
      }
    } else {
      // inject component registration as beforeCreate hook
      var existing = options.beforeCreate
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    }
  }

  return {
    exports: scriptExports,
    options: options
  }
}


/***/ }),

/***/ "./snap/admin/resources/assets/js/components/resource/DataTable.vue":
/*!**************************************************************************!*\
  !*** ./snap/admin/resources/assets/js/components/resource/DataTable.vue ***!
  \**************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _DataTable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./DataTable.vue?vue&type=script&lang=js& */ "./snap/admin/resources/assets/js/components/resource/DataTable.vue?vue&type=script&lang=js&");
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _DataTable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _DataTable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");
var render, staticRenderFns




/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__["default"])(
  _DataTable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"],
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "snap/admin/resources/assets/js/components/resource/DataTable.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./snap/admin/resources/assets/js/components/resource/DataTable.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************!*\
  !*** ./snap/admin/resources/assets/js/components/resource/DataTable.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_DataTable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./DataTable.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./snap/admin/resources/assets/js/components/resource/DataTable.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_DataTable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_DataTable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_DataTable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_DataTable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_DataTable_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ 3:
/*!********************************************************************************!*\
  !*** multi ./snap/admin/resources/assets/js/components/resource/DataTable.vue ***!
  \********************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Library/WebServer/Documents/daylight/age_and_peace/repo2/snap/admin/resources/assets/js/components/resource/DataTable.vue */"./snap/admin/resources/assets/js/components/resource/DataTable.vue");


/***/ })

/******/ });