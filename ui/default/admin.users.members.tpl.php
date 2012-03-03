<?php include 'head.tpl.php'; ?>
<h1>
	<?php Lang::out('ttl:list_members'); ?>
</h1>
<?php if(isset($this->error)): ?>
	<div class = "alert alert-error">
		<?php foreach($this->error as $error): ?>
			<?php echo $error; ?><br />
		<?php endforeach; ?>
	</div>
<?php endif; ?>
<?php if(count($this->Members)): ?>
	<table class = "table table-striped table-condensed">
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
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->Members as $Member): ?>
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
						<a href = "mailto:<?php echo $Member->email; ?>"><?php echo $Member->email; ?></a>
					</td>
					<td>
						<?php echo $Member->phone; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
	<?php Lang::out('info:no_members'); ?>
<?php endif; ?>