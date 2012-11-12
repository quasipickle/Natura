<?PHP

function __generatePO($Info,$file_path,$download = FALSE){
	include_once DIR_LIBRARY.'/phpexcel/PHPExcel.php';
	$Excel = new PHPExcel();
	$Excel->setActiveSheetIndex(0);
	
	poAddAbout($Excel,$Info);
	poAddItems($Excel,$Info);
			
	$Writer = PHPExcel_IOFactory::createWriter($Excel, 'Excel5');
	
	if($download)
	{
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.basename($file_path).'"');
		header('Cache-Control: max-age=0');
		
		$Writer = PHPExcel_IOFactory::createWriter($Excel, 'Excel5');
		if($Writer->save('php://output'))
			return TRUE;
	}
	else
	{
		$Writer->save($file_path);
		if(file_exists($file_path))
			return TRUE;
	}
		
	return FALSE;
}

function poAddAbout($Excel,$Info)
{
	$Excel->getActiveSheet()
			->setCellValue('A1',$Info->producer_name)
			->setCellValue('A2','Cycle:')
			->setCellValue('B2',$Info->cycle_name.' ('.date('Y-m-d',$Info->cycle_start_stamp).' -> '.date('Y-m-d',$Info->cycle_end_stamp).')');
	
	//change size of producer name		
	$Excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
	$Excel->getActiveSheet()->getRowDimension(1)->setRowHeight(30);
}


function poAddItems($Excel,$Info)
{
	$row = 4;
	foreach($Info->amounts as $name=>$product)
	{
		$Excel->getActiveSheet()
				->setCellValue("A$row",$name)
				->setCellValue("E$row",$product->total);
		
		//make product name bigger
		$Excel->getActiveSheet()->getStyle("A$row:E$row")->getFont()->setSize(14);
		$Excel->getActiveSheet()->getRowDimension($row)->setRowHeight(21);
		
		$row++;
			
		poAddOrderHeaders($Excel,$row);
		$row++;
		
		foreach($product->orders as $Order)
		{
			poAddOrder($Excel,$Order,$product->units,$row);
			$row++;
		}
		$row++;		
	}
}
	


function poAddOrderHeaders($Excel,$row)
{
	$Excel->getActiveSheet()
			->setCellValue("A$row",'Quantity')
			->setCellValue("B$row",'Unit')
			->setCellValue("C$row",'Price')
			->setCellValue("D$row",'Total')
			->setCellValue("E$row",'Member');
			
	//change weight of column headers
	$Excel->getActiveSheet()->getStyle("A$row:E$row")->getFont()->setBold(true);	
			
}

function poAddOrder($Excel,$Order,$units,$row)
{
	$Excel->getActiveSheet()
			->setCellValue("A$row",$Order->count)
			->setCellValue("B$row",$units)
			->setCellValue("C$row",$Order->price)
			->setCellValue("D$row",number_format($Order->count * $Order->price,2))
			->setCellValue("E$row",$Order->member_first_name.' '.$Order->member_last_name);
}

?>