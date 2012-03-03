<?PHP
require DIR_CLASS.'/AdminController.php';

class PageController extends AdminController
{
	public $page_level = LEVEL_ADMIN;
	public $template = 'admin.members.approve.tpl.php';
	
	public function setup()
	{
		parent::setup();
	}
					
	public function process()
	{
		if(isset($_POST['approve']))
		{
			$member_id = cleanGPC($_POST['id']);
			$Member = new Member($member_id);
			if($Member->approve() === TRUE)
				$this->TPL->approve_success = TRUE;
		}
	}
	
	public function showPage()
	{
		$this->loadPendingMembers();
		parent::showPage();
	}
	
	private function loadPendingMembers()
	{
		$DB = DB::getInstance();
		
		# Load all unapproved members
		$query = <<<SQL
			SELECT
				*
			FROM
				`members`
			WHERE
				`pending` = 1
SQL;
		$Result = $DB->execute($query);
		if(!$Result)
			Error::set('members.pending_load.error');
		if($Result->numRows() == 0)
			$this->TPL->members = FALSE;
		else
		{
			$members = array();		
			foreach($Result as $Row)
			{
				$Member = new Member($Row->id);
				$members[$Member->id] = $Member;
			}
			$this->TPL->members = $members;
		}
	}
}