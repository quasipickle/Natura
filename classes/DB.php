<?PHP
#
# class: DB
#
# A singleton class for database connections
#
# Invoke with:
#
#	$DB = DB::getInstance()
#
require 'DBResult.php';
class DB
{
	private static $instance;
	private $mysqli;
	
	#
	# Function: getInstance()
	# Purpose: singleton constructor.  Ensures only 1 connection to the DB
	#		   is made, regardless of how many DB objects are created
	#
	public static function getInstance()
	{
		if(!self::$instance)
			self::$instance = new DB();
		
		return self::$instance;		
	}
	
	#
	# Function: __construct()
	# Purpose: Create the connection to the database
	#		 : Delete the global credential variables
	#  	
	private function __construct()
	{
		# import the credentials from global space & defined in config.inc
		global $DB_HOST,$DB_PORT,$DB_USER,$DB_PASSWORD,$DB_DB;
		
		# make connection
		$this->mysqli = new mysqli($DB_HOST,$DB_USER,$DB_PASSWORD,$DB_DB,$DB_PORT);
		
		# forget the credentials
		unset($GLOBALS['DB_HOST'],
			  $GLOBALS['DB_PORT'],
			  $GLOBALS['DB_USER'],
			  $GLOBALS['DB_PASSWORD'],
			  $GLOBALS['DB_DB']);
		
	}
	
	#
	# Function: execute()
	# Parameters: $query (string): The SQL query to execute
	#             $multi [boolean]: Whether the query string consists of multiple
	#				   				queries delimited by ;
	# Returns: TRUE if the query doesn't have a result set (DELETE, INSERT, etc)
	#	     : FALSE if a failure happens.  Also sets an error
	#		 : a DBResult object if query had a result set
	#
	public function execute($query,$multi=FALSE)
	{
		$return = ($multi) 
			? $this->mysqli->multi_query($query) 
			: $this->mysqli->query($query);
		if($return === FALSE)
		{
			Error::send('db.query',array(
									'%ERROR%'=>$this->mysqli->error,
									'%ERRNO%'=>$this->mysqli->errno
									)
						);
			return FALSE;
		}
		if($return === TRUE)
		{
			# Flush any (empty) result sets
			if($multi)
			{
				while($this->mysqli->next_result()) 
					$this->mysqli->store_result();
			}
			return TRUE;
		}
		else
		{
			return new DBResult($return,$this->mysqli);
		}
	}
	
	#
	# Function: affectedRows()
	# Purpose: To return the number of rows affected by the most recent query
	#
	public function affectedRows()
	{
		return $this->mysqli->affected_rows;
	}
	
	#
	# Function: lastInsertID()
	# Purpose: To return the ID automatically generated in the last query
	#
	public function lastInsertID()
	{
		return $this->mysqli->insert_id;
	}
	
	#
	# Function: escape()
	# Purpose: Wrapper function for mysqli's escaping function
	# Parameters: $value - the value to be escaped
	# Returns: The escaped value
	#
	public function escape($value)
	{
		return $this->mysqli->real_escape_string($value);
	}
	
	#
	# Function: error()
	# Purpose: Wrapper function for mysqli::error
	#
	public function error()
	{
		return $this->mysqli->error;
	}
	
	#
	# Function: errno()
	# Purpose: Wrapper function for mysqli::errno
	#
	public function errno()
	{
		return $this->mysqli->errno;
	}
}



	

?>