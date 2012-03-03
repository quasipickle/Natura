<?PHP

class PageController extends Controller
{
	public $page_level = LEVEL_MEMBER;
	public $template = 'members.tpl.php';

	public function setup()
	{
		$this->TPL->member_page = TRUE;
	}
}

?>