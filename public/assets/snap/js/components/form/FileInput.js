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
/******/ 	return __webpack_require__(__webpack_require__.s = 21);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./snap/form/resources/assets/js/components/FileInput.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./snap/form/resources/assets/js/components/FileInput.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

Vue.component('snap-file-input', {
  template: '<div>\
					<label class="custom-file">\
						<input ref="fileinput" type="file" class="custom-file-input" :multiple="true ? multiple : null" :name="inputName" :id="id" @change="onFileChange">\
						<label class="custom-file-label" :for="id">Choose file</label>\
					</label>\
					<div v-if="files.length && preview">\
						<div class="row">\
							<div v-for="(file, index) in files" class="mr-3 ml-3 mb-3">\
								<div v-if="file.isImage">\
									<a :href="previewUrlLink(file)" target="_blank"><img :src="file.preview" v-bind:style="{ maxWidth: previewMaxWidth }" style="display: block;"></a>\
								</div>\
								<div class="mt-1">\
                                    <button @click="removeFile(index)" type="button" class="btn btn-sm btn-danger" aria-label="Remove"><i class="fa fa-trash"></i></button>\
                                    <small>{{ file.name }} ({{ file.friendlySize }})</small>\
                                </div>\
                                <input :name="inputName" type="hidden" :value="file.id">\
                            </div>\
						</div>\
					</div>\
					<div v-else>\
					    <input :name="inputName" type="hidden">\
					 </div>\
                     <input type="hidden" :name="name + \'_options\'" :value="optionsJSON">\
                </div>',
  props: {
    'name': {
      type: String,
      required: true,
      "default": ''
    },
    'id': {
      type: String,
      required: false
    },
    'type': {
      type: String,
      required: false
    },
    'previewMaxWidth': {
      required: false,
      "default": '200px'
    },
    'multiple': {
      type: Boolean,
      required: false,
      "default": false
    },
    'options': {
      type: Object,
      required: false
    },
    'value': {
      //type: Array,
      required: false
    },
    'preview': {
      type: Boolean,
      required: false,
      "default": true
    }
  },
  mounted: function mounted() {
    if (this.value) {
      // var files = [];
      // for (var n in this.value) {
      //     var file = this.value[n];
      //     files[n] = new File([""], file.name, {type: file.type, lastModified: file.last_modified});
      // }
      this.files = this.createFiles(this.value);
    }

    this.optionsJSON = typeof this.options != 'string' ? JSON.stringify(this.options) : this.options; //this.optionsJSON = this.options;
    // console.log(this.$get('previewMaxWidth'))
  },
  data: function data() {
    return {
      files: [],
      optionsJSON: {}
    };
  },
  computed: {
    'inputName': function inputName() {
      if (this.multiple) {
        return this.name + '[]';
      }

      return this.name;
    },
    'optionsValue': function optionsValue() {
      if (this.options) {
        return this.name + '[]';
      }

      return this.name;
    }
  },
  methods: {
    onFileChange: function onFileChange(e) {
      e.preventDefault();
      var files = e.target.files || e.dataTransfer.files;

      if (!files.length) {
        return;
      }

      this.files = this.createFiles(files);
    },
    createFiles: function createFiles(files) {
      var newFiles = [];

      for (var n in files) {
        var file = files[n];

        if (this.isImage(file)) {
          this.createImage(n, file);
          file.isImage = true;
        } else {
          file.isImage = false;
          file.preview = file.name;
        }

        file.friendlySize = this.humanFileSize(file.size);
        newFiles[n] = file;
      }

      return newFiles;
    },
    createImage: function createImage(n, file) {
      if (!file.preview) {
        var reader = new FileReader();
        var self = this;

        reader.onload = function (e) {
          var file = self.files[n];
          file.preview = e.target.result;
          self.files.splice(n, 1, file);
        };

        reader.readAsDataURL(file);
      }
    },
    removeFile: function removeFile(index) {
      this.files.splice(index, 1);
      $(this.$refs['fileinput']).val(''); // $fileInput.replaceWith($fileInput.val('').clone(true));
    },
    removeFiles: function removeFiles() {
      this.files = [];
      $(this.$refs['fileinput']).val(''); // $fileInput.replaceWith($fileInput.val('').clone(true));
    },
    isImage: function isImage(file) {
      return file.type == 'image/png' || file.type == 'image/jpeg' || file.type == 'image/gif';
    },
    humanFileSize: function humanFileSize(bytes, si) {
      var thresh = si ? 1000 : 1024;

      if (Math.abs(bytes) < thresh) {
        return bytes + ' B';
      }

      var units = si ? ['kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'] : ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
      var u = -1;

      do {
        bytes /= thresh;
        ++u;
      } while (Math.abs(bytes) >= thresh && u < units.length - 1);

      return bytes.toFixed(1) + ' ' + units[u];
    },
    previewUrlLink: function previewUrlLink(file) {
      if (file.preview_url) {
        return file.preview_url;
      } else {
        return file.preview;
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

/***/ "./snap/form/resources/assets/js/components/FileInput.vue":
/*!****************************************************************!*\
  !*** ./snap/form/resources/assets/js/components/FileInput.vue ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _FileInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./FileInput.vue?vue&type=script&lang=js& */ "./snap/form/resources/assets/js/components/FileInput.vue?vue&type=script&lang=js&");
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _FileInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _FileInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");
var render, staticRenderFns




/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__["default"])(
  _FileInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"],
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "snap/form/resources/assets/js/components/FileInput.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./snap/form/resources/assets/js/components/FileInput.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************!*\
  !*** ./snap/form/resources/assets/js/components/FileInput.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_FileInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./FileInput.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./snap/form/resources/assets/js/components/FileInput.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_FileInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_FileInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_FileInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_FileInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_FileInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ 21:
/*!**********************************************************************!*\
  !*** multi ./snap/form/resources/assets/js/components/FileInput.vue ***!
  \**********************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Library/WebServer/Documents/daylight/age_and_peace/repo2/snap/form/resources/assets/js/components/FileInput.vue */"./snap/form/resources/assets/js/components/FileInput.vue");


/***/ })

/******/ });