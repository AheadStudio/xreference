(function($) {
  var XREFERENCE = (function() {

    var $sel = {};
    $sel.window = $(window);
    $sel.html = $("html");
    $sel.body = $("body", $sel.html);

    return {
	    
		showCompanyText: {

	        init: function() {
	  			$("input[name=is_company]").on("change", function() {
		  			if($(this).prop("checked")) {
			  			$("input[name=company_name]").css("visibility", "visible");
		  			} else {
			  			$("input[name=company_name]").css("visibility", "hidden");
		  			}
	  			});
		        if($("input[name=is_company]").length && $("input[name=company_name]").val()) {
			        $("input[name=is_company]").trigger("click");
		        }     
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