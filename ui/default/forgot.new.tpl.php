<?php header('Content-type: text/html'); ?>
<?php include 'head.tpl.php'; ?>
<h1 id = "page-title">
	<?php Lang::out('ttl:forgot'); ?>
</h1>
<br />
<?php if(isset($this->error)): ?>
	<div class = "message error">
		<?php foreach($this->error as $error): ?>
			<?php echo $error; ?><br />
		<?php endforeach; ?>
	</div>
	<br />
<?php endif; ?>
<?php if($this->reset_success): ?>
	<div class = "message success">
		<?php Lang::out('msg:password_reset'); ?>
	</div>
	<br />
<?php endif; ?>
<?php if(!$this->reset_success && !$this->load_errors): ?>
	<form method = "post" action = "">
		<ul class = "userform">
			<li>
				<label>
					<?php Lang::out('lbl:email'); ?>
				</label>
				<?php echo $this->email; ?>
			</li>
			<li>
				<label>
					<?php Lang::out('lbl:new_password'); ?>
				</label>
				<input type = "password" name = "password" />
			</li>
			<li>
				<label>
					<?php Lang::out('lbl:confirm_password'); ?>
				</label>
				<input type = "password" name = "confirm_password" />
			</li>
			<li>
				<input type = "submit" class = "button" name = "reset" value = "<?php Lang::outSafe('btn:reset_password'); ?>"/>
			</li>
		</ul>
	</form>
<?php endif; ?>