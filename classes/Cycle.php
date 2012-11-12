<?PHP
require_once DIR_CLASS.'/ObjectList.php';
class Cycle
{
	public $id;
	public $name;
	public $start = FALSE;// MySQL datetime format
	public $start_stamp;// UNIX format
	public $end = FALSE;
	public $end_stamp;
	//keyed numerically, values are Category objects
	public $categories = array();
	
	// stores invoice and PO file paths if generated
	public $files = FALSE;
	
	public $members = FALSE;
	public $producers = FALSE;
	
	public $DB;
	
	public function __construct($id=FALSE)
	{
		$this->DB = DB::getInstance();		
		
		if($id != FALSE)
		{
			$this->id = $id;
			$this->load();
		}
	}
	
	private function load()
	{
		$cycle_id = $this->DB->escape($this->id);
		$query = <<<SQL
			SELECT
				*
			FROM
				`cycles`
			WHERE
				`id` = '$cycle_id'
SQL;
		$Result = $this->DB->execute($query);
		if(!$Result)
			Error::set('cycle.load.error');
		else if($Result->numRows() == 0)
			Error::set('cycle.load.no_exist',array('%ID%'=>$this->id));
		else
		{
			$Row = $Result->getRow();
			$this->name = $Row->name;
			$this->start = $Row->start;
			$this->start_stamp = strtotime($this->start);
			$this->end = $Row->end;
			$this->end_stamp = strtotime($this->end);
		}
		
		$this->loadCategories();
	}
	
	private function loadCategories()
	{
		$categories = array();
		$db_id = $this->DB->escape($this->id);
	
		$query = <<<SQL
			SELECT
				`c`.`id`
			FROM
				`cycle_categories` as `cc`,
				`categories` as `c`
			WHERE
				`cycle_id` = '$db_id' AND
				`cc`.`category_id` = `c`.`id`
SQL;
		$Result = $this->DB->execute($query);
		if(!$Result)
			Error::set('cycle.load_categories.fail');
		else if($Result->numRows() > 0)
		{
			$ids = array();
			foreach($Result as $Row)
			{
				$ids[] = $Row->id;
			}
			if(count($ids))
				$categories = ObjectList::load('category',$ids);
		}
		$this->categories = $categories;
	}
	
	
	public function create()
	{
		if(!strlen($this->name))
		{
			Error::set('cycle.create.name.empty');
			return FALSE;
		}
		if($this->checkDates())
		{
			$name 	= $this->DB->escape($this->name);
			$start 	= $this->DB->escape($this->start);
			$end 	= $this->DB->escape($this->end);
			
			$query = <<<SQL
				INSERT
				INTO
					`cycles`
					(`name`,
					 `start`,
					 `end`)
				VALUES
				 ('$name',
				  '$start',
				  '$end')
SQL;
			$Result = $this->DB->execute($query);
			if(!$Result)
				Error::set('cycle.create.error');
			else
			{
				$this->id = $this->DB->lastInsertID();
				return $this->createCategories();
			}
		}
		return FALSE;
	}
	
	public function createCategories()
	{
		if(count($this->categories))
		{
			$db_id = $this->DB->escape($this->id);
			$categories = array_keys($this->categories);
			$categories = array_map(array($this->DB,'escape'),$categories);		
			
			$query = <<<SQL
				INSERT
				INTO
					`cycle_categories`
					(`cycle_id`,
					 `category_id`)
				VALUES
SQL;
			foreach($categories as $id)
			{
				$query .= "('$db_id','$id'),";
			}
			$query = rtrim($query,',');
			
			$Result = $this->DB->execute($query);
			if(!$Result)
			{
				Error::set('cycle.add_categories.fail');
				return FALSE;
			}
			else
				return TRUE;
		}
		else
			return TRUE;
	}
	
	public function delete()
	{
		$now = date('Y-m-d');
		if($this->start >= $now && $this->end <= $now)
			Error::set('cycle.delete.now');
		else if($this->end < $now)
			Error::set('cycle.delete.past');
		else
		{
			$id = $this->DB->escape($this->id);
			
			$query = <<<SQL
				DELETE
				FROM
					`cycles`
				WHERE
					`id` = '$id'
SQL;
			$Result = $this->DB->execute($query);
			if(!$Result)
				Error::set('cycle.delete.error');
			else if($this->DB->affectedRows() == 0)
				Error::set('cycle.delete.none');
			else
				return TRUE;
		}		
		return FALSE;
	}
	
