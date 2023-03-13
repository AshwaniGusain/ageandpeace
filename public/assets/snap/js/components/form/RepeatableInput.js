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
/******/ 	return __webpack_require__(__webpack_require__.s = 28);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./snap/form/resources/assets/js/components/RepeatableInput.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./snap/form/resources/assets/js/components/RepeatableInput.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

Vue.component('snap-repeatable-input', {
  props: {
    'name': {
      type: String,
      required: true,
      "default": ''
    },
    'depthInit': {
      type: Number,
      required: false,
      "default": 0
    },
    'url': {
      type: String,
      required: false,
      "default": ''
    },
    'sortable': {
      type: Boolean,
      required: false,
      "default": true
    },
    'min': {
      type: Number,
      required: false,
      "default": null
    },
    'max': {
      type: Number,
      required: false,
      "default": null
    },
    'warn': {
      type: String,
      required: false,
      "default": 'Are you sure you want to remove this item?'
    },
    'collapse': {
      type: Boolean,
      required: false,
      "default": false
    },
    'containerClass': {
      type: String,
      required: false,
      "default": 'repeatable'
    },
    'rowClass': {
      type: String,
      required: false,
      "default": 'repeatable-row'
    },
    'ghostClass': {
      type: String,
      required: false,
      "default": 'sort-ghost'
    },
    'draggableSelector': {
      type: String,
      required: false,
      "default": '.repeatable-row'
    },
    'handleSelector': {
      type: String,
      required: false,
      "default": '.grabber'
    },
    'removeable': {
      type: Boolean,
      required: false,
      "default": true
    }
  },
  mounted: function mounted() {
    var self = this;
    this.scope = this.name;
    this.depth = this.depthInit;
    this.rows = this.$children;
    this.$nextTick(function () {
      if (this.depth == 0) {
        self.updateInputIndexes();
      }
    });

    if (this.sortable) {
      // @TODO... put .repeatable-rows as a prop
      $(this.$el).find('> .repeatable-rows').sortable({
        helper: 'clone',
        handle: this.handleSelector,
        appendTo: 'body',
        start: function start(e, ui) {
          ui.item.startIndex = ui.item.index();
        },
        update: function update(e, ui) {
          var newIndex = ui.item.index();
          var oldIndex = ui.item.startIndex;
          self.reorder(oldIndex, newIndex);
        }
      });
    }

    this.checkMaxAndMin();
    this.$on('remove-row', function (row) {
      self.remove(row);
    });
  },
  // template: html,
  data: function data() {
    return {
      depth: 0,
      indexValue: 0,
      scope: '',
      removable: false,
      prefix: '',
      values: {},
      displayed: true,
      canAdd: true,
      canRemove: true
    };
  },
  computed: {
    index: {
      get: function get() {
        return this.indexValue;
      },
      set: function set(newValue) {
        var self = this;
        this.indexValue = newValue;
        this.$nextTick(function () {// self.updateInputIndexes();
        });
      }
    }
  },
  methods: {
    reorder: function reorder(oldIndex, newIndex) {
      this.rows.splice(newIndex, 0, this.rows.splice(oldIndex, 1)[0]);
      this.updateInputIndexes();
      this.$emit('repeatable-sorted', this);
      this.$emit('repeatable-updated', self);
    },
    updateInputIndexes: function updateInputIndexes() {
      var self = this;

      if (!self.prefix.length) {
        self.prefix = self.scope;
      }

      this.rows.forEach(function (row, i) {
        row.index = i; // First, we need to loop through all inputs and labels and assign a depth data attribute.
        // Nested RepeatableFields will simply overwrite their children's depths to the new depth values.

        $(row.$el).find(':input, label').each(function () {
          $(this).attr('data-input-depth', self.depth);
        }); // The depth property is crucial for grabbing only those
        // input elements at a specific depth to alter their names.

        $(row.$el).find(':input[data-input-depth=' + self.depth + '], label[data-input-depth=' + self.depth + ']').each(function (index) {
          self.updateInputAttributes(this, i);
        }); // If the row is a "nested" Repeatable input, then we
        // set the new prefix and update the names on the child
        // inputs to have the correct nested array indexes.

        var $nestedRepeatables = $(row.$el).find('.repeatable');

        if ($nestedRepeatables.length) {
          $nestedRepeatables.each(function (j) {
            // Can't use $refs because of them being dynamically mounted and,
            // if added via AJAX and mounted with add method, they won't
            // have a reference. Instead we grab the __vue__ reference
            // hidden on the DOM node... Let me know if you can come up
            // with a better way.
            // https://stackoverflow.com/questions/26915193/dom-element-to-corresponding-vue-js-component
            var repeatable = this.__vue__;
            var prefix = $(this).attr('data-prefix');
            repeatable.prefix = self.scope + '[' + i + '][' + repeatable.scope + ']';
            repeatable.updateInputIndexes();
          });
        }
      });
    },
    updateInputAttributes: function updateInputAttributes(elem, i) {
      var $elem = $(elem);

      if (!$elem.data('orig')) {
        if ($elem.is('label') && !$elem.data('orig')) {
          $elem.data('orig', this.extractOriginalInputName($elem.attr('for')));
        } else {
          $elem.data('orig', this.extractOriginalInputName($elem.attr('name')));
        }
      }

      var id = this.prefix.replace(/\[/g, '-').replace(/\]/g, '') + '-' + i + '-' + $elem.data('orig');

      if ($elem.is('label')) {
        $elem.attr('for', id);
      } else {
        $elem.attr('id', id);
        $elem.attr('name', this.prefix + '[' + i + '][' + $elem.data('orig') + ']');
      } // We need to re-trigger the checked state on inputs that are checked
      // because the browser will visually check the last one with the same name
      // and multiple rows will all have the same name initially.


      if ($elem.attr('checked')) {
        $elem.prop('checked', true);
      }
    },
    extractOriginalInputName: function extractOriginalInputName(name) {
      var lastBeginBracket = name.lastIndexOf('[') + 1;

      if (lastBeginBracket > 0) {
        var lastEndBracket = name.lastIndexOf(']');
        return name.substring(lastBeginBracket, lastEndBracket);
      }

      return name;
    },
    isNested: function isNested(row) {
      return !!$(row.$el).find('.' + this.containerClass).length;
    },
    checkMaxAndMin: function checkMaxAndMin() {
      this.checkMax();
      this.checkMin();
    },
    checkMax: function checkMax() {
      if (this.max) {
        this.canAdd = this.rows.length < this.max;
      }
    },
    checkMin: function checkMin() {
      if (this.rows.length <= 1) {
        this.removable = false;
      } else if (this.min) {
        this.removable = this.rows.length > this.min;
      } else {
        this.removable = true;
      }
    },
    getRepeatableInput: function getRepeatableInput(elem) {
      var input = $(elem).data('input');
      this.inputs.push(input);
      var $parent = $(elem).parents('.' + this.containerClass + ':first');

      if ($parent.length) {
        return this.getRepeatableInput($parent);
      } else {
        return this.inputs.reverse().join('.');
      }
    },
    add: function add(e) {
      e.preventDefault();
      var self = this;

      if (this.canAdd) {
        this.inputs = [];
        var input = this.getRepeatableInput(this.$el);
        var url = this.url + '/' + input;
        var params = $(this.$el).closest('form').find('[data-ajax-param]').serialize();
        $.get(url, params, function (html) {
          // $.get(this.url, params, function(html){
          $(self.$refs['repeatable-rows']).append('<div></div>');
          var repeatable = $(self.$refs['repeatable-rows']).children(':last')[0]; //html = '<snap-repeatable-row name="' + self.name + '" :depth-init="' + self.depth + '" ref="row" v-on:removed="remove" class="list-group-item repeatable-row" :data-depth="depth" inline-template>' + html + '</snap-repeatable-row>';

          var component = new RepeatableRow({
            el: repeatable,
            template: html,
            data: {
              prefix: self.prefix,
              depth: self.depth,
              indexValue: self.rows.length,
              scope: self.scope
            }
          });
          self.rows.push(component.$children[0]); // component.$mount();
          // $(self.$refs['repeatable-rows']).append(component.$el);
          // self.$nextTick(function(){

          self.updateInputIndexes();
          self.checkMaxAndMin();
          self.$emit('repeatable-added', self, self.rows.length);
          self.$emit('repeatable-updated', self); // })
        });
      }
    },
    remove: function remove(row) {
      //if (this.removable) {
      if (confirm(this.warn)) {
        var _self = this; //
        // console.log(index)


        this.rows.splice(row.index, 1);
        row.$destroy(); // must be after splice
        // console.log(this.rows)
        // this.$nextTick(function(){

        $(row.$el).remove();

        _self.updateInputIndexes();

        _self.checkMaxAndMin();

        _self.$emit('repeatable-removed', row.index, _self);

        _self.$emit('repeatable-updated', _self); // })

      } //}

    }
  }
});
var RepeatableRow = Vue.extend({
  mounted: function mounted() {
    var self = this;
    this.$nextTick(function () {
      self.values = self.getRowValues();
    });

    if (this.parentElem) {
      this.displayed = !this.parentVue.collapse;
    }
  },
  data: function data() {
    return {
      values: true,
      depth: 0,
      rowToggle: [],
      index: 0,
      scope: '',
      prefix: '',
      displayed: true
    };
  },
  computed: {
    // So ugly!!! But this seems to be the most reliable way to get a
    // reference to the $parent Vue object with items being AJAXed in.
    parentVue: function parentVue() {
      return this.parentElem.__vue__;
    },
    parentElem: function parentElem() {
      return $(this.$el).closest('.repeatable')[0];
    },
    num: function num() {
      return this.index + 1;
    }
  },
  methods: {
    toggleRowDisplay: function toggleRowDisplay() {
      this.displayed = !this.displayed;
    },
    isRowDisplayed: function isRowDisplayed() {
      return this.displayed !== 0;
    },
    getRowValues: function getRowValues() {
      var values = {};
      $(this.parentElem).find(':input').each(function (i) {
        var $elem = $(this);
        values[$elem.attr('name')] = $elem.val();
      });
      return values;
    },
    remove: function remove() {
      this.parentVue.$emit('remove-row', this); //this.parentVue.$emit('remove-row', this);
      // Not sure why I need to use parentVue...
      // Please let me know if you figure it out...
      // The console clearly shows that this is a SnapRepeatableRow
      // that is a child of a SnapRepeatableField
      //this.parentVue.remove(this);
    } // hasNested: function(){
    //     console.log(this.$parent.containerClass)
    //     return $(this.$el).find('.' + this.$parent.containerClass).length;
    // }

  }
});
Vue.component('snap-repeatable-row', RepeatableRow);

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

