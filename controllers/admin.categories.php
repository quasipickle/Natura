<?PHP

class PageController extends AdminController
{
	public $page_level = LEVEL_ADMIN;
	public $template = 'admin.categories.tpl.php';
	
	public function setup()
	{
		$this->TPL->assign(array(
			'new_category'		=>'',
			'categories'		=>array(),
			'create_success'	=> FALSE,
			'delete_success'	=> FALSE
		));
		
		parent::setup();
	}
	
	public function process()
	{
		# new category
		if(isset($_POST['new_category_submit']))
		{
			$new_category = cleanGPC($_POST['new_category']);
			$this->TPL->new_category = $new_category;
			$Category = new Category();
			if($Category->create($new_category))
			{
				$this->TPL->create_success = TRUE;
			}			
		}
		
		# delete category
		if(isset($_POST['delete']))
		{
			$id = cleanGPC($_POST['id']);
			$Category = new Category();
			$Category->id = $id;
			if($Category->delete())
			{
				$this->TPL->delete_success = TRUE;
			}
		}
		
		// load Categories after all post processing is done so we
		// account for any changes
		$this->loadCategories();
	}
	
	
	public function loadCategories()
	{
		$categories = ObjectList::load('category');
		$this->TPL->categories = $categories;
	}			
}