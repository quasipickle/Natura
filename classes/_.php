<?PHP
# This class is a simple, base class that is used mainly for internal data structures

class _
{
	public function __construct($properties = array())
	{
		foreach($properties as $name=>$val)
		{
			$this->{$name} = $val;
		}
	}
	
	public function __set($name,$val){
		$this->{$name} = $val;
	}
	
	public function __get($name){
		if(isset($this->{$name}))
			return $this->{$name};
		else
			return NULL;
	}
}

?>