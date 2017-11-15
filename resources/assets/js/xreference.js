(function($) {
  var XREFERENCE = (function() {

    var $sel = {};
    $sel.window = $(window);
    $sel.html = $("html");
    $sel.body = $("body", $sel.html);

    return {
	    
		showCompanyText: {

	        init: function() {
	          var self = this,
	              checkbox = $(".deactivated");
	
	          checkbox.on("change", function() {
	            checkboxItem = $(this);
	            $hiddenField = $("#hiddenField", $sel.body);
	            
	            if($hiddenField.hasClass("tempHidden")) {
		            $hiddenField.slideDown().removeClass("tempHidden");
	            } else {
		            $hiddenField.addClass("tempHidden").slideUp();
	            }
	            
	          });
	          
	          
	        },

    	},
		
		addRatingId: {

	        init: function() {
	          var self = this,
	              btn = $(".btn-vote");
	
	          btn.on("click", function() {
	            btnItem = $(this);
	            $btnData = btnItem.data('id');
	            console.log($btnData);
 	            $(".rate-reference").val($btnData);
	            
	          });
	          
	          
	        },

    	},
		
		
		ratingBar: {
			
			init: function() {
				$('.rating-bar').barrating({
					theme: 'fontawesome-stars'
				});
			},
		},
		
		autocompleteComponent: {
			
			init: function() {
				
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
					
		            formatResult: function(suggestion, currentValue) {
						
						var strItem = ' ';
						
						itemName = suggestion.value.toUpperCase().replace(currentValue.toUpperCase(), "<b>" + currentValue.toUpperCase() + "</b>");
						
						strItem += '<a href="#" class="search-item list-group-item" data-id="'+suggestion.data.id+'">' + '<div class="search-item-name">' + itemName + " [" + suggestion.data.producer + "]" + '</div>' + '</a>';
						
						return strItem;
		            },
		            onSelect: function(suggestion) {
						
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
					
		            formatResult: function(suggestion, currentValue) {
						
						var strItem = ' ';
						
						itemName = suggestion.value.toUpperCase().replace(currentValue.toUpperCase(), "<b>" + currentValue.toUpperCase() + "</b>");
						
						strItem += '<a href="#" class="search-item list-group-item" data-id="'+suggestion.data.id+'">' + '<div class="search-item-name">' + itemName + " [" + suggestion.data.producer + "]" + '</div>' + '</a>';
						
						return strItem;
		            },
		            onSelect: function(suggestion) {
						
						$("#hidden_ref_id").val(suggestion.data.id);

 
			        }

				});
				
			},
		},
    };

  })();

  XREFERENCE.showCompanyText.init();
  XREFERENCE.autocompleteComponent.init();
  XREFERENCE.addRatingId.init();
  XREFERENCE.ratingBar.init();
  
})(jQuery);

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});