	public function deleteCategories()
	{
		$db_id = $this->DB->escape($this->id);
		$query = <<<SQL
			DELETE
			FROM
				`cycle_categories`
			WHERE
				`cycle_id` = '$db_id'
SQL;
		$Result = $this->DB->execute($query);
		if(!$Result)
		{
			Error::set('cycle.delete_categories.fail');
			return FALSE;
		}
		else
			return TRUE;
	}
	
	public function end()
	{
		$id = $this->DB->escape($this->id);
		$now = date('Y-m-d');
		$query = <<<SQL
			UPDATE
				`cycles`
			SET
				`end` = '$now'
			WHERE
				`id` = '$id'
SQL;
		$Result = $this->DB->execute($query);
		if(!$Result)
			Error::set('cycle.end.error');
		else if($this->DB->affectedRows() == 0)
			Error::set('cycle.end.none');
		else
			return TRUE;
		
		return FALSE;
	}
	
	public function update()
	{	
		if($this->checkDates(TRUE))
		{
			$id 	= $this->DB->escape($this->id);
			$name 	= $this->DB->escape($this->name);
			$start 	= $this->DB->escape($this->start);
			$end 	= $this->DB->escape($this->end);
			$query = <<<SQL
				UPDATE
					`cycles`
				SET
					`start` = '$start',
					`end` = '$end'
				WHERE
					`id` = '$id'
SQL;
			$Result = $this->DB->execute($query);
			if(!$Result)
				Error::set('cycle.edit.error');
			else
				return $this->updateCategories();
		}
		return FALSE;
	}
	
	public function updateCategories()
	{
		return($this->deleteCategories() && $this->createCategories());
	}
	
	#
	# Function: loadCurrent()
	# Purpose: To load the cycle that is currently happening
	# 	
	public function loadCurrent()
	{
		$query = <<<SQL
			SELECT
				*
			FROM
				`cycles`
			WHERE
				`start` <= DATE(NOW()) AND
				`end` >= DATE(NOW())
			ORDER BY
				`start` ASC
			LIMIT 1
SQL;
		$Result = $this->DB->execute($query);
		if(!$Result)
			Error::set('cycle.load_current.error');
		else if($Result->numRows() > 0)
		{
			$Row 				= $Result->getRow();
			$this->id 			= $Row->id;
			$this->name 		= $Row->name;
			$this->start 		= $Row->start;
			$this->start_stamp 	= strtotime($this->start);
			$this->end 			= $Row->end;
			$this->end_stamp 	= strtotime($this->end);
			return TRUE;
		}
		
		return FALSE;
	}
	
	#
	# Function: loadNext()
	# Purpose: To the next cycle that will happen
	# 	
	public function loadNext()
	{
		$query = <<<SQL
			SELECT
				*
			FROM
				`cycles`
			WHERE
				`start` > NOW()
			ORDER BY
				`start` ASC
			LIMIT 1
SQL;
		$Result = $this->DB->execute($query);
		if(!$Result)
			Error::set('cycle.load_next.error');
		else if($Result->numRows() > 0)
		{
			$Row = $Result->getRow();
			$this->id 			= $Row->id;
			$this->name 		= $Row->name;
			$this->start 		= $Row->start;
			$this->start_stamp 	= strtotime($this->start);
			$this->end 			= $Row->end;
			$this->end_stamp 	= strtotime($this->end);
			return TRUE;
		}
		
		return FALSE;
	}
	
