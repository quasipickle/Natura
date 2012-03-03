<?php include 'head.tpl.php'; ?>
	<h1>
		<?php echo Lang::out('ttl:signup'); ?>
	</h1>
	<?php if(isset($this->error)): ?>
		<div class = "alert alert-error">
			<?php foreach($this->error as $error): ?>
				<?php echo $error; ?><br />
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	
	<?php if(isset($this->signup_success)): ?>
		<div class = "alert alert-success">
			<?php Lang::out('msg:signup_success'); ?>
		</div>	
	<?php endif; ?>
	
	<div class = "alert alert-info">
		<strong class = "stark">
			<?php echo Lang::out('info:fields_required_specified'); ?>
		</strong>
	</div>
	<form method = "post" action = "">
		<div class = "row">
			<div class = "span5">
				<label>
					<?php echo Lang::out('lbl:member_first_name'); ?>
				</label>
				<input type = "text" name = "first_name" value = "<?php echo htmlentities($this->first_name); ?>" tabindex="1"/>
			</div>
			<div class = "span4">
				<label>
					<?php echo Lang::out('lbl:member_phone'); ?>
					
				</label>
				<input type = "text" name = "phone" value = "<?php echo htmlentities($this->phone); ?>" tabindex="3"/>
				<span class = "help-inline label label-info"><?php Lang::out('info:optional'); ?></small>
			</div>
		</div>
		<div class = "row">			
			<div class = "span5">
				<label>
					<?php echo Lang::out('lbl:member_last_name'); ?>
				</label>
				<input type = "text" name = "last_name" value = "<?php echo htmlentities($this->last_name); ?>" tabindex="2"/>
			</div>
		</div>
		<hr />
		<div class = "row">
			<div class = "span5">
				<label>
					<?php echo Lang::out('lbl:member_email'); ?>	
				</label>
				<input type = "text" name = "email" value = "<?php echo htmlentities($this->email); ?>" size = "40"tabindex="4"/>
			</div>
			<div class = "span5">
				<label>
					<?php echo Lang::out('lbl:password');?>
					
				</label>
				<input type = "password" name = "password" tabindex="6"/>
			</div>
		</div>
		<div class = "row">		
			<div class = "span5">
				<label>
					<?php echo Lang::out('lbl:member_confirm_email'); ?>
				</label>
				<input type = "text" name = "confirm_email" value = "<?php echo htmlentities($this->confirm_email); ?>" size = "40" tabindex="5"/>
			</div>
			<div class = "span4">
				<label>
					<?php echo Lang::out('lbl:member_password_confirm'); ?>
					
				</label>
				<input type = "password" name = "confirm_password" tabindex="7"/>
			</div>
		</div>
		<hr />
		<?php Lang::out('info:signup_terms'); ?>				
		<label class = "checkbox">
			<input type = "checkbox" name = "agree" tabindex="8"/><?php Lang::out('lbl:membership_term_agree'); ?>
		</label>
		<br />
		<div class = "form-actions">
			<input type = "submit" name = "signup" value = "<?php Lang::outSafe('btn:signup'); ?>" class = "btn"/>
		</div>
	</form>
<?php include 'foot.tpl.php'; ?>