<?PHP

class Product
{
	public $id;
	
	# the owning member id.  Called producer_id because it makes more sense in this context
	public $producer_id;
	public $name;
	public $description;
	public $units;
	public $price;
	public $count = NULL;
	public $categories = array();
	
	private $DB;
	
	public function __construct($product_id = FALSE)
	{
		$this->DB = DB::getInstance();
		if($product_id !== FALSE)
		{
			$this->id = $product_id;
			$this->load();
		}
	}
	
	#
	# Function: load()
	# Purpose: To load the product information from the database
	# Returns: boolean TRUE if loaded, FALSE if some problem
	#
	private function load()
	{
		$id = $this->DB->escape($this->id);
		
		$query = <<<SQL
			SELECT
				*
			FROM
				`products`
			WHERE
				`id` = '$id'
SQL;
		$Result = $this->DB->execute($query);
		if(!$Result)
		{
			Error::set('product.load.fail');
			return FALSE;
		}
		else if($Result->numRows() == 0)
		{
			Error::set('product.load.none',array('%NUMBER%'=>$this->id));
			$this->id = FALSE;
			return FALSE;
		}
		else
		{
			$Row = $Result->getRow();
			$this->producer_id 	= $Row->producer_id;
			$this->name 		= $Row->name;
			$this->description 	= $Row->description;
			$this->units 		= $Row->units;
			$this->price 		= $Row->price;
			$this->count 		= $Row->count;
		}
		
		return($this->loadCategories());
	}
	
	#
	# Function: loadCategories()
	# Purpose: To load the Categories this Product is associated with
	#
	private function loadCategories()
	{
		$db_id = $this->DB->escape($this->id);
		$query = <<<SQL
			SELECT
				`c`.`id`
			FROM
				`product_categories` AS `pc`,
				`categories` AS `c`
			WHERE
				`product_id` = '$db_id' AND
				`pc`.`category_id` = `c`.`id`
SQL;
		$Result = $this->DB->execute($query);
		if(!$Result)
		{
			Error::set('product.category.load.fail');
			return FALSE;
		}
		else if($Result->numRows())
		{
			$ids = array();
			foreach($Result as $Row)
			{
				$ids[] = $Row->id;
			}
			$this->categories = ObjectList::load('category',$ids);
		}
		return TRUE;
	}
	
	
	#
	# Function: create()
	# Purpose: To create a new product
	#
	public function create()
	{
		if($this->_checkLength() && $this->_checkFormat())
		{
			$producer_id 	= $this->DB->escape($this->producer_id);
			$name 			= $this->DB->escape($this->name);
			$description 	= $this->DB->escape($this->description);
			$units 			= $this->DB->escape($this->units);
			$price 			= $this->DB->escape($this->price);
			//need to include quotes for actual values, because we can't blanket assume all possible values,
			//ie NULL can have quotes
			$count 			= ($this->count === NULL) ? 'NULL' : "'".$this->DB->escape($this->count)."'";
			
			$query = <<<SQL
				INSERT
				INTO
					`products`
					(`producer_id`,
					 `name`,
					 `description`,
					 `units`,
					 `price`,
					 `count`)
				VALUES
					('$producer_id',
					 '$name',
					 '$description',
					 '$units',
					 '$price',
					 $count)
SQL;
			$Result = $this->DB->execute($query);
			if(!$Result)
			{
				# 1452 means a foreign key constraint failed, which means the producer_id
				# does not map to a valid user id
				if($this->DB->errno() == 1452)
					Error::set('product.create.no_producer',array('%NUMBER%',$this->producer_id));
				else
					Error::set('product.create.fail');
				
				return FALSE;
			}
			else
			{
				$this->id = $this->DB->lastInsertID();
				return $this->addCategories();
			}
		}
	}
	
	public function edit()
	{
		if($this->_checkLength() && $this->_checkFormat())
		{
			$product_id 	= $this->DB->escape($this->id);
			$name 			= $this->DB->escape($this->name);
			$description 	= $this->DB->escape($this->description);
			$units 			= $this->DB->escape($this->units);
			$price 			= $this->DB->escape($this->price);
			$count 			= (strlen($this->count)) 
								? "'".$this->DB->escape($this->count)."'"
								: 'NULL';
			
			$query = <<<SQL
				UPDATE
					`products`
				SET
					`name` = '$name',
					`description` = '$description',
					`units` = '$units',
					`price` = '$price',
					`count` = $count
				WHERE
					`id` = '$product_id'
SQL;
			$Result = $this->DB->execute($query);
			if(!$Result)
			{
				Error::set('product.edit.fail');
				return FALSE;
			}
			else
				return $this->editCategories();
		}
	}
	
	#
	# Function: editCategories()
	# Purpose: to update the Categories this Product is associated with,
	#          which just amounts to deleting & re-adding
	#
	private function editCategories()
	{
		return($this->deleteCategories() &&	$this->addCategories());
	}
	
