<?PHP
require DIR_CLASS.'/AdminController.php';

class PageController extends AdminController
{
	public $page_level = LEVEL_ADMIN;
	public $template = 'admin.producers.approve.tpl.php';
	
	public function setup()
	{
		parent::setup();
		$this->TPL->producers = FALSE;
	}
					
	public function process()
	{
		require_once DIR_CLASS.'/Producer.php';
		
		if(isset($_POST['approve']))
		{
			$id = cleanGPC($_POST['id']);
			$Producer = new Producer($id);
			if($Producer->approve() === TRUE)
				$this->TPL->approve_success = TRUE;
		}
	}
	
	public function showPage()
	{
		$this->loadPendingProducers();
		parent::showPage();
	}
	
	private function loadPendingProducers()
	{
		$DB = DB::getInstance();
		
		# Load all unapproved producers
		$query = <<<SQL
			SELECT
				`members`.`first_name`,
				`members`.`last_name`,
				`producers`.*
			FROM
				`members`,
				`producers`
			WHERE
				`members`.`id` = `producers`.`member_id` AND
				`producers`.`pending` = 1
SQL;

		$Result = $DB->execute($query);
		if(!$Result)
			Error::set('producers.pending_load.error');
		else if($Result->numRows() > 0)
		{
			$producers = array();		
			foreach($Result as $Row)
			{
				$id = $Row->member_id;
				$producers[$id] = new _(array(
						'id'=>$id,
						'first_name'=>$Row->first_name,
						'last_name'=>$Row->last_name,
						'business_name'=>$Row->name,
						'business_about'=>$Row->about,
						'business_email'=>$Row->email,
						'business_phone'=>$Row->phone
					)
				);
			}
			$this->TPL->producers = $producers;
		}
	}
}