	##
	# Function: loadPrevious()
	# Purpose: Load the last cycle to happen
	# 	
	public function loadPrevious()
	{
		$query = <<<SQL
			SELECT
				*
			FROM
				`cycles`
			WHERE
				`end` < NOW()
			ORDER BY
				`start` DESC
			LIMIT 1
SQL;
		$Result = $this->DB->execute($query);
		if(!$Result)
			Error::set('cycle.load_previous.error');
		else if($Result->numRows() > 0)
		{
			$Row = $Result->getRow();
			$this->id 			= $Row->id;
			$this->name 		= $Row->name;
			$this->start 		= $Row->start;
			$this->start_stamp 	= strtotime($this->start);
			$this->end 			= $Row->end;
			$this->end_stamp 	= strtotime($this->end);
			return TRUE;
		}
		
		return FALSE;
	}
	
	
	##
	# Function: sendEmails()
	# Purpose: To send out the emails containing the invoices & purchase orders
	#          for this Cycle
	# Parameters: None
	# Returns: TRUE if all emails were sent
	#          FALSE if at least one email wasn't set
	#          
	# Sets a fancy error listing the failed emails
	## 
	public function sendEmails()
	{
		require_once DIR_CLASS.'/ObjectList.php';
		require_once DIR_CLASS.'/Email.php';
	
		# Retrieve all participating members
		$invoices = array();
		$member_to = array();		
		$Members = $this->loadParticipatingMembers();
		foreach($Members as $Member)
		{
			# Generate an invoice for each member
			$Order = $Member->loadOrder($this->id,'cycle');
			$invoice_filename = $Order->generateInvoice();
			if(!$invoice_filename)
				return FALSE;
			else
				$invoices[] = $invoice_filename;
		
			$member_to[$Member->email] = $Member->first_name.' '.$Member->last_name;
		}	
		
		# Send emails to members
		$Email = new Email();
		$members_sent = $Email->send(
			$member_to,
			FALSE,
			Lang::get('cycle.complete.member.subject'),
			Lang::get('cycle.complete.member.body',array(
				'%NAME%'=>$this->name,
				'%FROM%'=>$this->start,
				'%TO%'	=>$this->end
			))
		);				
		
		# Retrieve all participating producers
		$purchase_orders = array();
		$Producers = $this->loadParticipatingProducers();
		$producer_to = array();
		foreach($Producers as $Producer)
		{
			$purchase_order_filename = $Producer->generatePurchaseOrder($this);
			if(!$purchase_order_filename)
				return FALSE;
			else
				$purchase_orders[] = $purchase_order_filename;
			
			
			$email = ($Producer->business_email) ? $Producer->business_email : $Producer->email;			
			
			$producer_to[$email] = $Producer->business_name;			
		}
		
		# Send emails to producers	
		$producers_sent = $Email->send(
			$producer_to,
			FALSE,
			Lang::get('cycle.complete.producer.subject'),
			Lang::get('cycle.complete.producer.body',array(
				'%NAME%'=>$this->name,
				'%FROM%'=>$this->start,
				'%TO%'	=>$this->end
			))
		);
		$producers_sent = TRUE;
		
		# If any members or producers didn't get the email, report an error
		if($Email->failures)
		{
			$send_failures = implode(',',$Email->failures);
			Error::set('mail_send_failures',array('%ADDRESSES%'=>$send_failures));
		}		
		
		
		if($members_sent === TRUE && $producers_sent === TRUE)
			return TRUE;
		else
			return FALSE;	
	}
	
	
	
	private function checkDates($ignore_self = FALSE)
	{		
		$now = date('Y-m-d');
		if(!strlen($this->start))
			Error::set('cycle.create.no_start');
		else if(!strlen($this->end))
			Error::set('cycle.create.no_end');
		else if($this->start < $now)
			Error::set('cycle.create.early');
		else if($this->start > $this->end)
			Error::set('cycle.create.backwards');
		else
			return TRUE;
		
		return FALSE;
	}
	
	##
	# Function: isActive()
	# Purpose: To report if this cycle is active
	# Returns: TRUE if the cycle started earlier than now, and ends later than now
	#		   FALSE otherwise
	##
	public function isActive()
	{
		$now = time();
		$start = ($this->start) ? $this->start_stamp : 0;
		$end = ($this->end) ? $this->end_stamp : 0;
		
		if($now >= $start && $now <= $end)
			return TRUE;
		else
			return FALSE;
	}
	
	
	##
	# Function: generateFiles)(
	# Purpose: To generate all the files necessary for this cycle
	# Returns: TRUE if at least one file was generated
	#          FALSE if no files were generated
	# Generates an Error if one file failed to be generated
	##
	private function generateFiles()
	{
		$files = array();
		$Members = $this->loadParticipatingMembers();
		foreach($Members as $Member)
		{
			$Order = $Member->loadOrder($cycle_id,'cycle');
			$file = $Order->generateInvoice();
			if($file)
				$files[] = $file;
			else
				Error::set('cycle.file_generation_failure');
		}
		
		$Producers = $this->loadParticipatingProducers();
		foreach($Producers as $Producer)
		{
			$file = $Producer->generatePurchaseOrder($this);
			if($file)
				$files[] = $file;
			else
				Error::set('cycle.file_generation_failure');
		}
		
		if(count($files))
		{
			$this->files = $files;
			return TRUE;
		}
		else
			return FALSE;
	}
	
