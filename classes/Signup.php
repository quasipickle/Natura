<?PHP
#
# class: Signup
# Purpose: To handle all the logic necessary for signing up
#
require DIR_CLASS.'/Email.php';
class Signup
{
	public function process($properties)
	{
		if($this->checkLength($properties))
		{
			if($this->checkMatch($properties))
			{
				if($this->save($properties))
					return TRUE;
			}
		}
		
		return FALSE;
	}
	
	
	#
	# Function: checkLength()
	# Purpose: To check the length of all the necessary properties
	# Parameters: $properties (array): An array of all the properties provided by the signup form
	# Note: Phone number is not checked as it's optional
	#
	public function checkLength($properties)
	{
		if(strlen($properties['first_name']) == 0)
			Error::set('signup.no_first_name');
		if(strlen($properties['last_name']) == 0)
			Error::set('signup.no_last_name');
		if(strlen($properties['email']) == 0)
			Error::set('signup.no_email');
		if(strlen($properties['confirm_email']) == 0)
			Error::set('signup.no_confirm_email');
		if(strlen($properties['password']) == 0)
			Error::set('signup.no_password');
		if(strlen($properties['confirm_password']) == 0)
			Error::set('signup.no_confirm_password');
		if(!isset($properties['agree']))
			Error::set('signup.no_agree');
		if(Error::s())
			return FALSE;
	
		return TRUE;
	}
	
	#
	# Function: checkMatch()
	# Purpose: To check if the credential fields match their respective confirmation fields
	# Parametesr: $properties (array): An array of all the properties provided by the signup form
	#
	public function checkMatch($properties)
	{
		if($properties['email'] != $properties['confirm_email'])
			Error::set('signup.no_email_match');
		if($properties['password'] != $properties['confirm_password'])
			Error::set('signup.no_password_match');

		if(Error::s())
			return FALSE;
		
		return TRUE;
	}
	
	#
	# Function: save()
	# Purpose: To create the member in the database
	# Parameters: $properties (array): An array of all the properties provided by the signup form
	#	
	public function save($properties)
	{
		$DB = DB::getInstance();
		
		$first_name 	= $DB->escape($properties['first_name']);
		$last_name 		= $DB->escape($properties['last_name']);
		$email 			= $DB->escape($properties['email']);
		$password 		= self::hash($DB->escape($properties['password']));
		$phone			= $DB->escape($properties['phone']);
		
		$query = <<<SQL
			INSERT
			INTO
				`members`
				(`email`,
				 `password`,
				 `first_name`,
				 `last_name`,
				 `phone`)
			VALUES
				('$email',
				 '$password',
				 '$first_name',
				 '$last_name',
				 '$phone')
SQL;

		$Result = $DB->execute($query);
		if(!$Result)
		{
			if($DB->errno() == '1062')
				Error::set('signup.existing_email');
			else
				Error::set('signup.failed');
			return FALSE;
		}
		$Email = new Email();
		$Email->send(
			CONTACT_ORDERS,
			FALSE,
			Lang::get('member.created.subject'),
			Lang::get('member.created.body',array('%FIRST_NAME%'=>$properties['first_name'],
													'%LAST_NAME%'=>$properties['last_name']))
		);
		return TRUE;
	}
	
	public static function hash($string)
	{
		return hash('sha256',$string);
	}
}