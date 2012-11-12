<?PHP

/*
	This "template" file is used to generate the invoice
	It's not a template file in the same sense as the rest, but it's here because
	it should be customizable.

	This file must define 1 function: __generateInvoice().
	The function must generate an invoice spreadsheet at the passed filename.
	Must return TRUE if it worked, FALSE if not
*/


function __generateInvoice($Order, $file_path, $download = FALSE)
{
	include_once DIR_LIBRARY.'/phpexcel/PHPExcel.php';
	$Excel = new PHPExcel();
	$Excel->setActiveSheetIndex(0);
	
	invoiceAddAbout($Excel,$Order);
	invoiceAddItems($Excel,$Order);
			
	$Writer = PHPExcel_IOFactory::createWriter($Excel, 'Excel5');
	
	if($download)
	{
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$file_path.'"');
		header('Cache-Control: max-age=0');
		
		$Writer = PHPExcel_IOFactory::createWriter($Excel, 'Excel5');
		$Writer->save('php://output');
	}
	else
		$Writer->save($file_path);
		
	return TRUE;
}


function invoiceAddAbout(&$Excel,&$Order)
{
	//add title
	$Excel->getActiveSheet()
			->setCellValue('A1','Invoice #:')
			->setCellValue('B1',$Order->id)
			->setCellValue('A2','Member:')
			->setCellValue('B2',$Order->member_first_name.' '.$Order->member_last_name)
			->setCellValue('A3','Time:')
			->setCellValue('B3',$Order->time_placed);
}

function invoiceAddItems(&$Excel,&$Order)
{
	//add column headers
	$Excel->getActiveSheet()
			->setCellValue('A5','Quantity')
			->setCellValue('B5','Producer')
			->setCellValue('C5','Product')
			->setCellValue('D5','Unit')
			->setCellValue('E5','Price')
			->setCellValue('F5','Total');
	
	//set formatting
	$Excel->getActiveSheet()->getStyle('A5:F5')->getFont()->setBold(TRUE);	
	
	$row = 6;
	foreach($Order->items as $Item)
	{
		//set values
		$Excel->getActiveSheet()
				->setCellValue("A$row",$Item->count)
				->setCellValue("B$row",$Item->producer_name)
				->setCellValue("C$row",$Item->product_name)
				->setCellValue("D$row",$Item->units)
				->setCellValue("E$row",$Item->price)
				->setCellValue("F$row",$Item->count * $Item->price);
		
		//set zebra striping
		if($row%2 == 0)
		{
			$Excel->getActiveSheet()
						->getStyle("A$row:F$row")
							->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setARGB('FFE0F2FF');
		}
		$row++;
	}

	//set column widths
	$Excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(TRUE);
	$Excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(TRUE);
	$Excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(TRUE);
	$Excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(TRUE);
	
	//set alignment
	$Excel->getActiveSheet()->getStyle("E6:F$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	
	//grand total - after column width so "Grand Total" doesn't get used to calculate width
	$Excel->getActiveSheet()->setCellValue('E'.($row + 1),'Grand total');
	$Excel->getActiveSheet()->setCellValue('F'.($row + 1),"=SUM(F6:F$row)");	

}