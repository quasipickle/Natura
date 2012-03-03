<?PHP
#
# AdminController
#
# An object that extends the Controller class & provides some extra Admin-only functionality
#
# All controllers for Admin pages should extend this controller, NOT Controller.
#
# Note that setup() is defined, so extending classes should call parent::setup() in their own setup() functions
#

class AdminController extends Controller
{
	public function setup()
	{
		$this->TPL->admin_page = TRUE;
	}
	public function showPage()
	{
		$this->getPendingAccountsCount();
		parent::showPage();
	}
	
	#
	# Function: getPendingAccountsCount()
	# Purpose: To retireve the number of pending accounts - both members and producers
	private function getPendingAccountsCount()
	{
		$DB = DB::getInstance();
		
		# Memberships
		$query = <<<SQL
			SELECT
				COUNT(*)
			FROM
				`members`
			WHERE
				`pending` = 1
SQL;
		$Result = $DB->execute($query);
		if($Result)
			if($Result->numRows() > 0)
			{
				$Row = $Result->getRow();
				if($Row->{'COUNT(*)'} != 0)
					$this->TPL->pending_memberships = $Row->{'COUNT(*)'};
			}
		
		# Producers
		$query = <<<SQL
			SELECT
				COUNT(*)
			FROM
				`producers`
			WHERE
				`pending` = 1
SQL;
		$Result = $DB->execute($query);
		if($Result)
			if($Result->numRows() > 0)
			{
				$Row = $Result->getRow();
				if($Row->{'COUNT(*)'} != 0)
					$this->TPL->pending_producers = $Row->{'COUNT(*)'};
			}
	}
}
		
		