<?PHP

/*
	This "template" file is used to generate the Cycle summary spreadsheet
	It's not a template file in the same sense as the rest, but it's here because
	it should be customizable.

	This file must define 1 function: _generateSummary().
	The function must force a download of the summary file.

	The function must accept 1 array argument that contains:

	'products'	=> an array of Product objects.  This is used to output the names of the products.  Indexed by product id
	'producers'	=> an array indexed by producer ids.  Each element has 2 sub-elements:
					'producer'	=> a Producer object
					'products'	=> an numerically indexed array of the products this producer had ordered in the cycle
	'orders'	=> a numerically indexed
 
*/


function _generateSummary($data,$file_path)
{
	
	include_once 'libraries/phpexcel/PHPExcel.php';
	$Excel = new PHPExcel();
	$Excel->setActiveSheetIndex(0);
	summaryAddMembers($Excel,$data);
	summaryAddProducts($Excel,$data);
	summaryAddOrders($Excel,$data);
	summaryAddTotals($Excel,$data);
		
	$Writer = PHPExcel_IOFactory::createWriter($Excel, 'Excel5');
	$Writer->save($file_path);
}

function summaryAddMembers(&$Excel,&$data)
{
	//used for determining how many columns to colour
	$num_products = count($data['products']);


	//add title
	$Excel->getActiveSheet()
			->setCellValue('A3','Members')
			->mergeCells('A3:B3')
			->getStyle('A3')
				->getFont()
					->setBold(TRUE);
	$Excel->getActiveSheet()
			->getStyle('A3')
				->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	foreach($data['orders'] as $increment=>$order)
	{
		//4 is starting row, each product gets 2 rows
		$row = 4 + ($increment * 2);
		
		/* Set name value */
		$Excel->getActiveSheet()
					->setCellValue('A'.$row,'#'.$order['member']->id)
					->setCellValue('B'.$row,$order['member']->first_name.' '.$order['member']->last_name);
		
		/* Set alignment */
		$Excel->getActiveSheet()
					->getStyle('A'.$row)
						->getAlignment()
							->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
							->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$Excel->getActiveSheet()
					->getStyle('B'.$row)
						->getAlignment()
							->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
							->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		
		/* Color the rows */
		if($row%4 == 0)
		{
			$end = abcInc('B',($num_products * 2)+1);//determine the letter of the last column.  +1 because we want to highlight the total row as well.
			$Excel->getActiveSheet()
						->getStyle('A'.$row.':'.$end.($row+1))//+1 because we're colouring 2 rows
							->getFill()
								->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
								->getStartColor()->setARGB('FFE0F2FF');
		}
		
		
		/* Merge cells */
		$Excel->getActiveSheet()
					->mergeCells('A'.$row.':A'.($row + 1))
					->mergeCells('B'.$row.':B'.($row + 1));
	}
}

function summaryAddProducts(&$Excel,&$data)
{
	$col = 'C';
	/* Add the producer & product headers first.  Each product has 2 columns */
	foreach($data['producers'] as $producer)
	{
		$num_cols = count($producer['products']) * 2;
		$end_col = abcInc($col,$num_cols-1);
	
		// add producer
		$Excel->getActiveSheet()
				->setCellValue($col.'1',$producer['producer']->business_name)
				->mergeCells($col.'1:'.$end_col.'1');
		$Excel->getActiveSheet()
				->getStyle($col.'1')
					->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$Excel->getActiveSheet()
				->getStyle($col.'1')
					->getFont()
						->setSize(16);
		
		// add products
		foreach($producer['products'] as $increment=>$product_id)
		{
			$Product = $data['products'][$product_id];
			$product_col = abcInc($col,($increment * 2));
			$next_col = abcInc($product_col,1);
			
			//add name
			$Excel->getActiveSheet()
					->setCellValue($product_col.'2',$Product->name)
					->mergeCells($product_col.'2:'.$next_col.'2')
					->getStyle($product_col.'2')
						->getAlignment()
							->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$Excel->getActiveSheet()
					->getStyle($product_col.'2')
						->getFont()
							->setBold(TRUE);
		
			//add count & cost headers
			$Excel->getActiveSheet()
					->setCellValue($product_col.'3','#')
					->setCellValue($next_col.'3','$$$')
						->getStyle($product_col.'3')
							->getAlignment()
								->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$Excel->getActiveSheet()
					->getStyle($next_col.'3')
						->getAlignment()
							->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			//add columns to Product objects in $data, so when orders are iterated
			//we know which column in which to put totals
			$data['products'][$product_id]->col_count = $product_col;
			$data['products'][$product_id]->col_cost = $next_col;
		}		
		$col = abcInc($end_col,1);
	}
}

function summaryAddOrders(&$Excel,&$data)
{
	foreach($data['orders'] as $increment=>$order)
	{
		$row_count = 4 + ($increment * 2);
		$row_cost = 5 + ($increment * 2);
		
		foreach($order['items']->items as $item_id => $Item)
		{ 
			$Product = $data['products'][$item_id];
			
			$Excel->getActiveSheet()
					->setCellValue($Product->col_count.$row_count,$Item->count)
					->setCellValue($Product->col_cost.$row_cost,$Item->price * $Item->count);
		}
	}
					
}

function summaryAddTotals(&$Excel,&$data)
{
	// add product total header
	$product_total_row = 6 + count($data['orders']) * 2;
	
	$Excel->getActiveSheet()
			->setCellValue('B'.$product_total_row,'Product totals: ')
			->getStyle('B'.$product_total_row)
				->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$Excel->getActiveSheet()
			->getStyle('B'.$product_total_row)
				->getFont()
					->setBold(TRUE);
	
	
	// add member total header
	$order_total_col = abcInc('C',count($data['products']) * 2);
	
	$Excel->getActiveSheet()
			->setCellValue($order_total_col.'3','Member totals')
			->getStyle($order_total_col.'3')
				->getFont()
					->setBold(TRUE);
	
	// add product formulas
	foreach($data['products'] as $Product)
	{
		$formula_cell = $Product->col_count.$product_total_row;
		$start_cell = $Product->col_count.'4';
		$end_cell = $Product->col_count.($product_total_row - 1);
		
		$Excel->getActiveSheet()
				->setCellValue($formula_cell,"=SUM($start_cell:$end_cell)")
				->getStyle($formula_cell)
					->getBorders()
						->getTop()
							->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	}
	
	// add order formulas
	foreach($data['orders'] as $increment=>$order)
	{
		$row_cost = 5 + ($increment * 2);
		$formula_cell = $order_total_col.$row_cost;
		$start_cell = 'A'.$row_cost;
		$end_cell = abcInc($order_total_col,-1).$row_cost;
		
		$Excel->getActiveSheet()
				->setCellValue($formula_cell,"=SUM($start_cell:$end_cell)");
	}
}

function abcInc($letter,$amount)
{
	return chr(ord($letter) + $amount);
}