/***/ "./snap/form/resources/assets/js/components/RepeatableInput.vue":
/*!**********************************************************************!*\
  !*** ./snap/form/resources/assets/js/components/RepeatableInput.vue ***!
  \**********************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _RepeatableInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./RepeatableInput.vue?vue&type=script&lang=js& */ "./snap/form/resources/assets/js/components/RepeatableInput.vue?vue&type=script&lang=js&");
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _RepeatableInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _RepeatableInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");
var render, staticRenderFns




/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__["default"])(
  _RepeatableInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"],
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "snap/form/resources/assets/js/components/RepeatableInput.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./snap/form/resources/assets/js/components/RepeatableInput.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************!*\
  !*** ./snap/form/resources/assets/js/components/RepeatableInput.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RepeatableInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./RepeatableInput.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./snap/form/resources/assets/js/components/RepeatableInput.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RepeatableInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RepeatableInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RepeatableInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__) if(__WEBPACK_IMPORT_KEY__ !== 'default') (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RepeatableInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));
 /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_RepeatableInput_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0___default.a); 

/***/ }),

/***/ 28:
/*!****************************************************************************!*\
  !*** multi ./snap/form/resources/assets/js/components/RepeatableInput.vue ***!
  \****************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Library/WebServer/Documents/daylight/age_and_peace/repo2/snap/form/resources/assets/js/components/RepeatableInput.vue */"./snap/form/resources/assets/js/components/RepeatableInput.vue");


/***/ })

/******/ });