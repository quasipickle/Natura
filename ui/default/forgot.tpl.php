<?php include 'head.tpl.php'; ?>
<h1>
	<?php Lang::out('ttl:forgot'); ?>
</h1>
<p>
	<?php Lang::out('info:forgot'); ?>
</p>
<br />
<?php if(isset($this->error)): ?>
	<div class = "alert alert-error">
		<?php foreach($this->error as $error): ?>
			<?php echo $error; ?><br />
		<?php endforeach; ?>
	</div>
	<br />
<?php endif; ?>
<?php if($this->send_success): ?>
	<div class = "alert alert-success">
		<?php Lang::out('msg:forgot_email_sent'); ?>
	</div>
	<br />
<?php endif; ?>
<form method = "post" action = "">
	<div class = "span3 offset4">
		<label>
			<?php Lang::out('lbl:email'); ?>
		</label>
		<input type = "text" name = "email" />
		<br />
		<input type = "submit" class = "btn" name = "submit" value = "<?php Lang::outSafe('btn:forgot_submit'); ?>"/>
	</div>
</form>