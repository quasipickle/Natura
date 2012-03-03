<?PHP
require_once DIR_CLASS.'/Order.php';

class PageController extends Controller
{
	public $page_level = LEVEL_MEMBER;
	public $template = 'members.summary.tpl.php';
	
	private $Member = FALSE;

	public function setup()
	{	
		$this->TPL->assign(array(
			'member_page'		=>TRUE,
			'old_cycles'		=>FALSE,
			'Order'				=>FALSE,
			'download'			=>FALSE
		));
	
	
		$this->Member = new Member(Session::get(array('member','id')));
		
		if(isset($_GET['download']))
		{
			$this->loadPastOrder($_GET['download'],'order');
			$this->template = 'summary.'.$_GET['format'].'.tpl.php';
			
			# default to txt format if requested format doesn't exist
			if(!file_exists(realpath(DIR_TEMPLATE.'/'.$this->template)))
				$this->template = 'summary.txt.tpl.php';
			
			$this->TPL->download = TRUE;
			
		}
		else
		{		
			# Load all past cycles in which the member purchased something
			$this->loadOrderedCycles();
			
			# If requested, load a particular order
			if(isset($_GET['cycle']))
				$this->loadPastOrder($_GET['cycle'],'cycle');
		}
	}
	
	##
	# Function: loadOrderedCycles()
	# Purpose: To load all old cycles this member has orders in
	##
	public function loadOrderedCycles()
	{
		$this->Member->loadOrderedCycles();	
		$this->TPL->old_cycles = $this->Member->previousOrderedCycles;
	}
	
	
	##
	# Function: loadPastOrder()
	# Purpose: To load a previous order
	# Parameters: $id (int): The ID to use to load the order
	# 		      $id_type (string): "cycle": id passed is a cycle ID
	#							     "order": id passed is an order ID
	##
	public function loadPastOrder($id,$id_type)
	{
		$Order = $this->Member->loadOrder($id,$id_type);
		if($Order)
		{			
			$this->TPL->Order = new _(array(
				'id'                =>$Order->id,
				'total'             =>$Order->total,
				'items'             =>$Order->items,
				'member_first_name' =>$Order->Member->first_name,
				'member_last_name'  =>$Order->Member->last_name
			));
		}
	}		
}

?>