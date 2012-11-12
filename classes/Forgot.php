<?php

class Forgot
{
	public $code 		= NULL;
	public $expiry 		= NULL;
	public $email 		= NULL;
	public $member_id 	= NULL;
	public $DB 			= FALSE;
	
	public function __construct($code=FALSE)
	{
		$this->DB = DB::getInstance();
		if($code)
		{
			$this->code = $code;
			$this->loadExpiry();
		}
	}
	
	public function loadExpiry()
	{
		$code = $this->DB->escape($this->code);
		$query = <<<SQL
			SELECT
				*
			FROM
				`reset_codes`
			WHERE
				`code` = '$code' AND
				`expiry` >= NOW()
SQL;

		$Result = $this->DB->execute($query);
		if(!$Result)
		{
			$this->expiry = FALSE;
			Error::set('reset_retrieve_fail');
			return FALSE;
		}
		else if($Result->numRows() == 0)
			Error::set('reset_retrieve_none');
		else
		{
			$Row = $Result->getRow();
			$this->expiry = $Row->expiry;
			$this->email = $Row->email;
			return TRUE;
		}
	}
	
	public function createCode($email)
	{
		if($this->generateCode())
		{
			if($this->getMemberID($email))
			{			
				$db_email = $this->DB->escape($email);
				$db_id = $this->DB->escape($this->member_id);
				$query = <<<SQL
					INSERT
					INTO
						`reset_codes`
						(`code`,
						 `member_id`,
						 `email`,
						 `expiry`)
					VALUES
						('$this->code',
						 '$db_id',
						 '$db_email',
						 DATE_ADD(NOW(), INTERVAL 24 HOUR)
						)
SQL;
				$Result = $this->DB->execute($query);
				if(!$Result)
					Error::set('reset_save_fail');
				else
				{
					require DIR_CLASS.'/Email.php';
					if(Email::send(
									$email,
									FALSE,
									Lang::get('reset.subject'),
									Lang::get('reset.message',
										array(	'%CODE%'=>$this->code))))
					{
						return TRUE;
					}
					else
						return FALSE;
				}
			}
			else
			{
				# if FALSE, then the provided email doesn't match any member
				# If we return FALSE & tell the user as much, that could be used
				# to fish for member passwords.  So, we clear the errors & pretend
				# everything went fine
				if($this->member_id == FALSE)
				{
					Error::clear();
					return TRUE;
				}
			}
		}
		else
			return FALSE;
	}
	
	private function generateCode()
	{
		$chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
		$code_length = 128;		
		$chars_length = strlen($chars)-1;
		
		$code = FALSE;
		do
		{
			# tack on a random character until we hit the session length
			while(strlen($code) < $chars_length)
			{
				$code .= substr($chars,rand(0,$chars_length),1);
			}
			
			# check if it already exists
			$query = "SELECT count(`code`) as 'count' FROM `reset_codes` WHERE `code` = '$code'";
			$Result = $this->DB->execute($query);
			if($Result === FALSE)
			{
				Error::set('reset_generate_code_fail');
				return FALSE;
			}
			$Row = $Result->getRow();
		}
		while($Row->count > 0);
		
		$this->code = $code;
		return TRUE;
	}
	
	public function getMemberID($email)
	{
		$db_email = $this->DB->escape($email);
		$query = <<<SQL
			SELECT
				*
			FROM
				`members`
			WHERE
				`email` = '$db_email'
SQL;
		$Result = $this->DB->execute($query);
		if($Result === FALSE)
		{
			Error::set('load_member_by_email_fail');
			return FALSE;
		}
		else
		{
			if($Result->numRows() == 0)
			{
				Error::set('load_member_by_email_none');
				$this->member_id = FALSE;
				return FALSE;
			}
			else
			{
				$Row = $Result->getRow();
				$this->member_id = $Row->id;
				return $this->member_id;
			}
		}
	}
	
	public function deleteCode()
	{
		$db_code = $this->DB->escape($this->code);
		$query = <<<SQL
			DELETE
			FROM
				`reset_codes`
			WHERE
				`code` = '$db_code'
SQL;
		// no error checking because it doesn't really matter if it worked or not
		$this->DB->execute($query);
	}
}
?>