	#
	# Function: deleteCategories()
	# Purpose: to delete all the Category associations for this product
	#
	private function deleteCategories()
	{
		$db_id = $this->DB->escape($this->id);
		$query = <<<SQL
			DELETE
			FROM
				`product_categories`
			WHERE
				`product_id` = '$db_id'
SQL;

		$Result = $this->DB->execute($query);
		if(!$Result)
		{
			Error::set('product.delete_categories.fail');
			return FALSE;
		}
		else
			return TRUE;
	}
	
	#
	# Function: addCategories()
	# Purpose: to add Category associations for this Product
	#
	private function addCategories()
	{
		if(count($this->categories))
		{
			$ids = array_keys($this->categories);
			$ids = array_map(array($this->DB,'escape'),$ids);
		
			$db_product_id = $this->DB->escape($this->id);
			$query = <<<SQL
				INSERT
				INTO
					`product_categories`
					(`product_id`,
					 `category_id`)
				VALUES
SQL;
			foreach($ids as $id)
			{
				$query .= "('$db_product_id','$id'),";
			}
			$query = rtrim($query,',');
			$Result = $this->DB->execute($query);
			if(!$Result)
			{
				Error::set('product.add_categories.fail');
				return FALSE;
			}
		}
		
		return TRUE;
	}	
	
	#
	# Function: delete()
	# Purpose: To delete the product
	# Parameters: None
	# Returns: TRUE if deleted,
	#          FALSE if not
	#
	# Note: If the product has already been ordered, the product will simply be de-activated
	#	    If deactivation succeeds, function returns TRUE (FALSE if not)
	#
	public function delete()
	{
		# Don't delete if this product doesn't actually exist
		if(!$this->id)
		{
			Error::set('product.delete.not_exist');
			return FALSE;
		}
		
		$product_id = $this->DB->escape($this->id);
		$member_id = $this->DB->escape(Session::get(array('member','id')));
		
		# Don't delete if current user is not the owner of the product
		if($this->producer_id != $member_id)
		{
			Error::set('product.delete.not_owner');
			return FALSE;
		}		
		
		# Check if product has been ordered
		$query = <<<SQL
			SELECT
				COUNT(*) as 'count'
			FROM
				`order_items`
			WHERE
				`product_id` = '$product_id'
SQL;
		$Result = $this->DB->execute($query);
		if(!$Result)
			Error::set('product.delete.order_check_error');
		else
		{
			$Row = $Result->getRow();
			# If ordered, deactivate
			if($Row->count > '0')
				return $this->deactivate($product_id);
			# otherwise, delete
			else
			{
				$query = <<<SQL
					DELETE
					FROM
						`products`
					WHERE
						`id` = '$product_id'
SQL;
				$Result = $this->DB->execute($query);
				if($this->DB->affectedRows() > 0)
					return TRUE;
				else
					Error::set('product.delete.error');
			}
		}
	}
	
	#
	# Function: deactivate
	# Purpose: To deactivate the current product by setting the `active` field
	# Parameters: $product_id: The database escaped product id.
	# Returns: TRUE if deactivated
	#		   FALSE if not
	# Note: Only called by Product::delete(), so we can rely on $product_id being
	#       properly escaped
	#
	private function deactivate($product_id)
	{
		$query = <<<SQL
			UPDATE
				`products`
			SET
				`active` = 1
			WHERE
				`id` = '$product_id'
SQL;
		$Result = $this->DB->execute($query);
		if(!$Result)
			Error::set('product.deactivate.error');
		else if($this->DB->affectedRows() == 0)
			Error::set('product.deactivate.none');
		else
			return TRUE;
		
		return FALSE;
	}	
	
	#
	# Function: _checkLength()
	# Purpose: Private function to check the length of required elements
	# Returns: Nothing - sets errors
	#
	private function _checkLength()
	{
		if(strlen($this->producer_id == 0))
			Error::set('product.no_producer');
		if(strlen($this->name) == 0)
			Error::set('product.no_name');
		if(strlen($this->description) == 0)
			Error::set('product.no_desc');
		if(strlen($this->units) == 0)
			Error::set('product.no_units');
		if(strlen($this->price) == 0)
			Error::set('product.no_price');
		
		return (Error::s()) ? FALSE : TRUE;
	}
	
	#
	# Function: _checkFormat()
	# Purpose: Private function to check the format of certain numeric elements
	# Returns; Nothing - sets errors
	#
	private function _checkFormat()
	{		
		if(!is_numeric($this->price))
			Error::set('product.price_not_numeric');
		if(strlen($this->count) &&
		   (!is_numeric($this->count) ||
		   (string)(int)$this->count !== $this->count))
		   	Error::set('product.count_not_integer');
	
		return (Error::s()) ? FALSE : TRUE;
	}
	
}
	