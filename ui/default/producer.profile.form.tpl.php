<form method = "post" action = "">
	<div class = "control-group">
		<label>
			<?php Lang::out('lbl:business_name'); ?>
		</label>
		<input type = "text" name = "business_name" value = "<?php echo htmlentities($this->business_name); ?>" />
	</div>
	<div class = "control-group">
		<label>
			<?php Lang::out('lbl:business_about'); ?>
		</label>
		<input type = "text" name = "business_about" value = "<?php echo htmlentities($this->business_about); ?>" maxlength = "255" class = "span8"/>
		<span class = "help-inline">
			<?php Lang::out('info:business_about'); ?>
		</span>
	</div>
	<div class = "control-group">
		<label>
			<?php Lang::out('lbl:business_email'); ?>
		</label>
		<input type = "text" name = "business_email" value = "<?php echo htmlentities($this->business_email); ?>" />
		<span class = "label label-info"><?php Lang::out('info:optional'); ?></span>
		<span class = "help-inline">
			<?php Lang::out('info:business_email'); ?>
		</span>
	</div>
	<div class = "control-group">
		<label>
			<?php Lang::out('lbl:business_phone'); ?>
		</label>
		<input type = "text" name = "business_phone" value = "<?php echo htmlentities($this->business_phone); ?>" />
		<span class = "label label-info"><?php Lang::out('info:optional'); ?></span>
		<span class = "help-inline"><?php Lang::out('info:business_phone'); ?></span>
	</div>
	<div class = "form-actions">
		<?php if(isset($this->editing)): ?>
			<input type = "submit" class = "btn" name = "edit" value = "<?php Lang::outSafe('btn:producer_profile_edit'); ?>" />
		<?php else: ?>
			<input type = "submit" class = "btn" name = "signup" value = "<?php Lang::outSafe('btn:producer_signup'); ?>" />
		<?php endif; ?>
	</div>
</form>