<?PHP

class PageController extends Controller
{
	public $page_level = LEVEL_PRODUCER;
	public $template = 'producers.product.edit.tpl.php';
	
	public function setup()
	{
		$this->TPL->producer_page = TRUE;
		$product_id = cleanGPC($_GET['id']);
		$Product = new Product($product_id);
		$this->TPL->assign(array(
			'id'			=>$Product->id,
			'name'			=>$Product->name,
			'description'	=>$Product->description,
			'units'			=>$Product->units,
			'price'			=>$Product->price,
			'count'			=>$Product->count,
			'categories'	=>$Product->categories,
			'all_categories'=>ObjectList::load('category')
			)
		);
	}
	
	public function process()
	{
		if(isset($_POST['edit']))
		{
			$posted = array_map('cleanGPC',$_POST);
			
			$Product 				= new Product($posted['id']);
			$Product->name 			= $posted['name'];
			$Product->description 	= $posted['description'];
			$Product->units			= $posted['units'];
			$Product->price			= $posted['price'];
			$Product->count			= $posted['count'];
			
			$Product->categories = array();
			if(isset($posted['categories']))
			{
				foreach($posted['categories'] as $id)
				{
					//we can just set to TRUE' as Product->edit only checks for existence of keys
					$Product->categories[$id] = TRUE;
				}
			}
			
			if($Product->edit())
			{
				if(!isset($_GET['ajax']))
					$this->TPL->assign(array(
						'name'			=>$Product->name,
						'description'	=>$Product->description,
						'units'			=>$Product->units,
						'price'			=>$Product->price,
						'count'			=>$Product->count,
						'categories'	=>$Product->categories,
						'edit_success'	=>TRUE
						)
					);
				else
				{
					echo json_encode(array('ok'=>TRUE));
					exit();
				}
			}
			//if there was an error & the page is being called via ajax
			elseif(isset($_GET['ajax']))
			{
				echo json_encode(array('ok'=>FALSE,'error'=>Error::get()));
				exit();
			}
		}
	}
			
}

?>