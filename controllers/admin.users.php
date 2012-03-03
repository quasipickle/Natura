<?PHP
require DIR_CLASS.'/AdminController.php';
require DIR_CLASS.'/ObjectList.php';

class PageController extends AdminController
{
	public $page_level = LEVEL_ADMIN;
	
	public function setup()
	{	
		parent::setup();
		$this->load();
	}
	
	public function load()
	{
		if(isset($_GET['producers']))
		{
			$this->TPL->Producers = ObjectList::load('producer');
			$this->template = 'admin.users.producers.tpl.php';
		}
		else
		{
			$this->TPL->Members = ObjectList::load('member');
			$this->template = 'admin.users.members.tpl.php';
		}
	}
}