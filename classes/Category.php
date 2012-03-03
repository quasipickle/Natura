<?PHP

class Category
{
	public $id;
	public $name_hr;
	
	public $DB;
	
	public function __construct($id = FALSE)
	{
		$this->DB = DB::getInstance();
		if($id !== FALSE)
		{
			$this->id = $id;
			$this->load();
		}
	}
	
	public function load()
	{
		$db_id = $this->DB->escape($this->id);
		$query = <<<SQL
			SELECT
				*
			FROM
				`categories`
			WHERE
				`id` = '$db_id'
SQL;
		$Result = $this->DB->execute($query);
		if(!$Result)
			Error::set('category_load_fail');
		else if($Result->numRows() == 0)
			Error::set('category_load_none');
		else
		{
			$Row = $Result->getRow();
			$this->name_hr = $Row->name_hr;
		}
	}
	
	public function create($name_hr)
	{
		if(strlen($name_hr) == 0)
			Error::set('new_category_empty');
		else
		{	
			$this->name_hr = $name_hr;
			$db_name_hr = $this->DB->escape($this->name_hr);
			$query = <<<SQL
				INSERT
				INTO
					`categories`
					(`name_hr`)
				VALUES
					('$db_name_hr')
SQL;
			$Result = $this->DB->execute($query);
			if(!$Result)
			{
				if($this->DB->errno() == 1062)
					Error::set('new_category_not_unique');
				else
					Error::set('new_category_fail');
			
				return FALSE;
			}
			else
			{
				$this->id = $this->DB->lastInsertID();
				return TRUE;
			}
		}
	}
	
	public function delete()
	{
		$db_id = $this->DB->escape($this->id);
		
		$query = <<<SQL
			DELETE
			FROM
				`categories`
			WHERE
				`id` = '$db_id'
SQL;
		$Result = $this->DB->execute($query);
		if(!$Result)
		{
			Error::set('category_delete_fail');
			return FALSE;
		}
		else
			return $this->deleteProductAssociations();					
	}
	
	public function deleteProductAssociations()
	{
		$db_id = $this->DB->escape($this->id);
		
		$query = <<<SQL
			DELETE
			FROM
				`product_categories`
			WHERE
				`category_id` = '$db_id'
SQL;
		$Result = $this->DB->execute($query);
		if(!$Result)
		{
			Error::set('category_delete_product_assoc_fail');
			return FALSE;
		}
		else
			return TRUE;
	}
}