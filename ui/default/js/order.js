$(document).ready(function(){
	$("#show-ordered")
		.show()
		.click(function(){
			$('#products tr').hide();
			$('#products input.product').each(function(){
				if($(this).val().length != 0)
				{
					$(this).closest('tr').show();
					$(this).closest('tbody').prev('thead').children('tr').show();
				}
			});			$("#show-ordered").hide();
			$("#show-all").show();
			return false;
		});	
	
	$("#show-all")
		.click(function(){
			$(this).hide();
			$("#products tr, #show-ordered").show();
		});
});