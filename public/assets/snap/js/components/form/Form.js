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
/******/ 	return __webpack_require__(__webpack_require__.s = 20);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./snap/form/resources/assets/js/components/Form.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./snap/form/resources/assets/js/components/Form.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

Vue.component('snap-form', {
  props: {},
  mounted: function mounted() {
    var self = this;
    this.$nextTick(function () {
      var savedTab = SNAP.storage.getLocal(SNAP.ui.getStorageKey('tabs'));

      if (savedTab) {
        $('.nav .nav-link[href="' + savedTab + '"]:first').click();
      }
    });
    $('.nav .nav-link', this.$el).on('shown.bs.tab', function (e) {
      var activeTab = $(e.target).attr('href');
      SNAP.storage.setLocal(SNAP.ui.getStorageKey('tabs'), activeTab);
    });
    $('#btn-save, #btn-save-exit, #btn-save-create, #btn-save-close').on('click', function (e) {
      self.removeCheckSave();
    });
    $('#btn-save-exit').on('click', function (e) {
      self.changeRedirect('index');
    });
    $('#btn-save-create').on('click', function (e) {
      self.changeRedirect('create');
    });
    $('#btn-untrash').on('click', function (e) {
      e.preventDefault();
      var action = $(this).attr('href');
      self.changeAction(action);
      self.submit();
    });
    $('#btn-close').on('click', function (e) {
      e.preventDefault();
      SNAP.event.$emit('modal-close');
    });
    $('[data-toggle="tooltip"]').tooltip();
    SNAP.event.$on('snap-template-changed', function (json, comp) {
      $('[data-toggle="tooltip"]', comp.$el).tooltip();
    });
    this.initializeCheckSave();
    SNAP.event.$on('removeCheckSave', function () {
      self.removeCheckSave();
    });
  },
  data: function data() {
    return {};
  },
  methods: {
    changeRedirect: function changeRedirect(to) {
      $('#__redirect__').val(to);
    },
    changeAction: function changeAction(action) {
      $(this.$refs['form']).attr('action', action);
    },
    submit: function submit() {
      $(this.$refs['form']).submit();
    },
    initializeCheckSave: function initializeCheckSave() {
      var self = this;
      var inputs = 'select,input,textarea'; // var inputs = 'select,input,textarea,[contenteditable="true"]';
      // var inputs = 'select,input,textarea,[contenteditable="true"]';
      // $(document).on('focus', inputs, function() {
      //     if (!$(this).data('checksaveOrigValue')) {
      //         if ($(this).is('[contenteditable="true"]')) {
      //             $(this).data('checksaveOrigValue', $(this).html());
      //             console.log($(this).html())
      //         } else {
      //             $(this).data('checksaveOrigValue', $(this).val());
      //         }
      //     }
      // });
      // window.onbeforeunload = function(){
      //     var msg = '';
      //     $(inputs).each(function(i){
      //         // if ($(this).data('checksaveOrigValue') &&
      //         //     (($(this).is('[contenteditable="true"]') && $(this).data('checksaveOrigValue') != $(this).html())
      //         //         || ($(this).not('[contenteditable="true"]') && $(this).data('checksaveOrigValue') != $(this).val()))) {
      //         //     console.log($(this).data('checksaveOrigValue'))
      //         //
      //         //     msg = 'Your unsaved data will be lost.';
      //         // }
      //         if ($(this).data('checksaveOrigValue')){
      //             if ($(this).is('[contenteditable="true"]') && $(this).data('checksaveOrigValue') != $(this).html()) {
      //                 msg = 'Your unsaved data will be lost.';
      //                 console.log($(this).data('checksaveOrigValue') + ':'+$(this).html())
      //             }
      //
      //             if ($(this).not('[contenteditable="true"]') && $(this).data('checksaveOrigValue') != $(this).val()) {
      //                 console.log($(this)[0])
      //                 // console.log($(this).data('checksaveOrigValue') + ':'+$(this).val())
      //                 msg = 'Your unsaved data will be lost.';
      //             }
      //         }
      //     });
      //
      //     if (msg.length){
      //         return msg;
      //     }
      //     //if (needToConfirm) {
      //         // Put your custom message here
      //         //return "Your unsaved data will be lost.";
      //     //}
      // };

      this.$nextTick(function () {
        // We are going to wait 1 second to allow fo AJAX loading
        setTimeout(function () {
          var $elems = $('input:text, input:checked, textarea, select', self.$el); // get current values

          $elems.each(function (i) {
            $(this).data('checksaveOrigValue', $(this).val());
          });

          window.onbeforeunload = function (e) {
            var msg = '';
            var changedMsg = 'You are about to lose unsaved data. Do you want to continue?';
            $elems.each(function (i) {
              //console.log(jQuery(this).attr('name') + " ------ " + escape(jQuery(this).data('checksaveStartValue').toString())  + " ------"  + escape(jQuery(this).val().toString()) )
              if ($(this).data('checksaveOrigValue') != undefined && $(this).data('checksaveOrigValue').toString() != $(this).val().toString()) {
                msg = changedMsg;
                return changedMsg;
              }
            });

            if (msg.length) {
              return msg;
            }
          };
        }, 1000);
      });
    },
    removeCheckSave: function removeCheckSave() {
      window.onbeforeunload = null;
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

/***/ "./snap/form/resources/assets/js/components/Form.vue":
/*!***********************************************************!*\
  !*** ./snap/form/resources/assets/js/components/Form.vue ***!
  \***********************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Form_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Form.vue?vue&type=script&lang=js& */ "./snap/form/resources/assets/js/components/Form.vue?vue&type=script&lang=js&");
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _Form_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _Form_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");
var render, staticRenderFns




/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__["default"])(
  _Form_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"],
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "snap/form/resources/assets/js/components/Form.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./snap/form/resources/assets/js/components/Form.vue?vue&type=script&lang=js&":
/*!************************************************************************************!*\
  !*** ./snap/form/resources/assets/js/components/Form.vue?vue&type=script&lang=js& ***!
  \************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Form_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./Form.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./snap/form/resources/assets/js/components/Form.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Form_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Form_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Form_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Form_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Form_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ 20:
/*!*****************************************************************!*\
  !*** multi ./snap/form/resources/assets/js/components/Form.vue ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Library/WebServer/Documents/daylight/age_and_peace/repo2/snap/form/resources/assets/js/components/Form.vue */"./snap/form/resources/assets/js/components/Form.vue");


/***/ })

/******/ });