$(document).ready(function(){
	$(".category_string").tooltip();
	
	var editing_product_id = 0;
	var $product;
	
	/*
	 * Show and populate the modal edit form
	 */
	$(".edit-product").on('click',function(){
		editing_product_id = $(this).siblings('input[name="id"]').val();	

		// get all the product values
		$product = $(this).closest('tr')
		var name = $.trim($('td.product-name',$product).text()),
			description = $.trim($('td.product-description',$product).text()),
			price = $.trim($('td.product-price',$product).text()).substr(1),
			units = $.trim($('td.product-units',$product).text()),
			count = $.trim($('td.product-count',$product).text()),
			category_string = $('td.product-categories',$product).children('span.category_id_string').text()
			categories = $.trim(category_string).split(','),
			$form = $("#edit-form .modal-body");
		
		// set the product values in the edit form	
		$('input[name="name"]',$form).val(name);
		$('input[name="description"]',$form).val(description);
		$('input[name="price"]',$form).val(price);
		$('input[name="units"]',$form).val(units);
		$('input[name="count"]',$form).val(count);
		
		// check the appropriate categories, uncheck the rest
		$('input[name^="categories"]',$form).each(function(){
			var name = $(this).attr('name'),
				match = /categories\[(\d*)\]/.exec(name),
				id = match[1];

				if($.inArray(id,categories) == -1)
					$(this).prop('checked',false);
				else
					$(this).prop('checked',true);
		});
				
		$("#edit-form")
			/* dynamically make modal fit the entire window, rather than the default 500px */
			.on('shown',function(){
				var $modal = $("#edit-form"),
					modal_height,
					$modal_body   = $modal.find('.modal-body'),
					body_padding  = $modal_body.outerHeight() - $modal_body.height(),
					body_height,
					$modal_header = $modal.find('.modal-header'),
					header_height = $modal_header.outerHeight(),
					$modal_footer = $modal.find('.modal-footer'),
					footer_height = $modal_footer.outerHeight();
								
				
				$modal.css({
					top:'20px',
					bottom:'20px',
					marginTop:0,
					maxHeight:10000
				});
				
				
				
				modal_height = $modal.outerHeight();
				
				//honestly not sure why the -2 needs to be there.  My guess is borders or something
				//not taken into consideration by outerHeight()
				body_height = modal_height - header_height - footer_height - body_padding - 2;				
				$modal_body.css({
					maxHeight: 	body_height,
					height:		body_height
				});
			})
			.modal();
	
		return false;
	});
	
	/*
	 * Save the product
	 */
	$("#save-product").on('click',function(){

		/* change button state */
		var $this = $(this).button('loading');
		
		/* get product properties */
		var name = $('input[name="name"]',$form).val(),
			description = $('input[name="description"]',$form).val(),
			price = $('input[name="price"]',$form).val(),
			units = $('input[name="units"]',$form).val(),
			count = $('input[name="count"]',$form).val(),
			categories = {},
			category_names = {},
			$form = $("#edit-form .modal-body"); 

		$('input[name^="categories"]:checked',$form).each(function(){
			var name = $(this).attr('name'),
				match = /categories\[(\d*)\]/.exec(name),
				id = match[1];
			
			categories[id] = id;
			category_names[id] = $(this).val();
		});
		
		/* build request */
		$.ajax({
			cache:		false,
			data:		{
							edit:			true,
							id:				editing_product_id,
							name:			name,
							description:	description,
							price:			price,
							units:			units,
							count:			count,
							categories:		categories
						},
			dataType:	'json',
			error:		function(){
							alert('Unable to save product due to an error talking to the server');
			},
			success: 	function(data){
							/*
							 * If the save is successful, transfer the new values back to the table
							 * and hide the modal
							 */
							if(data.ok)
							{
								$this.button('saved');
								
								$('td.product-name',$product).text(name);
								$('td.product-description',$product).text(description);
								$('td.product-price',$product).text('$'+price);
								$('td.product-units',$product).text(units);
								$('td.product-count',$product).text(count);
			
								var ids = '',
									names = '';
								for(var cat in categories)
								{
									ids = ids + cat + ',';
									names = names + category_names[cat] + ', ';
								}
								
								ids = ids.substr(0,ids.length - 1);
								names = names.substr(0,names.length - 2);
								var truncated_names = names.substr(0,10);
								truncated_names = (names.length > 10) ? truncated_names + '…' : truncated_names
								
								$('td.product-categories',$product)
									.children('span.category_id_string')
										.text(ids)
										.end()
									.children('span.category_string')
										.text(truncated_names)
										.attr('data-original-title',names)
								
								setTimeout(function(){ 
										$("#edit-form").modal('hide');
										$this.button('reset');
									},
									500
								);
							}
							else
							{					
								$this.button('reset');
								alert(data.error.join("\n"));
							}
			},
			type:		'POST',
			url:		DIR_WEB+'/producers/product/edit/?ajax&id='+editing_product_id
		});
		
		return false;
	});
});