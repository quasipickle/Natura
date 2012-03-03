<?PHP
class PageController extends Controller
{
	public $page_level = FALSE;
	public $template = 'login.tpl.php';
	
	public function setup()
	{
		$this->TPL->assign(array(
			'email' =>'',
			'error' =>FALSE
			)
		);
	}
	
	public function process()
	{
		if(isset($_POST['login']))
		{
			$email    = cleanGPC($_POST['email']);
			$password = cleanGPC($_POST['password']);
		
			if($this->Auth->checkCredentials($email,$password))
			{
				$this->Auth->login();
				header('Location: '.SITE_URL.'/members/');
				exit();
			}
			else
				$this->TPL->email = $email;
				
		}
	}
}

?>