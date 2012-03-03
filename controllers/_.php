<?PHP
#
# This is the default controller if nothing is requested
# In other words, this is the controller for the homepage
#

class PageController extends Controller
{	
	public $page_level = FALSE;
	public $template = 'index.tpl.php';	
}
?>