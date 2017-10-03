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
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
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
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 42);
/******/ })
/************************************************************************/
/******/ ({

/***/ 42:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(43);


/***/ }),

/***/ 43:
/***/ (function(module, exports) {

(function ($) {
	var XREFERENCE = function () {

		var $sel = {};
		$sel.window = $(window);
		$sel.html = $("html");
		$sel.body = $("body", $sel.html);

		return {

			showCompanyText: {

				init: function init() {
					var self = this,
					    checkbox = $(".deactivated");

					checkbox.on("change", function () {
						checkboxItem = $(this);
						$hiddenField = $("#hiddenField", $sel.body);

						if ($hiddenField.hasClass("tempHidden")) {
							$hiddenField.slideDown().removeClass("tempHidden");
						} else {
							$hiddenField.addClass("tempHidden").slideUp();
						}
					});
				}

			},

			addRatingId: {

				init: function init() {
					var self = this,
					    btn = $(".btn-vote");

					btn.on("click", function () {
						btnItem = $(this);
						$btnData = btnItem.data('id');
						console.log($btnData);
						$(".rate-reference").val($btnData);
					});
				}

			},

			ratingBar: {

				init: function init() {
					$('.rating-bar').barrating({
						theme: 'fontawesome-stars'
					});
				}
			},

			autocompleteComponent: {

				init: function init() {

					$("#component_name", $sel.body).autocomplete({

						minChars: 3,
						groupBy: "category",
						lookup: [{
							value: "MDIA-4942MN",
							data: {
								id: "692",
								producer: "Getranke Dunkok"
							}
						}, {
							value: "MDIA-7078VA",
							data: {
								id: "675",
								producer: "Tokoshima Ind."
							}
						}],

						formatResult: function formatResult(suggestion, currentValue) {

							var strItem = '<div class="list-group">';

							itemName = suggestion.value.toUpperCase().replace(currentValue.toUpperCase(), "<b>" + currentValue.toUpperCase() + "</b>");

							strItem += '<a href="#" class="search-item" data-id="' + suggestion.data.id + '">' + '<div class="search-item-name">' + itemName + " [" + suggestion.data.producer + "]" + '</div>' + '</a>';

							var strItem = '</div>';
							return strItem;
						},
						onSelect: function onSelect(suggestion) {
							console.log(suggestion);
							$("#hidden_comp_id").val(suggestion.data.id);
						}

					});
				}
			}
		};
	}();

	XREFERENCE.showCompanyText.init();
	XREFERENCE.autocompleteComponent.init();
	XREFERENCE.addRatingId.init();
	XREFERENCE.ratingBar.init();
})(jQuery);

/***/ })

/******/ });