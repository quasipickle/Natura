<?php include 'head.tpl.php'; ?>
<h1>
	<?php Lang::out('ttl:producer_profile_edit'); ?>
</h1>
<?php if(isset($this->updated)): ?>
	<div class = "alert alert-success">
		<?php Lang::out('msg:producer_profile_success'); ?>
	</div>
<?php endif; ?>
<?php if(isset($this->error)): ?>
	<div class = "alert alert-error">
		<?php foreach($this->error as $error): ?>
			<?php echo $error; ?><br />
		<?php endforeach; ?>
	</div>
<?php endif; ?>
<br />
<?php include 'producer.profile.form.tpl.php'; ?>
<?php include 'foot.tpl.php'; ?>