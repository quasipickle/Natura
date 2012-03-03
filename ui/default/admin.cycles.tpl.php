<?php include 'head.tpl.php'; ?>
<h1>
	<?php Lang::out('ttl:cycles_manage'); ?>
</h1>
<?php if(isset($this->error)): ?>
	<div class = "alert alert-error">
		<?php foreach($this->error as $error): ?>
			<?php echo $error; ?><br />
		<?php endforeach; ?>
	</div>
<?php endif; ?>
<?php if($this->emails_sent): ?>
	<div class = "alert alert-success">
		<?php Lang::out('msg:cycle_emails_sent'); ?>
	</div>
<?php endif; ?>
<a href = "<?php echo DIR_WEB; ?>/admin/cycles/create/"><?php Lang::out('btn:cycles_create_go'); ?></a>

<p>
	<h2>
		<?php Lang::out('ttl:cycle_current'); ?>
	</h2>
	<?php if(isset($this->end_success)): ?>
		<div class = "alert alert-success">
			<?php Lang::out('msg:cycle_end_success'); ?>
		</div>
		<br />
		<br />
	<?php endif; ?>
	<?php if(count($this->current_cycles)): ?>
		<table class = "table table-striped">
			<thead>
				<tr>
					<th>
						<?php Lang::out('lbl:cycle_id'); ?>
					</th>
					<th>
						<?php Lang::out('lbl:cycle_name'); ?>
					</th>
					<th>
						<?php Lang::out('lbl:cycle_start'); ?>
					</th>
					<th>
						<?php Lang::out('lbl:cycle_end'); ?>
					</th>					
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($this->current_cycles as $cycle): ?>
					<tr>
						<td>
							<?php echo $cycle['id']; ?>
						</td>
						<td>
							<?php echo $cycle['name']; ?>
						</td>
						<td>
							<?php echo $cycle['start']; ?>
						</td>
						<td>
							<?php echo $cycle['end']; ?>
						</td>
						<td>
							<form method = "post" action = "">
								<div>
									<input type = "hidden" value = "<?php echo htmlentities($cycle['id'],TRUE); ?>" name = "id" />
									<input type = "submit" class = "btn" name = "end" value = "<?php Lang::outSafe('btn:cycle_end_today'); ?>" />
								</div>
							</form>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<?php Lang::out('info:cycle_current_none'); ?>
	<?php endif; ?>
</p>
<p>
	<h2>
		<?php Lang::out('ttl:cycles_future'); ?>
	</h2>
	<?php if(isset($this->delete_success)): ?>
		<div class = "alert alert-success">
			<?php Lang::out('msg:cycle_delete_success'); ?>
		</div>
	<?php endif; ?>
	<?php if(count($this->future_cycles)): ?>
		<table class = "table table-striped">
			<thead>
				<tr>
					<th>
						<?php Lang::out('lbl:cycle_id'); ?>
					</th>
					<th>
						<?php Lang::out('lbl:cycle_name'); ?>
					</th>
					<th>
						<?php Lang::out('lbl:cycle_start'); ?>
					</th>
					<th>
						<?php Lang::out('lbl:cycle_end'); ?>
					</th>	
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($this->future_cycles as $cycle): ?>
					<tr>
						<td>
							<?php echo $cycle['id']; ?>
						</td>
						<td>
							<?php echo $cycle['name']; ?>
						</td>
						<td>
							<?php echo $cycle['start']; ?>
						</td>
						<td>
							<?php echo $cycle['end']; ?>
						</td>
						<td>
							<form method = "post" action = "">
								<div>
									<a href = "<?php echo DIR_WEB; ?>/admin/cycles/edit/?id=<?php echo htmlentities($cycle['id']); ?>" class = "btn"><?php Lang::out('btn:cycle_edit'); ?></a>
									<input type = "hidden" name = "id" value = "<?php echo htmlentities($cycle['id'],TRUE); ?>" />
									<input type = "submit" class = "btn btn-danger" name = "delete" value = "<?php echo Lang::outSafe('btn:cycle_delete'); ?>" />
								</div>
							</form>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<?php Lang::out('info:cycles_future_none'); ?>
	<?php endif; ?>
</p>

<p>
	<h2>
		<?php Lang::out('ttl:cycles_past'); ?>
	</h2>
	<?php if(count($this->past_cycles)): ?>
		<table class = "table table-striped">
			<thead>
				<tr>
					<th>
						<?php Lang::out('lbl:cycle_id'); ?>
					</th>
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
						&nbsp;
					</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($this->past_cycles as $cycle): ?>
					<tr>
						<td>
							<?php echo $cycle['id']; ?>
						</td>
						<td>
							<?php echo $cycle['name']; ?>
						</td>
						<td>
							<?php echo $cycle['start']; ?>
						</td>
						<td>
							<?php echo $cycle['end']; ?>
						</td>
						<td>
							<form method = "post" action = "">
								<div>
									<input type = "hidden" name = "cycle" value = "<?php echo $cycle['id']; ?>" />
									<div class = "btn-group">
										<button type = "submit" name = "send_emails" class = "btn">
											<i class = "icon-envelope"></i><?php Lang::outSafe('btn:cycle_send_emails'); ?>
										</button>
									</div>
									<div class = "btn-group">
										<button type = "submit" name = "download_files" class = "btn">
											<i class = "icon-file"></i><?php Lang::outSafe('btn:cycle.download.files'); ?>
										</button>
										<a class = "btn dropdown-toggle" href = "#" data-toggle="dropdown">
											<span class = "caret"></span>
										</a>
										<ul class = "dropdown-menu">
											<li>
												<input type = "submit" name = "download_txt" value = "<?php Lang::outSafe('btn:cycle.download.txt'); ?>" />
											</li>
											<li>
												<input type = "submit" name = "download_csv" value = "<?php Lang::outSafe('btn:cycle.download.csv'); ?>" />
											</li>
										</ul>
									</div> <?php # .btn-group ?>
								</div>
							</form>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<?php Lang::out('info:cycles_past_none'); ?>
	<?php endif; ?>
</p>

<script type = "text/javascript" src = "<?php echo DIR_TEMPLATE_WEB; ?>/js/jquery-ui-1.8.7.custom.min.js"></script>
<script type = "text/javascript">
$(document).ready(function(){
	$("#show-create-form").click(function(){
		$("#create-form").slideDown();
		$(this).parent().slideUp();
	});
	$("#cancel-create-form").click(function(){
		$("#create-form").slideUp();
		$("#show-create-form").parent().slideDown();
	});
	$("#start-field,#end-field").datepicker({
		autoSize:true,
		dateFormat:'yy-mm-dd'
	});
});
</script>
<?php include 'foot.tpl.php'; ?>