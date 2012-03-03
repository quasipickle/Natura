<?php include 'head.tpl.php'; ?>
	<div class = "span3 offset4">
		<h1>
			<?php Lang::out('ttl:login'); ?>
		</h1>
		<form method = "post" action = "">
			<?php if($this->error): ?>
				<div class = "alert alert-error">
					<?php foreach($this->error as $error): ?>
						<?php echo $error; ?><br />
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<div>
				<label for = "login-email">
					<?php Lang::out('lbl:email'); ?>
				</label>
				<input type = "text" name = "email" id = "login-email" value = "<?php echo htmlentities($this->email); ?>" />
				<label for = "login-password">
					<?php Lang::out('lbl:password'); ?>
				</label>
				<input type = "password" name = "password" />
				<br />
				<input type = "submit" class = "btn" name = "login" value = "<?php Lang::outSafe('btn:login'); ?>" />
				&nbsp;&nbsp;
				<a href = "<?php echo DIR_WEB; ?>/forgot/"><?php Lang::outSafe('btn:forgot'); ?></a>
			</div>
		</form>
	</div>
<script type = "text/javascript">
$(document).ready(function(){
	$("#login-email").focus();
});
</script>
<?php include 'foot.tpl.php'; ?>