<?PHP

#
# funclib.php
#
# Random functions that didn't fit anywhere else
#




function _autoload($classname)
{
	if(file_exists(DIR_CLASS.'/'.$classname.'.php'))
	require_once DIR_CLASS.'/'.$classname.'.php';
}
spl_autoload_register('_autoload');


#
# Function: dump()
# Used to output a variable (primarily arrays and objects)
# in an easy-to-read manner.
#
# Primarily intended as a debugging tool
#
function dump($dumpee,$use_vardump=FALSE)
{
	$backtrace = debug_backtrace();
	echo '<pre>File: '.$backtrace[0]['file'].'<br />Line: '.$backtrace[0]['line'].'<br />';

	if($use_vardump)
		var_dump($dumpee);
	else
		print_r($dumpee);
		
	echo '</pre>';
}

#
# Function: cleanGPC()
# Purpose: To stripslashes from the passed string if the magic_quotes_gpc directive is on
#	Very simple.  Main purpose is to clean up code rather than having a bunch of 
#	ternary operators everywhere
# Intended to be used to clean $_POST and $_GET variables after form submissions
#
function cleanGPC($value)
{
	if(MAGIC_QUOTES)
	{
		if(is_array($value))
		{
			foreach($value as $key=>$sub_value)
			{
				$value[$key] = cleanGPC($sub_value);
			}
		}
		else
			$value = stripslashes($value);
	}
	return $value;
}


##
# Function: escapeForCSV()
# Purpose: To escape a value & make it suitable for insertion in a CSV file
#		   Basically just replaces double quotes with double, double quotes - which is the
#          standard for escaping in CSV files
# Returns: The passed value, properly escaped
##
function escapeForCSV($value)
{
	//note: standard for CSV escaping of double quotes is to have the double quote twice - not an escaping slash
	return str_replace('"','""',$value);
}

##
# Function: generateOrderProductList()
# Purpose: To generate the array of categories, producers & products used by the order form template
# Parameters: A DBResult object of a query that asked for product ids, product producer ids, and producer names
# Returns: A massive array:
/*
	[category id] => Object(
		'name' => Category name,
		'products' => array(
			[producer id] => Object(
				'name'	=> Producer name,
				'products' => array(
					product_id => Product object
				)
			)...
		)...
	)...

	Each category contains an array of producers who have at least 1 product in that category.
	Each producer contains an array of products they have in that category
	
	There is a special "0" category that contains all producers and products in the cycle
*/
function generateOrderProductList($Result)
{
	$products = array();
	$sorted = array(0 => new _(array('name'=>'All','products'=>array())));
	
	foreach($Result as $Row)
	{
		# make product if it hasn't already been loaded
		if(!isset($products[$Row->id]))
			$products[$Row->id] =  new Product($Row->id);
		
		foreach($products[$Row->id]->categories as $Category)
		{
			# make Category entry if it doesn't exist
			if(!isset($sorted[$Category->id]))
			{
				$sorted[$Category->id] = new _(array(
					'name'     => $Category->name_hr,
					'products' => array()
				));
			}
		
			# make producer entry if it doesn't exist
			if(!isset($sorted[$Category->id]->products[$Row->producer_id]))
			{
				$sorted[$Category->id]->products[$Row->producer_id] = new _(array(
						'name'     => $Row->producer_name,
						'products' => array()
					));
			}
			
			# make producer entry in "every" category if it doesn't exist
			if(!isset($sorted[0]->products[$Row->producer_id]))
			{
				$sorted[0]->products[$Row->producer_id] = new _(array(
						'name'     => $Row->producer_name,
						'products' => array()
					));
			}
			
			# add product to this category & producer
			$sorted[$Category->id]->products[$Row->producer_id]->products[] = $products[$Row->id];
			
			# add product to the "every" category
			if(!isset($sorted[0]->products[$Row->producer_id]->products[$Row->id]))
				$sorted[0]->products[$Row->producer_id]->products[$Row->id] = $products[$Row->id];
		}
	}
	
	# sort by category name
	uasort($sorted,'orderListCategoryCompare');
	return $sorted;
}
# Only used by generateOrderProductList
function orderListCategoryCompare($A,$B)
{
	if($A->name > $B->name)
		return 1;
	else if($A->name < $B->name)
		return -1;
	else
		return 0;
}

function cleanFilename($value)
{
	$allowed = 'a-zA-Z0-9 \,!@#$%^().-';
	return preg_replace('/[^'.$allowed.']?/','',$value);
}
?>