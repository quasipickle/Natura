$(function(){
	/*
	 * All pages
	 */
		/* Button hovering */
		$(".button").hover(
			function(){ $(this).addClass('hover'); },
			function(){ $(this).removeClass('hover active'); }
		)
		/* Button clicking */
		.mousedown(function(){
			$(this).addClass('active');
		})
		.mouseup(function(){
			$(this).removeClass('active');
		});
});