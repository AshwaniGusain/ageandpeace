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
/******/ 	return __webpack_require__(__webpack_require__.s = 11);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./snap/admin/resources/assets/js/components/resource/ResourcePreview.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./snap/admin/resources/assets/js/components/resource/ResourcePreview.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

Vue.component('snap-resource-preview', {
  template: '<iframe v-if="isShown()" :src="previewUrl" style="border: 0px; width: 70%;" :style="{height: height}" name="module-preview" id="module-preview"></iframe>',
  timeout: null,
  props: {
    url: {
      type: String,
      required: true
    },
    loadingUrl: {
      type: String,
      required: false
    },
    slugInput: {
      type: String,
      required: true,
      "default": 'slug'
    },
    debounce: {
      type: Number,
      required: false,
      "default": 500
    }
  },
  created: function created() {
    this.xhr = null;
    SNAP.event.$on('data-changed', this.preview);
  },
  mounted: function mounted() {
    var self = this;
    this.displayLoader();
    this.$slug = $('[name="' + self.slugInput + '"]'); // $('#module-edit-btn-preview').on('click', function(e){
    // 	e.preventDefault();
    // 	self.preview();
    // })

    $('#btn-visit').on('click', function (e) {
      window.open(self.assembleUrl(false));
    });
    $('#btn-preview').on('click', function (e) {
      if (!self.$slug.val()) {
        self.alert();
      } else {
        self.toggle();
        self.preview();

        if (self.state === 'hidden') {
          $(this).text('Preview');
        } else {
          $(this).text('Close Preview');
        } // $('#sidebar').hide();
        // $('#panels-col').hide();
        // $('#form-col').attr('class', 'col-md-4');
        // $('#preview-col').attr('class', 'col-md-8');
        // self.preview();

      }
    });
    this.$nextTick(function () {
      var $form = $('#snap-form');
      $form.on('keyup', 'input, textarea', function () {
        // self.preview();
        SNAP.event.$emit('data-changed');
      });
      $form.on('change input', 'input, textarea, select, [contenteditable="true"]', function () {
        // self.preview();
        SNAP.event.$emit('data-changed');
      }); // self.preview();
    });
    $(window).on('resize', function () {
      self.height = self.getPreviewHeight();
    });
  },
  data: function data() {
    return {
      state: 'hidden',
      height: '500px',
      previewUrl: ''
    };
  },
  methods: {
    preview: function preview() {
      if (!this.isShown()) return;
      var self = this;
      clearTimeout(this.timeout);
      this.$slug.prop('disabled', true);
      var url = this.assembleUrl(true);
      this.displayLoader();
      this.timeout = setTimeout(function () {
        var target = 'module-preview';
        $form = $('#snap-form :input:not([name="_method"])');
        var data = $form.serialize(); // kill any current requests

        if (this.xhr) {
          this.xhr.abort();
        }

        this.xhr = $.post(url, data).done(function (html) {
          // https://stackoverflow.com/questions/5784638/replace-entire-content-of-iframe
          var iframe = $('#snap-resource-preview')[0];
          var doc = iframe.contentWindow.document;
          doc.open();
          doc.write(html);
          doc.close(); //$('#snap-resource-preview').contents().find('html').replace(html);
        });
      }, self.debounce);
      this.height = this.getPreviewHeight();
    },
    displayLoader: function displayLoader() {
      this.previewUrl = this.loadingUrl;
    },
    toggle: function toggle() {
      if (this.state === 'shown') {
        this.hide();
      } else {
        this.show();
      }
    },
    getPreviewHeight: function getPreviewHeight() {
      // var docHeight = $('#form-col').outerHeight() - $('#module-preview').offset().top;
      // var docHeight = $('#form-col').outerHeight();
      // var previewHeight = $('#snap-resource-preview').contents().outerHeight();
      var titleHeight = $('#snap-module-title').outerHeight(); // console.log(docHeight + " " + previewHeight)

      return window.innerHeight - titleHeight + 'px'; // return (previewHeight < docHeight) ? docHeight + 'px' : (previewHeight + 10) + 'px';
    },
    isShown: function isShown() {
      return this.state === 'shown';
    },
    show: function show() {
      //$('#snap-admin').addClass('snap-preview-mode');
      this.state = 'shown';
      $('.form-page .field-uri, .form-page .field-name').hide();
      SNAP.event.$emit('preview-mode-shown');
    },
    hide: function hide() {
      //$('#snap-admin').removeClass('snap-preview-mode');
      this.state = 'hidden';
      this.$slug.prop('disabled', false);
      $('.form-page .field-uri, .form-page .field-name').show();
      SNAP.event.$emit('preview-mode-hidden');
    },
    alert: function (_alert) {
      function alert() {
        return _alert.apply(this, arguments);
      }

      alert.toString = function () {
        return _alert.toString();
      };

      return alert;
    }(function () {
      alert('You must first enter in a value for ' + this.slugInput);
    }),
    assembleUrl: function assembleUrl(cacheBuster) {
      var prefix = this.$slug.closest('.input-group').find('.input-group-text').text();
      var slug = this.$slug.val();

      if (!slug) {
        self.alert();
        return;
      }

      var url = this.url + '/' + prefix + slug;

      if (cacheBuster) {
        url += '?c=' + new Date().getTime();
      }

      return url;
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

/***/ "./snap/admin/resources/assets/js/components/resource/ResourcePreview.vue":
/*!********************************************************************************!*\
  !*** ./snap/admin/resources/assets/js/components/resource/ResourcePreview.vue ***!
  \********************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ResourcePreview_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ResourcePreview.vue?vue&type=script&lang=js& */ "./snap/admin/resources/assets/js/components/resource/ResourcePreview.vue?vue&type=script&lang=js&");
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _ResourcePreview_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _ResourcePreview_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");
var render, staticRenderFns




/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__["default"])(
  _ResourcePreview_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"],
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "snap/admin/resources/assets/js/components/resource/ResourcePreview.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./snap/admin/resources/assets/js/components/resource/ResourcePreview.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************!*\
  !*** ./snap/admin/resources/assets/js/components/resource/ResourcePreview.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ResourcePreview_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../../node_modules/vue-loader/lib??vue-loader-options!./ResourcePreview.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./snap/admin/resources/assets/js/components/resource/ResourcePreview.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ResourcePreview_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ResourcePreview_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ResourcePreview_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ResourcePreview_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ResourcePreview_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ 11:
/*!**************************************************************************************!*\
  !*** multi ./snap/admin/resources/assets/js/components/resource/ResourcePreview.vue ***!
  \**************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Library/WebServer/Documents/daylight/age_and_peace/repo2/snap/admin/resources/assets/js/components/resource/ResourcePreview.vue */"./snap/admin/resources/assets/js/components/resource/ResourcePreview.vue");


/***/ })

/******/ });