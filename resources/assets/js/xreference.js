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
				
				$("#component_name", $sel.body).autocomplete({
		            
		            minChars: 3,
		            groupBy: "category",
		            lookup: [
						{
							value: "MDIA-4942MN",
							data: {
								id: "692",
								producer: "Getranke Dunkok"
							}
						}, 
						{
							value: "MDIA-7078VA",
							data: {
								id: "675",
								producer: "Tokoshima Ind."								
							}
						}
		            ],

		            formatResult: function(suggestion, currentValue) {
						
						var strItem = '<div class="list-group">';
						
						itemName = suggestion.value.toUpperCase().replace(currentValue.toUpperCase(), "<b>" + currentValue.toUpperCase() + "</b>");
						
						strItem += '<a href="#" class="search-item" data-id="'+suggestion.data.id+'">' + '<div class="search-item-name">' + itemName + " [" + suggestion.data.producer + "]" + '</div>' + '</a>';
						
						var strItem = '</div>';
						return strItem;
		            },
		            onSelect: function(suggestion) {
						console.log(suggestion);
						$("#hidden_comp_id").val(suggestion.data.id);

 
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