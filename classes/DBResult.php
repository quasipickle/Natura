<?PHP
#
# class: DBResult
# 
# A wrapper for the mysqli_result object.  Implements the Iterator interface
# so it can be looped through via foreach to retrieve the rows
#
class DBResult implements Iterator
{
	private $index = 0;
	private $resultset;
	private $num_rows;
	
	#
	# Function: __construct()
	# Purpose: To create the object & set up some local variables
	# Parameters: $result (mysqli_result object): The resultset from mysqli_query()
	# 			  Passed by reference - no sense duplicating
	#
	public function __construct(&$result)
	{
		$this->resultset = &$result;
	}
	
	#
	# Function: numRows()
	# Purpose: To return the number of rows in the result set
	#
	public function numRows()
	{
		if(empty($this->num_rows))
			$this->num_rows = $this->resultset->num_rows;
		
		return $this->num_rows;
	}
	
	#
	# Function: getRow()
	# Purpose: Returns the first row of the result set.
	#          Can be used when the result set is known to only have
	# 		   one row & you just want the data from that row
	#		   (ex: if the query was: SELECT count(*) FROM `members`)
	# Basically just an alias for current(), but more semantic for it's purpose
	#
	public function getRow()
	{
		return $this->current();
	}
	
	#
	# Function: rewind()
	# Purpose: Required by the Iterator interface.
	#		 : Rewinds the internal pointer back to the beginning
	#
	public function rewind()
	{
		$this->index = 0;
	}
	
	#
	# Function: current()
	# Purpose: Required by the Iterator interface.
	#	     : Returns a DBRow object corresponding to the $index-th row
	#		   of the result set
	#		   
	public function current()
	{
		$this->resultset->data_seek($this->index);
		# casting to (object) allows columns to be referenced as object vars
		return (object)$this->resultset->fetch_assoc();
	}
	
	#
	# Function: key()
	# Purpose: Required by the Iterator interface.
	#        : Returns the current index
	#
	public function key()
	{
		return $this->index;
	}
	
	#
	# Function: next()
	# Purpose: Required by the Iterator interface
	#		 : Returns the next row if possible, FALSE if not
	#
	public function next()
	{
		if($this->valid(++$this->index))
			return $this->current();
		else
			return FALSE;
	}
	
	#
	# Function: valid()
	# Purpose: Required by the Iterator interface
	#		 : Checks if there is a row for the passed index 
	#		   (or $this->index if none was passed
	#
	public function valid($index=FALSE)
	{
		$index = ($index !== FALSE) ? $index : $this->index;
		return $this->resultset->data_seek($index);
	}
}