<?php header('Content-type: text/html'); ?>
<?php include 'head.tpl.php'; ?>
<h1 id = "page-title">
	<?php Lang::out('ttl:forgot'); ?>
</h1>
<br />
<?php if(isset($this->error)): ?>
	<div class = "alert alert-error">
		<?php foreach($this->error as $error): ?>
			<?php echo $error; ?><br />
		<?php endforeach; ?>
	</div>
<?php endif; ?>
<?php if($this->reset_success): ?>
	<div class = "alert alert-success">
		<?php Lang::out('msg:password_reset'); ?>
	</div>
	<br />
<?php endif; ?>
<?php if(!$this->reset_success && !$this->load_errors): ?>
	<form method = "post" action = "">
		<div class = "control-group">
			<?php Lang::out('lbl:email'); ?>: 
			<?php echo $this->email; ?>
		</div>
		<div class = "control-group">
			<label>
				<?php Lang::out('lbl:new_password'); ?>
			</label>
			<input type = "password" name = "password" />
		</div>
		<div class = "control-group">
			<label>
				<?php Lang::out('lbl:confirm_password'); ?>
			</label>
			<input type = "password" name = "confirm_password" />
		</div>
		<div class = "form-actions">
			<input type = "submit" class = "btn" name = "reset" value = "<?php Lang::outSafe('btn:reset_password'); ?>"/>
		</div>
	</form>
<?php endif; ?>