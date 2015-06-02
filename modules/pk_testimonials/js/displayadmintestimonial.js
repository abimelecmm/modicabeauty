 
function confirmSubmit(confirmMsg)
		{
        var agree=confirm(confirmMsg);
			if (agree)
				return true ;
			else
				return false ;
		}

		
$(document).ready(function() {
      
    $(".testimonialselect").click(function(){

      var count = $(".testimonialselect:checked").length;
      
      if (count > 0) {
        $('#controls').fadeIn('medium');
      } else {
        $('#controls').fadeOut('medium');
      }
    });

});