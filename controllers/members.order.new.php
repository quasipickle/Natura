<?PHP

class PageController extends Controller
{
	public $page_level = LEVEL_MEMBER;
	public $active_cycle = FALSE;
	public $already_ordered = FALSE;
	public $template = 'members.order.new.tpl.php';
	
	public function setup()
	{
		$this->TPL->assign(array(
			'member_page'          => TRUE,
			'active_cycles'        => FALSE,
			'active_cycle'         => FALSE,
			'order_created'        => FALSE,
			'order_id'             => FALSE,
			'order_can_be_updated' => TRUE,
			'already_ordered'      => FALSE,
			'products'             => FALSE
			)
		);
		parent::setup();
		
		$this->loadCurrentCycles();
		
		if(isset($_POST['load_products']))
			$this->active_cycle = $_POST['active_cycles'];
		else if(isset($_GET['cycle']))
			$this->active_cycle = $_GET['cycle'];
			
		if($this->active_cycle)
		{
			$this->TPL->active_cycle    = $this->active_cycle;
			$Cycle                      = new Cycle($this->active_cycle);
			$this->already_ordered      = $this->orderPlaced($Cycle);
			$this->TPL->already_ordered = $this->already_ordered;		
		}
		
		if(!$this->already_ordered)
			$this->loadProducts();
	}
	
	public function process()
	{
		if(isset($_POST['submit']))
		{
			//Prepare the list
			$list = array();
			foreach($_POST['products'] as $product_id=>$count)
			{
				$Item              = new stdClass();
				$Item->count       = $count;
				$list[$product_id] = $Item;
			}
			
			$Cycle = new Cycle($_POST['active_cycle']);
			
			$Order = new Order();
			if($Order->save($list,$Cycle->id))
			{
				$this->TPL->order_id      = $Order->id;
				$this->TPL->order_edit_until_stamp = $Order->time_edit_until_stamp;
				$this->TPL->order_created = TRUE;
			}
			else
				$this->TPL->ordered_items = $list;
		}
	}
	
	private function loadCurrentCycles()
	{
		$DB = DB::getInstance();
		
		$query = <<<SQL
			SELECT
				*
			FROM
				`cycles`
			WHERE
				DATE(NOW()) BETWEEN `start` AND `end`
SQL;
		$Result = $DB->execute($query);
		if(!$Result)
			Error::set('cycles_load_fail');
		elseif(!$Result->numRows())
			$this->current_cycle = FALSE;
		else
		{
			$active_cycles = array();
			foreach($Result as $Row)
			{
				$active_cycles[$Row->id] = $Row->name;
			}
			$this->TPL->active_cycles = $active_cycles;
		}
	}
			
			
	public function loadProducts()
	{
		if($this->active_cycle)
		{
			$DB = DB::getInstance();
			$active_cycle = $DB->escape($this->active_cycle);
			
			$query = <<<SQL
				SELECT
					`products`.`id`,
					`c`.`name_hr`,
					`products`.`producer_id`,
					`producers`.`name` AS 'producer_name' 
				FROM 
					`products`,
					`producers`,
					`categories`		 AS `c`,
					`cycle_categories`   AS `cc`,
					`product_categories` AS `pc`
				WHERE 
					(`count` > 0 OR `count` IS NULL) AND 
					`active` = 1 AND 
					`products`.`producer_id` = `producers`.`member_id` AND
					`cc`.`cycle_id` = '$active_cycle' AND
					`c`.`id` = `cc`.`category_id` AND
					`cc`.`category_id` = `pc`.`category_id` AND
					`products`.`id` = `pc`.`product_id`
				GROUP BY
					`products`.`id`
				ORDER BY
					`producers`.`name` ASC,
					`products`.`name` ASC
SQL;

			$Result = $DB->execute($query);
			if(!$Result)
			{
				echo $DB->error();
				Error::set('order.products.load.error');
				return FALSE;
			}
			else if($Result->numRows() != 0)
			{
				$this->TPL->products = generateOrderProductList($Result);//in funclib.php
			}
		}
	}
	
	
	##
	# Function: orderPlaced()
	# Purpose: To determine if an existing order has been placed for the passed cycle
	#
	private function orderPlaced($Cycle)
	{	
		$DB = DB::getInstance();
	
		$member_id = $DB->escape(Session::get(array('member','id')));
		$cycle_id = $DB->escape($Cycle->id);
		
		//Don't Order::load(), as all we want to know is if it exists
		$query = <<<SQL
			SELECT
				`orders`.`id`
			FROM
				`orders`
			WHERE
				`cycle_id` = '$cycle_id' AND
				`member_id` = '$member_id'
SQL;

		$Result = $DB->execute($query);
		if($Result && $Result->numRows() > 0)
		{
			$Row = $Result->getRow();
			return $Row->id;
		}
		else
			return FALSE;
	}
}