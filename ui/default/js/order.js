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
			$('.show-products').hide();
			$("#products tr, #show-ordered, .hide-products").show();
			return false;
		});
	
	/* For hiding all products for a producer */
	$(".hide-products")
		.click(function(){
			var $this = $(this);
			
			/* hide the column names */
			$this.closest('tr').next('tr').hide();
			
			/* hide the products */
			$this.closest('thead').next('tbody').children('tr').hide();
			
			/* Swap the buttons */
			$this.hide();
			$this.next('a').show();
		});
	$(".show-products")
		.click(function(){
			var $this = $(this);
			
			/* show the column names */
			$this.closest('tr').next('tr').show();
			
			/* show the products */
			$this.closest('thead').next('tbody').children('tr').show();
			
			/* Swap the buttons */
			$this.hide();
			$this.prev('a').show();
		});
	
	/* Ensuring different input boxes for the same product auto-update when one box gets changed */
	$("input.product").on('keyup',function(){
		var value = $(this).val(),
			name  = $(this).attr('name');
		
		$('input.product[name="'+name+'"]').val(value);
	});
});