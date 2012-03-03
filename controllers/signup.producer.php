<?PHP
class PageController extends Controller
{
	public $page_level = LEVEL_MEMBER;
	public $template = 'signup.producer.tpl.php';
	
	public function setup()
	{
		$this->TPL->assign(array(
			'business_name'     =>'',
			'business_about'    =>'',
			'business_email'    =>'',
			'business_phone'    =>'',
			'member_page' =>TRUE
			)
		);
	}
	
	public function process()
	{
		if(isset($_POST['signup']))
		{
			require DIR_CLASS.'/Producer.php';
			$posted = array_map('cleanGPC',$_POST);
			$Producer = new Producer();			

			$Producer->business_name 	= $posted['business_name'];
			$Producer->business_about 	= $posted['business_about'];
			$Producer->business_email 	= $posted['business_email'];
			$Producer->business_phone 	= $posted['business_phone'];
			
			if($Producer->save())
				$this->TPL->signup_success = TRUE;
		}
	}		
}