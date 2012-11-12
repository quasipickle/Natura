<?PHP
#
# config.inc
#
# This is the main configuration file that sets constants & values used by Natura
#

#######
# Site properties
#
define('ORGANIZATION_NAME','Natura');

#######
# Language
#
# The language to use.  Affects both interface elements and error messages
#
# Can be any string, as long as there is an equivalently named directory in
# [ROOT NATURA DIRECTORY]/include/lang/
#
# If you're creating a new translation, it is recommended to use the 
# ISO 639-1 language code naming conventions, as seen here:
# http://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
define('LANG','en');



#######
# Template
#
# The template/theme to use.  Must have the equivalently named directory in
# [ROOT NATURA DIRECTORY]/ui/templates
#
# If the template directory specified here does not exist, will default to "default"
#
# The admin section does not respect this value & will always show "default" templates
# Important: No slashes
define('TEMPLATE','default');



#######
# Database
#
# Database credentials go here
#
# Note that these are not declared as constants, but globals
# the DB.php class will unset these when it loads - to prevent propogation
# more than is necessary
#
global $DB_HOST,$DB_PORT,$DB_USER,$DB_PASSWORD,$DB_DB;
$DB_HOST = 'localhost';
$DB_PORT = '';
$DB_USER = '';
$DB_PASSWORD = '';
$DB_DB = '';




#######
# Contacts
#
# Various contact email addresses

# The email address to send inquries to.  This is the generic contact address
define('CONTACT_INQUIRY','');

# The email address to send orders & other billing information to
define('CONTACT_ORDERS','');

# The email address that sent messages will appear to come from.  
#
# For example when new members get their account activation email, the email 
# will appear to come from this address.  
#
# If you don't want to give people a real address,a common practice is to use 
# a simple no-replay@... address.  That tends to clue in most users that 
# replying to the email is not an option.
define('CONTACT_OUTGOING','');

# The email address to send errors & other technical emails to
define('CONTACT_SYSADMIN','');


#######
# Mail server
#
# Outgoing mail server for Natura.  Most hosts provide this 
define('MAIL_SERVER','localhost');
#25 is the standard, default port.  Unless your host has specified otherwise, just leave this alone.
define('MAIL_SERVER_PORT',25);


#######
# Session
#

#
# Natura stores session variables in $_SESSION[SESSION_KEY]
# Change this if, for some strange reason, you've already got a 'natura' key in $_SESSION
#
define('SESSION_KEY','natura');

#
# Session id length
# Maximum length is 255, as that's the maximum length of the database field
# Note: This does NOT change the length PHP_SESSID cookie that PHP automatically uses
#
define('SESSION_ID_LENGTH',64);

#
# Session timeout
# Sessions will timeout after SESSION_TIMEOUT seconds of inactivity
# Default is 1800 (1/2 hour)
#
define('SESSION_TIMEOUT',1800);


#
# Temporary directory
# This directory is used when generating invoices and purchase orders for mailout
# Most servers use /tmp, so you can leave this alone in most cases
define('DIR_TMP','/tmp');



# # # # # # # # # # # # # # # #
#
# HERE BE DRAGONS!
# Don't touch anything below this comment.  Doing so could break Natura.
#
# # # # # # # # # # # # # # # #

# File system directories
define('DIR',dirname(__FILE__));
# Web directory to the root Natura directory
define ('DIR_WEB',substr(DIR,strlen($_SERVER['DOCUMENT_ROOT'])));

define('DIR_CLASS',DIR.'/classes');
define('DIR_CONTROLLER',DIR.'/controllers');
define('DIR_LIBRARY',DIR.'/libraries');
define('DIR_LIBRARY_WEB',DIR_WEB.'/libraries');
define('DIR_LANG',DIR.'/lang/'.LANG);


define('DIR_TEMPLATE',DIR.'/ui/'.TEMPLATE);
define('DIR_TEMPLATE_WEB',DIR_WEB.'/ui/'.TEMPLATE);

# Full URL to Natura
$protocol = (isset($_SERVER['HTTPS'])) ? 'https://' : 'http://';
define('SITE_URL',$protocol.$_SERVER['HTTP_HOST'].DIR_WEB);

# Access levels
# Use bitwise operations to determine member access levels
define('LEVEL_ADMIN',128);
define('LEVEL_PRODUCER',64);
define('LEVEL_MEMBER',32);

# Magic quotes
define('MAGIC_QUOTES',get_magic_quotes_gpc());

?>