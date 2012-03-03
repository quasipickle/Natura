<?php
# Output headers to force user to download the CSV
if(isset($this->download) && $this->download)
{
	$name = urlencode($this->cycle_name);
	header("Content-type: application/octet-stream");
	header("Content-disposition: attachment;filename=Orders(Cycle_{$name}).csv");
}


echo '"'.escapeForCSV(Lang::get('lbl:cycle')).': '.escapeForCSV($this->cycle_name).'('.date('Y-m-d',$this->cycle_start_stamp).' - '.date('Y-m-d',$this->cycle_end_stamp).')","",""'."\n";
echo '"'.escapeForCSV($this->producer_name).'","",""'."\n\n";

# column headers
echo '"'.escapeForCSV(Lang::get('lbl:product')).'","'.escapeForCSV(Lang::get('lbl:total')).'","'.escapeForCSV(Lang::get('lbl:orders_individual')).'","'.escapeForCSV(Lang::get('member')).'"'."\n";


#each product
foreach($this->amounts as $name=>$item):
	echo '"'.escapeForCSV($name).'","'.escapeForCSV($item['total']).'"'."\n";

	foreach($item['orders'] as $order):
		$line = '"","","'.escapeForCSV($order['count']).' '.escapeForCSV($item['units']).'@$'.escapeForCSV($order['price']).'/'.escapeForCSV($item['units']).'","'.escapeForCSV($order['member']).'"'."\n";
		echo $line;
	endforeach;
endforeach;

# Grand total
echo "\n".'"","","'.escapeForCSV(Lang::get('lbl:grand_total')).': $'.number_format($this->order_grand_total,2).'"';
