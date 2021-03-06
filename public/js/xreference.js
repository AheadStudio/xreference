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
/******/ 	return __webpack_require__(__webpack_require__.s = 9);
/******/ })
/************************************************************************/
/******/ ({

/***/ 10:
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
					$("input[name=is_company]").on("change", function () {
						if ($(this).prop("checked")) {
							$("input[name=company_name]").css("visibility", "visible");
						} else {
							$("input[name=company_name]").css("visibility", "hidden");
						}
					});
					if ($("input[name=is_company]").length && $("input[name=company_name]").val()) {
						$("input[name=is_company]").trigger("click");
					}
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

					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						}
					});

					$("#component_name", $sel.body).autocomplete({

						minChars: 3,
						showNoSuggestionNotice: true,
						noSuggestionNotice: "No results in DataBase",
						serviceUrl: '/api/component/search',
						type: 'POST',
						preventBadQueries: false,

						formatResult: function formatResult(suggestion, currentValue) {

							var strItem = ' ';

							itemName = suggestion.value.toUpperCase().replace(currentValue.toUpperCase(), "<b>" + currentValue.toUpperCase() + "</b>");

							strItem += '<a href="#" class="search-item list-group-item" data-id="' + suggestion.data.id + '">' + '<div class="search-item-name">' + itemName + " [" + suggestion.data.producer + "]" + '</div>' + '</a>';

							return strItem;
						},
						onSelect: function onSelect(suggestion) {

							$("#hidden_comp_id").val(suggestion.data.id);
						}

					});

					$("#reference_name", $sel.body).autocomplete({

						minChars: 3,
						showNoSuggestionNotice: true,
						noSuggestionNotice: "No results in DataBase",
						serviceUrl: '/api/component/search',
						type: 'POST',
						preventBadQueries: false,

						formatResult: function formatResult(suggestion, currentValue) {

							var strItem = ' ';

							itemName = suggestion.value.toUpperCase().replace(currentValue.toUpperCase(), "<b>" + currentValue.toUpperCase() + "</b>");

							strItem += '<a href="#" class="search-item list-group-item" data-id="' + suggestion.data.id + '">' + '<div class="search-item-name">' + itemName + " [" + suggestion.data.producer + "]" + '</div>' + '</a>';

							return strItem;
						},
						onSelect: function onSelect(suggestion) {

							$("#hidden_ref_id").val(suggestion.data.id);
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

$(document).ready(function () {
	$('[data-toggle="tooltip"]').tooltip();
});

/***/ }),

/***/ 9:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(10);


/***/ })

/******/ });