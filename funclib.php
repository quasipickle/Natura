<?PHP

#
# funclib.php
#
# Random functions used in the development of Natura 
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

?>