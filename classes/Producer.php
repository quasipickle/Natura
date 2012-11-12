<?PHP

require_once DIR_CLASS.'/Member.php';

class Producer extends Member
{
	public $business_name;
	public $business_about;
	public $business_email;
	public $business_phone;
	public $business_pending;
	public $orders = array();
	public $order_grand_total = 0;
		
	public function __construct($id = FALSE)
	{
		$this->DB = DB::getInstance();
		
		if($id)
		{
			$this->id = $id;
			parent::__construct($id);//load Member stuff
			$this->loadProducer();
		}
	}
	
	private function loadProducer()
	{
		$member_id = $this->DB->escape($this->id);
		
		$query = <<<SQL
			SELECT
				*
			FROM
				`producers`
			WHERE
				`member_id` = '$member_id'
SQL;
		$Result = $this->DB->execute($query);
		if(!$Result)
			Error::set('producer.load.error',array('%ID%'=>$this->id));
		else if($Result->numRows() == 0)
			Error::set('producer.load.no_exist',array('%ID%'=>$this->id));
		else
		{
			$Row = $Result->getRow();
			$this->business_name    = $Row->name;
			$this->business_about   = $Row->about;
			$this->business_email   = $Row->email;
			$this->business_phone   = $Row->phone;
			$this->business_pending = ($Row->pending == 1) ? TRUE : FALSE;
		}
	}
	
	# overloads Member::approve()
	public function approve()
	{
		require DIR_CLASS.'/Email.php';
		
		if(!$this->business_pending || !$this->id)
		{
			Error::set('producer.approve.unnecessary');
			return NULL;
		}
		else
		{
			$id = $this->DB->escape($this->id);
			
			$query = <<<SQL
				UPDATE
					`producers`
				SET
					`pending` = 0
				WHERE
					`member_id` = '$id'
SQL;
			$Result = $this->DB->execute($query);
			if(!$Result)
			{
				Error::set('producer.approve.error',array('%ID%'=>$this->id));
				return FALSE;
			}
			else if($this->DB->affectedRows() == 0)
			{
				Error::set('producer.approve.none',array('%ID%'=>$this->id));
				return FALSE;
			}
			else if(!$this->updateMemberLevel())
				return FALSE;
			
			$Email = new Email();
			$Email->send(
				$this->email,
				FALSE,
				Lang::get('producer.approved.subject'),
				Lang::get('producer.approved.body',array('%BUSINESS_NAME%'=>$this->business_name))
			);
			return TRUE;
		}
	}
	
	##
	# Function: updateMemberLevel()
	# Purpose: To update the member level of this producer from member to producer
	#
	public function updateMemberLevel()
	{
		$id = $this->DB->escape($this->id);
		$producer_level = LEVEL_PRODUCER;
				
		# Update the member level
		$query = <<<SQL
			UPDATE
				`members`
			SET
				`level` = `level` + $producer_level
			WHERE
				`id` = '$id'
SQL;
		$Result = $this->DB->execute($query);
		if(!$Result)
		{
			Error::set('producer.approve.update_level.error');
			return FALSE;
		}
		else if($this->DB->affectedRows() == 0)
		{
			Error::set('producer.approve.update_level.none');
			return FALSE;
		}
		
		return TRUE;
	}
	
	##
	# Function: loadOrderAmounts()
	# Purpose: To load the order amounts for this producer's
	#          products
	public function loadOrderAmounts($cycle_id)
	{
		$producer_id = $this->DB->escape($this->id);
		$cycle_id = $this->DB->escape($cycle_id);
		
		$query = <<<SQL
			SELECT
				`pdct`.`name`,
				`pdct`.`units`,
				`oi`.`price`,
				`oi`.`count`,
				`o`.`member_id`,
				`m`.`first_name`,
				`m`.`last_name`
			FROM
				`products` as `pdct`,
				`order_items` as `oi`,
				`orders` as `o`,
				`members` as `m`
			WHERE
				`o`.`cycle_id` = '$cycle_id' AND
				`o`.`id` = `oi`.`order_id` AND
				`oi`.`product_id` = `pdct`.`id` AND
				`pdct`.`producer_id` = '$producer_id' AND
				`o`.`member_id` = `m`.`id`
SQL;
		$Result = $this->DB->execute($query);
		if(!$Result)
		{
			Error::set('order.amounts.load.fail');
			return FALSE;
		}
		else
		{
			if($Result->numRows())
			{
				foreach($Result as $Row)
				{
					if(!isset($this->orders[$Row->name]))
						$this->orders[$Row->name] = new _(array(
							'orders'=>array(),
							'total'=>0,
							'units'=>''
							)
						);
					
					$this->orders[$Row->name]->orders[] = new _(array(
							'price'             => $Row->price,
							'count'             => $Row->count,
							'member_id'         => $Row->member_id,
							'member_first_name' => $Row->first_name,
							'member_last_name'  => $Row->last_name
						));
					$this->order_grand_total 		   += ($Row->price * $Row->count);
					$this->orders[$Row->name]->total   += $Row->count;
					$this->orders[$Row->name]->units   =  $Row->units;
				}
			}
			return TRUE;
		}
	}
	
