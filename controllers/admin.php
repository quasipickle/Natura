<?PHP
require DIR_CLASS.'/AdminController.php';

class PageController extends AdminController
{
	public $page_level = LEVEL_ADMIN;
	public $template = 'admin.tpl.php';

	public function setup()
	{
		parent::setup();
		$this->TPL->admin_page = TRUE;
	}
}