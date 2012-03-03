<?PHP
class PageController extends Controller
{
	public $page_level = 0;
	public $template = 'signup.tpl.php';

	public function setup()
	{
		$this->TPL->assign(array(
			'first_name'    =>'',
			'last_name'     =>'',
			'email'         =>'',
			'confirm_email' =>'',
			'phone'         =>''
		));
	}
	
	public function process()
	{
		if(isset($_POST['signup']))
		{	
			$posted = array_map('cleanGPC',$_POST);
			
			require DIR_CLASS.'/Signup.php';
			$Signup = new Signup();
			
			if($Signup->process($posted))
				$this->TPL->signup_success = TRUE;
			else
			{
				$this->TPL->assign(array(
					'first_name'    =>$posted['first_name'],
					'last_name'     =>$posted['last_name'],
					'email'         =>$posted['email'],
					'confirm_email' =>$posted['confirm_email'],
					'phone'         =>$posted['phone']
					)
				);
			}
		}
	}
}
?>