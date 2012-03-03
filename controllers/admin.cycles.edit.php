<?PHP
require DIR_CLASS.'/AdminController.php';
require DIR_CLASS.'/Cycle.php';

class PageController extends AdminController
{
	public $page_level = LEVEL_ADMIN;
	public $template = 'admin.cycles.edit.tpl.php';
	public $Cycle;
	
	public function setup()
	{
		$this->Cycle = new Cycle(cleanGPC($_GET['id']));
		$this->TPL->assign(array(
			'id'				=>cleanGPC($_GET['id']),
			'name'				=>$this->Cycle->name,	
			'start'				=>$this->Cycle->start,
			'end'				=>$this->Cycle->end,
			'categories' 		=>$this->Cycle->categories,
			'all_categories'	=>ObjectList::load('category')
		));	
		parent::setup();
	}
	public function process()
	{
		if(isset($_POST['edit']))
			$this->editCycle();
				
	}
	
	private function editCycle()
	{
		$name		= cleanGPC($_POST['name']);
		$start 		= cleanGPC($_POST['start']);
		$end 		= cleanGPC($_POST['end']);
		$categories = (isset($_POST['category'])) 
						? array_map('cleanGPC',$_POST['category']) 
						: array();
		
		$this->Cycle->name			= $name;
		$this->Cycle->start 		= $start;
		$this->Cycle->end 			= $end;
		$this->Cycle->categories	= $categories;
		
		if($this->Cycle->update())
		{
			$this->TPL->edit_success = TRUE;
			$this->TPL->assign(array(
				'name'			=>$this->Cycle->name,
				'start'			=>$this->Cycle->start,
				'end'			=>$this->Cycle->end,
				'categories'	=>$this->Cycle->categories
				)
			);
		}
		else
		{
			$this->TPL->assign(array(
				'name'			=> $name,
				'start'			=> $start,
				'end'			=> $end,
				'categories'	=>$this->Cycle->categories
				)
			);
		}
	}
}