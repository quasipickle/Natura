<?php
# Force download if requested
if(isset($this->download) && $this->download)
{
	# Output headers to force user to download the CSV
	header("Content-type: application/octet-stream");
	header("Content-disposition: attachment;filename=Invoice#{$this->Order->id}.csv");
}


# Order number
echo '"'.Lang::get('lbl:invoice_num').$this->Order->id.'","",""'."\n";
	
# Order person
echo '"'.escapeForCSV($this->Order->member_first_name.' '.$this->Order->member_last_name).'","","",""'."\n\n";

# column headers
echo '"'.escapeForCSV(Lang::get('lbl:product')).'","'.escapeForCSV(Lang::get('lbl:product_price')).'","'.escapeForCSV(Lang::get('lbl:product_count')).'","'.escapeForCSV(Lang::get('lbl:total')).'"'."\n"; ?>

<?php #each product
foreach($this->Order->items as $Item):
	echo '"'.escapeForCSV($Item->product_name).'","'.escapeForCSV($Item->price).'","'.escapeForCSV($Item->count).'","'.escapeForCSV($Item->count * $Item->price).'"'."\n";
endforeach;

#grand total
echo "\n".'"","","'.escapeForCSV(Lang::get('lbl:grand_total')).'","'.$this->Order->total.'"';