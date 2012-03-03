<?PHP
class PageController extends Controller
{
	public $page_level = LEVEL_PRODUCER;
	public $template = 'producers.product.new.tpl.php';
	private $posted = array();
	
	public function setup()
	{
		$this->TPL->assign(array(
			'name'					=>'',
			'description'			=>'',
			'units'					=>'',
			'price'					=>'',
			'count'					=>'',
			'categories'			=>array(),
			'all_categories'		=>ObjectList::load('category'),
			'producer_page'			=>TRUE
			)
		);
	}
	
	public function process()
	{
		if(isset($_POST['create']))
		{			
			$this->posted = array_map('cleanGPC',$_POST);
				
			# "categories" wouldn't have been posted if no checkboxes 
			# were checked, so we need to make sure the array is set
			if(!isset($this->posted['categories']))
				$this->posted['categories'] = array();

			if($this->_createProduct())
				$this->TPL->create_success = TRUE;
			else
			{
				# If creation failed, re-populate the form with entered values
				$this->TPL->assign(array(
					'name'			=>$this->posted['name'],
					'description'	=>$this->posted['description'],
					'units'			=>$this->posted['units'],
					'price'			=>$this->posted['price'],
					'count'			=>$this->posted['count'],
					'categories'	=>$this->posted['categories']
				));
			}
			
		}
	}
	
	#
	# Function: createProduct()
	# Purpose: To create the product after everything has been checked
	# Returns: boolean TRUE if product created, FALSE if not
	#
	private function _createProduct()
	{
		$Product = new Product();
		
		$Product->producer_id 	= Session::get(array('member','id'));
		$Product->name 			= $this->posted['name'];
		$Product->description 	= $this->posted['description'];
		$Product->units			= $this->posted['units'];
		$Product->price 		= $this->posted['price'];
		$Product->count 		= $this->posted['count'];
		$Product->categories	= $this->posted['categories'];
		
		$created_success = $Product->create();
		unset($Product);
		return $created_success;
	}
}
?>