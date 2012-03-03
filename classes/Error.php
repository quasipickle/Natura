<?PHP
#
# class: Error
# 
# Used as the error handling class.  Invoked statically so one doesn't need
# to re-create an Error object everywhere & worry about passing it around
#
#
class Error
{
	private static $encountered = array();

	#
	# Function: set()
	# Purpose: To record an error
	# Parameters: $key (string): the index in the language file that identifies the error string to use
	#			  $replacements [array]: Any replacements that need to be done on the error string.
	#									 occurrences of this array's keys are replaced with the corresponding
	#									 occurrences of this array's values
	# Usage: Error::set('member_not_found',array('%ID%'=>239);
	#        This would result in the error string "Unable to find member #%ID%" being rendered to
	#		 "Unable to find member #239"
	#
	public static function set($key,$replacements=array())
	{
		self::$encountered[] = Lang::get($key,$replacements);
	}
	
	#
	# Function: clear()
	# Purpose: To clear out any errors.
	#
	public static function clear()
	{
		self::$encountered = array();
	}
	
	#
	# Function: s()
	# Purpose: To report whether errors were encountered or not. Named solely
	#          so that calls to the function would look neat, ie:
	#		   if(Error::s())...
	#
	public static function s()
	{
		if(count(self::$encountered))
			return TRUE;
		else
			return FALSE;
	}
	
	#
	# Function: send()
	# Purpose: To send an error to the system administrator
	# Usage: Exactly like set()
	#
	public static function send($key,$replacements=array())
	{
		$string = Lang::get($key);
		$string = str_replace(array_keys($replacements),$replacements,$string);
		
		mail(CONTACT_SYSADMIN,'Natura error encountered',$string);
	}
	
	#
	# Function: get()
	# Purpose: To return the encountered error
	#
	public function get()
	{
		return self::$encountered;
	}
}