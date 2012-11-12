<?PHP

class PageController extends Controller
{
	public $order_id = FALSE;
	public $cycle_id = FALSE;
	public $page_level = LEVEL_MEMBER;
	public $template = 'members.order.view.tpl.php';
	
	public $Member;
	
	public function setup()
	{
		$this->TPL->assign(array(
			'member_page'		=> TRUE,
			'viewing_order'		=> TRUE,
			'orders'			=> FALSE,
			'order_id'			=> FALSE,
			'order_saved'		=> (isset($_GET['saved'])),
			'already_ordered'	=> TRUE
			)
		);
		
		$this->Member = new Member(Session::get(array('member','id')));		
		parent::setup();
	
	}
	
	public function process()
	{	
		/* If we're viewing one order, load it */
		if(isset($_GET['id']))
		{
			$this->order_id = cleanGPC($_GET['id']);
			$this->loadOrder();
			$this->loadProducts();
		}
		
		/* Otherwise, load all of the orders */
		else
			$this->loadOrders();	


		if(isset($_POST['submit']))
			$this->saveOrder();
		if(isset($_GET['download']))
			$this->downloadOrder();
	}
	
	public function saveOrder()
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
			if($Order->save($list,$Order->Cycle->id))
			{
				//redirect user to page for new order ID
				header('Location: '.SITE_URL.'/members/order/view/?id='.$Order->id.'&saved');
				exit();
				
			}
		}
	}
	
	/* Load all the products available for the order cycle this order was placed in */
	public function loadProducts()
	{
		$DB = DB::getInstance();
		$db_order_id = $DB->escape($this->order_id);
		$query = <<<SQL
			SELECT
				`products`.`id`,
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
			$this->TPL->products  = generateOrderProductList($Result);//in funclib.php
		}
	}
	
	#
	# Loads all orders for the user
	#
	private function loadOrders()
	{
		$DB = DB::getInstance();
		$member_id = $DB->escape($this->Member->id);
		
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
	
		$member_id = $DB->escape($this->Member->id);
		$order_id = cleanGPC($_GET['id']);
	
		$Order = new Order($order_id);
		if($Order->loaded)
		{
			if(!$Order->Member->id == $member_id)
				Error::set('order.not_yours');
			else
			{
				$this->order_id = $order_id;
				$this->cycle_id = $Order->Cycle->id;
				
				$this->TPL->assign(array(
					'order_id'				=>$Order->id,
					'order_can_be_updated'	=>$Order->inEditWindow(),
					'ordered_items'			=>$Order->items
				));
			}
		}
	}
	
	##
	# Function: loadPastOrder()
	# Purpose: To load a previous order
	# Parameters: $id (int): The ID to use to load the order
	##
	public function loadPastOrder($id)
	{
		$Order = $this->Member->loadOrder($id,'order');
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
		return $Order;
	}
	
	private function downloadOrder()
	{
		$Order = $this->loadPastOrder($this->order_id,'order');
		$Order->generateInvoice(TRUE);# TRUE forces download
	}	
}