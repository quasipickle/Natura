<?PHP
#
# class: Router
#
# This class takes care of routing the user to the correct controller.
#

class Router
{
	private $controller = '_.php';
	
	public function __construct()
	{
		$this->determineController();
	}
	
	##
	# Function: determineController()
	# Purpose: To determine which controller to use to show the page
	#
	# If nothing is requested, $this->controller stays with the default _.php
	# If the requested page has no associated controller, $this->controller gets the default
	# 404 controller, _?.php
	#
	private function determineController()
	{
		# get the part of the requested URI from the root of Natura on		
		$requested_sub_uri = substr($_SERVER['REQUEST_URI'],strlen(DIR_WEB));
		
		# remove any query string
		if(strlen($_SERVER['QUERY_STRING']))
			$requested_sub_uri = rtrim($requested_sub_uri,'?'.$_SERVER['QUERY_STRING']);
	
		# build the controller directly from the requested URL
		$requested_sub_uri = trim($requested_sub_uri,'/');
		$controller = str_replace('/','.',$requested_sub_uri);
		
		if(strlen($controller))
		{
			$controller .= '.php';
			if(file_exists(DIR_CONTROLLER.'/'.$controller))
				$this->controller = $controller;
			else
				$this->controller = '_?.php';
		}
	}
	
	public function __get($name)
	{
		if($name == 'controller')
			return $this->controller;
	}
}