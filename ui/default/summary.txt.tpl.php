<?PHP
	# This file generates a text-based invoice based on a $this->Order.
	# $this->Order must contain a 'total' property, as well as an 'items' property
	# identical to the 'items' property of the Order class
	# Note that $this->Order is NOT an instance of the Order class

	# Force download if requested
	if(isset($this->download) && $this->download)
	{
		header("Content-type: application/octet-stream");
		header("Content-disposition: attachment;filename=Invoice#{$this->Order->id}.txt");
	}


	$name_col_width = 50;
	$price_col_width = 10;
	$count_col_width = 10;
	$total_col_width = 20;

	# Order number
	echo Lang::get('lbl:summary_num').$this->Order->id."\n";
	
	# Order person
	echo $this->Order->member_first_name.' '.$this->Order->member_last_name."\n";

	$name_heading = str_pad('',$name_col_width,' ');
	$price_heading = str_pad(Lang::get('lbl:product_price'),$price_col_width,' ',STR_PAD_LEFT);
	$count_heading = str_pad(Lang::get('lbl:product_count'),$count_col_width,' ',STR_PAD_LEFT);
	$total_heading = str_pad(Lang::get('lbl:total'),$total_col_width,' ',STR_PAD_LEFT);
	
	echo $name_heading.$price_heading.$count_heading.$total_heading."\n";

	foreach($this->Order->items as $Item)
	{
		if(strlen($Item->product_name) > $name_col_width-3)
			$name = substr($Item->product_name,0,$name_col_width-3).'...';
		else
			$name = $Item->product_name;
		
		$name = str_pad($name,$name_col_width,' ');
		$price = str_pad('$'.number_format($Item->price,2),$price_col_width,' ',STR_PAD_LEFT);
		$count = str_pad($Item->count,$count_col_width,' ',STR_PAD_LEFT);
		$total = number_format($Item->price * $Item->count,2);
		$total = str_pad('$'.$total,$total_col_width,' ',STR_PAD_LEFT);
	
		echo $name.$price.$count.$total."\n";
	}
	
	$grand_total_label = str_pad(Lang::get('lbl:grand_total'),$name_col_width+$price_col_width+$count_col_width,' ',STR_PAD_LEFT);


	$grand_total = str_pad('$'.number_format($this->Order->total,2),$total_col_width,' ',STR_PAD_LEFT);
	
	echo "\n".$grand_total_label.$grand_total;
?>