<?PHP
require DIR_CLASS.'/Cycle.php';
require DIR_CLASS.'/Order.php';

class PageController extends Controller
{
	public $order_id = FALSE;
	public $cycle_id = FALSE;
	public $page_level = LEVEL_MEMBER;
	public $template = 'members.order.view.tpl.php';
	
	public function setup()
	{
		$this->TPL->assign(array(
			'viewing_order'		=> TRUE,
			'member_page'		=> TRUE,
			'orders'			=> FALSE,
			'order_id'			=> FALSE,
			'order_saved'		=> (isset($_GET['saved'])),
			'already_ordered'	=> TRUE
			)
		);
		parent::setup();
	
		$this->loadOrders();
		
		if(isset($_GET['id']))
		{
			$this->order_id = cleanGPC($_GET['id']);
			$this->loadOrder();
			$this->loadProducts();
		}
	}
	
	public function process()
	{	
		if(isset($_POST['load_order']))
		{
			header('Location: '.SITE_URL.'/members/order/view/?id='.$_POST['active_cycles']);
			exit();
		}
		if(isset($_POST['submit']))
		{
			//Prepare the list
			$list = array();
			foreach($_POST['products'] as $product_id=>$count)
			{
				$Item = new stdClass();
				$Item->count = $count;
				$list[$product_id] = $Item;
			}
			
			$Order = new Order($_POST['id']);
				
			# Order will fail to load if user reloads page - their
			# old order id no longer exists.  However, reloading
			# doesn't result in any new changes, so just silently do nothing
			if(!$Order->loaded)
			{
				Error::clear();
			}
			else
			{
				if($Order->save($list,$this->cycle_id))
				{
					//redirect user to page for new order ID
					header('Location: '.SITE_URL.'/members/order/view/?id='.$Order->id.'&saved');
					exit();
					
				}
			}
		}
	}
	
	public function loadProducts()
	{
		$DB = DB::getInstance();
		$db_order_id = $DB->escape($this->order_id);
		$query = <<<SQL
			SELECT
				`products`.`id`,
				`products`.`name`,
				`products`.`description`,
				`products`.`price`,
				`products`.`units`,
				`products`.`count`,
				`products`.`producer_id`,
				`producers`.`name` AS 'producer_name'
			FROM 
				`products`,
				`producers`,
				`cycle_categories` AS `cc`,
				`product_categories` AS `pc`,
				`orders`
			WHERE 
				(`count` > 0 OR `count` IS NULL) AND 
				`active` = 1 AND 
				`products`.`producer_id` = `producers`.`member_id` AND
				`orders`.`id` = '$db_order_id' AND
				`orders`.`cycle_id` = `cc`.`cycle_id` AND
				`cc`.`category_id` = `pc`.`category_id` AND
				`products`.`id` = `pc`.`product_id`
			ORDER BY
				`producers`.`name` ASC,
				`products`.`name` ASC
SQL;

		$Result = $DB->execute($query);
		if(!$Result)
		{
			Error::set('order.products.load.error');
			return FALSE;
		}
		else if($Result->numRows() != 0)
		{
			$products = array();
			
			foreach($Result as $Row)
			{
				if(!isset($products[$Row->producer_id]))
				{
					$products[$Row->producer_id] = new _(array(
							'name'     =>$Row->producer_name,
							'products' =>array()
						));
				}
				
				$products[$Row->producer_id]->products[$Row->id] = new _(array(
						'id'			=>$Row->id,
						'name'			=>$Row->name,
						'description'	=>$Row->description,
						'price'			=>$Row->price,
						'units'			=>$Row->units,
						'count'			=>$Row->count
					));				
			}
			
			$this->TPL->products = $products;
		}
	}
	
	#
	# Loads all orders for the user
	#
	private function loadOrders()
	{
		$DB = DB::getInstance();
		$member_id = $DB->escape(Session::get(array('member','id')));
		
		$query = <<<SQL
			SELECT
				`o`.`id`
			FROM
				`orders` AS `o`
			WHERE
				`o`.`member_id` = '$member_id'
			ORDER BY
				`o`.`time_placed` DESC
SQL;
		$Result = $DB->execute($query);
		if(!$Result)
			Error::set('cycles_load_fail');
		elseif(!$Result->numRows())
			$this->current_cycle = FALSE;
		else
		{
			$orders = array();
			if($Result->numRows())
			{
				foreach($Result as $Row)
				{
					$orders[$Row->id] = new Order($Row->id);
				}
			}
			
			$this->TPL->orders = $orders;
		}
	}
	
	##
	# Function: loadOrder()
	# Purpose: To load the ordered items
	#
	private function loadOrder()
	{	
		$DB = DB::getInstance();
	
		$member_id = $DB->escape(Session::get(array('member','id')));
		$order_id = cleanGPC($_GET['id']);
	
		$Order = new Order($order_id);
		if($Order->loaded)
		{
			if(!$Order->Member->id == $member_id)
				Error::set('order.not_yours');
			else
			{
				$this->order_id = $order_id;
				$this->cycle_id = $Order->cycle_id;
				$this->TPL->assign(array(
					'order_id'				=>$Order->id,
					'order_can_be_updated'	=>$Order->inEditWindow(),
					'ordered_items'			=>$Order->items
				));
			}
		}
	}			
}