<?PHP
#
# This is the default controller if the requested page doesn't exist
# In other words, this is the controller for 404s
#

class PageController extends Controller
{
	public $page_level = FALSE;
	public $template = '404.tpl.php';
}
?>