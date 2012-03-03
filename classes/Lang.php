<?PHP

class Lang
{
	private static $lang = FALSE;
	
	//outputs the language string
	public static function out($key,$replacements=FALSE){ echo self::getString($key,$replacements); }
	
	//outputs the language string, escaped for inclusion in html code
	public static function outSafe($key,$replacements=FALSE){ echo htmlentities(self::getString($key,$replacements)); }
	
	//returns the language string
	public static function get($key,$replacements=FALSE){ return self::getString($key,$replacements); }
	
	//returns the language string, escaped for inclusion in html code
	public static function getSafe($key,$replacements=FALSE){ return htmlentities(self::getString($key,$replacements)); }
		
	
	//loads the language files
	public static function load()
	{
		require DIR_LANG.'/email.inc';
		require DIR_LANG.'/errors.inc';
		require DIR_LANG.'/ui.inc';
		
		self::$lang = $lang;		
	}
	
	//retrieves the language entry for the passed key, or returns an error message if not found
	private static function getString($key,$replacements)
	{
		if(self::$lang === FALSE)
			self::load();
	
		if(isset(self::$lang['ui'][$key]))
			$string = self::$lang['ui'][$key];
		else if(isset(self::$lang['err'][$key]))
			$string = self::$lang['err'][$key];
		else if(isset(self::$lang['email'][$key]))
			$string = self::$lang['email'][$key];
		else
			$string = 'String "'.$key.'" not found.';
		
		if($replacements !== FALSE)
			$string = str_replace(array_keys($replacements),$replacements,$string);

		return $string;
	}
}
Lang::load();