<?PHP
class PageController extends Controller
{
	public $page_level = 0;
	public $template = 'logout.tpl.php';

	public function process()
	{
		$Auth = new Auth();
		if($Auth->logout())
		{
			$this->TPL->assign(array(
				'is_authed'      =>FALSE,
				'is_member'      =>FALSE,
				'is_producer'    =>FALSE,
				'is_admin'       =>FALSE,
				'logout_success' =>TRUE
				)
			);
		}
	}
}