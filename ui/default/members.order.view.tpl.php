<?php include 'head.tpl.php'; ?>
<h1>
	<?php Lang::out('ttl:view_order'); ?>
</h1>
<?php if(isset($this->error)): ?>
	<div class = "alert alert-error">
		<?php foreach($this->error as $error): ?>
			<?php echo $error; ?><br />
		<?php endforeach; ?>
	</div>
<?php endif; ?>
<?php if($this->order_saved): ?>
<div class = "alert alert-success">
	<?php Lang::out('msg:order_saved'); ?>
</div>
<?php endif; ?>
<p>
	<?php Lang::get('info:order_view'); ?>
</p>

<?php if($this->order_id):
	include 'order_form.tpl.php';
else:
	if($this->orders): ?>
		<table class = "table table-striped">
			<thead>
				<tr>
					<th>
						<?php Lang::out('lbl:cycle_name'); ?>
					</th>
					<th>
						<?php Lang::out('lbl:cycle_start'); ?>
					</th>
					<th>
						<?php Lang::out('lbl:cycle_end'); ?>
					</th>
					<th>
						<?php Lang::out('lbl:order_placed'); ?>
					</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($this->orders as $Order): ?>
					<tr>
						<td>
							<?php echo $Order->Cycle->name; ?>
						</td>
						<td>
							<?php echo $Order->Cycle->start; ?>
						</td>
						<td>
							<?php echo $Order->Cycle->end; ?>
						</td>
						<td>
							<?php echo date('Y-m-d g:i a',$Order->time_placed_stamp); ?>
						</td>
						<td>
							<?php if($Order->inEditWindow()): ?>
								<a href = "?id=<?php echo $Order->id; ?>" class = "btn">
									<?php echo Lang::out('btn:order_edit'); ?>
								</a>
							<?php else: ?>
								<a href = "?id=<?php echo $Order->id; ?>" class = "btn">
									<i class = "icon-eye-open"></i>
									<?php echo Lang::out('btn:view'); ?>
								</a>
								&nbsp;&nbsp;
								<a href = "<?php echo DIR_WEB; ?>/members/order/view/?id=<?php echo $Order->id; ?>&download" class = "btn">
									<i class = "icon-download-alt"></i>
									<?php echo Lang::out('btn:download'); ?>
								</a>
							<?php endif; ?>
							
						</td>
					</tr>
				<?php endforeach; ?>				
			</tbody>
		</table>
	<?php
	else:
		echo '<p>'.Lang::get('info:no_past_orders').'</p>';
	endif;
endif;


include 'foot.tpl.php';
?>
