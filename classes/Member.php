<?PHP
require_once 'ObjectList.php';
class Member
{
	public $id = FALSE;
	public $email;
	public $first_name;
	public $last_name;
	public $phone;
	public $level;
	public $pending = NULL;
	
	# An array of basic objects of each
	# Cycle this member has placed an order in
	# Each element has an 'id' and 'name' property
	public $previousOrderedCycles = FALSE;
	
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
	
	#
	# Function: load();
	# Purpose: Loads the member from the database
	# Parameters: None - requires $this->id to be set;
	# Returns: Nothing - generates an error if a problem occurs
	#
	private function load()
	{
		$id = $this->DB->escape($this->id);

		$query = <<<SQL
			SELECT
				*
			FROM
				`members`
			WHERE
				`id` = '$id'
SQL;
		$Result = $this->DB->execute($query);
		if(!$Result)
			Error::set('member.load.error',array('%ID%',$this->id));
		else if($Result->numRows() == 0)
			Error::set('member.load.no_exist',array('%ID%',$this->id));
		else
		{
			$Row 				= $Result->getRow();
			$this->email 		= $Row->email;
			$this->first_name 	= $Row->first_name;
			$this->last_name 	= $Row->last_name;
			$this->phone 		= $Row->phone;
			$this->level 		= $Row->level;
			$this->pending 		= ($Row->pending == '0') ? FALSE : TRUE;
		}
	}
	
	#
	# Function: approve()
	# Purpose: To "approve" a member by setting their pending value to 1
	# Parameters: None
	# Returns: TRUE if pending flag changed
	#          FALSE if a problem occurred
	#		   NULL if the member was not pending
	#
	public function approve()
	{
		require DIR_CLASS.'/Email.php';
		
		if(!$this->pending || !$this->id)
		{
			Error::set('member.approve.unnecessary');
			return NULL;
		}
		else
		{
			$id = $this->DB->escape($this->id);
			
			$query = <<<SQL
				UPDATE
					`members`
				SET
					`pending` = 0
				WHERE
					`id` = '$id'
SQL;
			$Result = $this->DB->execute($query);
			if(!$Result)
			{
				Error::set('member.approve.error',array('%ID%'=>$this->id));
				return FALSE;
			}
			else if($this->DB->affectedRows() == 0)
			{
				Error::set('member.approve.none',array('%ID%'=>$this->id));
				return FALSE;
			}
			else
			{
				$Email = new Email();
				$Email->send(
					$this->email,
					FALSE,
					Lang::get('member.approved.subject'),
					Lang::get('member.approved.body')
				);
				return TRUE;
			}
		}
	}
	
	
	
	##
	# Function: loadOrderedCycles()
	# Purpose: To load all old cycles this member has ordered in
	# Parameters: None
	# Returns: Nothing.  Sets $this->previousOrderdCycles()
	##
	public function loadOrderedCycles()
	{
		$DB = DB::getInstance();
		$member_id = $DB->escape($this->id);

		$query = <<<SQL
			SELECT
				`cycles`.`id`
			FROM
				`cycles`,
				`orders`
			WHERE
				`cycles`.`id` = `orders`.`cycle_id` AND
				`orders`.`member_id` = '$member_id' AND
				`cycles`.`end` < NOW()
			GROUP BY
				`cycles`.`id`
SQL;

		$Result = $DB->execute($query);
		if(!$Result)
		{
			Error::set('member.cycles.old');
			return FALSE;
		}
		else if($Result->numRows())
		{
			$old_cycles = array();
			$ids = array();
			foreach($Result as $Row)
			{
				$ids[] = $Row->id;
			}
			$old_cycles = ObjectList::load('cycle',$ids);
			
			$this->previousOrderedCycles = $old_cycles;
		}
	}
	
	
	##
	# Function: loadOrder()
	# Purpose: To load an order for this user
	# Parameters: $id (int): The ID to use to load the order
	# 		      $id_type (string): "cycle": id passed is a cycle ID
	#							     "order": id passed is an order ID
	# Returns: The loaded Order object
	##
	public function loadOrder($id,$id_type)
	{
		require_once DIR_CLASS.'/Order.php';
		
		$id 		= $this->DB->escape($id);
		$member_id 	= $this->DB->escape($this->id);
		$id_field 	= ($id_type == 'cycle') ? 'cycle_id' : 'id';
		
		$query = <<<SQL
			SELECT
				`id`
			FROM
				`orders`
			WHERE
				`orders`.`$id_field` = '$id' AND
				`orders`.`member_id` = '$member_id'
SQL;

		$Result = $this->DB->execute($query);
		if(!$Result)
			Error::set('member.invoice.find');
		else
		{
			if($Result->numRows())
			{
				$Row = $Result->getRow();		
				$Order = new Order($Row->id);
				return $Order;
			}		
		}
		
		return FALSE;
	}
	
	public function setPassword($password)
	{
		require DIR_CLASS.'/Signup.php';
		$hash = Signup::hash($password);
		$db_id = $this->DB->escape($this->id);
		
		$query = <<<SQL
			UPDATE
				`members`
			SET
				`password` = '$hash'
			WHERE
				`id` = '$db_id'
SQL;

		$Result = $this->DB->execute($query);
		if(!$Result)
		{
			Error::set('set_password_fail');
			return FALSE;
		}
		else
			return TRUE;
	}
}
?>