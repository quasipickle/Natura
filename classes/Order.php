<?php
/*
TODO:
	- If an order is modified, need to update the inventory total has to be updated
	  to reflect the potential difference (ie: initial order was for 4 whatsits, but updated
	  to only have 1.  Inventory count of whatsits needs to be increased by 3.
*/
require_once DIR_CLASS.'/Product.php';
class Order
{
	public $id;
	public $DB;
	public $Cycle;
	public $time_placed = 0;
	public $loaded = FALSE;
	
	//Member who placed the order.  Populated by load();
	public $Member = FALSE;
	
	//Array of generic objects indexed by product_id
	//If generated from load(), will contain "product_id", "product_name", "price", and "count" properties
	//otherwise (ie: if generated from a form submission) will only have "count" property
	public $items;
	
	public function __construct($order_id = FALSE)
	{
		$this->DB = DB::getInstance();
		if($order_id != FALSE)
		{
			$this->id = $order_id;
			$this->load();
		}
	}
	
	public function load()
	{
		$query = <<<SQL
			SELECT
				`members`.*,
				`products`.`id` as `product_id`,
				`products`.`name`,
				`orders`.`time_placed`,
				`orders`.`cycle_id`,
				`order_items`.`price`,
				`order_items`.`count`
			FROM
				`members`,
				`orders`,
				`order_items`,
				`products`
			WHERE
				`orders`.`id` = '$this->id' AND
				`order_items`.`order_id` = '$this->id' AND	
				`members`.`id` = `orders`.`member_id` AND
				`order_items`.`product_id` = `products`.`id`
SQL;
		$Result = $this->DB->execute($query);
		if(!$Result)
		{
			Error::set('order.load.fail');
			return FALSE;
		}
		else
		{
			if($Result->numRows() > 0)
			{
				foreach($Result as $Row)
				{
					$this->time_placed_stamp = strtotime($Row->time_placed);//time_placed is MySQL datetime stamp
					$this->time_placed = $Row->time_placed;
					$this->Cycle = new Cycle($Row->cycle_id);
	
					$Item = new stdClass();
					$Item->product_id = $Row->product_id;
					$Item->product_name = $Row->name;
					$Item->price = $Row->price;
					$Item->count = $Row->count;
					
					$this->items[$Row->product_id] = $Item;
				}
				
				//member info will be included in every row, so we can load it from last row
				$this->Member 				= new Member();
				$this->Member->id			= $Row->id;
				$this->Member->email 		= $Row->email;
				$this->Member->first_name 	= $Row->first_name;
				$this->Member->last_name	= $Row->last_name;
				$this->Member->phone		= $Row->phone;		
				
				$this->loaded = TRUE;
			}
			else
			{
				Error::set('order.load.none');
				return FALSE;
			}
		}
		
		return TRUE;
	}
	
	
	
	##
	# Function: save()
	# Purpose: To save an order
	# Parameters: $item_list (array): same format as this::$items, 
	#								  but with only "count" properties
	# Returns: TRUE if order was saved, FALSE if not
	#
	# Notes: Changes made to the database are atomic and
	#        transactional.  Table is locked while queries are being run
	#        to ensure inventory amounts are properly altered
	public function save($item_list,$cycle_id)
	{
		# Make sure order can be placed
		if(!$this->inEditWindow())
		{
			Error::set('order.edit_window.expired');
			return FALSE;
		}
		# Make sure list is valid
		if(!$this->checkList($item_list))
			return FALSE;
			
		# Condense list to just ordered items
		foreach($item_list as $product_id=>$Item)
		{
			if(!strlen($Item->count) || (int)$Item->count != $Item->count)
				unset($item_list[$product_id]);
		}
		
		# generate queries early, so we don't have to wait for them
		# to be generated while the tables are locked
		$order_queries = $this->createOrderQueries($item_list,$cycle_id);	
		
		
		$this->lockTables();
		$this->startTransaction();
		
		$order_success = FALSE;
	
		if(!$this->id)
			$order_success = $this->createOrder($item_list,$order_queries);
		else
			$order_success = $this->updateOrder($item_list,$order_queries);
		
		$this->endTransaction($order_success);
		$this->unlockTables();
		
		return $order_success;
	}
	
