<?PHP
#
# setup.inc
#
# Loads configuration, dependencies, and libraries
#
ini_set('display_errors',1);
session_start();

#
# configuration
#
require 'config.inc.php';

#
# helper functions
#
require 'funclib.php';

#	
# language
#

require DIR_LANG.'/errors.inc';
require DIR_LANG.'/ui.inc';

#
# template engine
#
require DIR_CLASS.'/Template.php';
$TPL = new Template(array(
	'template_dir'=>DIR_TEMPLATE
	));
	
# $lang defined by language files
# view_*_tools are reset by individual controllers
$TPL->assign(array(
	'member_page'=>FALSE,
	'producer_page'=>FALSE,
	'admin_page'=>FALSE
	)
);

#
# Miscellaneous useful classes
#
require DIR_CLASS.'/Error.php';
require DIR_CLASS.'/Member.php';
require DIR_CLASS.'/Lang.php';
require DIR_CLASS.'/Session.php';

#
# Database connection
#
require DIR_CLASS.'/DB.php';
$DB = DB::getInstance();

#
# Authentication/authorization
#
require DIR_CLASS.'/Auth.php';
$Auth = new Auth();

#
# Parent Controller class
#
require DIR_CLASS.'/Controller.php';


#
# Start session
#
if(Auth::isAuthed())
	define('IS_AUTHED',TRUE);
else	
	define('IS_AUTHED',FALSE);


#
# Setting access level constants
#
$member_level = (Session::exists(array('member','level')))
				? Session::get(array('member','level'))
				: 0;

# Admin
if($member_level & LEVEL_ADMIN)
	define('IS_ADMIN',TRUE);
else
	define('IS_ADMIN',FALSE);

# Producer
if($member_level & LEVEL_PRODUCER)
	define('IS_PRODUCER',TRUE);
else
	define('IS_PRODUCER',FALSE);

# Member
if($member_level & LEVEL_MEMBER)
	define('IS_MEMBER',TRUE);
else
	define('IS_MEMBER',FALSE);

?>