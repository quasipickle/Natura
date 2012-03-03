<?PHP
class PageController extends Controller
{
	public $page_level = FALSE;
	public $template = 'forgot.tpl.php';
	
	public function setup()
	{
		parent::setup();
		
		# Initialize page variables
		$this->TPL->assign(array(
			'send_success'=>FALSE
			)
		);		
	}
					
	public function process()
	{
		# initial "send me a code" post
		if(isset($_POST['submit']))
			$this->processSend();
		
		# following the email link
		if(isset($_GET['c']))
			$this->processSet();
	}
	
	private function processSend()
	{
		if(!strlen($_POST['email']))
			Error::set('forgot_email_blank');
		else
		{
			$email = cleanGPC($_POST['email']);
			require DIR_CLASS.'/Forgot.php';
			$Forgot = new Forgot();
			if($Forgot->createCode($email))
				$this->TPL->send_success = TRUE;
		}
	}
	
	private function processSet()
	{
		$this->template = 'forgot.new.tpl.php';
		$this->TPL->assign(array(
			'reset_success'	=>FALSE,
			'email'			=>'',
			'load_errors'	=>FALSE
		));
	
		require DIR_CLASS.'/Forgot.php';
		$code = cleanGPC($_GET['c']);
		$Forgot = new Forgot($code);
		
		if(Error::s())
			$this->TPL->load_errors = TRUE;
		else
			$this->TPL->email = $Forgot->email;
		
		
		# actually reseting the password
		if(isset($_POST['reset']))
		{
			$member_id = $Forgot->getMemberID($Forgot->email);
			$password = cleanGPC($_POST['password']);
			$confirm_password = cleanGPC($_POST['confirm_password']);
			
			if($password != $confirm_password)
				Error::set('signup.no_password_match');
			else
			{			
				$Member = new Member();
				$Member->id = $member_id;
				if($Member->setPassword($password))
				{
					$Forgot->deleteCode();
					$this->TPL->reset_success = TRUE;
				}
			}
		}
	}
	
}