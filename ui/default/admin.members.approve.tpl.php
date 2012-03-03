<?php include 'head.tpl.php'; ?>
<h1>
	<?php Lang::out('ttl:member_approve'); ?>
</h1>
<?php if(isset($this->approve_success)): ?>
	<div class = "alert alert-success">
		<?php Lang::out('msg:member_approve_success'); ?>
	</div>
	<br />
<?php endif; ?>
<?php if(isset($this->error)): ?>
	<div class = "alert alert-error">
		<?php foreach($this->error as $error): ?>
			<?php echo $error; ?><br />
		<?php endforeach; ?>
	</div>
	<br />
<?php endif; ?>

<?php if($this->members): ?>
	<table class = "table table-striped">
		<thead>
			<tr>
				<th>
					<?php Lang::out('lbl:member_id'); ?>
				</th>
				<th>
					<?php Lang::out('lbl:member_first_name'); ?>	
				</th>
				<th>
					<?php Lang::out('lbl:member_last_name'); ?>	
				</th>
				<th>
					<?php Lang::out('lbl:member_email'); ?>	
				</th>
				<th>
					<?php Lang::out('lbl:member_phone'); ?>	
				</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->members as $Member): ?>
				<tr>
					<td>
						<?php echo $Member->id; ?>
					</td>
					<td>
						<?php echo $Member->first_name; ?>
					</td>
					<td>
						<?php echo $Member->last_name; ?>
					</td>
					<td>
						<?php echo $Member->email; ?>
					</td>
					<td>
						<?php echo $Member->phone; ?>
					</td>
					<td>
						<form method = "post" action = "" style = "margin:0;">
							<div>
								<input type = "hidden" name = "id" value = "<?php echo htmlentities($Member->id); ?>" />
								<input type = "submit" name = "approve" class = "btn" value = "<?php Lang::outSafe('btn:approve'); ?>" />
							</div>
						</form>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
	<?php Lang::out('info:member_approve_none'); ?>
<?php endif; ?>


<?php include 'foot.tpl.php'; ?>