	public function generateDownloadFile()
	{
		if($this->generateFiles() && !Error::s())
		{
			if(!class_exists('ZipArchive'))
				Error::set('zip.library_missing');
			else
			{
				$Zip = new ZipArchive();
				$filename = DIR_TMP.'/cycle_'.$this->id.'.zip';
				
				# Overwrite the file if it exists.  Otherwise, the files get appended to the zip archive
				$create_mode = (file_exists($filename)) ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE;
							
				if($Zip->open($filename, $create_mode) !== TRUE)
					Error::set('zip.file_cannot_open');
				
				if(count($this->files))
				{
					foreach($files as $file)
					{
						$Zip->addFile($file,basename($file));
					}
				
					if($Zip->close())
						return $filename;
					else
						Error::set('zip.file_cannot_close');
				}
				else
				{
					 Error::set('cycle.download.none');
				}
			}
		}
		return FALSE;	
	}
	
	
	
	
	
	##
	# Function: loadParticipatingMembers()
	# Purpose: To load all Members who participated in this Cycle
	# Parameters: None
	# Returns: An array of Member objects if Members found
	#		   A blank array if no members found
	#		   FALSE if something went wrong
	#
	public function loadParticipatingMembers()
	{
		$cycle_id = $this->DB->escape($this->id);
		
		$query = <<<SQL
			SELECT
				`members`.`id`
			FROM
				`members`,
				`orders`
			WHERE
				`orders`.`cycle_id` = '$cycle_id' AND
				`orders`.`member_id` = `members`.`id`
SQL;
		$Result = $this->DB->execute($query);
		if(!$Result)
		{
			Error::set('cycle.load_participating_members.fail');
			return FALSE;
		}
		else if($Result->numRows() > 0)
		{
			$ids = array();
			foreach($Result as $Row)
			{
				$ids[] = $Row->id;
			}
			$this->members = ObjectList::load('member',$ids);
			return $this->members;
		}
		else
			return array();
	}

	##
	# Function: loadParticipatingProducers()
	# Purpose: To load all Producers who participated in this Cycle
	# Parameters: None
	# Returns: An array of Producer objects if Members found
	#		   A blank array if no producers found
	#		   FALSE if something went wrong
	#
	public function loadParticipatingProducers()
	{
		$cycle_id = $this->DB->escape($this->id);
		
		$query = <<<SQL
			SELECT
				`producers`.`member_id`
			FROM
				`orders`,
				`order_items`,
				`producers`,
				`products`
			WHERE
				`orders`.`cycle_id` = '$cycle_id' AND
				`orders`.`id` = `order_items`.`order_id` AND
				`order_items`.`product_id` = `products`.`id` AND
				`products`.`producer_id` = `producers`.`member_id`
			GROUP BY
				`member_id`
SQL;
		$Result = $this->DB->execute($query);
		if(!$Result)
		{
			Error::set('cycle.load_participating_producers.fail');
			return FALSE;
		}
		else if($Result->numRows() > 0)
		{
			$ids = array();
			foreach($Result as $Row)
			{
				$ids[] = $Row->member_id;
			}
			$this->producers = ObjectList::load('producer',$ids);
			return $this->producers;
		}
		else
			return array();
	}
	
	public function generateSummary()
	{	
		if(!$this->members)
			$this->loadParticipatingMembers();
		
		if(!$this->members)
			return FALSE;
		
		//a collection of all data for the order - to be used when generating the summary
		//lots of duplication of data because it needs to be iterable in different ways
		$data = array(
			'products'	=>array(),
			'producers'	=>array(),
			'orders'	=>array()
		);
		
		/* Gather all the individual invoices */
		$invoices = array();
		foreach($this->members as $Member)
		{
			$Order = $Member->loadOrder($this->id,'cycle');
			
			$data['orders'][] = array(
				'member'	=> $Member,
				'items'		=> $Order
			);
			
			foreach($Order->items as $Item)
			{
				/* Ensure $data has a record of this item & producer */
				if(!isset($data['products'][$Item->product_id]))
				{
					$Product = new Product($Item->product_id);
					if(!isset($data['producers'][$Product->producer_id]))
					{
						$Producer = new Producer($Product->producer_id);
						$data['producers'][$Product->producer_id] = array(
							'producer'	=> $Producer,
							'products'	=> array()
						);				
					}
								
					// store the product			
					$data['products'][$Item->product_id] = $Product;
					
					// store the fact that the product is produced by this producer
					$data['producers'][$Product->producer_id]['products'][] = $Item->product_id;
				}		
			}
		}
		
		$file_path = DIR_TMP.'/summary.xls';
		
		include DIR_TEMPLATE.'/admin.cycles.summary.php';
		_generateSummary($data,$file_path);
		
		return $file_path;
	}
}

?>