	function createOrder($item_list,$order_queries)
	{
		if($this->enoughInventory($item_list))
			if($this->placeOrder($order_queries,$item_list))
				return TRUE;		
		
		return FALSE;		
	}
	
	function updateOrder($item_list,$order_queries)
	{
		if($this->delete($order_queries['increment']))
			if($this->enoughInventory($item_list))
				if($this->placeOrder($order_queries,$item_list))
					return TRUE;
		return FALSE;
	}
	
	##
	# Function: placeOrder()
	# Purpose: To run the order queries, effectively recording the order
	##
	private function placeOrder($queries,$item_list)
	{
		# Create the order
		$Result = $this->DB->execute($queries['order']);
		if($Result)
		{
			$order_id = $this->DB->lastInsertID();
			$items_query = str_replace('%ORDER_ID%',$order_id,$queries['items']);
			$Result = $this->DB->execute($items_query);
			if($Result)
			{
				$Result = $this->DB->execute($queries['decrement'],TRUE);			
				if($Result)
				{
					$this->id = $order_id;
					$this->items = $item_list;
					return TRUE;
				}
				else//if count query failed
				{
					Error::set('order.decriment.fail');
				}
			}
			else//if items query failed
			{
				Error::set('order.items.fail');
			}
		}
		else if($this->DB->errno() == 1062)//if order query failed
			Error::set('order.exists');
		else
		{
			Error::set('order.order.fail');
		}
		
		return FALSE;

	}
	
	##
	# Function: delete()
	# Purpose: To delete this order & reset the product inventory counts
	# Parameters: $increment_query: the query that was generated to update
	#                               all the product counts
	#			   [optional]  If not provided, the query will be generated
	##
	public function delete($increment = FALSE)
	{
		if(!$increment)
		{
			list(,$increment) = $this->createCountQueries($this->items);
		}
	
		$order_id = $this->DB->escape($this->id);
		$query = <<<SQL
			DELETE
			FROM
				`orders`
			WHERE
				`id` = '$order_id'
SQL;
		$Result = $this->DB->execute($query);
		if(!$Result)
			Error::set('order.delete.fail');
		else
		{			
			$Result = $this->DB->execute($increment,TRUE);
			if(!$Result)
			{
				Error::set('order.increment.fail');
			}
			else
			{
				return TRUE;
			}
		}
		
		return FALSE;
	}
	
	##
	# Function: inEditWindow()
	# Purpose: To determine if this order was initially placed within the
	#          last 24 hours
	# Returns: TRUE if the order can be updated
	#          FALSE if not
	# Note: Does not generate an error
	#
	public function inEditWindow()
	{
		if($this->time_placed != 0 && $this->time_placed < strtotime('-24 hours',time()))
			return FALSE;
		else
			return TRUE;
	}
	
	
	##
	# Function: checkList()
	# Purpose: To check the passed list to make sure it contains ordered items
	#		   Specifically, ensures size of list > 0 & at least 1 item has
	#		   been ordered
	# Parameters: $item_list (array): Same as save()
	# Returns: TRUE if everything is OK
	#          FALSE otherwise.
	##
	private function checkList($item_list)
	{
		// check that an item list was provided
		if(count($item_list) == 0)
		{
			Error::set('order.products.empty');
			return FALSE;
		}
		
		// check that at least one item was ordered
		else
		{
			$no_items = TRUE;
			$bad_format = FALSE;
			foreach($item_list as $Item)
			{
				if(is_numeric($Item->count) && (int)$Item->count == $Item->count)
					$no_items = FALSE;
				else if((int)$Item->count != $Item->count)
					$bad_format = TRUE;
			}
			if($no_items)
			{
				Error::set('order.products.no_count');
				return FALSE;
			}
			if($bad_format)
			{
				Error::set('order.products.amount.bad');
				return FALSE;
			}
		}
		return TRUE;
	}
	
