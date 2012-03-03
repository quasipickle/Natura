<?php
if(isset($this->download) && $this->download)
{
	$name = urlencode($this->cycle_name);
	header("Content-type: application/octet-stream");
	header("Content-disposition: attachment;filename=Orders(Cycle_{$name}).txt");
}


$product_col_width 		= 30;
$total_col_width		= 10;
$individual_col_width 	= 50;
$member_col_width		= 30;


#order #
echo Lang::get('lbl:cycle').': '.$this->cycle_name.'('.date('Y-m-d',$this->cycle_start_stamp).' - '.date('Y-m-d',$this->cycle_end_stamp).")\n";
echo $this->producer_name."\n\n";



#headers
$product_header 	= str_pad(Lang::get('lbl:product'),$product_col_width,' ');
$total_header 		= str_pad(Lang::get('lbl:total'),$total_col_width,' ',STR_PAD_LEFT);
$individual_header 	= str_pad(Lang::get('lbl:orders_individual'),$individual_col_width,' ',STR_PAD_LEFT);
$member_header 		= str_pad(Lang::get('member'),$member_col_width,' ',STR_PAD_LEFT);

echo $product_header.$total_header.$individual_header.$member_header."\n";

foreach($this->amounts as $name=>$Item):
	$name 	= str_pad($name,$product_col_width,' ');
	$total 	= str_pad($Item->total,$total_col_width,' ',STR_PAD_LEFT);
	echo $name.$total."\n";

	foreach($Item->orders as $Order):
		$line = $Order->count.' '.$Item->units.'@$'.number_format($Order->price,2).'/'.$Item->units;		
		$member = str_pad($Order->member,$member_col_width,' ',STR_PAD_LEFT);
		$member = str_pad($Order->member_last_name.', '.$Order->member_first_name,$member_col_width,' ',STR_PAD_LEFT);
		$member = substr($member,0,$member_col_width);
		echo str_pad($line,$product_col_width+$total_col_width+$individual_col_width,' ',STR_PAD_LEFT);
		echo $member;
		echo "\n";
	endforeach;
endforeach;

echo "\n\n";
$string = Lang::get('lbl:grand_total').': $'.number_format($this->order_grand_total,2);
echo str_pad($string,$product_col_width+$total_col_width+$individual_col_width,' ',STR_PAD_LEFT);