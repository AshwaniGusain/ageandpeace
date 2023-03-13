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
/******/ 	return __webpack_require__(__webpack_require__.s = 14);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./snap/form/resources/assets/js/components/CreateEdit.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./snap/form/resources/assets/js/components/CreateEdit.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

Vue.component('snap-create-edit', {
  // template : '<div style="display: inline-block;"><a :href="editUrl" @click.prevent="showModal(editUrl)" class="btn btn-primary edit-inline-button">Edit</a> <a :href="createUrl" @click.prevent="showModal(createUrl)" class="btn btn-primary add-inline-button">Create</a></div>',
  template: '<div class="row"><div class="col-md-9"><slot></slot></div><div class="col-md-3"><div class="btn-group mt-1"><a :href="editUrl" @click.prevent="showModal(editUrl, false)" class="btn btn-secondary btn-sm edit-inline-button border" :class="{ disabled: editStatus }"><i class="fa fa-pencil"></i></a> <a :href="createUrl" @click.prevent="showModal(createUrl, true)" class="btn btn-secondary btn-sm add-inline-button border"><i class="fa fa-plus"></i></a></div></div></div>',
  //template : '<div><slot></slot></div>',
  props: {
    'activeUrl': {
      type: String,
      required: false,
      "default": null
    },
    'moduleUrl': {
      type: String,
      required: true
    },
    'targetSelector': {
      type: String,
      required: false
    },
    'editIdSelector': {
      type: String,
      required: false,
      "default": '#id'
    },
    'urlParams': {
      type: String
    }
  },
  mounted: function mounted() {
    var self = this;
    this.$nextTick(function () {
      self.targetElem = self.getTargetElem();
      self.updateEditUrl();
      $(self.targetElem).on('change', function (e) {
        self.updateEditUrl();
        self.editStatus = self.checkIsEditDisable();
      });
      self.editStatus = self.checkIsEditDisable();
    });
    SNAP.event.$on('snap-modal-saved', function (e) {
      self.updateTargetValue();
    });
    SNAP.event.$on('snap-modal-after-hide', function (e) {
      self.active = false;
    });
  },
  data: function data() {
    return {
      createUrl: this.moduleUrl + '/create_inline',
      editUrl: '',
      active: false,
      editStatus: false
    };
  },
  methods: {
    getTargetElem: function getTargetElem() {
      if (this.targetSelector) {
        return $(this.$el).closest('form').find(this.targetSelector);
      }

      return $(this.$el).closest('.row').find('.col-md-9 select');
    },
    updateEditUrl: function updateEditUrl() {
      var val = this.targetElem.val();

      if (val && val.length) {
        if (typeof val !== 'string') {
          val = val[0];
        }

        this.editUrl = this.moduleUrl + '/' + val + '/edit_inline';
      } else {//alert('Select an item to edit.');
      }

      return this;
    },
    checkIsEditDisable: function checkIsEditDisable() {
      var val = this.targetElem.val();
      return val == null || val === '' || val.length !== 1 ? true : false;
    },
    updateTargetValue: function updateTargetValue() {
      if (!this.active) return;
      var input = this.targetElem.attr('name');

      if (input) {
        var self = this;
        var iframe = $('#snap-modal').find('iframe');
        var val = iframe[0].contentWindow.$(this.editIdSelector).val();
        var url = this.activeUrl + '/input/' + input;

        if (val) {
          url += '/' + val;
        }

        $.get(url, {
          value: $(this.targetElem).val()
        }, function (html) {
          if (html) {
            // Only get the select element to replace otherwise we mess with Vue too much
            var $html = $(document.createDocumentFragment());
            var $fragment = $(html);
            $html.append($fragment);
            var MyComponent = Vue.extend({
              template: $html.find('snap-create-edit').html()
            });
            var component = new MyComponent().$mount(); //let targetElem = self.getTargetElem();

            self.targetElem.parent().html(component.$el); //https://stackoverflow.com/questions/50241423/how-to-update-a-slot-in-vue-js
            //targetElem.closest('.form-input-col').html(component.$el);
            // self.$slots.default = component._vnode;
            // console.log(self.$slots.default);
            //self.$forceUpdate()
          }
        });
      }
    },
    showModal: function showModal(url, create) {
      if (!url) {
        // TODO...put in js language file
        alert('Select an item to edit first.');
      } else {
        var val = this.targetElem.val();

        if (!create && typeof val !== 'string' && val.length > 1) {
          // TODO...put in js language file
          alert('You must have a single item to edit.');
        } else {
          if (this.urlParams) {
            url += '?' + this.urlParams;
          }

          SNAP.ui.modal.load(url).show();
          this.active = true;
        }
      }
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

/***/ "./snap/form/resources/assets/js/components/CreateEdit.vue":
/*!*****************************************************************!*\
  !*** ./snap/form/resources/assets/js/components/CreateEdit.vue ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _CreateEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./CreateEdit.vue?vue&type=script&lang=js& */ "./snap/form/resources/assets/js/components/CreateEdit.vue?vue&type=script&lang=js&");
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _CreateEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _CreateEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");
var render, staticRenderFns




/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__["default"])(
  _CreateEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"],
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "snap/form/resources/assets/js/components/CreateEdit.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./snap/form/resources/assets/js/components/CreateEdit.vue?vue&type=script&lang=js&":
/*!******************************************************************************************!*\
  !*** ./snap/form/resources/assets/js/components/CreateEdit.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CreateEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./CreateEdit.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./snap/form/resources/assets/js/components/CreateEdit.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CreateEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CreateEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CreateEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CreateEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_CreateEdit_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ 14:
/*!***********************************************************************!*\
  !*** multi ./snap/form/resources/assets/js/components/CreateEdit.vue ***!
  \***********************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Library/WebServer/Documents/daylight/age_and_peace/repo2/snap/form/resources/assets/js/components/CreateEdit.vue */"./snap/form/resources/assets/js/components/CreateEdit.vue");


/***/ })

/******/ });