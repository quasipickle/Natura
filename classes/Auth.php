<?PHP

class Auth
{
	public $DB;
	private $memberResult = FALSE;

	public function __construct()
	{
		$this->DB = DB::getInstance();
	}
	
	public function checkCredentials($email,$password)
	{
		$db_email = $this->DB->escape($email);
		require DIR_CLASS.'/Signup.php';
		$hashed_password = Signup::hash($password);
		$query = <<<SQL
			SELECT
				*
			FROM
				`members`
			WHERE
				`email` = '$db_email' AND
				`password` = '$hashed_password'
SQL;
		$Result = $this->DB->execute($query);
		if(!$Result)
			return FALSE;
		else if($Result->numRows() === 0)
		{
			Error::set('login.wrong');
			return FALSE;
		}
		else
		{
			$this->memberResult = $Result;
			return TRUE;
		}
	}

	public function login()
	{
		if($this->createSession())
		{
			$Row = $this->memberResult->getRow();
						
			$member = array(
				'id'			=> $Row->id,
				'email'			=> $Row->email,
				'first_name'	=>	$Row->first_name,
				'last_name'		=> 	$Row->last_name,
				'phone'			=>	$Row->phone,
				'level'			=>	$Row->level
			);
			Session::set('member',$member);
			
			return TRUE;
		}
		return FALSE;
	}
	
	public function logout()
	{
		if(Session::exists())
		{
			$session_id = Session::get('session_id');
			$session_id = $this->DB->escape($session_id);
			$query= <<<SQL
				DELETE
				FROM
					`sessions`
				WHERE
					`session_id` = '$session_id'
SQL;
			# don't really care if it fails - it's just housekeeping
			$Result = $this->DB->execute($query);
			Session::destroy();
		}
		
		return TRUE;
	}
	
	private function createSession()
	{
		$session_id = $this->generateSessionID();
		$session_timeout = SESSION_TIMEOUT;
		$query = <<<SQL
			INSERT
			INTO
				`sessions`
				(session_id,
				 expiry_time)
			VALUES
				('$session_id',
				 DATE_ADD(NOW(), INTERVAL $session_timeout SECOND)
				)
SQL;
		$Result = $this->DB->execute($query);
		if($Result === FALSE)
		{
			Error::set('login.create_session_failure');
			return FALSE;
		}
		else
		{
			# regenerate PHP_SESSID value to prevent hijacking
			session_regenerate_id();			
			Session::set('session_id',$session_id);
			return TRUE;
		}
	}
	
	
	
	#
	# Function: checkSession()
	# Purpose: to check if the current session is valid for the current page
	# Parameters: $required_level [integer]: number between 1 (highest) and 255 (lowest)
	#				User must be at that level or higher to be considered valid
	#				Defaults to 1 (top level)
	# Returns: boolean TRUE if the session checks out
	#		   boolean FALSE if the session is invalid for any reason
	#
	public function checkSession($required_level = 1)
	{
		# return right away if no session id exists
		if(!$this->isAuthed())
			return FALSE;
		
		# try to find session info
		$session_id = Session::get('session_id');
		$session_id = $this->DB->escape($session_id);
		$query = <<<SQL
			SELECT
				UNIX_TIMESTAMP(`expiry_time`) as 'expiry',
				`members`.`level`
			FROM
				`sessions`,
				`members`
				
			WHERE
				`session_id` = '$session_id'
SQL;
		
		$Result = $this->DB->execute($query);
		if($Result === FALSE)
		{
			Error::set('session.unable_check');
			return FALSE;
		}
		else if($Result->numRows() == 0)
			return FALSE;
		else
		{
			$Row = $Result->getRow();
			if($Row->expiry < time())
				return FALSE;
			else if($Row->level < $required_level)
				return FALSE;
			else
			{
				if($this->updateSession())
					return TRUE;
				else
					return FALSE;
			}
		}
	}
	
	private function updateSession()
	{
		$session_id = Session::get('session_id');
		$session_id = $this->DB->escape($session_id);
		$session_timeout = SESSION_TIMEOUT;
		$query = <<<SQL
			UPDATE
				`sessions`
			SET
				`expiry_time` = DATE_ADD(`expiry_time`, INTERVAL $session_timeout SECOND)
			WHERE
				`session_id` = '$session_id'
SQL;

		$Result = $this->DB->execute($query);
		if($Result === FALSE)
		{
			Error::set('session.unable_extend');
			return FALSE;
		}
		else
			return TRUE;
	}
	
	private function generateSessionID()
	{
		$chars = 'abcdefghijklmnopqrstuvwxyZABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*()_+-=?;:,.<>`~[]{}|';
		$chars_length = strlen($chars)-1;
		$id = FALSE;
		do
		{
			# prime the key with the current microsecond
			$id = (string)microtime(TRUE);
			
			# tack on a random character until we hit the session length
			while(strlen($id) < SESSION_ID_LENGTH)
			{
				$id .= substr($chars,rand(0,$chars_length),1);
			}
			
			# check if it already exists
			$query = "SELECT count(`session_id`) as 'count' FROM `sessions` WHERE `session_id` = '$id'";
			$Result = $this->DB->execute($query);
			if($Result === FALSE)
			{
				Error::set('login.generate_session_failed');
				return FALSE;
			}
			$Row = $Result->getRow();
		}
		while($Row->count > 0);
		
		return $id;
	}
	
	#
	# Function: isAuthed()
	# Purpose: To determine if the current member is authenticated
	#
	public static function isAuthed()
	{
		if(Session::exists('session_id'))
			return TRUE;
		
		return FALSE;
	}	
}