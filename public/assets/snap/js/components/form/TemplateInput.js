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
/******/ 	return __webpack_require__(__webpack_require__.s = 34);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./snap/form/resources/assets/js/components/TemplateInput.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./snap/form/resources/assets/js/components/TemplateInput.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

Vue.component('snap-template-input', {
  // template: '<div></div>',
  template: '<div>\
            <div class="form-group">\
                <select :name="name" class="form-control" v-model="template" :data-ajax-param="name" ref="selector">\
                    <option v-if="placeholder" value="">{{ placeholder}}</option>\
                    <option v-for="(label, val) in templates" :value="val" :selected="isSelected(val)">{{ label }}</option>\
                </select>\
                <input v-if="nested" type="hidden" :name="getTemplateFieldName()" v-model="template">\
            </div>\
            <div ref="fields" class="form-group form-group-fields">\
            <div v-if="loading" class="snap-loader"></div>\
            </div>\
        </div>',
  props: {
    'templateUrl': {
      type: String,
      required: true,
      "default": null
    },
    'templates': {
      // type: Object,
      required: true,
      "default": {}
    },
    'value': {},
    'placeholder': {
      type: String,
      "default": 'Select one'
    },
    'name': {
      type: String
    },
    'scopeKey': {
      type: String
    },
    'resourceId': {
      type: String,
      required: false,
      "default": 'id'
    },
    'scope': {
      type: String,
      required: false,
      "default": ''
    }
  },
  data: function data() {
    return {
      template: '',
      nested: false,
      fieldName: '',
      loading: false
    };
  },
  created: function created() {
    SNAP.event.$on('snap-template-changed', function (json, vue) {
      if (json.uriPrefix && $('#uri').length) {
        var prefix = json.uriPrefix ? '/' + json.uriPrefix + '/' : '/'; //$('#uri').closest('.input-group').find('.input-group-text').text(prefix);

        if (!$('#uri').val()) {
          $('#uri').val(prefix);
        }
      }
    });
  },
  mounted: function mounted() {
    var self = this;
    this.$nextTick(function () {
      self.template = self.value;
    });
    setTimeout(function () {
      self.fieldName = $(self.$refs['selector']).attr('name');
      self.nested = self.fieldName.indexOf('[') !== -1 ? true : false; // console.log($(self.$refs['selector']).attr('name'))
    }, 0);
  },
  watch: {
    'template': function template() {
      this.load();
    }
  },
  methods: {
    load: function load() {
      if (this.template) {
        var self = this;
        var url = this.templateUrl + '/' + this.template;

        if (this.resourceId && (value = $('#' + this.resourceId).val())) {
          url = url + '/' + value;
        }

        var params = '';
        params += 'scope=' + this.getScope(); // params += '&key=' + this.scopeKey;

        params += '&noajax=1&'; // For AjaxableTrait inputs like repeatable to display the non-ajax view initially.

        params += $(this.$el).closest('form').serialize();
        this.loading = true;
        $.ajax({
          'type': 'get',
          'url': url,
          'data': params,
          'dataType': 'json'
        }).done(function (json) {
          self.$nextTick(function () {
            var html = json.form;
            var EmbeddedComponent = Vue.extend({
              name: 'template-field',
              template: '<div class="template-field-wrapper-inner">' + html + '</div>'
            });
            $fields = $(self.$el).find('.form-group-fields:first');
            $fields.empty().append('<div class="template-field-wrapper-inner"></div>');
            $wrapper = $fields.find('.template-field-wrapper-inner:first')[0]; // $(self.$refs['fields']).empty().append('<div class="template-field-wrapper-inner"></div>');

            var component = new EmbeddedComponent().$mount($wrapper);
            SNAP.event.$emit('snap-template-changed', json, self);
            self.loading = false;
          });
        }); // $.getJSON(url, {}, function (json) {
        //     //var scripts = json.scripts;
        //     //self.injectScripts(scripts);
        //
        //     self.$nextTick(function(){
        //         var html = json.form;
        //         var EmbeddedComponent = Vue.extend({
        //             name: 'template-field',
        //             template: '<div class="template-field-wrapper-inner">' + html + '</div>'
        //         });
        //
        //         $fields = $(self.$el).find('.form-group-fields:first');
        //         $fields.empty().append('<div class="template-field-wrapper-inner"></div>');
        //         $wrapper = $fields.find('.template-field-wrapper-inner:first')[0];
        //         // $(self.$refs['fields']).empty().append('<div class="template-field-wrapper-inner"></div>');
        //
        //         var component = new EmbeddedComponent().$mount($wrapper);
        //
        //         SNAP.event.$emit('snap-template-changed', json, self);
        //     });
        // });
      } else {
        $(this.$refs['fields']).empty();
      }
    },
    isSelected: function isSelected(val) {
      return val == this.value;
    },
    getScope: function getScope() {
      return this.scope.length ? this.scope : $(this.$refs['selector']).attr('name');
    },
    getTemplateFieldName: function getTemplateFieldName() {
      return this.fieldName + '[__value__]';
    } // isNested: function(){
    //     var self = this;
    //     this.$nextTick(function(){
    //         console.log($(self.$refs['selector']).attr('name'));
    //         var name = (self.$refs['selector']).attr('name');
    //         name && name.indexOf('[') !== -1 ? true : false
    //     })
    //     // var name = (self.$refs['selector']).attr('name');
    //     // return name && name.indexOf('[') !== -1 ? true : false;
    //     //return (this.scopeKey.indexOf('.') !== -1) ? true : false;
    // }

    /*injectScripts: function(files){
         var used = this.getLoadedScripts();
        // Loop through the files and if they do not already exist in head, inject them.
        for (var n in files){
            var src = files[n];
             if (used.indexOf(src) === -1){
                var elem = document.createElement('script');
                elem.setAttribute('src', src);
                elem.setAttribute('type', 'text/javascript');
            }
            document.getElementsByTagName("head")[0].appendChild(elem);
        }
    },
     getLoadedScripts: function() {
        var used = [];
        var scripts = document.getElementsByTagName('scripts');
        if (scripts.length){
            for (var i = 0; i < scripts.length; i++){
                var tag = scripts[i];
                if (tag && tag.getAttribute("src")){
                    used.push(tag.getAttribute(src));
                }
            }
        }
         return used;
    }*/

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

/***/ "./snap/form/resources/assets/js/components/TemplateInput.vue":
/*!********************************************************************!*\
  !*** ./snap/form/resources/assets/js/components/TemplateInput.vue ***!
  \********************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _TemplateInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./TemplateInput.vue?vue&type=script&lang=js& */ "./snap/form/resources/assets/js/components/TemplateInput.vue?vue&type=script&lang=js&");
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _TemplateInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _TemplateInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");
var render, staticRenderFns




/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__["default"])(
  _TemplateInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"],
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "snap/form/resources/assets/js/components/TemplateInput.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./snap/form/resources/assets/js/components/TemplateInput.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************!*\
  !*** ./snap/form/resources/assets/js/components/TemplateInput.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_TemplateInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./TemplateInput.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./snap/form/resources/assets/js/components/TemplateInput.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_TemplateInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_TemplateInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_TemplateInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_TemplateInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_TemplateInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ 34:
/*!**************************************************************************!*\
  !*** multi ./snap/form/resources/assets/js/components/TemplateInput.vue ***!
  \**************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Library/WebServer/Documents/daylight/age_and_peace/repo2/snap/form/resources/assets/js/components/TemplateInput.vue */"./snap/form/resources/assets/js/components/TemplateInput.vue");


/***/ })

/******/ });