	##
	# Function: createOrderQueries()
	# Purpose: To generate the queries that will be run to create the order
	#          These queries are generated well before they are run, to minimize
	#          the time the tables are locked to verify/decriment inventory
	##
	private function createOrderQueries($item_list,$cycle_id)
	{
		$order_query = $this->createOrderQuery($cycle_id);
		$items_query = $this->createItemsQuery($item_list);
		list($decrement_query,$increment_query) = $this->createCountQueries($item_list);
		
		return array(
			'order'		=>$order_query,
			'items'		=>$items_query,
			'decrement'	=>$decrement_query,
			'increment'	=>$increment_query
		);
	}
	
	private function createOrderQuery($cycle_id)
	{
		# Create order query
		$member_id = $this->DB->escape(Session::get(array('member','id')));
		$cycle_id = $this->DB->escape($cycle_id);
		$query = <<<ORDER
			INSERT
			INTO
				`orders`
				(`member_id`,
				 `cycle_id`,
				 `time_placed`)
			VALUES
				('$member_id',
				 '$cycle_id',
				 NOW())
ORDER;
		return $query;
	}
	
	private function createItemsQuery($item_list)
	{
		# Create order items query
		$query = <<<ITEMS
			INSERT
			INTO
				`order_items`
				(`order_id`,
				 `product_id`,
				 `price`,
				 `count`)
			VALUES
ITEMS;
		foreach($item_list as $product_id=>$Item)
		{
			$Product = new Product($product_id);
			$product_id = $this->DB->escape($product_id);
			$count = $this->DB->escape($Item->count);
			
			# Price is included so orders record the price the member saw when they ordered,
			# which may not necessarily be the price when the cycle closes
			$price = $this->DB->escape($Product->price);
		
			//%ORDER_ID% will be replaced by the actual ID after the above query is actually run
			$query .= <<<ITEM
				('%ORDER_ID%',
				 '$product_id',
				 '$price',
				 '$count'),
ITEM;
		}
		$query = rtrim($query,',');
		
		return $query;
	}
	
	private function createCountQueries($item_list)
	{
		// decrement query is based on passed $item_list, as it
		// will be decrementing by what is entered on the page
		$decrement = '';
		if(count($item_list))
		{
			foreach($item_list as $product_id=>$Item)
			{
				$product_id 	= (int)$product_id;
				$count 			= (int)$Item->count;
				
				$decrement .= <<<COUNT
					UPDATE
						`products`
					SET
						`count` = `count` - $count
					WHERE
						`id` = $product_id;
COUNT;
			}
			$decrement = rtrim($decrement,';');
		}
		// increment query is based on what is stored in the DB,
		// as it will be incrementing based on what the order already is
		$increment = '';
		if($this->items)
		{
			foreach($this->items as $product_id=>$Item)
			{
				$product_id 	= (int)$product_id;
				$count 			= (int)$Item->count;
				
				$increment .= <<<COUNT
					UPDATE
						`products`
					SET
						`count` = `count` + $count
					WHERE
						`id` = $product_id;
COUNT;
			}
			$increment = rtrim($increment,';');
		}
		
		return array($decrement,$increment);
	}

	
	
	##
	# Function: lockTables()
	# Purpose: To lock the tables relevant to creating an order
	#
	private function lockTables()
	{
		$query = <<<LOCK
			LOCK
			TABLES
				`products` WRITE,
				`orders` WRITE,
				`order_items` WRITE
LOCK;
		$Result = $this->DB->execute($query);
		if(!$Result)
		{
			Error::set('order.lock.unable');
			return FALSE;
		}
		else
			return TRUE;
	}
	
