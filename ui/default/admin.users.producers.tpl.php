<?php include 'head.tpl.php'; ?>
<h1>
	<?php Lang::out('ttl:list_producers'); ?>
</h1>
<?php if(isset($this->error)): ?>
	<div class = "alert alert-error">
		<?php foreach($this->error as $error): ?>
			<?php echo $error; ?><br />
		<?php endforeach; ?>
	</div>
<?php endif; ?>
<?php if(count($this->Producers)): ?>
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
					<?php Lang::out('lbl:business_name'); ?>
				</th>
				<th>
					<?php Lang::out('lbl:business_email'); ?>
				</th>
				<th>
					<?php Lang::out('lbl:business_phone'); ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($this->Producers as $Producer): ?>
				<tr>
					<td>
						<?php echo $Producer->id; ?>
					</td>
					<td>
						<?php echo $Producer->first_name; ?>
					</td>
					<td>
						<?php echo $Producer->last_name; ?>
					</td>
					<td>
						<?php echo $Producer->business_name; ?>
					</td>
					<td>
						<?PHP $email = (strlen($Producer->business_email)) 
										? $Producer->business_email
										: $Producer->email; ?>
						<a href = "mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
					</td>
					<td>
						<?php echo $Producer->business_phone; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php else: ?>
	<?php Lang::out('info:no_members'); ?>
<?php endif; ?>