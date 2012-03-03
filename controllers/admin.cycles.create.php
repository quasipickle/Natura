<?PHP
require DIR_CLASS.'/AdminController.php';
require DIR_CLASS.'/Cycle.php';

class PageController extends AdminController
{
	public $page_level = LEVEL_ADMIN;
	public $template = 'admin.cycles.create.tpl.php';
	
	public function setup()
	{
		$this->TPL->assign(array(
		  	'name'				=>'',
			'start'				=>'',
			'end'				=>'',
			'all_categories'	=>ObjectList::load('category')
		));	
		parent::setup();
	}
	public function process()
	{
		if(isset($_POST['create']))
			$this->createCycle();
	}
	
	private function createCycle()
	{
		$name	= cleanGPC($_POST['name']);
		$start 	= cleanGPC($_POST['start']);
		$end 	= cleanGPC($_POST['end']);
		$categories = (isset($_POST['category'])) 
						? array_map('cleanGPC',$_POST['category']) 
						: array();
								
		$Cycle 				= new Cycle();
		$Cycle->name		= $name;
		$Cycle->start 		= $start;
		$Cycle->end 		= $end;
		$Cycle->categories	= $categories;
		
		if($Cycle->create())
			$this->TPL->create_success = TRUE;
		else
		{
			$this->TPL->assign(array(
				'name'			=> $name,
				'start'			=> $start,
				'end'			=> $end,
				'categories'	=>$Cycle->categories
				)
			);
		}
	}
}