<?php include 'head.tpl.php'; ?>
<h1 class = "page_title">
	<?php Lang::out('ttl:producer_signup'); ?>
</h1>
<p>
	<?php Lang::out('info:producer_signup'); ?>
</p>
<br />
<div class = "alert alert-info">
	<?php Lang::out('info:fields_required_specified'); ?> <?php Lang::out('info:update_later'); ?>
</div>
<?php if(isset($this->signup_success)): ?>
	<div class = "alert alert-success">
		<?php Lang::out('msg:producer_signup_success'); ?>
	</div>
<?php endif; ?>
<?php if(isset($this->error)): ?>
	<div class = "alert alert-error">
		<?php foreach($this->error as $error): ?>
			<?php echo $error; ?><br />
		<?php endforeach; ?>
	</div>
<?php endif; ?>

<?php include 'producer.profile.form.tpl.php'; ?>
<?php include 'foot.tpl.php'; ?>