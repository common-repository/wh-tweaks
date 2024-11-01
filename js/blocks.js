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
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./blocks/js/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./blocks/js/index.js":
/*!****************************!*\
  !*** ./blocks/js/index.js ***!
  \****************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _scss_editor_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../scss/editor.scss */ "./blocks/scss/editor.scss");
/* harmony import */ var _scss_editor_scss__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_scss_editor_scss__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _scss_style_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../scss/style.scss */ "./blocks/scss/style.scss");
/* harmony import */ var _scss_style_scss__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_scss_style_scss__WEBPACK_IMPORTED_MODULE_1__);


var _wp$editor = wp.editor,
    InnerBlocks = _wp$editor.InnerBlocks,
    InspectorControls = _wp$editor.InspectorControls;
var registerBlockType = wp.blocks.registerBlockType;
var Fragment = wp.element.Fragment;
var _wp$components = wp.components,
    ToggleControl = _wp$components.ToggleControl,
    TextControl = _wp$components.TextControl,
    PanelBody = _wp$components.PanelBody;
var applyFilters = wp.hooks.applyFilters;
wp.blocks.registerBlockType('wh-tweaks/container-block', {
  title: 'WH Tweaks Container',
  description: 'A block to wrap muliple blocks in a container',
  icon: 'archive',
  category: 'layout',
  keywords: ['wrapper', 'wh', 'container'],
  attributes: {
    innerContainer: {
      type: 'boolean',
      // changing the default may cause the existing blocks to break, careful!
      default: applyFilters('wh-tweaks.container-block.innerContainer.default', false)
    },
    innerContainerClass: {
      type: 'string',
      // changing the default may cause the existing blocks to break, careful!
      default: applyFilters('wh-tweaks.container-block.innerContainerClass.default', '')
    }
  },
  supports: {
    align: true
  },
  edit: function edit(_ref) {
    var insertBlocksAfter = _ref.insertBlocksAfter,
        className = _ref.className,
        attributes = _ref.attributes,
        setAttributes = _ref.setAttributes,
        setState = _ref.setState;
    var innerContainerClassControl = '';

    if (attributes.innerContainer) {
      innerContainerClassControl = wp.element.createElement(TextControl, {
        label: "Inner Container Class",
        value: attributes.innerContainerClass,
        onChange: function onChange(innerContainerClass) {
          return setAttributes({
            innerContainerClass: innerContainerClass
          });
        }
      });
    } // WP Bug with styles and inner blocks, workaround
    // https://github.com/WordPress/gutenberg/issues/9897


    if (typeof insertBlocksAfter !== 'undefined') {
      return wp.element.createElement(Fragment, null, wp.element.createElement("div", {
        className: className
      }, wp.element.createElement(InnerBlocks, null)), wp.element.createElement(InspectorControls, null, wp.element.createElement(PanelBody, null, wp.element.createElement(ToggleControl, {
        label: 'Inner Container',
        checked: attributes.innerContainer,
        help: 'Add an additional container within the Container block',
        onChange: function onChange(innerContainer) {
          setAttributes({
            innerContainer: innerContainer
          });
        }
      }), innerContainerClassControl)));
    } else {
      return wp.element.createElement("div", null);
    }
  },
  save: function save(props) {
    if (props.attributes.innerContainer) {
      return wp.element.createElement("div", null, wp.element.createElement("div", {
        "class": 'wh-tweaks-inner-container ' + props.attributes.innerContainerClass
      }, wp.element.createElement(InnerBlocks.Content, null)));
    } else {
      return wp.element.createElement("div", null, wp.element.createElement(InnerBlocks.Content, null));
    }
  }
});

/***/ }),

/***/ "./blocks/scss/editor.scss":
/*!*********************************!*\
  !*** ./blocks/scss/editor.scss ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),

/***/ "./blocks/scss/style.scss":
/*!********************************!*\
  !*** ./blocks/scss/style.scss ***!
  \********************************/
/*! no static exports found */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })

