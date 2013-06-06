<?PHP
/*
 * Class: Session
 * Purpose: To facilitate interaction with $_SESSION.  Eliminates the need for $_SESSION[SESSION_KEY]
 * 			to be used everywhere.
 *
 *			All public functions should be called statically
 */
class Session
{
	/**
	 * Function: exists()
	 * Purpose: To determine if a session variable exists.  Equivalent
	 *          to isset($_SESSION['some_var'])
	 * Parameters: $name:	If blank, function will return whether or not 
	 *							the Natura session has been initialized.
	 *						If a string, function will return whether or 
	 *							not that Natura session variable has been set.
	 *						If an array, function will iterate through $_SESSION
	 *							using the array as keys, and return whether or not
	 *							that "deep" var is set.
	 * Returns: TRUE or FALSE
	 */					
	public static function exists($name=FALSE)
	{
		if($name === FALSE)
		{		
			if(isset($_SESSION[SESSION_KEY]))
				return TRUE;
			else
				return FALSE;
		}
		else if(is_string($name))
		{
			if(self::exists() && isset($_SESSION[SESSION_KEY][$name]))
				return TRUE;
			else
				return FALSE;
		}
		else if(is_array($name))
		{
			if(self::exists())
			{
				$property = self::findDeepVar($name);
				if(!is_null($property))
					return TRUE;
			}
			return FALSE;
		}	
	}
	
	/**
	 * Function: destroy()
	 * Purpose: To determine if a session variable exists.  Equivalent
	 *          to unset($_SESSION['some_var'])
	 * Parameters: $name:	If blank, function will unset the entire Natura session.
	 *						If a string, function will unset that Natura session 
	 *							variable.
	 *						If an array, function will iterate through $_SESSION
	 *							using the array as keys, and unset that "deep" var.
	 *
	 * Returns: Nothing
	 */	
	public static function destroy($name=FALSE)
	{
		if($name === FALSE)
			unset($_SESSION[SESSION_KEY]);
		if(is_string($name) && self::exists())
			unset($_SESSION[SESSION_KEY][$name]);
		else if(is_array($name) && self::exists())
		{
			$property = &self::findDeepVar($name);
			unset($property);
		}
	}
			
	/**
	 * Function: get()
	 * Purpose: To return if a session variable
	 * Parameters: $name:	If a string, function will return that Natura session 
	 *							variable.
	 *						If an array, function will iterate through $_SESSION
	 *							using the array as keys, and return that "deep" var.
	 *
	 * Returns: Found value, or NULL if value not found
	 */	
	public static function get($name)
	{
		if(is_string($name))
		{
			if(self::exists() && self::exists($name))
				return $_SESSION[SESSION_KEY][$name];
			else
				return NULL;
		}
		else if(is_array($name) && self::exists())
		{
			$property = self::findDeepVar($name);
			return $property;
		}
	}
	
	/**
	 * Function: set()
	 * Purpose: To set a Natura session variable
	 * Parameters: $name:	If a string, function will set that Natura session 
	 *							variable.
	 *						If an array, function will iterate through $_SESSION
	 *							using the array as keys, and set that "deep" var.
	 *			   $value: The value to set.  Can be anything allowable in $_SESSION
	 *
	 * Returns: Nothing
	 */	
	public static function set($name,$value)
	{
		if(!self::exists())
			$_SESSION[SESSION_KEY] = array();
	
		if(is_string($name))
			$_SESSION[SESSION_KEY][$name] = $value;
		else if(is_array($name))
		{
			$property = &self::findDeepVar($name);
			$property = $value;
		}
	}
	
	/**
	 * Function: findDeepVar()
	 * Purpose: To find a variable inside a Natura session variable array
	 *
	 *			Ex: $_SESSION[SESSION_KEY]['member']['id'] can be retrieved with
	 *			findDeepVar(array('member','id'));
	 * Parameters: $name:	Must be an array. Function will iterate through $_SESSION
	 *							using the array as keys, and return that "deep" var.
	 *
	 * Returns: The value if found, NULL if not
	 */	
	private static function findDeepVar($name)
	{
		$property = $_SESSION[SESSION_KEY][$name[0]];
		array_shift($name);
		foreach($name as $curr_name)
		{
			if(isset($property[$curr_name]))
				$property = &$property[$curr_name];
			else
				return NULL;
		}

		return $property;
	}
}

?>