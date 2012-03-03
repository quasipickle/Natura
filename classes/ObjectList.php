<?PHP

/*
 * Class: ObjectList
 * Purpose: To create an array of objects, without running a query for each object
 *          The result will be an array of the requested objects, with all the basic
 *          properties of each object.
 *
 * Note: This class does not call the load() function of each class, so if the classes
 *       gain new properties, this class will need to be updated
 */

class ObjectList
{
	private static $DB;
	##
	# Function: load()
	# Purpose: To initiate the loading of the objects
	# Parameters: $type (string): One of "member", "cycle", "product", "producer"
	#			  				  Order objects cannot be loaded with this class, as the
	#							  load() functionality is not simple
	# Returns: An array of the objects of the requested type, matching the requested ids, keyed by id
	public static function load($type,$ids=FALSE)
	{
		self::$DB = DB::getInstance();
		if($ids)
			$ids = self::makeIDClause($ids);
		switch($type)
		{
			case 'member':
				require_once DIR_CLASS.'/Member.php';
				$query = $ids 
							? "SELECT * FROM `members` WHERE `id` IN $ids ORDER BY `last_name` ASC"
							: 'SELECT * FROM `members` ORDER BY `last_name` ASC';
				break;
			case 'cycle':
				require_once DIR_CLASS.'/Cycle.php';
				$query = $ids
							? "SELECT * FROM `cycles` WHERE `id` IN $ids ORDER BY `start` DESC"
							: '"SELECT * FROM `cycles` ORDER BY `start` DESC';
				break;
			case 'product':
				require_once DIR_CLASS.'/Product.php';
				$query = $ids
							? "SELECT * FROM `products` WHERE `id` IN $ids ORDER BY `name` ASC"
							: 'SELECT * FROM `products` ORDER BY `name` ASC';
				break;
			case 'category':
				require_once DIR_CLASS.'/Category.php';
				$query = $ids
							? "SELECT * FROM `categories` WHERE `id` IN $ids ORDER BY `name_hr` ASC"
							: 'SELECT * FROM `categories` ORDER BY `name_hr` ASC';
				break;
			case 'producer':
				require_once DIR_CLASS.'/Producer.php';
				$id_clause = ($ids) ? "`member_id` in $ids AND" : '';
				$query = <<<SQL
					SELECT
						`members`.*,
						`producers`.`name` as 'business_name',
						`producers`.`about` as 'business_about',
						`producers`.`email` as 'business_email',
						`producers`.`phone` as 'business_phone',
						`producers`.`pending` as 'business_pending'
					FROM
						`members`,
						`producers`
					WHERE
						$id_clause
						`members`.`id` = `producers`.`member_id`
SQL;
				break;
			default:
				Error::set('list.type_not_found',array('%TYPE%',type));
				return FALSE;
		}
		
		$Result = self::$DB->execute($query);
		if(!$Result)
		{
			Error::set('list.load.fail');
			return FALSE;
		}
		else
		{
			$return = array();
			if($Result->numRows() > 0)
			{
				switch($type)
				{
					case 'member':						
						foreach($Result as $Row)
						{
							$Member 			= new Member();
							$Member->id			= $Row->id;
							$Member->email 		= $Row->email;
							$Member->first_name = $Row->first_name;
							$Member->last_name 	= $Row->last_name;
							$Member->phone 		= $Row->phone;
							$Member->level 		= $Row->level;
							$Member->pending 	= ($Row->pending == '0') ? FALSE : TRUE;
							$return[$Row->id] 	= $Member;
						}
						break;
					case 'cycle':
						foreach($Result as $Row)
						{
							$Cycle 				= new Cycle();
							$Cycle->id 			= $Row->id;
							$Cycle->name 		= $Row->name;
							$Cycle->start 		= $Row->start;
							$Cycle->start_stamp = strtotime($Cycle->start);
							$Cycle->end 		= $Row->end;
							$Cycle->end_stamp 	= strtotime($Cycle->end);
							$return[$Row->id] 	= $Cycle;
						}
						break;
					case 'product':
						foreach($Result as $Row)
						{
							$Product				= new Product();
							$Product->id			= $Row->id;
							$Product->producer_id 	= $Row->producer_id;
							$Product->name 			= $Row->name;
							$Product->description 	= $Row->description;
							$Product->units 		= $Row->units;
							$Product->price 		= $Row->price;
							$Product->count 		= $Row->count;
							$return[$Row->id]		= $Product;
						}
						break;
					case 'category':
						foreach($Result as $Row)
						{
							$Category				= new Category();
							$Category->id			= $Row->id;
							$Category->name_hr		= $Row->name_hr;
							$return[$Category->id] 	= $Category;
						}
						break;
					case 'producer':
						foreach($Result as $Row)
						{
							$Producer				= new Producer();
							
							# Member properties
							$Producer->id			= $Row->id;
							$Producer->email 		= $Row->email;
							$Producer->first_name 	= $Row->first_name;
							$Producer->last_name 	= $Row->last_name;
							$Producer->phone 		= $Row->phone;
							$Producer->level 		= $Row->level;
							$Producer->pending 		= ($Row->pending == '0') ? FALSE : TRUE;
							
							# Producer properties
							$Producer->business_name 	= $Row->business_name;
							$Producer->business_about 	= $Row->business_about;
							$Producer->business_email 	= $Row->business_email;
							$Producer->business_phone 	= $Row->business_phone;
							$Producer->business_pending = ($Row->business_pending == 1) ? TRUE : FALSE;
							$return[$Row->id]			= $Producer;

						}
						break;
				}//switch()
			}//if numRows()
			return $return;
		}//if $Result				
	}

	
	public static function makeIDClause($ids)
	{
		# make IDs safe
		array_walk($ids,array(self::$DB,'escape'));
		
		return '('.implode(',',$ids).')';
	}
}