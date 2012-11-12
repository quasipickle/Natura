<?php include 'head.tpl.php'; ?>
<h1>
	<?php Lang::out('ttl:purchase_orders'); ?>
</h1>
<?php if(isset($this->error)): ?>
	<div class = "alert alert-error">
		<?php foreach($this->error as $error): ?>
			<?php echo $error; ?><br />
		<?php endforeach; ?>
	</div>
<?php endif; ?>
<?php if($this->old_cycles): ?>
	<form method = "get" action = "" class = "well form-inline">
		<div>
			<select name = "cycle" id = "cycle-chooser">
				<option value = "-1"><?php Lang::out('lbl:cycles_past_view') ?></option>
				<?php foreach($this->old_cycles as $Cycle): ?>
					<option value = "<?php echo $Cycle->id; ?>" <?php if($this->cycle_id === $Cycle->id): ?>selected="selected"<?php endif; ?>><?php echo $Cycle->name.' ('.$Cycle->start.' - '.$Cycle->end.')'; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</form>
<?php endif; ?>	

<?php if($this->active_cycle): ?>
	<div class = "alert">
		<?php Lang::out('info:order_amounts_not_final'); ?>
	</div>
<?php endif; ?>

<?php if(isset($this->amounts) && count($this->amounts)): ?>
	<?php Lang::out('lbl:cycle'); echo '('.date('Y-m-d',$this->cycle_start_stamp).' - '.date('Y-m-d',$this->cycle_end_stamp).')'; ?><br />
	<?php echo $this->producer_name; ?>

	<table class = "table table-condensed">
		<thead>
			<tr>
				<th>
					<?php Lang::out('lbl:product'); ?>	
				</th>
				<th>
					<?php Lang::out('lbl:total'); ?>
				</th>
				<th>
					<?php Lang::out('lbl:orders_individual'); ?>
				</th>
				<th>
					<?php Lang::get('member'); ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->amounts as $name=>$Item): ?>
				<tr>
					<th>
						<?php echo $name; ?>
					</th>
					<th>
						<?php echo $Item->total; ?>
					</th>
					<th colspan = "2"></th>
				</tr>
				<?php foreach($Item->orders as $Order): ?>
					<tr>
						<td colspan = "2"></td>
						<td>
							<?php echo $Order->count.' '.$Item->units.'@$'.number_format($Order->price,2).'/'.$Item->units; ?>
						</td>
						<td>
							<?php echo $Order->member_last_name.', '.$Order->member_first_name; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endforeach; ?>		
			</tr>
		</tbody>
	</table>
	<div class = "form-actions" style = "text-align:right;">
		<a href = "<?php echo DIR_WEB; ?>/producers/orders/?cycle=<?php echo $this->cycle_id; ?>&amp;download" class = "btn"><i class = "icon-download-alt"></i><?php Lang::out('btn:download'); ?></a>
	</div>
<?php elseif($this->cycle_id): ?>
	<?php Lang::out('msg:purchase_orders_none'); ?>
<?php endif; ?>		

<script type = "text/javascript">
	$(document).ready(function(){
		$("#cycle-chooser").on('change',function(){
			var id = $(this).val();
			if(id != '-1')
			{
				window.location.href = window.location.protocol+'//'+window.location.host+window.location.pathname+'?cycle='+id
			}
		});
	});
</script>
 
<?php include 'foot.tpl.php'; ?>