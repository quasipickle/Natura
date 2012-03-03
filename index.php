<?PHP
##
# Natura
#
# A system for local food co-ops to organize their client ordering
#
# © 2010, Dylan Anderson
# Released under GPLv2
#
#

#
# index.php
#
# The main router for the application.  The only file users will load directly
#

#####
# Configuration
##

# Import setup
require 'setup.inc.php';

require DIR_CLASS.'/_.php';


# Import routing class
require DIR_CLASS.'/Router.php';

# Set up route
$Router = new Router();
require DIR_CONTROLLER.'/'.$Router->controller;

$PageController = new PageController($TPL,$Auth);
$PageController->setup();
$PageController->checkAuth();
$PageController->process();
$PageController->showPage();
?>