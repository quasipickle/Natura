<?PHP
require DIR_LIBRARY.'/swiftmailer/lib/swift_required.php';
class Email
{
	public static $from = array(CONTACT_OUTGOING);
	
	# __get used to retrieve
	private $failures = FALSE;
		
	#
	# Function: send()
	# Purpose: Send an email
	# Parameters: $to: email address or array of strings with the key being email addresses and values being names
	#			  $from: same format as $to
	#			  $subject: subject of the email
	#			  $message: message to send
	#
	# If $to is an array, and $from is a string, SwiftMailer's batchSend() function is used
	#
	public static function send($to,$from = FALSE,$subject,$message)
	{
		# convert to array
		$to = (gettype($to) == 'string') ? array($to) : $to;
		
		# Only proceed if the email has a recipient
		if(count($to) == 0)
			return FALSE;
		
		# Use $from if set, default if not
		$from = (!$from) ? self::$from : $from;		
		
		$Transport = Swift_SmtpTransport::newInstance(MAIL_SERVER,MAIL_SERVER_PORT);
		# Set the domain name to the hostname of the CONTACT_OUTGOING email for the SMTP transport layer.
		# Necessary in very few cases
		$Transport->setLocalDomain(substr(CONTACT_OUTGOING,strpos(CONTACT_OUTGOING,'@')+1));
		$Mailer = Swift_Mailer::newInstance($Transport);		
		
		$Message = Swift_Message::newInstance($subject)
					->setTo($to)
					->setFrom($from)
					->setBody($message);
				
		$success = FALSE;
		ob_start();
		try{
			if(count($to) && is_string($from))
				$success = $Mailer->batchSend($Message);
			else
				$success = $Mailer->send($Message);
		}
		catch(Exception $e)
		{
			$success = FALSE;
		}
		ob_clean();
		
		if($success)
			return TRUE;
		else
		{
			Error::set('mail_not_sent');
			return FALSE;
		}
	}

	public function __get($name)
	{
		# $failures is private & retrieved with __get, because it's format is not-intuitive.
		# If no failures were found, it still gets populated, but the first value is false
		# Expected behaviour is for $failures to be false if no failures were found, so
		# __get "translates"
		if($name == 'failures')
		{
			if(count($this->failures) == 1 && $this->failures[0] === FALSE)
				return FALSE;
			else
				return $this->failures;
		}
	}			
}	
	