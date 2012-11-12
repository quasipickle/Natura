<?PHP
require DIR_CLASS.'/AdminController.php';

class PageController extends AdminController
{
	public $page_level = LEVEL_ADMIN;
	public $template = 'admin.homepage.tpl.php';
	public $content_file = '';

	public function setup()
	{
		parent::setup();
		$this->TPL->admin_page = TRUE;
		
		$this->content_file = DIR_TEMPLATE.'/index.content.html';
		
		$this->loadContent();
	}
	
	private function loadContent()
	{
		if(file_exists($this->content_file))
			$this->TPL->content = file_get_contents($this->content_file);
		else
			$this->TPL->content = '';
	}
	
	public function process()
	{
		if(isset($_POST['save']))
		{
			$content = (MAGIC_QUOTES) ? stripslashes($_POST['content']) : $_POST['content'];
			file_put_contents($this->content_file,$content);
			$this->TPL->content = $content;
		}
	}
}