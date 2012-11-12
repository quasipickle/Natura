<?PHP
class PageController extends Controller
{
	public $page_level = LEVEL_PRODUCER;
	public $template = 'producers.orders.tpl.php';

	public function setup()
	{
		$this->TPL->assign(array(
			'producer_page'			=>TRUE,
			'old_cycles'			=>FALSE,
			'cycle_id'				=>FALSE,
			'showing_current_cycle'	=>FALSE,
			'active_cycle'			=>FALSE,
			'download'				=>FALSE,
		));

		if(isset($_GET['download']))
			$this->downloadPO();
		else
		{
			$this->loadOrders();
			$this->loadOldCycles();
		}
	}
	
	public function loadOrders()
	{
		$DB = DB::getInstance();
		$producer_id = Session::get(array('member','id'));		
		$Producer = new Producer($producer_id);
		
		if(isset($_GET['cycle']))
		{
			$this->cycle_id = cleanGPC($_GET['cycle']);
			$Cycle = new Cycle($this->cycle_id);
			$Producer->loadOrderAmounts($Cycle->id);
			$orders_info = array(
				'producer_name'		=>$Producer->business_name,
				'cycle_id'			=>$Cycle->id,
				'cycle_name'		=>$Cycle->name,
				'cycle_start_stamp'	=>$Cycle->start_stamp,
				'cycle_end_stamp'	=>$Cycle->end_stamp,
				'amounts'			=>$Producer->orders,
				'active_cycle'		=>$Cycle->isActive(),
				'order_grand_total'	=>$Producer->order_grand_total
			);
			
			$this->TPL->assign($orders_info);
		}
	}	
	
	##
	# Function: loadOldCycles()
	# Purpose: To load all old cycles this producer has orders in
	##
	public function loadOldCycles()
	{
		$DB = DB::getInstance();
		$producer_id = $DB->escape(Session::get(array('member','id')));

		$query = <<<SQL
			SELECT
				`cycles`.*
			FROM
				`cycles`,
				`orders`,
				`order_items`,
				`products`,
				`producers`
			WHERE
				`cycles`.`id` = `orders`.`cycle_id` AND
				`orders`.`id` = `order_items`.`order_id` AND
				`order_items`.`product_id` = `products`.`id` AND
				`products`.`producer_id` = `producers`.`member_id` AND
				`producers`.`member_id` = '$producer_id'
			GROUP BY
				`cycles`.`start` DESC
SQL;

		$Result = $DB->execute($query);
		if(!$Result)
		{
			Error::set('producer.cycles.old');
			return FALSE;
		}
		else if($Result->numRows())
		{
			$old_cycles = array();
			foreach($Result as $Row)
			{
				$old_cycles[] = new _(array(
					'id'    =>$Row->id,
					'name'  =>$Row->name,
					'start' =>$Row->start,
					'end'   =>$Row->end
				));
			}
			$this->TPL->old_cycles = $old_cycles;
		}
	}
	
	public function downloadPO()
	{
		$this->cycle_id = cleanGPC($_GET['cycle']);
		$Cycle = new Cycle($this->cycle_id);
		
		$Producer = new Producer(Session::get(array('member','id')));
		$Producer->generatePurchaseOrder($Cycle,TRUE);
	}
}
	