/******/ });
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vd2VicGFjay9ib290c3RyYXAiLCJ3ZWJwYWNrOi8vLy4vYmxvY2tzL2pzL2luZGV4LmpzIiwid2VicGFjazovLy8uL2Jsb2Nrcy9zY3NzL2VkaXRvci5zY3NzIiwid2VicGFjazovLy8uL2Jsb2Nrcy9zY3NzL3N0eWxlLnNjc3MiXSwibmFtZXMiOlsid3AiLCJlZGl0b3IiLCJJbm5lckJsb2NrcyIsIkluc3BlY3RvckNvbnRyb2xzIiwicmVnaXN0ZXJCbG9ja1R5cGUiLCJibG9ja3MiLCJGcmFnbWVudCIsImVsZW1lbnQiLCJjb21wb25lbnRzIiwiVG9nZ2xlQ29udHJvbCIsIlRleHRDb250cm9sIiwiUGFuZWxCb2R5IiwiYXBwbHlGaWx0ZXJzIiwiaG9va3MiLCJ0aXRsZSIsImRlc2NyaXB0aW9uIiwiaWNvbiIsImNhdGVnb3J5Iiwia2V5d29yZHMiLCJhdHRyaWJ1dGVzIiwiaW5uZXJDb250YWluZXIiLCJ0eXBlIiwiZGVmYXVsdCIsImlubmVyQ29udGFpbmVyQ2xhc3MiLCJzdXBwb3J0cyIsImFsaWduIiwiZWRpdCIsImluc2VydEJsb2Nrc0FmdGVyIiwiY2xhc3NOYW1lIiwic2V0QXR0cmlidXRlcyIsInNldFN0YXRlIiwiaW5uZXJDb250YWluZXJDbGFzc0NvbnRyb2wiLCJzYXZlIiwicHJvcHMiXSwibWFwcGluZ3MiOiI7QUFBQTtBQUNBOztBQUVBO0FBQ0E7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7O0FBRUE7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTs7O0FBR0E7QUFDQTs7QUFFQTtBQUNBOztBQUVBO0FBQ0E7QUFDQTtBQUNBLGtEQUEwQyxnQ0FBZ0M7QUFDMUU7QUFDQTs7QUFFQTtBQUNBO0FBQ0E7QUFDQSxnRUFBd0Qsa0JBQWtCO0FBQzFFO0FBQ0EseURBQWlELGNBQWM7QUFDL0Q7O0FBRUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtB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UyxxQ0FBVDtBQUNEO0FBQ0YsR0ExRHFEO0FBMkR0REMsTUEzRHNELGdCQTJEaERDLEtBM0RnRCxFQTJEeEM7QUFDWixRQUFLQSxLQUFLLENBQUNkLFVBQU4sQ0FBaUJDLGNBQXRCLEVBQXVDO0FBQ3JDLGFBQ0Usc0NBQ0U7QUFBSyxpQkFBTywrQkFBK0JhLEtBQUssQ0FBQ2QsVUFBTixDQUFpQkk7QUFBNUQsU0FDRSx5QkFBQyxXQUFELENBQWEsT0FBYixPQURGLENBREYsQ0FERjtBQU9ELEtBUkQsTUFTSztBQUNILGFBQ0Usc0NBQ0UseUJBQUMsV0FBRCxDQUFhLE9BQWIsT0FERixDQURGO0FBS0Q7QUFDRjtBQTVFcUQsQ0FBMUQsRTs7Ozs7Ozs7Ozs7QUNUQSx5Qzs7Ozs7Ozs7Ozs7QUNBQSx5QyIsImZpbGUiOiJibG9ja3MuanMiLCJzb3VyY2VzQ29udGVudCI6WyIgXHQvLyBUaGUgbW9kdWxlIGNhY2hlXG4gXHR2YXIgaW5zdGFsbGVkTW9kdWxlcyA9IHt9O1xuXG4gXHQvLyBUaGUgcmVxdWlyZSBmdW5jdGlvblxuIFx0ZnVuY3Rpb24gX193ZWJwYWNrX3JlcXVpcmVfXyhtb2R1bGVJZCkge1xuXG4gXHRcdC8vIENoZWNrIGlmIG1vZHVsZSBpcyBpbiBjYWNoZVxuIFx0XHRpZihpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSkge1xuIFx0XHRcdHJldHVybiBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXS5leHBvcnRzO1xuIFx0XHR9XG4gXHRcdC8vIENyZWF0ZSBhIG5ldyBtb2R1bGUgKGFuZCBwdXQgaXQgaW50byB0aGUgY2FjaGUpXG4gXHRcdHZhciBtb2R1bGUgPSBpbnN0YWxsZWRNb2R1bGVzW21vZHVsZUlkXSA9IHtcbiBcdFx0XHRpOiBtb2R1bGVJZCxcbiBcdFx0XHRsOiBmYWxzZSxcbiBcdFx0XHRleHBvcnRzOiB7fVxuIFx0XHR9O1xuXG4gXHRcdC8vIEV4ZWN1dGUgdGhlIG1vZHVsZSBmdW5jdGlvblxuIFx0XHRtb2R1bGVzW21vZHVsZUlkXS5jYWxsKG1vZHVsZS5leHBvcnRzLCBtb2R1bGUsIG1vZHVsZS5leHBvcnRzLCBfX3dlYnBhY2tfcmVxdWlyZV9fKTtcblxuIFx0XHQvLyBGbGFnIHRoZSBtb2R1bGUgYXMgbG9hZGVkXG4gXHRcdG1vZHVsZS5sID0gdHJ1ZTtcblxuIFx0XHQvLyBSZXR1cm4gdGhlIGV4cG9ydHMgb2YgdGhlIG1vZHVsZVxuIFx0XHRyZXR1cm4gbW9kdWxlLmV4cG9ydHM7XG4gXHR9XG5cblxuIFx0Ly8gZXhwb3NlIHRoZSBtb2R1bGVzIG9iamVjdCAoX193ZWJwYWNrX21vZHVsZXNfXylcbiBcdF9fd2VicGFja19yZXF1aXJlX18ubSA9IG1vZHVsZXM7XG5cbiBcdC8vIGV4cG9zZSB0aGUgbW9kdWxlIGNhY2hlXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLmMgPSBpbnN0YWxsZWRNb2R1bGVzO1xuXG4gXHQvLyBkZWZpbmUgZ2V0dGVyIGZ1bmN0aW9uIGZvciBoYXJtb255IGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uZCA9IGZ1bmN0aW9uKGV4cG9ydHMsIG5hbWUsIGdldHRlcikge1xuIFx0XHRpZighX193ZWJwYWNrX3JlcXVpcmVfXy5vKGV4cG9ydHMsIG5hbWUpKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIG5hbWUsIHsgZW51bWVyYWJsZTogdHJ1ZSwgZ2V0OiBnZXR0ZXIgfSk7XG4gXHRcdH1cbiBcdH07XG5cbiBcdC8vIGRlZmluZSBfX2VzTW9kdWxlIG9uIGV4cG9ydHNcbiBcdF9fd2VicGFja19yZXF1aXJlX18uciA9IGZ1bmN0aW9uKGV4cG9ydHMpIHtcbiBcdFx0aWYodHlwZW9mIFN5bWJvbCAhPT0gJ3VuZGVmaW5lZCcgJiYgU3ltYm9sLnRvU3RyaW5nVGFnKSB7XG4gXHRcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsIFN5bWJvbC50b1N0cmluZ1RhZywgeyB2YWx1ZTogJ01vZHVsZScgfSk7XG4gXHRcdH1cbiBcdFx0T2JqZWN0LmRlZmluZVByb3BlcnR5KGV4cG9ydHMsICdfX2VzTW9kdWxlJywgeyB2YWx1ZTogdHJ1ZSB9KTtcbiBcdH07XG5cbiBcdC8vIGNyZWF0ZSBhIGZha2UgbmFtZXNwYWNlIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDE6IHZhbHVlIGlzIGEgbW9kdWxlIGlkLCByZXF1aXJlIGl0XG4gXHQvLyBtb2RlICYgMjogbWVyZ2UgYWxsIHByb3BlcnRpZXMgb2YgdmFsdWUgaW50byB0aGUgbnNcbiBcdC8vIG1vZGUgJiA0OiByZXR1cm4gdmFsdWUgd2hlbiBhbHJlYWR5IG5zIG9iamVjdFxuIFx0Ly8gbW9kZSAmIDh8MTogYmVoYXZlIGxpa2UgcmVxdWlyZVxuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy50ID0gZnVuY3Rpb24odmFsdWUsIG1vZGUpIHtcbiBcdFx0aWYobW9kZSAmIDEpIHZhbHVlID0gX193ZWJwYWNrX3JlcXVpcmVfXyh2YWx1ZSk7XG4gXHRcdGlmKG1vZGUgJiA4KSByZXR1cm4gdmFsdWU7XG4gXHRcdGlmKChtb2RlICYgNCkgJiYgdHlwZW9mIHZhbHVlID09PSAnb2JqZWN0JyAmJiB2YWx1ZSAmJiB2YWx1ZS5fX2VzTW9kdWxlKSByZXR1cm4gdmFsdWU7XG4gXHRcdHZhciBucyA9IE9iamVjdC5jcmVhdGUobnVsbCk7XG4gXHRcdF9fd2VicGFja19yZXF1aXJlX18ucihucyk7XG4gXHRcdE9iamVjdC5kZWZpbmVQcm9wZXJ0eShucywgJ2RlZmF1bHQnLCB7IGVudW1lcmFibGU6IHRydWUsIHZhbHVlOiB2YWx1ZSB9KTtcbiBcdFx0aWYobW9kZSAmIDIgJiYgdHlwZW9mIHZhbHVlICE9ICdzdHJpbmcnKSBmb3IodmFyIGtleSBpbiB2YWx1ZSkgX193ZWJwYWNrX3JlcXVpcmVfXy5kKG5zLCBrZXksIGZ1bmN0aW9uKGtleSkgeyByZXR1cm4gdmFsdWVba2V5XTsgfS5iaW5kKG51bGwsIGtleSkpO1xuIFx0XHRyZXR1cm4gbnM7XG4gXHR9O1xuXG4gXHQvLyBnZXREZWZhdWx0RXhwb3J0IGZ1bmN0aW9uIGZvciBjb21wYXRpYmlsaXR5IHdpdGggbm9uLWhhcm1vbnkgbW9kdWxlc1xuIFx0X193ZWJwYWNrX3JlcXVpcmVfXy5uID0gZnVuY3Rpb24obW9kdWxlKSB7XG4gXHRcdHZhciBnZXR0ZXIgPSBtb2R1bGUgJiYgbW9kdWxlLl9fZXNNb2R1bGUgP1xuIFx0XHRcdGZ1bmN0aW9uIGdldERlZmF1bHQoKSB7IHJldHVybiBtb2R1bGVbJ2RlZmF1bHQnXTsgfSA6XG4gXHRcdFx0ZnVuY3Rpb24gZ2V0TW9kdWxlRXhwb3J0cygpIHsgcmV0dXJuIG1vZHVsZTsgfTtcbiBcdFx0X193ZWJwYWNrX3JlcXVpcmVfXy5kKGdldHRlciwgJ2EnLCBnZXR0ZXIpO1xuIFx0XHRyZXR1cm4gZ2V0dGVyO1xuIFx0fTtcblxuIFx0Ly8gT2JqZWN0LnByb3RvdHlwZS5oYXNPd25Qcm9wZXJ0eS5jYWxsXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLm8gPSBmdW5jdGlvbihvYmplY3QsIHByb3BlcnR5KSB7IHJldHVybiBPYmplY3QucHJvdG90eXBlLmhhc093blByb3BlcnR5LmNhbGwob2JqZWN0LCBwcm9wZXJ0eSk7IH07XG5cbiBcdC8vIF9fd2VicGFja19wdWJsaWNfcGF0aF9fXG4gXHRfX3dlYnBhY2tfcmVxdWlyZV9fLnAgPSBcIlwiO1xuXG5cbiBcdC8vIExvYWQgZW50cnkgbW9kdWxlIGFuZCByZXR1cm4gZXhwb3J0c1xuIFx0cmV0dXJuIF9fd2VicGFja19yZXF1aXJlX18oX193ZWJwYWNrX3JlcXVpcmVfXy5zID0gXCIuL2Jsb2Nrcy9qcy9pbmRleC5qc1wiKTtcbiIsImltcG9ydCAnLi4vc2Nzcy9lZGl0b3Iuc2Nzcyc7XG5pbXBvcnQgJy4uL3Njc3Mvc3R5bGUuc2Nzcyc7XG5cbmNvbnN0IHsgSW5uZXJCbG9ja3MsIEluc3BlY3RvckNvbnRyb2xzIH0gPSB3cC5lZGl0b3I7XG5jb25zdCB7IHJlZ2lzdGVyQmxvY2tUeXBlIH0gPSB3cC5ibG9ja3M7XG5jb25zdCB7IEZyYWdtZW50IH0gPSB3cC5lbGVtZW50O1xuY29uc3QgeyBUb2dnbGVDb250cm9sLCBUZXh0Q29udHJvbCwgUGFuZWxCb2R5IH0gPSB3cC5jb21wb25lbnRzO1xuY29uc3QgeyBhcHBseUZpbHRlcnMgfSA9IHdwLmhvb2tzO1xuXG53cC5ibG9ja3MucmVnaXN0ZXJCbG9ja1R5cGUoICd3aC10d2Vha3MvY29udGFpbmVyLWJsb2NrJywge1xuICAgIHRpdGxlOiAnV0ggVHdlYWtzIENvbnRhaW5lcicsXG4gICAgZGVzY3JpcHRpb246ICdBIGJsb2NrIHRvIHdyYXAgbXVsaXBsZSBibG9ja3MgaW4gYSBjb250YWluZXInLFxuICAgIGljb246ICdhcmNoaXZlJyxcbiAgICBjYXRlZ29yeTogJ2xheW91dCcsXG4gICAga2V5d29yZHM6IFsgJ3dyYXBwZXInLCAnd2gnLCAnY29udGFpbmVyJyBdLFxuICAgIGF0dHJpYnV0ZXM6IHtcbiAgICAgIGlubmVyQ29udGFpbmVyOiB7XG4gICAgICAgIHR5cGU6ICdib29sZWFuJyxcbiAgICAgICAgLy8gY2hhbmdpbmcgdGhlIGRlZmF1bHQgbWF5IGNhdXNlIHRoZSBleGlzdGluZyBibG9ja3MgdG8gYnJlYWssIGNhcmVmdWwhXG4gICAgICAgIGRlZmF1bHQ6IGFwcGx5RmlsdGVycyggJ3doLXR3ZWFrcy5jb250YWluZXItYmxvY2suaW5uZXJDb250YWluZXIuZGVmYXVsdCcsIGZhbHNlICksXG4gICAgICB9LFxuICAgICAgaW5uZXJDb250YWluZXJDbGFzczoge1xuICAgICAgICB0eXBlOiAnc3RyaW5nJyxcbiAgICAgICAgLy8gY2hhbmdpbmcgdGhlIGRlZmF1bHQgbWF5IGNhdXNlIHRoZSBleGlzdGluZyBibG9ja3MgdG8gYnJlYWssIGNhcmVmdWwhXG4gICAgICAgIGRlZmF1bHQ6IGFwcGx5RmlsdGVycyggJ3doLXR3ZWFrcy5jb250YWluZXItYmxvY2suaW5uZXJDb250YWluZXJDbGFzcy5kZWZhdWx0JywgJycgKVxuICAgICAgfVxuICAgIH0sXG4gICAgc3VwcG9ydHM6IHtcbiAgICAgIGFsaWduOiB0cnVlXG4gICAgfSxcbiAgICBlZGl0KHsgaW5zZXJ0QmxvY2tzQWZ0ZXIsIGNsYXNzTmFtZSwgYXR0cmlidXRlcywgc2V0QXR0cmlidXRlcywgc2V0U3RhdGUgfSkge1xuICAgICAgbGV0IGlubmVyQ29udGFpbmVyQ2xhc3NDb250cm9sID0gJyc7XG4gICAgICBpZiAoIGF0dHJpYnV0ZXMuaW5uZXJDb250YWluZXIgKSB7XG4gICAgICAgIGlubmVyQ29udGFpbmVyQ2xhc3NDb250cm9sID0gKFxuICAgICAgICAgIDxUZXh0Q29udHJvbFxuICAgICAgICAgICAgICBsYWJlbD1cIklubmVyIENvbnRhaW5lciBDbGFzc1wiXG4gICAgICAgICAgICAgIHZhbHVlPXsgYXR0cmlidXRlcy5pbm5lckNvbnRhaW5lckNsYXNzIH1cbiAgICAgICAgICAgICAgb25DaGFuZ2U9eyAoIGlubmVyQ29udGFpbmVyQ2xhc3MgKSA9PiBzZXRBdHRyaWJ1dGVzKCB7IGlubmVyQ29udGFpbmVyQ2xhc3MgfSApIH1cbiAgICAgICAgICAvPlxuICAgICAgICApO1xuICAgICAgfVxuICAgICAgLy8gV1AgQnVnIHdpdGggc3R5bGVzIGFuZCBpbm5lciBibG9ja3MsIHdvcmthcm91bmRcbiAgICAgIC8vIGh0dHBzOi8vZ2l0aHViLmNvbS9Xb3JkUHJlc3MvZ3V0ZW5iZXJnL2lzc3Vlcy85ODk3XG4gICAgICBpZiAoIHR5cGVvZiBpbnNlcnRCbG9ja3NBZnRlciAhPT0gJ3VuZGVmaW5lZCcgKSB7XG4gICAgICAgIHJldHVybiAoXG4gICAgICAgICAgPEZyYWdtZW50PlxuICAgICAgICAgICAgPGRpdiBjbGFzc05hbWU9eyBjbGFzc05hbWUgfT5cbiAgICAgICAgICAgICA8SW5uZXJCbG9ja3MgLz5cbiAgICAgICAgICAgIDwvZGl2PlxuXG4gICAgICAgICAgICA8SW5zcGVjdG9yQ29udHJvbHM+XG4gICAgICAgICAgICAgICAgPFBhbmVsQm9keT5cbiAgICAgICAgICAgICAgICAgICAgPFRvZ2dsZUNvbnRyb2xcbiAgICAgICAgICAgICAgICAgICAgbGFiZWw9J0lubmVyIENvbnRhaW5lcidcbiAgICAgICAgICAgICAgICAgICAgY2hlY2tlZD17YXR0cmlidXRlcy5pbm5lckNvbnRhaW5lcn1cbiAgICAgICAgICAgICAgICAgICAgaGVscD0nQWRkIGFuIGFkZGl0aW9uYWwgY29udGFpbmVyIHdpdGhpbiB0aGUgQ29udGFpbmVyIGJsb2NrJ1xuICAgICAgICAgICAgICAgICAgICBvbkNoYW5nZT17ICggaW5uZXJDb250YWluZXIgKSA9PiB7IHNldEF0dHJpYnV0ZXMoIHsgaW5uZXJDb250YWluZXIgfSApIH0gfVxuICAgICAgICAgICAgICAgICAgICAvPlxuICAgICAgICAgICAgICAgICAgICB7IGlubmVyQ29udGFpbmVyQ2xhc3NDb250cm9sIH1cbiAgICAgICAgICAgICAgICA8L1BhbmVsQm9keT5cbiAgICAgICAgICAgIDwvSW5zcGVjdG9yQ29udHJvbHM+XG4gICAgICAgICAgPC9GcmFnbWVudD5cbiAgICAgICAgKTtcbiAgICAgIH1cbiAgICAgIGVsc2Uge1xuICAgICAgICByZXR1cm4gKCA8ZGl2IC8+ICk7XG4gICAgICB9XG4gICAgfSxcbiAgICBzYXZlKCBwcm9wcyApIHtcbiAgICAgIGlmICggcHJvcHMuYXR0cmlidXRlcy5pbm5lckNvbnRhaW5lciApIHtcbiAgICAgICAgcmV0dXJuIChcbiAgICAgICAgICA8ZGl2PlxuICAgICAgICAgICAgPGRpdiBjbGFzcz17J3doLXR3ZWFrcy1pbm5lci1jb250YWluZXIgJyArIHByb3BzLmF0dHJpYnV0ZXMuaW5uZXJDb250YWluZXJDbGFzc30+XG4gICAgICAgICAgICAgIDxJbm5lckJsb2Nrcy5Db250ZW50IC8+XG4gICAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgICA8L2Rpdj5cbiAgICAgICAgKTtcbiAgICAgIH1cbiAgICAgIGVsc2Uge1xuICAgICAgICByZXR1cm4gKFxuICAgICAgICAgIDxkaXY+XG4gICAgICAgICAgICA8SW5uZXJCbG9ja3MuQ29udGVudCAvPlxuICAgICAgICAgIDwvZGl2PlxuICAgICAgICApOyBcbiAgICAgIH1cbiAgICB9XG59KTsiLCIvLyByZW1vdmVkIGJ5IGV4dHJhY3QtdGV4dC13ZWJwYWNrLXBsdWdpbiIsIi8vIHJlbW92ZWQgYnkgZXh0cmFjdC10ZXh0LXdlYnBhY2stcGx1Z2luIl0sInNvdXJjZVJvb3QiOiIifQ==