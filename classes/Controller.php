<?PHP

#
# Class: Controller
# Purpose: A basic class all other controllers extend & use. Provides some functionality common to all pages
# Each separate controller file will implement a PageController class that extends this object
#

class Controller
{
	# The user level required to view the page
	# Defaults to LEVEL_ADMIN (highest)
	# If set to boolean FALSE, the page is open to the unauthenticated public
	public $page_level = LEVEL_ADMIN;
	
	# The template to display
	# Defaults to the homepage
	public $template = 'index.tpl.php';

	# Template object
	public $TPL;
	
	# Authorization object
	public $Auth;
	
	#
	# Function: __construct()
	# Purpose: Default constructor
	# 	Just sets local references to Template and Authorization objects
	#
	public function __construct(&$TPL,&$Auth)
	{
		$this->TPL = &$TPL;
		$this->Auth = &$Auth;
		
		# Set these rather than using globals, so we can re-set them if logging in/out
		$this->TPL->assign(array(
			'is_authed'		=>IS_AUTHED,
			'is_member'		=>IS_MEMBER,
			'is_producer'	=>IS_PRODUCER,
			'is_admin'		=>IS_ADMIN
			)
		);
		
		$this->checkAuth();
	}
	
	#
	# Function: setup()
	#
	# This function is called before checkAuth() and showPage()
	# Should be used by extending PageController classes to setup page-specific properties
	#
	public function setup()
	{}
	
	
	#
	# Function: process()
	#
	# This function is called after checkAuth() and before showPage()
	# Should be used by extending PageController classes to process form submission
	# or do anything else before the page is displayed
	#
	public function process()
	{}
	
	#
	# Function: checkAuth()
	# Purpose: To check that the currently logged in user level is sufficient
	# 	to view the page.  Automatically displays the "unauth" page & dies if not
	#
	# Sets the is_member, is_producer, is_admin properties of the template
	#
	public function checkAuth()
	{
		$member_level = (Session::exists('member')) 
						? Session::get(array('member','level'))
						: 0;
						
		if(!$this->page_level || $member_level & $this->page_level)
		{
			return TRUE;
		}
		else
		{
			$this->TPL->member_page = FALSE;
			$this->TPL->producer_page = FALSE;
			$this->TPL->admin_page = FALSE;
			$this->TPL->display('unauth.tpl.php');
			exit();
		}
	}
	
	#
	# Function: showPage()
	# Purpose: To display the page.
	#
	# If this function is overloaded, be sure errors get added
	# Easiest just to call parent::showPage()
	#
	public function showPage()
	{
		if(Error::s())
			$this->TPL->error = Error::get();
		
		$this->TPL->display($this->template);
	}
}
