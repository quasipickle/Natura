<?PHP

require DIR_CLASS.'/Producer.php';
class PageController extends Controller
{
	public $page_level = LEVEL_PRODUCER;
	public $template   = 'producers.profile.tpl.php';
	private $DB;
	private $Producer;
	
	public function setup()
	{
		$this->TPL->producer_page = TRUE;
		$this->DB = DB::getInstance();
		
		//used by producer.profile.form.tpl.php to determine
		//which submit button to display
		$this->TPL->editing = TRUE;
	}
	
	public function process()
	{
		if(isset($_POST['edit']))
		{
			$Producer                 = new Producer(Session::get(array('member','id')));
			$posted                   = array_map('cleanGPC',$_POST);
			$Producer->business_name  = $posted['business_name'];
			$Producer->business_about = $posted['business_about'];
			$Producer->business_email = $posted['business_email'];
			$Producer->business_phone = $posted['business_phone'];
			
			if($Producer->save())
				$this->TPL->updated = TRUE;
		}		
	}
	
	public function showPage()
	{
		$this->loadProfile();
		parent::showPage();
	}
	
	
	private function loadProfile()
	{
		$Producer                  = new Producer(Session::get(array('member','id')));
		$this->TPL->business_name  = $Producer->business_name;
		$this->TPL->business_about = $Producer->business_about;
		$this->TPL->business_email = $Producer->business_email;
		$this->TPL->business_phone = $Producer->business_phone;
	}
}