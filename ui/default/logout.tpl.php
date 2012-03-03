<?php include 'head.tpl.php'; ?>
<?php if(isset($this->error)): ?>
	<div class = "alert alert-error">
		<?php foreach($this->error as $error): ?>
			<?php echo $error; ?><br />
		<?php endforeach; ?>
	</div>
<?php endif; ?>
<?php if($this->logout_success): ?>
	<div class = "alert alert-success">
		<?php echo Lang::out('msg:logout_success'); ?>
	</div>
<?php endif; ?>
<?php include 'foot.tpl.php'; ?>