	##
	# Function: unlockTables()
	# Purpose: To unlock the tables relevant to creating an order
	#
	private function unlockTables()
	{
		$query = 'UNLOCK TABLES';
		$Result = $this->DB->execute($query);
		if(!$Result)
		{
			Error::set('order.unlock.unable');
			return FALSE;
		}
		else
			return TRUE;
	}
	
	##
	# Function: startTransaction()
	# Purpose: To start the transaction so atomic queries can be run
	#
	private function startTransaction()
	{
		# Start the transaction
		$Result = $this->DB->execute('START TRANSACTION');
		if(!$Result)
		{
			Error::set('order.transaction.start.fail');
			return FALSE;
		}
		
		return TRUE;
	}
	
	
	##
	# Function: endTransaction()
	# Purpose: To end the transaction
	# Parameters: $success (boolean): if TRUE, the transaction will be COMMITed
	#								  otherwise it will be ROLLBACKed
	#
	private function endTransaction($success = FALSE)
	{
		if($success)
		{
			$Result = $this->DB->execute('COMMIT');
			if(!$Result)
			{
				Error::set('order.transaction.commit.fail');
				return FALSE;
			}
			else
				return TRUE;
		}
		else
		{
			//if we get to this point, something failed & we need to rollback
			//don't watch for errors, because any error would overwrite the error thrown
			//that caused us to get here
			$this->DB->execute('ROLLBACK');
			return TRUE;
		}
	}
	
	##
	# Function: enoughInventory()
	# Purpose: To determine if there is enough inventory for the order
	#
	private function enoughInventory($item_list)
	{
		$query = <<<SQL
			SELECT
				`id`,
				`count`
			FROM
				`products`
			WHERE
				`id` IN (
SQL;
		foreach($item_list as $product_id=>$Item)
		{
			$query .= $this->DB->escape($product_id).',';
		}
		$query = rtrim($query,',').')';
		
		$Result = $this->DB->execute($query);
		if(!$Result)
		{
			Error::set('order.inventory.check');
			return FALSE;
		}
		
		foreach($Result as $Row)
		{
			if(!is_null($Row->count) && $item_list[$Row->id]->count > $Row->count)
			{
				Error::set('order.inventory.inadequate');
				return FALSE;
			}
		}	
		return TRUE;
	}
	
	##
	# Function: generateInvoice()
	# Purpose: To generate an invoice file for this order
	# Parameters: $type ("txt" || "csv"): the format of the file
	# Returns: The filename of the generated file, or FALSE if something went wrong
	##
	public function generateInvoice($type = 'txt')
	{
		$Order = new stdClass();
		$Order->id					= $this->id;
		$Order->member_first_name 	= $this->Member->first_name;
		$Order->member_last_name 	= $this->Member->last_name;
		$Order->total 				= $this->total;
		$Order->items 				= $this->items;
		
		$TPL = new Template();
		$TPL->Order = $Order;
		$output = ($type == 'txt') 
					? $TPL->fetch('summary.txt.tpl.php')
					: $TPL->fetch('summary.csv.tpl.php');
		
	
		$filename = 'invoice - '.$Order->member_last_name.', '.$Order->member_first_name;
		$filename = ($type == 'txt')
					? DIR_TMP.'/'.$filename.'.txt'
					: DIR_TMP.'/'.$filename.'.csv';
					
		if(FALSE === file_put_contents($filename,$output))
		{
			Error::set('order.generate_invoice.fail',array('%ID%',$Order->id));
			return FALSE;
		}
		else
			return $filename;
	}
		
	
	
	public function __get($name)
	{
		if($name == 'total')
		{
			$total = 0;
			
			# Only generate a total if there are items, and each item has more than just the 'count' property
			if(count($this->items) && isset(current($this->items)->price))
			{
				foreach($this->items as $item)
				{
					$total += ($item->price * $item->count);
				}
			}
			
			return $total;
		}
	}
}