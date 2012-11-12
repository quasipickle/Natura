<?PHP
require DIR_CLASS.'/Product.php';

class PageController extends Controller
{
	public $page_level = LEVEL_PRODUCER;
	public $template = 'producers.products.view.tpl.php';
	private $DB;
	
	public function setup()
	{
		$this->TPL->assign(array(
			'producer_page'	=> TRUE,
			'products'      => FALSE,
			'all_categories'=> ObjectList::load('category')
		));
		$this->DB	= DB::getInstance();
	}
	
	public function process()
	{
		if(isset($_POST['delete']))
			$this->_deleteProduct();
		
		$this->_loadProducts();
	}
	
	private function _deleteProduct()
	{
		$product_id = cleanGPC($_POST['id']);
		$Product    = new Product($product_id);
		
		if($Product->delete())
			$this->TPL->delete_success = TRUE;
	}
	
	private function _loadProducts()
	{
		$member_id = $this->DB->escape(Session::get(array('member','id')));
		$query = <<<SQL
			SELECT 
				`p`.*
			FROM 
				`products` as `p`
 			WHERE 
 				`producer_id` = '$member_id' AND 
 				`active` = 1
SQL;
	
		$Result = $this->DB->execute($query);
		if(!$Result)
		{
			Error::set('products.load.error');
			return FALSE;
		}
		if($Result->numRows() > 0)
		{
			$products = array();
			foreach($Result as $Row)
			{
				$products[$Row->id] = new _(array(
						'id'			=>$Row->id,
						'name'			=>$Row->name,
						'description'	=>$Row->description,
						'units'			=>$Row->units,
						'price'			=>$Row->price,
						'count'			=>$Row->count,
						'categories'	=>array()	
				));
			}
			$this->_loadProductCategories($products);
			$this->TPL->products = $products;			
		}
	}
	
	private function _loadProductCategories(&$products)
	{
		$product_ids = '('.implode(',',array_keys($products)).')';

		$query = <<<SQL
			SELECT
				`pc`.*,
				`c`.`name_hr` 
			FROM 
				`product_categories` as `pc`,
				`categories` as `c`
 			WHERE 
 				`pc`.`product_id` IN $product_ids AND
				`pc`.`category_id` = `c`.`id`
SQL;
	
		$Result = $this->DB->execute($query);
		if(!$Result)
		{
			Error::set('products.load.categories.error');
			return FALSE;
		}
		if($Result->numRows() > 0)
		{
			foreach($Result as $Row)
			{
				$products[$Row->product_id]->categories[$Row->category_id] = $Row->name_hr;
			}
		}
	}
}
?>