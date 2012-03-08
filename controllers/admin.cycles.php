<?PHP
class PageController extends AdminController
{
	public $page_level = LEVEL_ADMIN;
	public $template = 'admin.cycles.tpl.php';
	
	public function setup()
	{
		# prime the start and end date values for new cycles
		$this->TPL->assign(array(
			'name'				=>'',
			'start'				=>'',
			'create_failure'	=> FALSE,
			'end'				=>'',
			'emails_sent'		=>FALSE
			
		));		
		parent::setup();
	}
	
	public function process()
	{
		if(isset($_POST['delete']))
			$this->deleteCycle();
		else if(isset($_POST['end']))
			$this->endCycle();
		else if(isset($_POST['send_emails']))
			$this->sendCycleEmails();
		else if(isset($_POST['download_files']))
			$this->downloadFiles();
		else if(isset($_POST['download_txt']))
			$this->downloadFiles('txt');
		else if(isset($_POST['download_csv']))
			$this->downloadFiles('csv');

		$this->loadCycles();
	}
	
	private function deleteCycle()
	{
		$cycle_id = $_POST['id'];
		$Cycle = new Cycle($cycle_id);
		if($Cycle->delete())
			$this->TPL->delete_success = TRUE;
	}
	
	
	private function endCycle()
	{
		$cycle_id = $_POST['id'];
		$Cycle = new Cycle($cycle_id);
		if($Cycle->end())
			$this->TPL->end_success = TRUE;
	}
	
	private function sendCycleEmails()
	{
		$Cycle = new Cycle($_POST['cycle']);
		if($Cycle->sendEmails())
			$this->TPL->emails_sent = TRUE;
		else
			$this->TPL->message = Lang::get('msg:cycle_no_orders');
	}
	
	
	private function downloadFiles($type = 'txt')
	{	
		$cycle_id = cleanGPC($_POST['cycle']);
		$Cycle = new Cycle($cycle_id);
		$Cycle->loadParticipatingMembers();
		$Cycle->loadParticipatingProducers();
		
		$files = array();
		
		# Only proceed if there were actual orders placed
		if($Cycle->members)
		{
			# Generate the summary spreadsheet
			$summary = $Cycle->generateSummary();
			$files[] = $summary;
			
			# Generate the invoices
			foreach($Cycle->members as $Member)
			{
				$Order = $Member->loadOrder($cycle_id,'cycle');
				$file = $Order->generateInvoice($type);
				if($file)
					$files[] = $file;
			}
	
			# Generate the purchase orders
			foreach($Cycle->producers as $Producer)
			{
				$file = $Producer->generatePurchaseOrder($Cycle,$type);
				if($file)
					$files[] = $file;
			}
			
			# Add the files to the zip file
			if(!class_exists('ZipArchive'))
				Error::set('zip.library_missing');
			else
			{
				$Zip = new ZipArchive();
				$filename = DIR_TMP.'/cycle_'.$cycle_id.'.zip';
				
				if ($Zip->open($filename, ZIPARCHIVE::CREATE) !== TRUE)
					Error::set('zip.file_cannot_open');
				else if(count($files))
				{
					foreach($files as $file)
					{
						$Zip->addFile($file,basename($file));
					}
				
					if($Zip->close())
					{	
						header("Content-Type: application/zip");
						header("Content-Length: ".filesize($filename));
						header("Content-Disposition: attachment; filename=".basename($filename));
						readfile($filename);
						unlink($filename);
						exit();
					}
					else
						Error::set('zip.file_cannot_close');		
				}
				else
					Error::set('cycle.download.none');
			}
		}
		else
			$this->TPL->message = Lang::get('msg:cycle_no_orders');
	}
	
	private function loadCycles()
	{
		$DB = DB::getInstance();
		
		$query = <<<SQL
			SELECT
				*
			FROM
				`cycles`
			ORDER BY
				`start` DESC
SQL;
		$Result = $DB->execute($query);
		if(!$Result)
			Error::set('cycles.load.error');
		if($Result->numRows() == 0)
		{
			$this->TPL->current_cycles 	= array();
			$this->TPL->future_cycles 	= array();
			$this->TPL->past_cycles 	= array();
		}
		else
		{
			$now = date('Y-m-d');//MySQL date format
			$current_cycles = array();
			$future_cycles = array();
			$past_cycles = array();
			
			foreach($Result as $Row)
			{
				$cycle = array(
					'id'	=>$Row->id,
					'name'	=>$Row->name,
					'start'	=>$Row->start,
					'end'	=>$Row->end
				);
							
				if($Row->start <= $now && $Row->end >= $now)
					$current_cycles[] = $cycle;
				else if($Row->end >= $now)
					$future_cycles[] = $cycle;
				else
					$past_cycles[] = $cycle;
			}
			
			$this->TPL->current_cycles 	= $current_cycles;
			$this->TPL->future_cycles 	= $future_cycles;
			$this->TPL->past_cycles 	= $past_cycles;
		}
	}
}

?>