	public function save()
	{
		if(!$this->checkLength())
			return FALSE;
			
		require DIR_CLASS.'/Email.php';

		$member_id 	= $this->DB->escape(Session::get(array('member','id')));
		$name		= $this->DB->escape($this->business_name);
		$about 		= $this->DB->escape($this->business_about);
		$email 		= $this->DB->escape($this->business_email);
		$phone		= $this->DB->escape($this->business_phone);		
		
		if($this->id !== FALSE)
			return $this->update($name,$about,$email,$phone);
		else
			return $this->create($member_id,$name,$about,$email,$phone);
	}
	
	##
	# Function: create()
	# Purpose: To create a new member
	private function create($member_id,$name,$about,$email,$phone)
	{		
		$query = <<<SQL
			INSERT
			INTO
				`producers`
				(`member_id`,
				 `name`,
				 `about`,
				 `email`,
				 `phone`,
				 `pending`)
			VALUES
				('$member_id',
				 '$name',
				 '$about',
				 '$email',
				 '$phone',
				 1)
SQL;
		$Result = $this->DB->execute($query);
		if(!$Result)
		{
			if($this->DB->errno() == '1062')
				Error::set('signup.producer.already');
			else
				Error::set('signup.producer.error');
			return FALSE;
		}
		else
		{
			$this->id = $member_id;
			$Email = new Email();
			$Email->send(
				CONTACT_ORDERS,
				FALSE,
				Lang::get('producer.created.subject'),
				Lang::get('producer.created.body',array('%BUSINESS_NAME%'=>$name))
			);	
			return TRUE;
		}
	}
	
	private function update($name,$about,$email,$phone)
	{
		$id = $this->DB->escape($this->id);
		
		$query = <<<SQL
			UPDATE
				`producers`
			SET
				`name` = '$name',
				`about` = '$about',
				`email` = '$email',
				`phone` = '$phone'
			WHERE
				`member_id` = '$id'
SQL;
		
		$Result = $this->DB->execute($query);
		if(!$Result)
		{
			Error::set('producer.update.error');
			return FALSE;
		}
		else
			return TRUE;
	}
	
	public function generatePurchaseOrder($Cycle,$download = FALSE)
	{
		$orders = $this->loadOrderAmounts($Cycle->id);
		if(!$orders)
			return FALSE;
	
		$Info = new _(array(
			'cycle_start_stamp'	=>$Cycle->start_stamp,
			'cycle_end_stamp'	=>$Cycle->end_stamp,
			'cycle_name'		=>$Cycle->name,
			'producer_name'		=>$this->business_name,
			'order_grand_total'	=>$this->order_grand_total,
			'amounts'			=>$this->orders
		));
	
		$filename = cleanFilename("po - {$this->business_name}.xls");//in funclib.php
		$file_path = DIR_TMP.'/'.$filename;		
		include_once(DIR_TEMPLATE.'/purchase_order.php');
		
		if(!__generatePO($Info,$file_path,$download))
		{
			Error::set('producer.generate_purchase_order.fail',array('%ID%'=>$this->id));
			return FALSE;
		}
		else
			return $file_path;
	}
	
	
	##
	# Function: checkLength()
	# Purpose: To check the length of $business_name and $business_about
	#          to make sure they're set
	# Returns: TRUE if the length is acceptable
	#          FALSE if not
	# Sets appropriate errors depending on which length failed
	#
	private function checkLength()
	{
		if(strlen($this->business_name) == 0)
			Error::set('signup.producer.no_name');
		if(strlen($this->business_about) == 0)
			Error::set('signup.producer.no_about');
		if(strlen($this->business_about) > 255)
			Error::set('signup.producer.about_length');
		if(Error::s())
			return FALSE;
	
		return TRUE;
	}
	
}

?>