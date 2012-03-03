<?PHP

class PageController extends Controller
{
	public $page_level = LEVEL_PRODUCER;
	public $template = 'producers.tpl.php';

	public function setup()
	{
		$this->TPL->producer_page = TRUE;
	}
}