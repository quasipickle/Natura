<?PHP
require DIR_CLASS.'/Product.php';

class PageController extends Controller
{
	public $page_level = LEVEL_PRODUCER;
	public $template = 'producers.products.view.tpl.php';
	private $DB;
	
	public function setup()
	{
		$this->TPL->producer_page = TRUE;
		$this->TPL->products      = FALSE;
		$this->DB                 = DB::getInstance();
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
				*
			FROM
				`products`
			WHERE
				`producer_id` = '$member_id' AND
				`active` = 1
SQL;
		$Result = $this->DB->execute($query);
		if(!$Result)
			Error::set('products.load.error');
		if($Result->numRows() > 0)
		{
			$products = array();
			foreach($Result as $Row)
			{
				$products[] = new _(array(
					'id'			=>$Row->id,
					'name'			=>$Row->name,
					'description'	=>$Row->description,
					'units'			=>$Row->units,
					'price'			=>$Row->price,
					'count'			=>$Row->count	
				));
			}
			$this->TPL->products = $products;
		}
	}
}
?>