<?PHP
#
# Class: Template
#
# This is the template "engine" for Natura
#
# To assign variables to a template, use $TPL->variable_name = variable_value
# To reference that variable from inside the template file, use $this->variable_name
#
# Based on the Savant engine, but greatly simplified
#

class Template
{
	# Store configuration in a single $__config var to reduce chances of collision,
	# and reduce the number of "reserved variable names"
	private $__config = array(
		'template_dir'=>DIR_TEMPLATE,
		'template_file'=>FALSE
	);
	
	#
	# Function: __construct()
	# Parameters: $config [array]: An array similar in structure to $this->__config
	#             Can contain any and all keys in $this->__config
	#
	public function __construct($config = array())
	{
		global $lang;
		$this->lang = $lang;
		$this->setConfig($config);
	}
	
	
	#
	# Function: setConfig()
	# Purpose: To override the default config values with the passed values (if any)
	# Parametesr: $config [array]: An array similar in structure to $this->__config
	#             Can contain any and all keys in $this->__config
	#
	public function setConfig($config = array())
	{
		if(count($config))
		{
			foreach($config as $key=>$value)
			{
				$this->__config[$key] = $value;
			}
		}
	}
	
	#
	# Function: assign()
	# Purpose: To assign multiple properties at once
	#          Rather than use $TPL->var = val;
	# Parameters: $vars (array): Array keys become property names, 
	#			  array values become corresponding property values
	#
	public function assign($vars)
	{
		foreach($vars as $var=>$val)
		{
			$this->__set($var,$val);
		}
	}
	
	
	#
	# Function: display()
	# Purpose: To render & output the passed template file
	# Parameters: $template (string) filename to display
	#
	public function display($template)
	{
		# set the full path to the template file we'll be including
		$this->__config['template_file'] = $this->__config['template_dir'].'/'.$template;
		
		
		# Check that the file exists first	
		if(!file_exists($this->__config['template_file']))
		{
			echo 'Template file: '.$this->__config['template_file'].' not found';
		}
		else
		{
			# clean up local scope to avoid collisions
			unset($template);
			
			# include template file
			include($this->__config['template_file']);
		}
	}
	
	##
	# Function: fetch()
	# Purpose: To render the passed template file, and return the resulting string
	# Parameters: $template (strong) filename to render
	# Returns: A string of the rendered template file
	# Note: Ignores headers, so fetch() won't work for templates that require special headers (ie CSV, PDF, etc)
	##
	public function fetch($template)
	{
		ob_start();
		$this->display($template);
		return ob_get_clean();
	}
	
	#
	# Function: __set()
	# Purpose: To be a setter for any undeclared properties
	# Will not allow any property named "__config" to be set
	#
	public function __set($var,$val)
	{
		if($var != "__config")
			$this->{$var} = $val;
	}
}
